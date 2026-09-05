<?php

namespace App\Services;

use App\Enums\PartidaStatus;
use App\Models\Partida;
use App\Models\Aposta;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SimulacaoService
{
    private const GOLS_MANDANTE = [0, 0, 1, 1, 1, 2, 2, 2, 3, 3, 4, 5];

    private const GOLS_VISITANTE = [0, 0, 0, 1, 1, 1, 2, 2, 3, 4];

    public function simularPartida(Partida $partida, User $usuario): bool
    {
        if ($partida->estaFinalizada()) {
            return false;
        }

        return DB::transaction(function () use ($partida, $usuario): bool {
            $partida = Partida::query()->lockForUpdate()->findOrFail($partida->id);

            if ($partida->estaFinalizada()) {
                return false;
            }

            $this->aplicarPlacar($partida, $usuario);

            return true;
        });
    }

    public function simularProximaRodada(User $usuario): ?int
    {
        $rodada = Partida::query()->agendadas()->min('rodada');

        if ($rodada === null) {
            return null;
        }

        DB::transaction(function () use ($rodada, $usuario): void {
            $partidas = Partida::query()
                ->agendadas()
                ->where('rodada', $rodada)
                ->lockForUpdate()
                ->get();

            foreach ($partidas as $partida) {
                $this->aplicarPlacar($partida, $usuario);
            }
        });

        return (int) $rodada;
    }

    public function reiniciar(): int
    {
        return DB::transaction(function () {
            Partida::query()->lockForUpdate()->get();
            foreach (Aposta::where('status', 'pendente')->lockForUpdate()->get() as $aposta) {
                app(ApostaService::class)->devolver($aposta);
            }

            return Partida::query()->update([
            'gols_mandante' => null,
            'gols_visitante' => null,
            'status' => PartidaStatus::AGENDADA->value,
            'simulada_por' => null,
            'updated_at' => now(),
            ]);
        });
    }

    private function aplicarPlacar(Partida $partida, User $usuario): void
    {
        $partida->update([
            'gols_mandante' => self::GOLS_MANDANTE[array_rand(self::GOLS_MANDANTE)],
            'gols_visitante' => self::GOLS_VISITANTE[array_rand(self::GOLS_VISITANTE)],
            'status' => PartidaStatus::FINALIZADA,
            'simulada_por' => $usuario->id,
        ]);
        app(ApostaService::class)->liquidar($partida);
    }
}
