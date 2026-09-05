<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Bet dos Sapo Véio</h2>
                <p class="mt-1 text-sm text-gray-500">Acompanhe o andamento do campeonato.</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @can('simular', App\Models\Partida::class)
                    <form action="{{ route('simulacao.proxima') }}" method="POST">
                        @csrf
                        <x-primary-button>Simular próxima rodada</x-primary-button>
                    </form>
                @endcan

                @if (auth()->user()->isAdmin())
                    <form action="{{ route('simulacao.reiniciar') }}" method="POST" onsubmit="return confirm('Reiniciar o campeonato? Os placares serão apagados e os palpites pendentes serão cancelados com devolução dos créditos. O histórico concluído será mantido.')">
                        @csrf
                        <x-danger-button>Reiniciar campeonato</x-danger-button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <section class="bet-hero mx-4 mb-6 flex flex-wrap items-center justify-between gap-5 rounded-2xl p-6 sm:mx-0">
                <div class="flex items-center gap-4"><x-application-logo class="h-20 w-20" /><div><p class="bet-eyebrow">O PALPITE É SEU. A RESENHA É NOSSA.</p><h3 class="mt-2 text-2xl font-bold">{{ number_format(auth()->user()->saldo_creditos, 0, ',', '.') }} créditos virtuais</h3><p class="mt-1 text-sm text-green-100">Escolha um confronto e acompanhe o resultado simulado.</p></div></div>
                <a class="bet-cta" href="{{ route('apostas.index') }}">Central de palpites →</a>
            </section>
            <div class="grid gap-4 px-4 sm:grid-cols-3 sm:px-0">
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Rodada atual</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $rodadaAtual ?: 'Não iniciada' }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Partidas realizadas</p>
                    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $partidasRealizadas }}</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Líder</p>
                    <p class="mt-2 truncate text-2xl font-semibold text-gray-900">
                        {{ $classificacao->first()['time']->nome ?? 'A definir' }}
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-6 px-4 sm:px-0 lg:grid-cols-2">
                <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                        <h3 class="font-semibold text-gray-900">Primeiros colocados</h3>
                        <a href="{{ route('classificacao.index') }}" class="text-sm font-medium text-green-700 hover:text-green-800">Ver tabela</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                                <tr>
                                    <th class="px-5 py-3">Pos.</th>
                                    <th class="px-5 py-3">Time</th>
                                    <th class="px-5 py-3 text-center">Pts</th>
                                    <th class="px-5 py-3 text-center">J</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse ($classificacao->take(5) as $linha)
                                    <tr>
                                        <td class="px-5 py-3 font-semibold text-gray-700">{{ $linha['posicao'] }}º</td>
                                        <td class="px-5 py-3 text-gray-900">{{ $linha['time']->nome }}</td>
                                        <td class="px-5 py-3 text-center font-semibold">{{ $linha['pontos'] }}</td>
                                        <td class="px-5 py-3 text-center text-gray-600">{{ $linha['jogos'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-8 text-center text-gray-500">Nenhum time cadastrado.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4">
                        <h3 class="font-semibold text-gray-900">Próximas partidas</h3>
                        <a href="{{ route('partidas.index') }}" class="text-sm font-medium text-green-700 hover:text-green-800">Ver todas</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse ($proximasPartidas as $partida)
                            <a href="{{ route('partidas.show', $partida) }}" class="block px-5 py-4 hover:bg-gray-50">
                                <div class="flex items-center justify-between gap-4 text-sm">
                                    <span class="flex-1 text-right font-medium text-gray-900">{{ $partida->mandante->nome }}</span>
                                    <span class="shrink-0 text-xs font-semibold text-gray-500">x</span>
                                    <span class="flex-1 font-medium text-gray-900">{{ $partida->visitante->nome }}</span>
                                </div>
                                <p class="mt-2 text-center text-xs text-gray-500">
                                    Rodada {{ $partida->rodada }}
                                    @if ($partida->data_partida)
                                        · {{ $partida->data_partida->format('d/m/Y H:i') }}
                                    @endif
                                </p>
                            </a>
                        @empty
                            <p class="px-5 py-8 text-center text-sm text-gray-500">Não há partidas agendadas.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
