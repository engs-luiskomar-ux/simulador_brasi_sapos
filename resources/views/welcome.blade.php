<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Simulador do Brasileirão') }}</title>
        <style>
            * { box-sizing: border-box; }
            body { margin: 0; background: #f3f4f6; color: #1f2937; font-family: Arial, sans-serif; }
            .pagina { min-height: 100vh; display: grid; place-items: center; padding: 24px; }
            .cartao { width: 100%; max-width: 760px; overflow: hidden; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; box-shadow: 0 2px 8px rgba(0, 0, 0, .06); }
            .topo { padding: 30px; border-bottom: 5px solid #15803d; }
            .marca { display: flex; align-items: center; gap: 12px; color: #166534; font-size: 14px; font-weight: bold; }
            .bola { display: grid; width: 40px; height: 40px; place-items: center; border: 2px solid #15803d; border-radius: 50%; font-size: 22px; }
            h1 { margin: 28px 0 12px; font-size: clamp(28px, 5vw, 42px); line-height: 1.1; }
            .descricao { max-width: 600px; margin: 0; color: #4b5563; font-size: 17px; line-height: 1.6; }
            .acoes { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 26px; }
            .botao { display: inline-block; padding: 11px 18px; border: 1px solid #d1d5db; border-radius: 6px; color: #374151; font-size: 14px; font-weight: bold; text-decoration: none; }
            .botao:hover { background: #f9fafb; }
            .botao-principal { border-color: #15803d; background: #15803d; color: #fff; }
            .botao-principal:hover { background: #166534; }
            .recursos { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1px; background: #e5e7eb; }
            .recurso { min-height: 120px; padding: 22px; background: #fff; }
            .recurso strong { display: block; margin-bottom: 8px; }
            .recurso span { color: #6b7280; font-size: 14px; line-height: 1.5; }
            @media (max-width: 640px) { .recursos { grid-template-columns: 1fr; } .topo { padding: 24px; } }
        </style>
    </head>
    <body>
        <main class="pagina">
            <section class="cartao">
                <div class="topo">
                    <div class="marca"><span class="bola" aria-hidden="true">⚽</span> SIMULADOR DO BRASILEIRÃO</div>
                    <h1>Acompanhe e simule o campeonato.</h1>
                    <p class="descricao">
                        Consulte a classificação, veja os confrontos de cada rodada e acompanhe a evolução dos times.
                    </p>

                    <div class="acoes">
                        @auth
                            <a href="{{ route('dashboard') }}" class="botao botao-principal">Entrar no sistema</a>
                        @else
                            <a href="{{ route('login') }}" class="botao botao-principal">Entrar</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="botao">Criar conta</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="recursos">
                    <div class="recurso"><strong>Classificação</strong><span>Pontos, vitórias, saldo de gols e demais critérios.</span></div>
                    <div class="recurso"><strong>Rodadas</strong><span>Confrontos organizados por rodada e data.</span></div>
                    <div class="recurso"><strong>Simulação</strong><span>Resultados gerados e tabela atualizada automaticamente.</span></div>
                </div>
            </section>
        </main>
    </body>
</html>
