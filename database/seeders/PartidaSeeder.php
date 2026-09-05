<?php

namespace Database\Seeders;

use App\Enums\PartidaStatus;
use App\Enums\UserRole;
use App\Models\Partida;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PartidaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $times = Time::query()->orderBy('id')->pluck('id')->all();

        if (count($times) !== 20) {
            throw new \RuntimeException('O calendário precisa de exatamente 20 times.');
        }

        $administrador = User::query()
            ->where('role', UserRole::ADMIN->value)
            ->firstOrFail();
        $rotacao = $times;
        $inicio = Carbon::create(2026, 1, 28, 19, 0);
        $totalTimes = count($times);
        $metade = intdiv($totalTimes, 2);

        for ($rodada = 1; $rodada <= 19; $rodada++) {
            for ($indice = 0; $indice < $metade; $indice++) {
                $primeiro = $rotacao[$indice];
                $segundo = $rotacao[$totalTimes - 1 - $indice];
                $inverterMando = ($rodada + $indice) % 2 === 0;
                $mandante = $inverterMando ? $segundo : $primeiro;
                $visitante = $inverterMando ? $primeiro : $segundo;
                $data = $inicio->copy()
                    ->addWeeks($rodada - 1)
                    ->addDays($indice % 3)
                    ->addHours(($indice % 3) * 2);

                $partidaTurno = [
                    'rodada' => $rodada,
                    'data_partida' => $data,
                    'mandante_id' => $mandante,
                    'visitante_id' => $visitante,
                    'status' => PartidaStatus::AGENDADA,
                ];

                if ($rodada === 1) {
                    $partidaTurno['gols_mandante'] = ($indice * 2 + 1) % 4;
                    $partidaTurno['gols_visitante'] = ($indice + 2) % 3;
                    $partidaTurno['status'] = PartidaStatus::FINALIZADA;
                    $partidaTurno['simulada_por'] = $administrador->id;
                }

                Partida::create($partidaTurno);

                Partida::create([
                    'rodada' => $rodada + 19,
                    'data_partida' => $data->copy()->addWeeks(19),
                    'mandante_id' => $visitante,
                    'visitante_id' => $mandante,
                    'status' => PartidaStatus::AGENDADA,
                ]);
            }

            $fixo = array_shift($rotacao);
            $ultimo = array_pop($rotacao);
            array_unshift($rotacao, $fixo, $ultimo);
        }
    }
}
