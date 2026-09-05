<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Detalhes da partida</h2>
                <p class="mt-1 text-sm text-gray-500">Rodada {{ $partida->rodada }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @can('simular', $partida)
                    @if ($partida->status->value === 'agendada')
                        <form action="{{ route('partidas.simular', $partida) }}" method="POST">
                            @csrf
                            <x-primary-button>Simular partida</x-primary-button>
                        </form>
                    @endif
                @endcan

                @can('update', $partida)
                    <a href="{{ route('partidas.edit', $partida) }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">Editar</a>
                @endcan
                <a href="{{ route('partidas.index', ['rodada' => $partida->rodada]) }}" class="inline-flex items-center rounded-md bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white hover:bg-gray-700">Voltar</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 text-center">
                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $partida->status->value === 'finalizada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $partida->status->label() }}
                    </span>
                    <p class="mt-2 text-sm text-gray-500">
                        {{ $partida->data_partida?->format('d/m/Y H:i') ?? 'Data a definir' }}
                    </p>
                </div>

                <div class="grid grid-cols-[1fr_auto_1fr] items-center gap-4 px-5 py-10 sm:gap-8 sm:px-10">
                    <a href="{{ route('times.show', $partida->mandante) }}" class="text-center hover:text-green-700">
                        <span class="mx-auto block h-10 w-10 rounded-full border border-gray-300" style="background-color: {{ $partida->mandante->cor_primaria }}"></span>
                        <span class="mt-3 block text-lg font-semibold">{{ $partida->mandante->nome }}</span>
                        <span class="mt-1 block text-xs text-gray-500">Mandante</span>
                    </a>

                    <div class="text-center">
                        <p class="text-3xl font-bold text-gray-900 sm:text-4xl">
                            {{ is_null($partida->gols_mandante) ? '–' : $partida->gols_mandante }}
                            <span class="mx-1 text-lg font-normal text-gray-400">x</span>
                            {{ is_null($partida->gols_visitante) ? '–' : $partida->gols_visitante }}
                        </p>
                    </div>

                    <a href="{{ route('times.show', $partida->visitante) }}" class="text-center hover:text-green-700">
                        <span class="mx-auto block h-10 w-10 rounded-full border border-gray-300" style="background-color: {{ $partida->visitante->cor_primaria }}"></span>
                        <span class="mt-3 block text-lg font-semibold">{{ $partida->visitante->nome }}</span>
                        <span class="mt-1 block text-xs text-gray-500">Visitante</span>
                    </a>
                </div>

                <div class="border-t border-gray-200 px-6 py-4 text-sm text-gray-600">
                    <p><strong class="font-medium text-gray-900">Local:</strong> {{ $partida->mandante->estadio }}</p>
                    @if ($partida->simulador)
                        <p class="mt-1"><strong class="font-medium text-gray-900">Simulada por:</strong> {{ $partida->simulador->name }}</p>
                    @endif
                </div>
            </section>

            @can('delete', $partida)
                <div class="mt-5 flex justify-end">
                    <form action="{{ route('partidas.destroy', $partida) }}" method="POST" onsubmit="return confirm('Deseja excluir esta partida?')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button>Excluir partida</x-danger-button>
                    </form>
                </div>
            @endcan
        </div>
    </div>
</x-app-layout>
