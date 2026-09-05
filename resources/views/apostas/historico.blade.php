<x-app-layout>
    <x-slot name="header"><h2 class="text-xl font-semibold text-gray-800">Meus palpites</h2><p class="mt-1 text-sm text-gray-500">Seu histórico e os créditos de cada resultado.</p></x-slot>
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4"><p class="text-lg font-semibold text-green-900">Saldo: {{ number_format(auth()->user()->saldo_creditos, 0, ',', '.') }} créditos virtuais</p><a href="{{ route('apostas.index') }}" class="bet-cta">Novo palpite →</a></div>
        <div class="space-y-4">
            @forelse ($apostas as $aposta)
                <article class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div><p class="text-xs text-gray-500">#{{ $aposta->id }} · {{ $aposta->created_at->format('d/m/Y H:i') }}</p><h3 class="mt-1 font-bold text-gray-900">{{ $aposta->confronto }}</h3><p class="mt-1 text-sm text-gray-600">{{ App\Models\Aposta::OPCOES[$aposta->palpite]['nome'] }} · {{ $aposta->valor }} créditos · {{ $aposta->multiplicador }}×</p></div>
                    <div class="text-sm"><span @class(['rounded-full px-3 py-1 font-semibold', 'bg-green-100 text-green-800' => $aposta->status === 'ganha', 'bg-red-50 text-red-700' => $aposta->status === 'perdida', 'bg-gray-100 text-gray-700' => $aposta->status === 'cancelada', 'bg-amber-50 text-amber-800' => $aposta->status === 'pendente'])>{{ ucfirst($aposta->status) }}</span><p class="mt-3 text-gray-600">{{ $aposta->status === 'pendente' ? 'Possível retorno: '.($aposta->valor * $aposta->multiplicador) : 'Créditos recebidos: '.$aposta->retorno }}</p>@if ($aposta->placar)<p class="mt-1 text-gray-500">Placar: {{ $aposta->placar }}</p>@endif</div>
                    @if ($aposta->status === 'pendente')
                        <form action="{{ route('apostas.cancelar', $aposta) }}" method="POST" onsubmit="return confirm('Cancelar este palpite e receber os créditos de volta?')">@csrf<x-secondary-button type="submit">Cancelar palpite</x-secondary-button></form>
                    @endif
                </article>
            @empty
                <div class="rounded-xl bg-white p-10 text-center"><h3 class="text-lg font-semibold text-gray-800">A resenha começa no primeiro palpite.</h3><p class="mt-2 text-gray-500">Escolha uma partida na Central de palpites para começar.</p></div>
            @endforelse
        </div>
        <div class="mt-6">{{ $apostas->links() }}</div>
    </div>
</x-app-layout>
