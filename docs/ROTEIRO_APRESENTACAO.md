# Roteiro de apresentação — Bet dos Sapo Véio

Veja a seção **Atualização: Bet dos Sapo Véio**, ao final, para demonstrar os palpites e a carteira virtual junto com os recursos abaixo.

**Duração prevista:** 7 minutos  
**Faixa permitida:** 6 a 8 minutos

Este roteiro cobre os sete itens exigidos na demonstração: autenticação, controle de acesso, CRUD, relacionamento entre entidades, validação, autorização por Policy e banco de dados.

## Preparação antes de começar

- Execute <code>php artisan migrate:fresh --seed</code>.
- Execute <code>php artisan test</code> e confirme que não há falhas.
- Inicie a aplicação com <code>php artisan serve</code>.
- Deixe as três credenciais de teste disponíveis.
- Deixe um terminal aberto na pasta do projeto.
- Prepare o cadastro de um time temporário chamado **Time de Demonstração**, com sigla **TDE**, para não alterar um dos 20 times oficiais.
- Se possível, use três janelas privadas ou perfis do navegador para reduzir o tempo entre os logins.
- Aumente o zoom do navegador e do terminal para que o professor consiga enxergar.

> Não execute o reset novamente depois de iniciar o CRUD, pois ele apagará as alterações feitas durante a apresentação.

## Resumo do tempo

| Tempo | Etapa | Requisito comprovado |
|---|---|---|
| 0:00–0:35 | Apresentação do problema | Objetivo e sistema funcional |
| 0:35–1:15 | Banco, Migrations e Seeders | Banco reconstruível |
| 1:15–2:00 | Login como torcedor | Autenticação e acesso de consulta |
| 2:00–3:10 | Login como organizador | Papéis, partidas e simulação |
| 3:10–5:30 | Login como administrador | Validação, CRUD e Policy |
| 5:30–6:20 | Relacionamentos e MVC | Eloquent e organização |
| 6:20–7:00 | README e encerramento | Instalação, testes e trabalho do grupo |

## 0:00–0:35 — Apresentação do sistema

### Fala sugerida

“Nosso projeto é um simulador do Brasileirão Série A de 2026, desenvolvido em Laravel. Ele possui os 20 times oficiais, 38 rodadas e 380 partidas. O sistema permite acompanhar a classificação, administrar partidas e simular a próxima rodada, respeitando três níveis diferentes de acesso.”

### Mostrar

- página inicial;
- menu principal;
- resumo com 20 times, 38 rodadas e 380 partidas.

## 0:35–1:15 — Banco de dados, Migrations e Seeders

### Mostrar no terminal

~~~text
php artisan migrate:fresh --seed
~~~

### Explicar

- As Migrations criam as tabelas de usuários, times e partidas.
- A tabela de usuários possui o campo <code>role</code>.
- Cada partida possui relacionamentos com o time mandante e o visitante.
- Os Seeders criam três usuários, 20 times e 380 partidas.
- O comando comprova que o professor pode apagar e reconstruir o banco.

### Resultado esperado

O comando termina sem erro e informa a execução das migrations e dos seeders.

## 1:15–2:00 — Torcedor: autenticação e consulta

### Credenciais

- E-mail: <code>torcedor@brasileirao.test</code>
- Senha: <code>12345678</code>

### Demonstrar

1. Fazer login.
2. Abrir a classificação.
3. Abrir a lista de partidas ou uma rodada.
4. Mostrar que o torcedor não possui botões para criar, editar, excluir ou simular.
5. Tentar abrir uma rota administrativa previamente anotada e mostrar o bloqueio.
6. Encerrar a sessão.

### Fala sugerida

“O torcedor possui acesso somente de consulta. A interface esconde as ações proibidas, mas a proteção também existe no servidor por Middleware e Policy.”

### Resultado esperado

O torcedor visualiza os dados, mas recebe uma resposta de acesso negado ou um redirecionamento controlado ao tentar acessar uma área restrita.

## 2:00–3:10 — Organizador: partidas e simulação

### Credenciais

- E-mail: <code>organizador@brasileirao.test</code>
- Senha: <code>12345678</code>

### Demonstrar

1. Fazer login.
2. Abrir as partidas da próxima rodada pendente.
3. Cadastrar ou editar uma partida, se necessário.
4. Acionar **Simular próxima rodada**.
5. Voltar à classificação e mostrar os pontos e estatísticas atualizados.
6. Mostrar que o organizador não acessa usuários, times, reset ou exclusões.
7. Encerrar a sessão.

### Fala sugerida

“O organizador gerencia os jogos e pode simular a próxima rodada, mas não administra usuários ou times e não pode excluir nem reiniciar o campeonato.”

### Resultado esperado

Dez partidas da próxima rodada pendente recebem resultados, e a tabela de classificação é recalculada.

## 3:10–5:30 — Administrador: validação, CRUD e Policy

### Credenciais

- E-mail: <code>admin@brasileirao.test</code>
- Senha: <code>12345678</code>

### Parte 1 — Login e área exclusiva

