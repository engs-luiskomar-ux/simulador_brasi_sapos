# Bet dos Sapo Véio

Plataforma acadêmica de palpites sobre um Brasileirão fictício. A base mantém os cadastros, permissões e simulação do projeto original, com uma nova identidade visual e carteira de créditos virtuais.

## Como demonstrar a bet

1. Entre como `torcedor@brasileirao.test`, senha `12345678`, ou crie uma conta. Cada usuário começa com 1.000 créditos virtuais.
2. Abra **Palpitar**, escolha mandante (2×), empate (3×) ou visitante (3×) e use entre 10 e 1.000 créditos inteiros.
3. Em outro navegador, entre como `organizador@brasileirao.test`, mesma senha, e simule uma partida ou rodada.
4. Atualize **Meus palpites**: o resultado e o saldo são atualizados automaticamente. Um acerto de 100 créditos a 2× devolve 200 créditos no total, incluindo os 100 usados.

Não há dinheiro real, depósitos, saques, compras de créditos ou prêmios. As cotações são fixas para fins didáticos; os resultados são simulados, independentemente dos palpites. Apenas torcedores registram palpites. Administradores e organizadores gerenciam o campeonato.

Um palpite pendente pode ser cancelado pelo dono antes do resultado, com devolução integral. Partidas com histórico de palpites não podem ser editadas ou excluídas. Reiniciar o campeonato cancela e reembolsa os palpites pendentes; resultados e retornos já concluídos permanecem no histórico, sem novo pagamento.

Para atualizar esta instalação existente, execute `php artisan migrate` e `npm run build`. Para iniciar, `php artisan serve`. Não use `migrate:fresh` para atualizar: esse comando apaga os dados existentes.

A logo vetorial está em `public/images/sapo-veio.svg`. As novas regras ficam em `app/Services/ApostaService.php`, as rotas e telas seguem o padrão de controllers, models e Blade da base.

## Base original do campeonato

Projeto final desenvolvido para demonstrar, de forma simples, organizada e funcional, os principais conceitos estudados em Laravel durante o bimestre.

O sistema representa o Campeonato Brasileiro da Série A de 2026. Ele começa com os 20 times participantes e a tabela completa do campeonato, formada por 38 rodadas e 380 partidas. Usuários autorizados podem administrar os dados e simular jogos; os demais podem acompanhar partidas e classificação.

