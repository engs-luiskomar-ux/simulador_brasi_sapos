<?php

namespace App\Http\Controllers;

use App\Models\Aposta;
use App\Models\Partida;
use App\Services\ApostaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApostaController extends Controller
{
    public function index(Request $request): View
    {
        $partidas = Partida::with(['mandante', 'visitante'])->agendadas()
            ->orderBy('rodada')->orderBy('id')->paginate(10);

        return view('apostas.index', compact('partidas'));
    }

    public function historico(Request $request): View
    {
        $apostas = Aposta::where('user_id', $request->user()->id)->latest('id')->paginate(15);

        return view('apostas.historico', compact('apostas'));
    }

    public function store(Request $request, ApostaService $service): RedirectResponse
    {
        $dados = $request->validate([
            'partida_id' => ['required', 'integer', 'exists:partidas,id'],
            'palpite' => ['required', Rule::in(array_keys(Aposta::OPCOES))],
            'valor' => ['required', 'integer', 'min:10', 'max:1000'],
        ]);
        $service->registrar($request->user(), (int) $dados['partida_id'], $dados['palpite'], (int) $dados['valor']);

        return back()->with('success', 'Palpite registrado! Acompanhe o resultado em Meus palpites.');
    }

    public function cancelar(Request $request, Aposta $aposta, ApostaService $service): RedirectResponse
    {
        $service->cancelar($aposta, $request->user());

        return back()->with('success', 'Palpite cancelado. Seus créditos foram devolvidos.');
    }
}