1. Fazer login.
2. Abrir o gerenciamento de usuários.
3. Mostrar que o administrador pode alterar papéis.
4. Mostrar o controle de reset sem acioná-lo.

### Parte 2 — Validação com Form Request

1. Abrir o cadastro de time.
2. Enviar o formulário com campos obrigatórios vazios ou uma sigla inválida.
3. Mostrar as mensagens de validação.
4. Explicar que as regras ficam em um Form Request de <code>app/Http/Requests</code>.

### Parte 3 — CRUD completo de times

1. **Create:** cadastrar **Time de Demonstração**, sigla **TDE**, com os demais campos válidos.
2. **Read:** localizar o time na listagem e abrir seus detalhes.
3. **Update:** alterar um campo, como estádio ou cidade, e salvar.
4. **Delete:** excluir o time temporário e mostrar a mensagem de sucesso.

### Parte 4 — Policy

Explique a comparação com o organizador:

- o administrador recebe autorização da <code>TimePolicy</code> e consegue excluir;
- o organizador não recebe essa autorização;
- o torcedor só pode consultar.

Se o professor solicitar uma prova adicional, mostre rapidamente a Policy e o ponto do Controller em que a autorização é aplicada.

### Resultado esperado

- Dados inválidos não são salvos e produzem mensagens claras.
- O CRUD completo funciona.
- A exclusão é permitida ao administrador e negada aos outros papéis.

## 5:30–6:20 — Relacionamentos Eloquent e MVC

### Mostrar

1. Uma partida com seu mandante e visitante.
2. A página de um time ou rodada usando os dados relacionados.
3. Rapidamente, um Model, um Controller e uma View Blade.

### Fala sugerida

“A entidade Partida pertence a dois registros de Time: mandante e visitante. O Model define os relacionamentos Eloquent, o Controller organiza o fluxo e a View Blade apresenta as informações. A classificação é calculada a partir das partidas disputadas.”

### Apontar sem demorar

- <code>app/Models</code>: entidades e relacionamentos;
- <code>app/Http/Controllers</code>: fluxo das requisições;
- <code>resources/views</code>: telas Blade;
- <code>app/Http/Middleware</code>: controle por papel;
- <code>app/Policies</code>: autorização sobre times e partidas;
- <code>app/Http/Requests</code>: validação.

## 6:20–7:00 — README, testes e encerramento

### Mostrar

- nomes dos integrantes;
- divisão real das atividades;
- instruções de instalação;
- contas de teste;
- matriz de permissões;
- comando de testes.

### Fala final sugerida

“Nosso foco foi entregar um sistema simples, completo, organizado e funcional. O banco é reconstruível por Migrations e Seeders, os três papéis possuem permissões diferentes e as principais regras estão protegidas e testadas.”

## Checklist dos sete itens obrigatórios

- [ ] **Autenticação:** login com administrador, organizador e torcedor.
- [ ] **Controle de acesso:** diferenças reais entre os três papéis.
- [ ] **CRUD:** cadastrar, listar/visualizar, editar e excluir o time temporário.
- [ ] **Relacionamento:** partida ligada aos times mandante e visitante.
- [ ] **Validação:** tentativa de cadastrar dados inválidos e exibição dos erros.
- [ ] **Policy:** exclusão autorizada para admin e bloqueada para outros papéis.
- [ ] **Banco de dados:** Migrations, Seeders e <code>migrate:fresh --seed</code>.

## Plano de contingência

Se algum passo demorar:

- Não improvise alterações nos 20 times oficiais; use o Time de Demonstração.
- Se a internet estiver indisponível, o sistema continuará funcionando localmente.
- Se a simulação já tiver sido executada, use o reset somente no início e apenas como administrador.
- Se faltarem menos de 30 segundos, pule a navegação pelos diretórios e vá direto ao encerramento.
- Se ocorrer um erro visual, mantenha a calma, leia a mensagem e use o terminal já preparado.

## Perguntas que o grupo deve saber responder

1. Por que foi escolhido o padrão MVC?
2. Qual é a diferença entre Middleware e Policy neste projeto?
3. Onde ficam as regras de validação?
4. Como a classificação é calculada?
5. Como as 380 partidas são geradas?
6. Por que o SQLite foi escolhido?
7. Como uma pessoa reconstrói o banco?
8. O que cada integrante realmente implementou?
# Atualização: Bet dos Sapo Véio

Apresente o sistema como uma plataforma de palpites com créditos virtuais sobre um campeonato fictício. Demonstre: login do torcedor → Central de palpites → escolher resultado e créditos → Meus palpites → login do organizador em outro navegador → simular partida → atualizar saldo e histórico do torcedor. Mostre também um cancelamento antes da simulação.

Explique que os 1.000 créditos iniciais não têm valor financeiro; não há depósitos ou saques. O retorno total é valor × cotação, somente em caso de acerto. O organizador não pode apostar. O sistema usa transações para salvar saldo e palpite juntos e impedir pagamento repetido.

No código, mostre a migration de `apostas`, os relacionamentos com usuário e partida, `ApostaController`, `ApostaService` e as views em `resources/views/apostas`. Os cadastros e a classificação descritos acima continuam disponíveis como parte administrativa.
