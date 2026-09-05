<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Services\SimulacaoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SimulacaoController extends Controller
{
    public function proximaRodada(Request $request, SimulacaoService $simulacaoService): RedirectResponse
    {
        Gate::authorize('simular', Partida::class);

        $rodada = $simulacaoService->simularProximaRodada($request->user());

        if ($rodada === null) {
            return back()->with('error', 'Todas as rodadas já foram finalizadas.');
        }

        return redirect()
            ->route('partidas.index', ['rodada' => $rodada])
            ->with('success', "Rodada {$rodada} simulada com sucesso!");
    }

    public function partida(
        Request $request,
        Partida $partida,
        SimulacaoService $simulacaoService,
    ): RedirectResponse {
        Gate::authorize('simular', $partida);

        if (! $simulacaoService->simularPartida($partida, $request->user())) {
            return back()->with('error', 'Esta partida já foi finalizada.');
        }

        return back()->with('success', 'Partida simulada com sucesso!');
    }

    public function reiniciar(SimulacaoService $simulacaoService): RedirectResponse
    {
        $total = $simulacaoService->reiniciar();

        return redirect()
            ->route('dashboard')
            ->with('success', "Campeonato reiniciado. {$total} partidas voltaram ao estado agendado.");
    }
}
