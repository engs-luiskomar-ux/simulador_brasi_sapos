<?php

namespace Tests\Feature;

use App\Enums\PartidaStatus;
use App\Enums\UserRole;
use App\Models\Partida;
use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ControleAcessoTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_e_redirecionado_das_paginas_protegidas(): void
    {
        $time = $this->criarTime('VIS');
        $partida = $this->criarPartida($time, $this->criarTime('FOR'));

        foreach ([
            route('times.index'),
            route('times.show', $time),
            route('partidas.index'),
            route('partidas.show', $partida),
            route('classificacao.index'),
            route('usuarios.index'),
        ] as $url) {
            $this->get($url)->assertRedirect(route('login'));
        }
    }

    public function test_todos_os_papeis_podem_consultar_o_campeonato_mas_so_admin_gerencia_usuarios(): void
    {
        $admin = $this->criarUsuario(UserRole::ADMIN);
        $organizador = $this->criarUsuario(UserRole::ORGANIZADOR);
        $torcedor = $this->criarUsuario(UserRole::TORCEDOR);
        $time = $this->criarTime('LEI');
        $partida = $this->criarPartida($time, $this->criarTime('TUR'));

        foreach ([$admin, $organizador, $torcedor] as $usuario) {
            $this->actingAs($usuario)->get(route('times.index'))->assertOk();
            $this->actingAs($usuario)->get(route('times.show', $time))->assertOk();
            $this->actingAs($usuario)->get(route('partidas.index'))->assertOk();
            $this->actingAs($usuario)->get(route('partidas.show', $partida))->assertOk();
            $this->actingAs($usuario)->get(route('classificacao.index'))->assertOk();
        }

        $this->actingAs($admin)->get(route('usuarios.index'))->assertOk();
        $this->actingAs($organizador)->get(route('usuarios.index'))->assertForbidden();
        $this->actingAs($torcedor)->get(route('usuarios.index'))->assertForbidden();
    }

    public function test_policies_representam_a_matriz_de_permissoes_dos_tres_papeis(): void
    {
        $time = $this->criarTime('POL');
        $partida = $this->criarPartida($time, $this->criarTime('ICY'));

        $matriz = [
            UserRole::ADMIN->value => [true, true, true, true, true, true],
            UserRole::ORGANIZADOR->value => [false, false, false, true, true, false],
            UserRole::TORCEDOR->value => [false, false, false, false, false, false],
        ];

        foreach (UserRole::cases() as $papel) {
            $usuario = $this->criarUsuario($papel);
            $gate = Gate::forUser($usuario);

            $this->assertTrue($gate->allows('viewAny', Time::class));
            $this->assertTrue($gate->allows('view', $time));
            $this->assertTrue($gate->allows('viewAny', Partida::class));
            $this->assertTrue($gate->allows('view', $partida));

            $atual = [
                $gate->allows('create', Time::class),
                $gate->allows('update', $time),
                $gate->allows('delete', $time),
                $gate->allows('create', Partida::class),
                $gate->allows('update', $partida),
                $gate->allows('delete', $partida),
            ];

            $this->assertSame($matriz[$papel->value], $atual, "Permissões incorretas para {$papel->value}.");
        }
    }

    public function test_admin_altera_papeis_e_demais_usuarios_nao_acessam_a_administracao(): void
    {
        $admin = $this->criarUsuario(UserRole::ADMIN);
        $organizador = $this->criarUsuario(UserRole::ORGANIZADOR);
        $primeiroAlvo = $this->criarUsuario(UserRole::TORCEDOR);
        $segundoAlvo = $this->criarUsuario(UserRole::TORCEDOR);

        $this->actingAs($admin)
            ->put(route('usuarios.update', $primeiroAlvo), ['role' => UserRole::ORGANIZADOR->value])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $primeiroAlvo->id,
            'role' => UserRole::ORGANIZADOR->value,
        ]);

        $this->actingAs($organizador)
            ->put(route('usuarios.update', $segundoAlvo), ['role' => UserRole::ADMIN->value])
            ->assertForbidden();

        $this->assertDatabaseHas('users', [
            'id' => $segundoAlvo->id,
            'role' => UserRole::TORCEDOR->value,
        ]);
    }

    public function test_organizador_gerencia_partidas_sem_poder_excluir_e_torcedor_apenas_consulta(): void
    {
        $admin = $this->criarUsuario(UserRole::ADMIN);
        $organizador = $this->criarUsuario(UserRole::ORGANIZADOR);
        $torcedor = $this->criarUsuario(UserRole::TORCEDOR);
        $mandante = $this->criarTime('MAN');
        $visitante = $this->criarTime('VIS');
        $partida = $this->criarPartida($mandante, $visitante);

        $this->actingAs($organizador)->get(route('partidas.create'))->assertOk();
        $this->actingAs($organizador)->get(route('partidas.edit', $partida))->assertOk();

        $this->actingAs($organizador)
            ->put(route('partidas.update', $partida), $this->dadosPartida($mandante, $visitante, [
                'gols_mandante' => 2,
                'gols_visitante' => 1,
                'status' => PartidaStatus::FINALIZADA->value,
            ]))
            ->assertRedirect();

        $this->assertDatabaseHas('partidas', [
            'id' => $partida->id,
            'gols_mandante' => 2,
            'gols_visitante' => 1,
            'status' => PartidaStatus::FINALIZADA->value,
        ]);

        $this->actingAs($organizador)
            ->delete(route('partidas.destroy', $partida))
            ->assertForbidden();
        $this->assertDatabaseHas('partidas', ['id' => $partida->id]);

        $this->actingAs($torcedor)->get(route('partidas.create'))->assertForbidden();
        $this->actingAs($torcedor)
            ->put(route('partidas.update', $partida), $this->dadosPartida($mandante, $visitante))
            ->assertForbidden();

        $partidaDoAdmin = $this->criarPartida($visitante, $mandante, 2);
        $this->actingAs($admin)
            ->delete(route('partidas.destroy', $partidaDoAdmin))
            ->assertRedirect();
        $this->assertDatabaseMissing('partidas', ['id' => $partidaDoAdmin->id]);
    }

    private function criarUsuario(UserRole $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    private function criarTime(string $sigla): Time
    {
        return Time::query()->create([
            'nome' => "Time {$sigla}",
            'sigla' => $sigla,
            'cidade' => 'Cidade Teste',
            'estado' => 'SC',
            'estadio' => "Estádio {$sigla}",
            'cor_primaria' => '#123456',
        ]);
    }

    private function criarPartida(Time $mandante, Time $visitante, int $rodada = 1): Partida
    {
        return Partida::query()->create($this->dadosPartida($mandante, $visitante, ['rodada' => $rodada]));
    }

    /**
     * @param  array<string, mixed>  $sobrescrever
     * @return array<string, mixed>
     */
    private function dadosPartida(Time $mandante, Time $visitante, array $sobrescrever = []): array
    {
        return array_merge([
            'rodada' => 1,
            'data_partida' => now()->addDay()->format('Y-m-d H:i:s'),
            'mandante_id' => $mandante->id,
            'visitante_id' => $visitante->id,
            'gols_mandante' => null,
            'gols_visitante' => null,
            'status' => PartidaStatus::AGENDADA->value,
            'simulada_por' => null,
        ], $sobrescrever);
    }
}
