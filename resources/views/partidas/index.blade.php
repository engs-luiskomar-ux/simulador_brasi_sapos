<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Partidas</h2>
                <p class="mt-1 text-sm text-gray-500">Confrontos e resultados por rodada.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @can('simular', App\Models\Partida::class)
                    <form action="{{ route('simulacao.proxima') }}" method="POST">
                        @csrf
                        <x-primary-button>Simular próxima rodada</x-primary-button>
                    </form>
                @endcan

                @can('create', App\Models\Partida::class)
                    <a href="{{ route('partidas.create') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 hover:bg-gray-50">
                        Nova partida
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('partidas.index') }}" method="GET" class="mb-5 flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-xs">
                    <x-input-label for="rodada" value="Filtrar por rodada" />
                    <select id="rodada" name="rodada" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Próxima rodada pendente</option>
                        @foreach ($rodadas as $rodada)
                            <option value="{{ $rodada }}" @selected((string) $rodada === (string) $rodadaSelecionada)>Rodada {{ $rodada }}</option>
                        @endforeach
                    </select>
                </div>
                <x-primary-button type="submit">Filtrar</x-primary-button>
                @if ($rodadaSelecionada)
                    <a href="{{ route('partidas.index') }}" class="pb-2 text-sm font-medium text-gray-600 hover:text-gray-900">Ir para a rodada atual</a>
                @endif
            </form>

            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Rodada</th>
                                <th class="px-4 py-3">Data</th>
                                <th class="px-4 py-3 text-right">Mandante</th>
                                <th class="px-4 py-3 text-center">Placar</th>
                                <th class="px-4 py-3">Visitante</th>
                                <th class="px-4 py-3">Situação</th>
                                <th class="px-4 py-3 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($partidas as $partida)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-4 py-4 text-gray-600">{{ $partida->rodada }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-gray-600">{{ $partida->data_partida?->format('d/m/Y H:i') ?? 'A definir' }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right font-medium text-gray-900">{{ $partida->mandante->sigla }}</td>
                                    <td class="whitespace-nowrap px-4 py-4 text-center font-bold text-gray-900">
                                        {{ is_null($partida->gols_mandante) ? '–' : $partida->gols_mandante }} x {{ is_null($partida->gols_visitante) ? '–' : $partida->gols_visitante }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 font-medium text-gray-900">{{ $partida->visitante->sigla }}</td>
                                    <td class="whitespace-nowrap px-4 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $partida->status->value === 'finalizada' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $partida->status->label() }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('partidas.show', $partida) }}" class="font-medium text-green-700 hover:text-green-800">Ver</a>
                                            @can('update', $partida)
                                                <a href="{{ route('partidas.edit', $partida) }}" class="font-medium text-blue-700 hover:text-blue-800">Editar</a>
                                            @endcan
                                            @can('delete', $partida)
                                                <form action="{{ route('partidas.destroy', $partida) }}" method="POST" onsubmit="return confirm('Deseja excluir esta partida?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-700 hover:text-red-800">Excluir</button>
                                                </form>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-gray-500">Nenhuma partida encontrada.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($partidas->hasPages())
                <div class="mt-6">{{ $partidas->appends(request()->query())->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