Os 20 clubes cadastrados correspondem à Série A de 2026, conforme a [relação oficial de times da CBF](https://www.cbf.com.br/futebol-brasileiro/times/campeonato-brasileiro/serie-a/2026). Os placares, resultados e classificações produzidos pelo sistema são simulações acadêmicas e não representam resultados oficiais da CBF.

> [!IMPORTANT]
> Antes da entrega, o grupo deve substituir todos os campos entre colchetes, registrar as atividades realmente realizadas e remover integrantes não utilizados. Não entregue o projeto com placeholders.

## Integrantes

O trabalho pode ter até quatro integrantes.

| Integrante | Nome completo | Turma | Usuário no GitHub |
|---|---|---|---|
| 1 | [NOME DO INTEGRANTE 1] | [TURMA] | [USUÁRIO DO GITHUB] |
| 2 | [NOME DO INTEGRANTE 2] | [TURMA] | [USUÁRIO DO GITHUB] |
| 3 | [NOME DO INTEGRANTE 3] | [TURMA] | [USUÁRIO DO GITHUB] |
| 4 | [NOME DO INTEGRANTE 4 OU REMOVER ESTA LINHA] | [TURMA] | [USUÁRIO DO GITHUB] |

## Funcionalidades

- autenticação com cadastro, login e encerramento de sessão pelo Laravel Breeze;
- painel inicial com resumo do campeonato;
- 20 times oficiais da Série A de 2026 inseridos por Seeder;
- calendário de turno e returno, com 38 rodadas e 380 partidas;
- visualização das partidas por rodada;
- classificação calculada a partir dos resultados;
- simulação da próxima rodada ainda não disputada;
- cadastro, listagem, visualização, edição e exclusão de times;
- cadastro, listagem, visualização, edição e exclusão de partidas;
- gerenciamento de usuários e alteração de papéis pelo administrador;
- três níveis de acesso: administrador, organizador e torcedor;
- proteção das rotas por autenticação e Middleware;
- autorização das ações por Policies;
- validação dos formulários por Form Requests;
- mensagens de sucesso, erro, validação e bloqueio de acesso.

## Regras do campeonato

- Cada time enfrenta todos os outros duas vezes: uma como mandante e outra como visitante.
- São 10 partidas por rodada, 38 rodadas e 380 partidas no total.
- Vitória vale 3 pontos, empate vale 1 ponto para cada time e derrota vale 0 ponto.
- A classificação apresenta posição, pontos, jogos, vitórias, empates, derrotas, gols pró, gols contra e saldo de gols.
- A simulação processa somente a próxima rodada pendente.
- Uma partida não pode ter o mesmo time como mandante e visitante.
- Placares não podem ser negativos.
- O reset do campeonato é exclusivo do administrador.

## Perfis e controle de acesso

| Funcionalidade | Administrador | Organizador | Torcedor |
|---|:---:|:---:|:---:|
| Consultar classificação, times e partidas | Sim | Sim | Sim |
| Cadastrar e editar partidas | Sim | Sim | Não |
| Simular a próxima rodada | Sim | Sim | Não |
| Excluir partidas | Sim | Não | Não |
| Gerenciar times | Sim | Não | Não |
| Excluir times | Sim | Não | Não |
| Gerenciar usuários e papéis | Sim | Não | Não |
| Reiniciar o campeonato | Sim | Não | Não |

### Administrador

Possui acesso completo. Pode gerenciar times, partidas e usuários, excluir registros e reiniciar o campeonato.

### Organizador

Pode cadastrar e editar partidas, registrar resultados e simular a próxima rodada. Não pode administrar usuários, times, excluir registros nem reiniciar o campeonato.

### Torcedor

Possui acesso de consulta. Pode acompanhar times, rodadas, partidas e classificação, mas não pode alterar os dados do campeonato.

Uma conta criada pelo formulário público recebe o papel de torcedor. Papéis com mais privilégios só podem ser atribuídos por um administrador.

## Conceitos de Laravel utilizados

### Arquitetura MVC

- **Models:** representam usuários, times e partidas.
- **Controllers:** recebem as requisições e coordenam as funcionalidades.
- **Views:** apresentam os dados ao usuário com Blade e Tailwind CSS.

### Banco de dados

- Migrations criam todas as tabelas e relacionamentos.
- Seeders inserem usuários de teste, os 20 times e as 380 partidas.
- O banco pode ser apagado e reconstruído com um único comando.
- SQLite simplifica a instalação e dispensa um servidor de banco separado.

### Eloquent

Cada partida pertence a um time mandante e a um time visitante. Um time, por sua vez, possui partidas disputadas nas duas condições. Esses relacionamentos são usados nas consultas, nas telas das partidas e no cálculo da classificação.

### Segurança e validação

- Laravel Breeze realiza autenticação, cadastro e recuperação de sessão.
- Middleware verifica o papel do usuário antes de liberar rotas restritas.
- <code>TimePolicy</code> e <code>PartidaPolicy</code> autorizam ações sobre as entidades.
- Form Requests validam os dados de times, partidas e alterações de papel.
- As permissões são verificadas no servidor; esconder um botão na interface não é a única proteção.

## Tecnologias

- PHP 8.3 ou superior;
- Laravel 13;
- Laravel Breeze;
- Blade;
- Tailwind CSS;
- Alpine.js;
- SQLite;
- Vite e npm;
- PHPUnit.

## Pré-requisitos

Instale:

- Git;
- PHP 8.3 ou superior, com suporte a SQLite;
- Composer;
- Node.js e npm.

Confirme as instalações:

~~~text
php -v
composer --version
node --version
npm --version
~~~

## Instalação no Windows

Os exemplos abaixo usam PowerShell.

1. Clone o repositório e entre na pasta:

~~~powershell
git clone URL_DO_REPOSITORIO
Set-Location simulador-brasileirao
~~~

2. Instale as dependências do PHP:

~~~powershell
composer install
~~~

3. Crie o arquivo de ambiente:

~~~powershell
Copy-Item .env.example .env
~~~

4. Crie o arquivo do SQLite, caso ainda não exista:

~~~powershell
if (-not (Test-Path database/database.sqlite)) {
    New-Item -ItemType File -Path database/database.sqlite | Out-Null
}
~~~

5. Gere a chave da aplicação:

~~~powershell
php artisan key:generate
~~~

6. Instale e compile os arquivos visuais:

~~~powershell
npm install
npm run build
~~~

7. Reconstrua o banco e carregue os dados de teste:

~~~powershell
php artisan migrate:fresh --seed
~~~

## Instalação no Linux

1. Clone o repositório e entre na pasta:

~~~bash
git clone URL_DO_REPOSITORIO
cd simulador-brasileirao
~~~

2. Instale as dependências do PHP:

~~~bash
composer install
~~~

3. Crie o arquivo de ambiente e o banco SQLite:

~~~bash
cp .env.example .env
mkdir -p database
touch database/database.sqlite
~~~

4. Gere a chave da aplicação:

~~~bash
php artisan key:generate
~~~

5. Instale e compile os arquivos visuais:

~~~bash
npm install
npm run build
~~~

6. Reconstrua o banco e carregue os dados de teste:

~~~bash
php artisan migrate:fresh --seed
~~~

## Configuração do banco

O arquivo <code>.env.example</code> já utiliza SQLite:

~~~dotenv
DB_CONNECTION=sqlite
~~~

Não é necessário informar host, porta, usuário ou senha. Nunca envie o arquivo <code>.env</code> para o repositório.

## Execução

Inicie o servidor:

~~~text
php artisan serve
~~~

Abra no navegador:

~~~text
http://127.0.0.1:8000
~~~

Durante o desenvolvimento visual, execute em outro terminal:

~~~text
npm run dev
~~~

## Usuários para teste

Todos os usuários criados pelo Seeder utilizam a senha <code>12345678</code>.

| Papel | E-mail | Senha |
|---|---|---|
| Administrador | admin@brasileirao.test | 12345678 |
| Organizador | organizador@brasileirao.test | 12345678 |
| Torcedor | torcedor@brasileirao.test | 12345678 |

Essas contas são exclusivamente para desenvolvimento e apresentação escolar.

## Testes

Para executar toda a suíte:

~~~text
php artisan test
~~~

Os testes de funcionalidade verificam, entre outros pontos:

- autenticação;
- cálculo da classificação;
- controle de acesso dos três papéis;
- simulação da próxima rodada.

Também é possível executar um arquivo específico:

~~~text
php artisan test tests/Feature/ClassificacaoTest.php
php artisan test tests/Feature/ControleAcessoTest.php
php artisan test tests/Feature/SimulacaoTest.php
~~~

Antes da entrega, todos os testes devem terminar sem falhas.

## Estrutura principal

~~~text
app/
├── Http/
│   ├── Controllers/     Fluxo das funcionalidades
│   ├── Middleware/      Verificação dos papéis
│   └── Requests/        Validação dos formulários
├── Models/              User, Time e Partida
└── Policies/            Autorizações de times e partidas
database/
├── migrations/          Estrutura das tabelas
└── seeders/             Usuários, times e partidas iniciais
resources/
└── views/               Interfaces Blade
routes/
├── auth.php             Rotas do Laravel Breeze
└── web.php              Rotas do sistema
tests/
└── Feature/             Testes das funcionalidades
docs/
└── ROTEIRO_APRESENTACAO.md
~~~

## Divisão real das atividades

Preencha esta seção somente com atividades que cada pessoa realmente realizou. Cite telas, classes, regras, migrations, seeders, testes ou documentação específicos.

| Integrante | Atividades realmente realizadas | Commits ou arquivos que comprovam |
|---|---|---|
| [NOME DO INTEGRANTE 1] | [DESCREVER AS TAREFAS REALMENTE EXECUTADAS] | [HASH OU LINK DOS COMMITS/ARQUIVOS] |
| [NOME DO INTEGRANTE 2] | [DESCREVER AS TAREFAS REALMENTE EXECUTADAS] | [HASH OU LINK DOS COMMITS/ARQUIVOS] |
| [NOME DO INTEGRANTE 3] | [DESCREVER AS TAREFAS REALMENTE EXECUTADAS] | [HASH OU LINK DOS COMMITS/ARQUIVOS] |
| [NOME DO INTEGRANTE 4 OU REMOVER] | [DESCREVER AS TAREFAS REALMENTE EXECUTADAS] | [HASH OU LINK DOS COMMITS/ARQUIVOS] |

Cada integrante deve fazer commits com a própria conta do GitHub, usando mensagens que identifiquem o trabalho realizado. Exemplos de formato de mensagem:

~~~text
feat: implementa cadastro e edição de partidas
test: adiciona testes da classificação
docs: completa instruções de instalação
~~~

Os exemplos acima mostram apenas o formato. A mensagem de cada commit deve descrever uma alteração que realmente foi feita.

## Roteiro da apresentação

O roteiro de 6 a 8 minutos está em [docs/ROTEIRO_APRESENTACAO.md](docs/ROTEIRO_APRESENTACAO.md). Ele cobre autenticação, controle de acesso, CRUD, relacionamento Eloquent, validação, Policy e reconstrução do banco.

## Solução de problemas

### Erro relacionado ao SQLite

Verifique se as extensões <code>pdo_sqlite</code> e <code>sqlite3</code> estão habilitadas:

~~~text
php -m
~~~

Depois, limpe o cache e reconstrua o banco:

~~~text
php artisan optimize:clear
php artisan migrate:fresh --seed
~~~

### Alterações visuais não aparecem

Execute:

~~~text
npm install
npm run dev
~~~

### Erro depois de alterar o arquivo de ambiente

Execute:

~~~text
php artisan optimize:clear
~~~

## Checklist antes da entrega

- [ ] Substituir o endereço <code>URL_DO_REPOSITORIO</code>.
- [ ] Preencher nomes, turma e usuários do GitHub.
- [ ] Registrar a divisão real das atividades.
- [ ] Remover a quarta linha caso o grupo tenha menos de quatro pessoas.
- [ ] Confirmar que cada integrante possui commits identificáveis.
- [ ] Executar <code>php artisan migrate:fresh --seed</code>.
- [ ] Entrar com as três contas de teste.
- [ ] Executar <code>php artisan test</code> sem falhas.
- [ ] Ensaiar o roteiro de apresentação.
- [ ] Confirmar que o repositório não contém o arquivo <code>.env</code>.

## Finalidade

Projeto acadêmico desenvolvido para avaliação bimestral e demonstração dos fundamentos do Laravel.
