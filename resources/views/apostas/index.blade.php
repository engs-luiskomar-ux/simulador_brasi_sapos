<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Central de palpites</h2><p class="mt-1 text-sm text-gray-500">Brasileirão fictício · Palpites abertos até o organizador simular a partida.</p></x-slot>
    <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <section class="bet-hero flex flex-wrap items-center justify-between gap-5 rounded-2xl p-6">
            <div class="flex items-center gap-4"><x-application-logo class="h-20 w-20" /><div><p class="bet-eyebrow">CARTEIRA VIRTUAL</p><h3 class="mt-1 text-3xl font-bold">{{ number_format(auth()->user()->saldo_creditos, 0, ',', '.') }} <span class="text-base font-normal">créditos</span></h3><p class="mt-1 text-sm text-green-100">Sem valor em dinheiro. Só vale a resenha.</p></div></div>
            <a href="{{ route('apostas.historico') }}" class="bet-cta">Meus palpites →</a>
        </section>
        @unless (auth()->user()->isTorcedor())
            <p class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">Você gerencia os resultados. Para participar dos palpites, entre com uma conta de torcedor.</p>
        @endunless
        <p class="text-sm text-gray-600">Cotações fixas da simulação: mandante 2×, empate 3× e visitante 3×. O retorno inclui os créditos usados no palpite. Se errar, o valor utilizado não retorna.</p>
        <div class="grid gap-5 lg:grid-cols-2">
            @forelse ($partidas as $partida)
                <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex justify-between gap-3 text-xs font-semibold uppercase tracking-wide text-green-700"><span>Rodada {{ $partida->rodada }}</span><span>Palpites abertos</span></div>
                    <h3 class="my-5 text-center text-lg font-bold text-gray-900">{{ $partida->mandante->nome }} <span class="mx-2 text-sm font-normal text-gray-400">×</span> {{ $partida->visitante->nome }}</h3>
                    @if (auth()->user()->isTorcedor())
                        <form action="{{ route('apostas.store') }}" method="POST" x-data="{ odd: 2, valor: 10 }">
                            @csrf
                            <input type="hidden" name="partida_id" value="{{ $partida->id }}">
                            <fieldset><legend class="mb-2 text-sm text-gray-600">Escolha seu palpite</legend><div class="grid grid-cols-3 gap-2">
                                @foreach (App\Models\Aposta::OPCOES as $chave => $opcao)
                                    <label class="bet-option"><input type="radio" name="palpite" value="{{ $chave }}" @checked($loop->first) @change="odd = {{ $opcao['multiplicador'] }}" required><span class="block text-xs">{{ $opcao['nome'] }}</span><strong class="block text-lg">{{ $opcao['multiplicador'] }}×</strong></label>
                                @endforeach
                            </div></fieldset>
                            <div class="mt-4 flex flex-wrap items-end gap-3"><div class="flex-1"><label for="valor-{{ $partida->id }}" class="block text-sm text-gray-600">Créditos (10 a 1.000)</label><input id="valor-{{ $partida->id }}" name="valor" type="number" min="10" max="1000" step="1" x-model="valor" value="10" required class="mt-1 w-full rounded-md border-gray-300"></div><x-primary-button>Confirmar palpite</x-primary-button></div>
                            <p class="mt-3 text-sm text-green-800">Retorno total se acertar: <strong x-text="(Number(valor || 0) * odd).toLocaleString('pt-BR')">20</strong> créditos.</p>
                        </form>
                    @else
                        <a href="{{ route('partidas.show', $partida) }}" class="block rounded-lg bg-green-50 p-3 text-center font-semibold text-green-800">Gerenciar partida →</a>
                    @endif
                </article>
            @empty
                <div class="rounded-xl bg-white p-8 text-gray-600 lg:col-span-2">Não há partidas abertas. Confira seus resultados em Meus palpites ou aguarde o organizador iniciar um novo campeonato.</div>
            @endforelse
        </div>
        {{ $partidas->links() }}
    </div>
</x-app-layout>
