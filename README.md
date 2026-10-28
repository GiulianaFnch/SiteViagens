# BestWay
# Bem-vindo ao repositório do Best Way!
![image](https://github.com/user-attachments/assets/012ec1b6-7b77-4313-b6e1-e81328c8ee99)

Este projeto é uma aplicação web para gerenciar reservas de viagens, hospedagens, passagens aéreas e passeios turísticos. A aplicação permite que os usuários pesquisem, adicionem itens ao carrinho, façam reservas e visualizem perfis de vendedores.

## Funcionalidades

- **Hospedagem**: Pesquisa e reserva de hospedagens.
- **Passagens Aéreas**: Pesquisa e reserva de passagens aéreas.
- **Passeios Turísticos**: Pesquisa e reserva de passeios turísticos.
- **Carrinho de Compras**: Adição de itens ao carrinho e cálculo do total.
- **Perfil**: Configurações de conta do usuário.
- **Perfil do Vendedor**: Visualização do perfil do vendedor.
- **Chat**: Comunicação entre usuários e vendedores.

## Estrutura do Projeto


- **adm/**: Painel de gestão para o administrador/developer.
- **assets/**: Arquivos de estilo e imagens.
- **config/**: Arquivos de configuração e validação.
- **database/**: Arquivos da base de dados.
- **public/**: Arquivos públicos acessíveis via URL.
   - **carrinho/**: Funcionalidades relacionadas ao carrinho de compras.
   - **hotels/**: Funcionalidades relacionadas a hospedagens.
   - **pacotes/**: Funcionalidades relacionadas a pacotes de viagens.
   - **tours/**: Funcionalidades relacionadas a passeios turísticos.
   - **voos/**: Funcionalidades relacionadas a passagens aéreas.
   - **vendedor/**: Funcionalidades do perfil do vendedor.
   - **reservas.php**: Página de gerenciamento de reservas do usuário normal.
- **views/**: Partials e templates reutilizáveis.

## Como executar

### Opção 1 — Docker (recomendado)

Precisa apenas do [Docker](https://www.docker.com/products/docker-desktop/) instalado.

```bash
git clone https://github.com/GiulianaFnch/SiteViagens.git
cd SiteViagens
docker compose up
```

- Site: <http://localhost:8080>
- phpMyAdmin: <http://localhost:8081> (utilizador `root`, senha `root`)

A base de dados é criada automaticamente a partir de `database/site_viagens.sql` na primeira execução.
Para recomeçar com a base de dados limpa: `docker compose down -v`.

### Opção 2 — XAMPP / WAMP

Requisitos: PHP 8.1+ (testado em 8.3) e MySQL 5.7+ / MariaDB 10+.
O PHP 8.1 é o mínimo porque é a partir dele que `htmlspecialchars` também escapa aspas simples por omissão (proteção contra XSS).

1. Clone o repositório para a pasta `htdocs` do XAMPP (ex.: `C:\xampp\htdocs\SiteViagens`).
2. No phpMyAdmin, crie a base de dados `site_viagens` e importe `database/site_viagens.sql`.
3. Inicie o Apache e o MySQL e abra <http://localhost/SiteViagens/>.

## Testes

`tests/smoke_test.sh` abre o site a correr e verifica os fluxos principais (login, registo, reservas, avaliações,
permissões, XSS, newsletter e logout). Corre automaticamente no GitHub a cada push (`.github/workflows/php-lint.yml`).

Para correr localmente, **numa base de dados de teste** (o script altera senhas e reservas dos dados de exemplo):

```bash
php -S 127.0.0.1:8080 -t .            # noutro terminal
tests/smoke_test.sh http://127.0.0.1:8080
```

Com o XAMPP, use o endereço do site (ex.: `http://localhost/SiteViagens`) e, se precisar, o comando do MySQL em
`MYSQL` (ex.: `MYSQL="C:/xampp/mysql/bin/mysql -uroot site_viagens"`).

## Configuração

Toda a configuração fica em `config/config.php`. Para alterar credenciais da base de dados,
o caminho do site ou chaves de API **sem mexer no código versionado**:

```bash
cp config/config.local.example.php config/config.local.php
```

e edite `config/config.local.php` (este ficheiro está no `.gitignore`).
Também é possível usar variáveis de ambiente: `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `BASE_URL`, `SERPAPI_KEY`.

- **BASE_URL** — é detetado automaticamente (ex.: `/SiteViagens/` no XAMPP, `/` no Docker).
- **SERPAPI_KEY** — necessária para a pesquisa de voos (`public/voos/voos.php`). Crie uma chave gratuita em <https://serpapi.com>.
- **debug** (`APP_DEBUG`) — `false` por omissão: erros inesperados mostram só uma mensagem genérica e os detalhes vão
  para o log do servidor (no XAMPP, `xampp/apache/logs/error.log`). Ponha `true` no seu computador para ver os erros na página.

### Atualizar uma base de dados existente

Se já tinha a base de dados importada antes, aplique os scripts de `database/migrations/` por ordem
(no phpMyAdmin → separador SQL, ou `mysql site_viagens < database/migrations/001_correcoes_t_user.sql`).

O `003_atualizar_datas_exemplo.sql` é opcional: prolonga a disponibilidade dos passeios e hospedagens
**de exemplo** (que terminava em 2024/2025) para que possam ser reservados. Não o use com dados reais.

## Reservas

As regras ficam em `config/reservas.php` e são usadas no carrinho, no checkout e nos painéis:

- **Hospedagem** — o cliente escolhe check-in, check-out e nº de quartos. O preço é
  `diária × noites × quartos`, e a reserva só é aceite se houver quartos livres em todas as noites
  (contando apenas reservas confirmadas) e se as datas estiverem dentro do período de disponibilidade.
- **Passeio** — o cliente escolhe o dia (dentro do período do passeio) e o nº de pessoas (1 a 20).
  O preço é `preço × pessoas`. Se o vendedor definir **vagas por dia**, a reserva só é aceite enquanto houver vagas nesse dia
  (migração `007_vagas_passeios.sql`).
- O preço é sempre calculado no servidor; o valor pago fica gravado na reserva e não muda se o
  vendedor alterar o preço depois.
- Finalizar a reserva é uma transação: se algum item deixou de estar disponível, nada é reservado.
- O cliente pode cancelar uma reserva até à data de início; o vendedor e o administrador também.
  Reservas canceladas ficam no histórico e libertam os quartos.

## Avaliações

- Nota de 1 a 5 estrelas e comentário opcional, em passeios e hospedagens.
- Só avalia quem tem uma reserva confirmada desse item que já terminou (check-out ou dia do passeio no passado).
- Uma avaliação por utilizador e item: avaliar de novo edita a anterior. Cada um pode apagar a sua; o administrador
  pode apagar qualquer uma.
- O vendedor dono do passeio ou da hospedagem pode responder a cada avaliação (e editar ou apagar a resposta).
- A média aparece nos detalhes e nas listagens. Para bases de dados existentes, aplique
  `database/migrations/004_avaliacoes.sql` e `008_resposta_avaliacoes.sql`.

## Newsletter

O formulário "Inscreva-se para receber nossas ofertas" grava o e-mail em `t_newsletter`
(`public/backend/newsletter.php`). Para bases de dados existentes, aplique `database/migrations/005_newsletter.sql`.
O envio dos e-mails ainda não está implementado.

## Tipos de utilizador (`t_user.tipo_user`)

| Valor | Tipo |
|-------|------|
| 0 | Cliente |
| 1 | Bloqueado |
| 2 | Vendedor (parceiro) |
| 3 | Vendedor a aguardar aprovação |
| 4 | Administrador (painel em `/adm`) |

As constantes correspondentes (`TIPO_CLIENTE`, `TIPO_ADMIN`, ...) estão em `config/acesso.php`. Ao bloquear um utilizador, o tipo
anterior fica guardado em `tipo_antes_bloqueio` e é reposto quando ele é reativado (migração `006_tipo_antes_bloqueio.sql`).

## Uso

### Perfil do Utilizador
Para visualizar o perfil do usuário normal, use o arquivo `perfil.php`. Este arquivo busca as informações do vendedor na tabela `t_user` onde `tipo_user` é 0.

### Adicionar ao Carrinho
Para adicionar um item ao carrinho, realize a pesquisa que quiser até o item específico, e clique em "Adicionar ao carrinho". Será verificado se o item já está no carrinho e atualiza a quantidade ou insere um novo registro.

### Visualizar Carrinho
Para visualizar os itens no carrinho, use o arquivo `carrinho.php`. Este arquivo exibe os itens adicionados e calcula o total do carrinho.

### Perfil do Vendedor
Para visualizar o perfil do vendedor, use o arquivo `perfil_vendedor.php`. Este arquivo busca as informações do vendedor na tabela `t_user` onde `tipo_user` é 2.

## Contribuição

Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests.

## Contato

Para mais informações, entre em contato com um dos colaboradores do projeto:
- `Giuliana Finochio`
- `Guilherme Silva`
- `Gabrielle Reis`
- `Karoline Moura`
