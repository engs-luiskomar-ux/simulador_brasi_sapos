<?php

namespace Tests\Feature;

use App\Enums\PartidaStatus;
use App\Enums\UserRole;
use App\Models\Aposta;
use App\Models\Partida;
use App\Models\Time;
use App\Models\User;
use App\Services\ApostaService;
use App\Services\SimulacaoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ApostaTest extends TestCase
{
    use RefreshDatabase;

    private function partida(): Partida
    {
        $times = collect(['SAP', 'BRE'])->map(fn ($sigla) => Time::create([
            'nome' => 'Time '.$sigla, 'sigla' => $sigla, 'cidade' => 'Lagoa',
            'estado' => 'SC', 'estadio' => 'Estádio '.$sigla, 'cor_primaria' => '#123456',
        ]));

        return Partida::create(['rodada' => 1, 'mandante_id' => $times[0]->id,
            'visitante_id' => $times[1]->id, 'status' => PartidaStatus::AGENDADA]);
    }

    public function test_palpite_desconta_creditos_e_ignora_cotacao_enviada_pelo_cliente(): void
    {
        $user = User::factory()->create(['role' => UserRole::TORCEDOR]);
        $partida = $this->partida();
        $this->actingAs($user)->post(route('apostas.store'), [
            'partida_id' => $partida->id, 'palpite' => 'mandante', 'valor' => 100, 'multiplicador' => 999,
        ])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertEquals(900, $user->fresh()->saldo_creditos);
        $this->assertDatabaseHas('apostas', ['user_id' => $user->id, 'valor' => 100, 'multiplicador' => 2]);
        $this->get(route('apostas.index'))->assertOk()->assertSee('Central de palpites');
        $this->get(route('apostas.historico'))->assertOk()->assertSee('Time SAP');
    }

    public function test_rejeita_saldo_insuficiente_valores_invalidos_e_partida_encerrada(): void
    {
        $user = User::factory()->create(['role' => UserRole::TORCEDOR, 'saldo_creditos' => 50]);
        $partida = $this->partida();
        $this->actingAs($user);
        foreach ([-10, 0, 9, 10.5, 100, 1001] as $valor) {
            $this->post(route('apostas.store'), ['partida_id' => $partida->id, 'palpite' => 'mandante', 'valor' => $valor])->assertSessionHasErrors();
        }
        $this->post(route('apostas.store'), ['partida_id' => $partida->id, 'palpite' => 'invalido', 'valor' => 10])->assertSessionHasErrors();
        $partida->update(['status' => PartidaStatus::FINALIZADA, 'gols_mandante' => 1, 'gols_visitante' => 0]);
        $this->post(route('apostas.store'), ['partida_id' => $partida->id, 'palpite' => 'mandante', 'valor' => 10])->assertSessionHasErrors();
        $this->assertEquals(50, $user->fresh()->saldo_creditos);
        $this->assertDatabaseCount('apostas', 0);
    }

    public function test_acertos_e_perdas_sao_liquidados_uma_unica_vez(): void
    {
        $user = User::factory()->create(['role' => UserRole::TORCEDOR]);
        $partida = $this->partida();
        $service = app(ApostaService::class);
        foreach (['mandante', 'empate', 'visitante'] as $palpite) {
            $service->registrar($user, $partida->id, $palpite, 100);
        }
        $partida->update(['status' => PartidaStatus::FINALIZADA, 'gols_mandante' => 1, 'gols_visitante' => 1]);
        DB::transaction(fn () => $service->liquidar($partida));
        DB::transaction(fn () => $service->liquidar($partida));
        $this->assertEquals(1000, $user->fresh()->saldo_creditos);
        $this->assertEquals(1, Aposta::where('status', 'ganha')->count());
        $this->assertEquals(2, Aposta::where('status', 'perdida')->count());
        $this->assertDatabaseHas('apostas', ['palpite' => 'empate', 'retorno' => 300, 'placar' => '1 × 1']);
    }

    public function test_cancelamento_devolve_uma_vez_e_protege_apostas_de_outro_usuario(): void
    {
        $user = User::factory()->create(['role' => UserRole::TORCEDOR]);
        $outro = User::factory()->create(['role' => UserRole::TORCEDOR]);
        app(ApostaService::class)->registrar($user, $this->partida()->id, 'empate', 100);
        $aposta = Aposta::firstOrFail();
        $this->actingAs($outro)->post(route('apostas.cancelar', $aposta))->assertForbidden();
        $this->get(route('apostas.historico'))->assertDontSee('Time SAP');
        $this->actingAs($user)->post(route('apostas.cancelar', $aposta))->assertSessionHasNoErrors();
        $this->post(route('apostas.cancelar', $aposta))->assertSessionHasErrors();
        $this->assertEquals(1000, $user->fresh()->saldo_creditos);
    }

    public function test_simulacao_paga_resultado_e_reinicio_preserva_historico(): void
    {
        $user = User::factory()->create(['role' => UserRole::TORCEDOR]);
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $partida = $this->partida();
        app(ApostaService::class)->registrar($user, $partida->id, 'mandante', 100);
        $this->actingAs($admin)->post(route('partidas.simular', $partida))->assertSessionHasNoErrors();
        $this->assertNotEquals('pendente', Aposta::first()->status);
        $saldo = $user->fresh()->saldo_creditos;
        app(SimulacaoService::class)->reiniciar();
        app(ApostaService::class)->registrar($user, $partida->id, 'empate', 10);
        app(SimulacaoService::class)->reiniciar();
        $this->assertEquals($saldo, $user->fresh()->saldo_creditos);
        $this->assertEquals(2, Aposta::count());
        $this->assertEquals('cancelada', Aposta::latest('id')->first()->status);
    }

    public function test_administradores_nao_apostam_e_partidas_com_apostas_nao_sao_editaveis(): void
    {
        $user = User::factory()->create(['role' => UserRole::TORCEDOR]);
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $partida = $this->partida();
        app(ApostaService::class)->registrar($user, $partida->id, 'mandante', 100);
        $this->actingAs($admin)->post(route('apostas.store'), [
            'partida_id' => $partida->id, 'palpite' => 'empate', 'valor' => 10,
        ])->assertForbidden();
        $this->delete(route('partidas.destroy', $partida))->assertSessionHasErrors('partida');
        $this->put(route('partidas.update', $partida), [
            'rodada' => 1, 'mandante_id' => $partida->mandante_id, 'visitante_id' => $partida->visitante_id,
            'gols_mandante' => 3, 'gols_visitante' => 0,
        ])->assertSessionHasErrors('partida');
        $this->assertFalse($partida->fresh()->estaFinalizada());
    }

    public function test_visitante_precisa_entrar_para_acessar_palpites(): void
    {
        $this->get(route('apostas.index'))->assertRedirect(route('login'));
        $this->get(route('home'))->assertOk()->assertSee('Bet dos Sapo Véio');
    }
}
