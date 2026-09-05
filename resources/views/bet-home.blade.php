<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bet dos Sapo Véio</title>
    <link rel="icon" href="{{ asset('images/sapo-veio.svg') }}" type="image/svg+xml">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bet-landing font-sans antialiased">
    <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-6 py-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3 font-bold"><x-application-logo class="h-12 w-12" /><span>Bet dos Sapo Véio</span></a>
        <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="bet-outline">Entrar ↗</a>
    </nav>
    <main class="mx-auto max-w-7xl px-6 pb-12">
        <section class="bet-hero my-8 grid items-center gap-10 rounded-3xl p-8 md:grid-cols-2 md:p-14">
            <div>
                <p class="bet-eyebrow">BRASILEIRÃO FICTÍCIO • SÓ NA RESENHA</p>
                <h1 class="mt-5 text-5xl font-black leading-tight md:text-6xl">O palpite é seu.<br><span class="bet-lime">A resenha é nossa.</span></h1>
                <p class="mt-6 max-w-lg text-lg leading-relaxed text-green-100">Bem-vindo à Bet dos Sapo Véio. Escolha seu resultado, use créditos virtuais e acompanhe cada rodada do nosso campeonato simulado.</p>
                <a href="{{ auth()->check() ? route('apostas.index') : route('register') }}" class="bet-cta mt-8 inline-block">{{ auth()->check() ? 'Ver partidas para palpitar' : 'Criar conta e começar' }} →</a>
                <p class="mt-4 text-sm text-green-100">1.000 créditos de brincadeira na criação da conta.</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <x-application-logo class="w-full max-w-xs drop-shadow-2xl" />
                <p class="mt-6 text-3xl font-black uppercase tracking-tight">Bet dos <span class="bet-lime">Sapo Véio</span></p>
                <p class="mt-2 text-sm tracking-widest text-green-100">PALPITE DE RESPEITO.</p>
            </div>
        </section>
        <section class="grid gap-5 md:grid-cols-3" aria-label="Como funciona">
            <div class="bet-info"><span class="bet-lime text-sm font-bold">01 / ESCOLHA</span><h2 class="mt-3 text-xl font-bold">Mandante, empate ou visitante?</h2><p class="mt-2 text-green-100">Confira os confrontos e escolha o resultado em que você acredita.</p></div>
            <div class="bet-info"><span class="bet-lime text-sm font-bold">02 / PALPITE</span><h2 class="mt-3 text-xl font-bold">Créditos, só de brincadeira.</h2><p class="mt-2 text-green-100">Use entre 10 e 1.000 créditos por palpite. O retorno total é o valor multiplicado pela cotação.</p></div>
            <div class="bet-info"><span class="bet-lime text-sm font-bold">03 / ACOMPANHE</span><h2 class="mt-3 text-xl font-bold">Saiu o placar? Tá na conta.</h2><p class="mt-2 text-green-100">O organizador simula a rodada e o sistema atualiza seu histórico e saldo.</p></div>
        </section>
        <p class="mt-10 text-center text-sm text-green-100">Projeto acadêmico • Resultados fictícios • Sem dinheiro real, depósitos, saques ou prêmios.</p>
    </main>
</body>
</html>
