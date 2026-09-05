<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use App\Services\ClassificacaoService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(ClassificacaoService $classificacaoService): View
    {
        $proximaRodada = Partida::query()->agendadas()->min('rodada');
        $rodadaAtual = $proximaRodada ?? 38;
        $partidasRealizadas = Partida::query()->finalizadas()->count();
        $classificacao = $classificacaoService->calcular()->take(5);
        $proximasPartidas = Partida::query()
            ->with(['mandante', 'visitante'])
            ->when($proximaRodada, fn ($query) => $query->where('rodada', $proximaRodada))
            ->when(! $proximaRodada, fn ($query) => $query->where('rodada', 38))
            ->orderBy('data_partida')
            ->get();

        return view('dashboard', compact(
            'classificacao',
            'proximasPartidas',
            'rodadaAtual',
            'partidasRealizadas',
        ));
    }
}
