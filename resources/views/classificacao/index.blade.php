<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Classificação</h2>
            <p class="mt-1 text-sm text-gray-500">Tabela atual do Campeonato Brasileiro.</p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500">
                            <tr>
                                <th class="px-4 py-3 text-left">Pos.</th>
                                <th class="px-4 py-3 text-left">Time</th>
                                <th class="px-3 py-3 text-center" title="Pontos">Pts</th>
                                <th class="px-3 py-3 text-center" title="Jogos">J</th>
                                <th class="px-3 py-3 text-center" title="Vitórias">V</th>
                                <th class="px-3 py-3 text-center" title="Empates">E</th>
                                <th class="px-3 py-3 text-center" title="Derrotas">D</th>
                                <th class="px-3 py-3 text-center" title="Gols pró">GP</th>
                                <th class="px-3 py-3 text-center" title="Gols contra">GC</th>
                                <th class="px-3 py-3 text-center" title="Saldo de gols">SG</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($classificacao as $linha)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-4 py-3">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full text-xs font-semibold {{ $linha['posicao'] <= 4 ? 'bg-green-100 text-green-800' : ($linha['posicao'] >= 17 ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ $linha['posicao'] }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900">
                                        <a href="{{ route('times.show', $linha['time']) }}" class="hover:text-green-700">
                                            <span class="mr-2 inline-block h-3 w-3 rounded-full border border-gray-300 align-middle" style="background-color: {{ $linha['time']->cor_primaria }}"></span>
                                            {{ $linha['time']->nome }}
                                            <span class="ml-1 text-xs font-normal text-gray-400">{{ $linha['time']->sigla }}</span>
                                        </a>
                                    </td>
                                    <td class="px-3 py-3 text-center font-bold text-gray-900">{{ $linha['pontos'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-600">{{ $linha['jogos'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-600">{{ $linha['vitorias'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-600">{{ $linha['empates'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-600">{{ $linha['derrotas'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-600">{{ $linha['gols_pro'] }}</td>
                                    <td class="px-3 py-3 text-center text-gray-600">{{ $linha['gols_contra'] }}</td>
                                    <td class="px-3 py-3 text-center font-medium text-gray-700">{{ $linha['saldo_gols'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-12 text-center text-gray-500">Nenhum time cadastrado para montar a classificação.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-wrap gap-4 border-t border-gray-200 px-4 py-3 text-xs text-gray-500">
                    <span><span class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-green-200"></span> Primeiros quatro colocados</span>
                    <span><span class="mr-1 inline-block h-2.5 w-2.5 rounded-full bg-red-200"></span> Últimos quatro colocados</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
