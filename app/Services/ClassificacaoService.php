<?php

namespace App\Services;

use App\Models\Partida;
use App\Models\Time;
use Illuminate\Support\Collection;

class ClassificacaoService
{
    /**
     * Calcula a tabela diretamente a partir das partidas finalizadas.
     */
    public function calcular(): Collection
    {
        $tabela = [];

        foreach (Time::query()->orderBy('nome')->get() as $time) {
            $tabela[$time->id] = [
                'posicao' => 0,
                'time' => $time,
                'pontos' => 0,
                'jogos' => 0,
                'vitorias' => 0,
                'empates' => 0,
                'derrotas' => 0,
                'gols_pro' => 0,
                'gols_contra' => 0,
                'saldo_gols' => 0,
            ];
        }

        $partidas = Partida::query()
            ->finalizadas()
            ->with(['mandante', 'visitante'])
            ->get();

        foreach ($partidas as $partida) {
            if (! isset($tabela[$partida->mandante_id], $tabela[$partida->visitante_id])) {
                continue;
            }

            $mandante = &$tabela[$partida->mandante_id];
            $visitante = &$tabela[$partida->visitante_id];

            $mandante['jogos']++;
            $visitante['jogos']++;
            $mandante['gols_pro'] += $partida->gols_mandante;
            $mandante['gols_contra'] += $partida->gols_visitante;
            $visitante['gols_pro'] += $partida->gols_visitante;
            $visitante['gols_contra'] += $partida->gols_mandante;

            if ($partida->gols_mandante > $partida->gols_visitante) {
                $mandante['vitorias']++;
                $mandante['pontos'] += 3;
                $visitante['derrotas']++;
            } elseif ($partida->gols_mandante < $partida->gols_visitante) {
                $visitante['vitorias']++;
                $visitante['pontos'] += 3;
                $mandante['derrotas']++;
            } else {
                $mandante['empates']++;
                $visitante['empates']++;
                $mandante['pontos']++;
                $visitante['pontos']++;
            }

            unset($mandante, $visitante);
        }

        foreach ($tabela as &$linha) {
            $linha['saldo_gols'] = $linha['gols_pro'] - $linha['gols_contra'];
        }
        unset($linha);

        $linhas = array_values($tabela);

        usort($linhas, function (array $a, array $b): int {
            return ($b['pontos'] <=> $a['pontos'])
                ?: ($b['vitorias'] <=> $a['vitorias'])
                ?: ($b['saldo_gols'] <=> $a['saldo_gols'])
                ?: ($b['gols_pro'] <=> $a['gols_pro'])
                ?: ($a['time']->nome <=> $b['time']->nome);
        });

        foreach ($linhas as $indice => &$linha) {
            $linha['posicao'] = $indice + 1;
        }

        return collect($linhas);
    }
}
