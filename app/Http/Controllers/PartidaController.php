<?php

namespace App\Http\Controllers;

use App\Enums\PartidaStatus;
use App\Http\Requests\PartidaRequest;
use App\Models\Partida;
use App\Models\Time;
use App\Models\User;
use App\Models\Aposta;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PartidaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Partida::class);

        $rodadas = Partida::query()
            ->select('rodada')
            ->distinct()
            ->orderBy('rodada')
            ->pluck('rodada');

        $rodadaPadrao = Partida::query()->agendadas()->min('rodada')
            ?? $rodadas->last()
            ?? 1;
        $rodadaSelecionada = $request->integer('rodada', (int) $rodadaPadrao);

        if (! $rodadas->contains($rodadaSelecionada)) {
            $rodadaSelecionada = (int) $rodadaPadrao;
        }

        $partidas = Partida::query()
            ->with(['mandante', 'visitante', 'simulador'])
            ->where('rodada', $rodadaSelecionada)
            ->orderBy('data_partida')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('partidas.index', compact('partidas', 'rodadas', 'rodadaSelecionada'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Partida::class);

        $partida = new Partida;
        $times = Time::query()->orderBy('nome')->get();

        return view('partidas.create', compact('partida', 'times'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartidaRequest $request): RedirectResponse
    {
        Gate::authorize('create', Partida::class);

        $partida = Partida::create($this->prepararDados(
            $request->validated(),
            $request->user(),
        ));

        return redirect()
            ->route('partidas.show', $partida)
            ->with('success', 'Partida cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Partida $partida): View
    {
        Gate::authorize('view', $partida);

        $partida->load(['mandante', 'visitante', 'simulador']);

        return view('partidas.show', compact('partida'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Partida $partida): View
    {
        Gate::authorize('update', $partida);

        $times = Time::query()->orderBy('nome')->get();

        return view('partidas.edit', compact('partida', 'times'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartidaRequest $request, Partida $partida): RedirectResponse
    {
        Gate::authorize('update', $partida);

        DB::transaction(function () use ($request, $partida) {
            $atual = Partida::query()->lockForUpdate()->findOrFail($partida->id);
            if (Aposta::where('partida_id', $atual->id)->exists()) {
                throw ValidationException::withMessages(['partida' => 'Partidas com palpites ficam protegidas contra edição. Use a simulação para gerar o resultado.']);
            }
            $atual->update($this->prepararDados($request->validated(), $request->user()));
        });

        return redirect()
            ->route('partidas.show', $partida)
            ->with('success', 'Partida atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Partida $partida): RedirectResponse
    {
        Gate::authorize('delete', $partida);

        $rodada = $partida->rodada;
        DB::transaction(function () use ($partida) {
            $atual = Partida::query()->lockForUpdate()->findOrFail($partida->id);
            if (Aposta::where('partida_id', $atual->id)->exists()) {
                throw ValidationException::withMessages(['partida' => 'Esta partida possui histórico de palpites e não pode ser excluída.']);
            }
            $atual->delete();
        });

        return redirect()
            ->route('partidas.index', ['rodada' => $rodada])
            ->with('success', 'Partida excluída com sucesso!');
    }

    private function prepararDados(array $dados, User $usuario): array
    {
        $dados['gols_mandante'] = $dados['gols_mandante'] ?? null;
        $dados['gols_visitante'] = $dados['gols_visitante'] ?? null;
        $possuiPlacar = $dados['gols_mandante'] !== null
            && $dados['gols_visitante'] !== null;

        $dados['status'] = $possuiPlacar
            ? PartidaStatus::FINALIZADA
            : PartidaStatus::AGENDADA;
        $dados['simulada_por'] = $possuiPlacar ? $usuario->id : null;

        return $dados;
    }
}
