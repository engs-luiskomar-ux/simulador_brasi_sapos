<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Time;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidacaoCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_realiza_crud_completo_de_time(): void
    {
        $admin = User::factory()->create(['role' => UserRole::ADMIN]);

        $this->actingAs($admin)
            ->post(route('times.store'), [
                'nome' => '',
                'sigla' => 'X',
                'cidade' => '',
                'estado' => 'S',
                'estadio' => '',
                'cor_primaria' => 'verde',
            ])
            ->assertSessionHasErrors(['nome', 'sigla', 'cidade', 'estado', 'estadio', 'cor_primaria']);

        $this->actingAs($admin)
            ->post(route('times.store'), $this->dadosTime())
            ->assertRedirect();

        $time = Time::query()->where('sigla', 'TDE')->firstOrFail();
        $this->actingAs($admin)->get(route('times.show', $time))->assertOk();

        $this->actingAs($admin)
            ->put(route('times.update', $time), $this->dadosTime(['estadio' => 'Estádio Atualizado']))
            ->assertRedirect(route('times.show', $time));

        $this->assertDatabaseHas('times', [
            'id' => $time->id,
            'estadio' => 'Estádio Atualizado',
        ]);

        $this->actingAs($admin)
            ->delete(route('times.destroy', $time))
            ->assertRedirect(route('times.index'));

        $this->assertDatabaseMissing('times', ['id' => $time->id]);
    }

    public function test_form_request_impede_partida_com_o_mesmo_time(): void
    {
        $organizador = User::factory()->create(['role' => UserRole::ORGANIZADOR]);
        $time = Time::query()->create($this->dadosTime());

        $this->actingAs($organizador)
            ->post(route('partidas.store'), [
                'rodada' => 1,
                'data_partida' => now()->addDay()->format('Y-m-d H:i:s'),
                'mandante_id' => $time->id,
                'visitante_id' => $time->id,
                'gols_mandante' => null,
                'gols_visitante' => null,
            ])
            ->assertSessionHasErrors('visitante_id');

        $this->assertDatabaseCount('partidas', 0);
    }

    public function test_cadastro_publico_cria_apenas_torcedor(): void
    {
        $this->post(route('register'), [
            'name' => 'Novo Usuário',
            'email' => 'novo@exemplo.test',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role' => UserRole::ADMIN->value,
        ])->assertRedirect(route('dashboard', absolute: false));

        $this->assertDatabaseHas('users', [
            'email' => 'novo@exemplo.test',
            'role' => UserRole::TORCEDOR->value,
        ]);
    }

    /**
     * @param  array<string, string>  $sobrescrever
     * @return array<string, string>
     */
    private function dadosTime(array $sobrescrever = []): array
    {
        return array_merge([
            'nome' => 'Time de Demonstração',
            'sigla' => 'TDE',
            'cidade' => 'Cidade Teste',
            'estado' => 'SC',
            'estadio' => 'Estádio Municipal',
            'cor_primaria' => '#15803D',
        ], $sobrescrever);
    }
}
