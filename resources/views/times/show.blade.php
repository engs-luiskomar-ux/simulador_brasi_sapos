<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <span class="h-8 w-8 rounded-full border border-gray-300" style="background-color: {{ $time->cor_primaria }}"></span>
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800">{{ $time->nome }}</h2>
                    <p class="mt-1 text-sm text-gray-500">{{ $time->sigla }} · {{ $time->cidade }}/{{ $time->estado }}</p>
                </div>
            </div>

            <div class="flex gap-3">
                @can('update', $time)
                    <a href="{{ route('times.edit', $time) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">Editar</a>
                @endcan
                <a href="{{ route('times.index') }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">Voltar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <section class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-gray-900">Dados do time</h3>
                <dl class="mt-5 grid gap-5 text-sm sm:grid-cols-3">
                    <div>
                        <dt class="text-gray-500">Nome</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $time->nome }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Cidade</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $time->cidade }}/{{ $time->estado }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Estádio</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $time->estadio }}</dd>
                    </div>
                </dl>
            </section>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-semibold text-gray-900">Partidas como mandante</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($time->partidasComoMandante as $partida)
                            <a href="{{ route('partidas.show', $partida) }}" class="flex items-center justify-between gap-4 px-5 py-4 text-sm hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $partida->mandante->sigla }} x {{ $partida->visitante->sigla }}</p>
                                    <p class="mt-1 text-xs text-gray-500">Rodada {{ $partida->rodada }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        {{ is_null($partida->gols_mandante) ? '–' : $partida->gols_mandante }} x {{ is_null($partida->gols_visitante) ? '–' : $partida->gols_visitante }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $partida->status->label() }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="px-5 py-8 text-center text-sm text-gray-500">Nenhuma partida encontrada.</p>
                        @endforelse
                    </div>
                </section>

                <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-5 py-4">
                        <h3 class="font-semibold text-gray-900">Partidas como visitante</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($time->partidasComoVisitante as $partida)
                            <a href="{{ route('partidas.show', $partida) }}" class="flex items-center justify-between gap-4 px-5 py-4 text-sm hover:bg-gray-50">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $partida->mandante->sigla }} x {{ $partida->visitante->sigla }}</p>
                                    <p class="mt-1 text-xs text-gray-500">Rodada {{ $partida->rodada }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        {{ is_null($partida->gols_mandante) ? '–' : $partida->gols_mandante }} x {{ is_null($partida->gols_visitante) ? '–' : $partida->gols_visitante }}
                                    </p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $partida->status->label() }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="px-5 py-8 text-center text-sm text-gray-500">Nenhuma partida encontrada.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
