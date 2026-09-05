<?php

namespace Tests\Feature;

use App\Enums\PartidaStatus;
use App\Enums\UserRole;
use App\Models\Partida;
use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SimulacaoTest extends TestCase
{
    use RefreshDatabase;

    public function test_organizador_simula_somente_a_proxima_rodada_completa(): void
    {
        $organizador = $this->criarUsuario(UserRole::ORGANIZADOR);
        [$timeA, $timeB, $timeC, $timeD] = $this->criarTimes('A', 'B', 'C', 'D');
        $primeiraPartida = $this->criarPartida(1, $timeA, $timeB);
        $segundaPartida = $this->criarPartida(1, $timeC, $timeD);
        $partidaPosterior = $this->criarPartida(2, $timeA, $timeC);

        $this->actingAs($organizador);
        $this->requisitarRota('simulacao.proxima')->assertRedirect();

        foreach ([$primeiraPartida, $segundaPartida] as $partida) {
            $partida->refresh();

            $this->assertSame(PartidaStatus::FINALIZADA, $partida->status);
            $this->assertNotNull($partida->gols_mandante);
            $this->assertNotNull($partida->gols_visitante);
            $this->assertGreaterThanOrEqual(0, $partida->gols_mandante);
            $this->assertGreaterThanOrEqual(0, $partida->gols_visitante);
            $this->assertSame($organizador->id, $partida->simulada_por);
        }

        $partidaPosterior->refresh();
        $this->assertSame(PartidaStatus::AGENDADA, $partidaPosterior->status);
        $this->assertNull($partidaPosterior->gols_mandante);
        $this->assertNull($partidaPosterior->gols_visitante);
        $this->assertNull($partidaPosterior->simulada_por);
    }

    public function test_rodada_finalizada_nao_e_sorteada_novamente(): void
    {
        $admin = $this->criarUsuario(UserRole::ADMIN);
        [$timeA, $timeB, $timeC, $timeD] = $this->criarTimes('A', 'B', 'C', 'D');
        $finalizada = $this->criarPartida(1, $timeA, $timeB, [
            'gols_mandante' => 4,
            'gols_visitante' => 3,
            'status' => PartidaStatus::FINALIZADA,
            'simulada_por' => $admin->id,
        ]);
        $agendada = $this->criarPartida(2, $timeC, $timeD);

        $this->actingAs($admin);
        $this->requisitarRota('simulacao.proxima')->assertRedirect();

        $finalizada->refresh();
        $agendada->refresh();

        $this->assertSame(4, $finalizada->gols_mandante);
        $this->assertSame(3, $finalizada->gols_visitante);
        $this->assertSame(PartidaStatus::FINALIZADA, $finalizada->status);
        $this->assertSame(PartidaStatus::FINALIZADA, $agendada->status);
        $this->assertSame($admin->id, $agendada->simulada_por);
    }

    public function test_torcedor_nao_pode_simular_e_nenhum_resultado_e_alterado(): void
    {
        $torcedor = $this->criarUsuario(UserRole::TORCEDOR);
        [$mandante, $visitante] = $this->criarTimes('MAN', 'VIS');
        $partida = $this->criarPartida(1, $mandante, $visitante);

        $this->actingAs($torcedor);
        $this->requisitarRota('simulacao.proxima')->assertForbidden();

        $partida->refresh();
        $this->assertSame(PartidaStatus::AGENDADA, $partida->status);
        $this->assertNull($partida->gols_mandante);
        $this->assertNull($partida->gols_visitante);
        $this->assertNull($partida->simulada_por);
    }

    public function test_admin_reinicia_o_campeonato_e_organizador_nao_pode_reiniciar(): void
    {
        $admin = $this->criarUsuario(UserRole::ADMIN);
        $organizador = $this->criarUsuario(UserRole::ORGANIZADOR);
        [$timeA, $timeB, $timeC, $timeD] = $this->criarTimes('A', 'B', 'C', 'D');
        $primeira = $this->criarPartida(1, $timeA, $timeB, [
            'gols_mandante' => 2,
            'gols_visitante' => 0,
            'status' => PartidaStatus::FINALIZADA,
            'simulada_por' => $admin->id,
        ]);
        $segunda = $this->criarPartida(1, $timeC, $timeD, [
            'gols_mandante' => 1,
            'gols_visitante' => 1,
            'status' => PartidaStatus::FINALIZADA,
            'simulada_por' => $organizador->id,
        ]);

        $this->actingAs($organizador);
        $this->requisitarRota('simulacao.reiniciar')->assertForbidden();
        $this->assertSame(2, $primeira->fresh()->gols_mandante);

        $this->actingAs($admin);
        $this->requisitarRota('simulacao.reiniciar')->assertRedirect();

        foreach ([$primeira, $segunda] as $partida) {
            $partida->refresh();

            $this->assertSame(PartidaStatus::AGENDADA, $partida->status);
            $this->assertNull($partida->gols_mandante);
            $this->assertNull($partida->gols_visitante);
            $this->assertNull($partida->simulada_por);
        }
    }

    private function criarUsuario(UserRole $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    /**
     * @return array<int, Time>
     */
    private function criarTimes(string ...$siglas): array
    {
        return array_map(fn (string $sigla): Time => Time::query()->create([
            'nome' => "Time {$sigla}",
            'sigla' => $sigla,
            'cidade' => 'Cidade Teste',
            'estado' => 'SC',
            'estadio' => "Estádio {$sigla}",
            'cor_primaria' => '#123456',
        ]), $siglas);
    }

    /**
     * @param  array<string, mixed>  $sobrescrever
     */
    private function criarPartida(int $rodada, Time $mandante, Time $visitante, array $sobrescrever = []): Partida
    {
        return Partida::query()->create(array_merge([
            'rodada' => $rodada,
            'data_partida' => now()->subDay(),
            'mandante_id' => $mandante->id,
            'visitante_id' => $visitante->id,
            'gols_mandante' => null,
            'gols_visitante' => null,
            'status' => PartidaStatus::AGENDADA,
            'simulada_por' => null,
        ], $sobrescrever));
    }

    private function requisitarRota(string $nome): TestResponse
    {
        $rota = app('router')->getRoutes()->getByName($nome);

        $this->assertNotNull($rota, "A rota {$nome} não foi registrada.");
        $metodo = collect($rota->methods())->first(fn (string $metodo): bool => $metodo !== 'HEAD');

        return $this->call($metodo, route($nome));
    }
}
