<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeRequest;
use App\Models\Time;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Time::class);

        $times = Time::query()->orderBy('nome')->paginate(10);

        return view('times.index', compact('times'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Time::class);

        $time = new Time;

        return view('times.create', compact('time'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TimeRequest $request): RedirectResponse
    {
        Gate::authorize('create', Time::class);

        $time = Time::create($request->validated());

        return redirect()
            ->route('times.show', $time)
            ->with('success', 'Time cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Time $time): View
    {
        Gate::authorize('view', $time);

        $time->load([
            'partidasComoMandante.visitante',
            'partidasComoVisitante.mandante',
        ]);

        return view('times.show', compact('time'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Time $time): View
    {
        Gate::authorize('update', $time);

        return view('times.edit', compact('time'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TimeRequest $request, Time $time): RedirectResponse
    {
        Gate::authorize('update', $time);

        $time->update($request->validated());

        return redirect()
            ->route('times.show', $time)
            ->with('success', 'Time atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Time $time): RedirectResponse
    {
        Gate::authorize('delete', $time);

        $possuiPartidas = $time->partidasComoMandante()->exists()
            || $time->partidasComoVisitante()->exists();

        if ($possuiPartidas) {
            return redirect()
                ->route('times.index')
                ->with('error', 'O time não pode ser excluído porque possui partidas cadastradas.');
        }

        $time->delete();

        return redirect()
            ->route('times.index')
            ->with('success', 'Time excluído com sucesso!');
    }
}
