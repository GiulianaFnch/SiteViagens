# Guia de estudo: proteção CSRF no BestWay

Este guia resume o que foi feito no projeto nesta sessão. Os passos foram estes:

1. Revisei o código e montei o [TODO.md](../TODO.md).
2. Implementei a **proteção contra CSRF** (item 1 do TODO).

A ideia é que você entenda **o problema**, **a solução** e **onde cada parte está no código**.

---

## 1. O ponto de partida: a revisão (TODO.md)

Antes de mexer no código, li o projeto inteiro procurando o que ainda faltava depois do PR #11. O resultado está no `TODO.md`, organizado por prioridade:

| Seção | Exemplos |
|---|---|
| Segurança | CSRF, XSS, preço do voo vindo do navegador, sessão não renovada no login |
| Bugs / lógica | reserva sem checar disponibilidade, consultas N+1 |
| Funcionalidades | Pacotes é só HTML, sem pagamento, sem recuperação de senha |
| Organização | cabeçalho/rodapé repetidos, números "mágicos" em `tipo_user` |

Você pediu para fazer o item 1: **CSRF**.

---

## 2. O que é CSRF?

**CSRF** = *Cross-Site Request Forgery* (falsificação de requisição entre sites).

### A ideia em uma frase

> Um site malicioso faz o **navegador da vítima** enviar uma requisição para o **seu** site, e o seu site aceita porque o navegador manda o cookie de sessão junto, automaticamente.

### Por que isso funciona?

Quando um usuário faz login no BestWay, o PHP cria uma sessão e o navegador guarda um cookie (`PHPSESSID`). A partir daí, **toda** requisição para o BestWay leva esse cookie, **não importa qual site disparou a requisição**.

Ou seja: o servidor sabe **quem** está fazendo o pedido, mas não sabe **se foi a pessoa que quis fazer**.

### Exemplo concreto no BestWay (antes da correção)

Um vendedor está logado no BestWay. Em outra aba, ele abre um site qualquer com este HTML escondido:

```html
<form action="http://localhost/SiteViagens/public/vendedor/excluir_tour.php" method="post" id="ataque">
    <input type="hidden" name="id" value="15">
</form>
<script>document.getElementById('ataque').submit();</script>
```

O que acontece:

1. O navegador envia o POST para `excluir_tour.php`, **com o cookie de sessão do vendedor**.
2. `valida_vendedor.php` vê uma sessão válida e deixa passar.
3. O passeio 15 é apagado. O vendedor nem percebeu.

O mesmo valia para: editar reservas, ativar/desativar contas (admin), trocar a senha, mandar mensagens no chat, mexer no carrinho, fazer reservas...

> **Importante:** as correções do PR #11 (só apagar itens do próprio vendedor) **não** resolvem isso. O ataque usa a sessão do próprio dono, então o item **é** dele.

---

## 3. A solução: token CSRF

### A ideia

O servidor gera um **segredo aleatório** (o *token*) e guarda na sessão. Toda página com formulário coloca esse segredo num campo escondido. Quando o formulário chega, o servidor confere se o token enviado é igual ao da sessão.

```
 Seu site (página com formulário)          Site malicioso
 ┌──────────────────────────────┐          ┌──────────────────────────┐
 │ <input name="csrf_token"     │          │ <input name="csrf_token" │
 │        value="a8f3...e91">   │          │        value="???">      │
 └──────────────┬───────────────┘          └────────────┬─────────────┘
                │ POST + cookie + token certo           │ POST + cookie + SEM token
                ▼                                       ▼
        ┌───────────────────────────────────────────────────────┐
        │ servidor: token enviado == $_SESSION['csrf_token'] ?  │
        └───────────────┬───────────────────────────┬───────────┘
                     SIM ✅                       NÃO ❌ → 403
```

### Por que o site malicioso não consegue o token?

Por causa da **Same-Origin Policy** do navegador: um site em outro domínio pode **enviar** requisições para o seu, mas **não pode ler** as respostas. Ele não consegue abrir a sua página e copiar o valor do campo escondido. O cookie vai junto sozinho; o token, não.

---

## 4. O código: `config/csrf.php`

Arquivo novo, com três funções. Vamos linha por linha.

```php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
```
O token fica na sessão, então ela precisa estar aberta. O `if` evita o aviso *"session already active"* caso outro arquivo já tenha chamado `session_start()`.

```php
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
```
- Gera o token **uma vez por sessão** e reaproveita depois.
- `random_bytes(32)`: 32 bytes vindos de um gerador **criptograficamente seguro**. Não use `rand()` ou `uniqid()`: são previsíveis.
- `bin2hex`: transforma os bytes em texto (64 caracteres hexadecimais) para caber num `<input>`.

```php
function csrf_campo()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}
```
Atalho para não repetir o HTML do campo em todo formulário.

```php
function csrf_verificar()
{
    $enviado = $_POST['csrf_token'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_string($enviado) || !hash_equals(csrf_token(), $enviado)) {
        http_response_code(403);
        exit('Pedido inválido: ...');
    }
}
```
Três checagens:

| Checagem | Por quê |
|---|---|
| `REQUEST_METHOD !== 'POST'` | Ações que alteram dados só aceitam POST. Bloqueia ataques via link/imagem (`<img src="efetuar_reserva.php">`). |
| `!is_string($enviado)` | Se alguém mandar `csrf_token[]=x`, o PHP cria um array; `hash_equals` daria erro. |
| `!hash_equals(...)` | Compara em **tempo constante**. Um `==` comum para no primeiro caractere diferente, e medindo o tempo daria para descobrir o token aos poucos (*timing attack*). |

`http_response_code(403)` = "Proibido". `exit` garante que **nada** depois disso roda.

---

## 5. Onde a proteção foi aplicada

São quatro partes, e todas precisam existir para funcionar.

### Parte A: os "porteiros" carregam o helper

Os três arquivos de validação de login passaram a incluir o `csrf.php`:

- `config/valida.php` (cliente)
- `public/vendedor/valida_vendedor.php` (vendedor)
- `adm/valida_adm.php` (admin)

```php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/csrf.php';   // ← novo
```

Assim, qualquer página que já faz `include 'valida.php'` ganha as funções de graça.

Também troquei o `session_start();` solto de `valida.php` e `valida_adm.php` pela versão com `if (session_status() === PHP_SESSION_NONE)`, igual à que `valida_vendedor.php` já usava.

### Parte B: quem altera dados verifica o token

Logo depois do `include` do porteiro, foi adicionado:

```php
include '../config/valida.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
```

Usado em páginas que **mostram** algo no GET e **alteram** algo no POST:

| Área | Arquivos |
|---|---|
| Cliente | `perfil.php`, `perfil_vendedor.php`, `reservas.php`, `configuracoes.php` |
| Carrinho / favoritos | `carrinho/adicionar_ao_carrinho.php`, `carrinho/remover_do_carrinho.php`, `backend/adicionar_favoritos.php`, `backend/remover_favorito.php` |
| Vendedor | `excluir_tour.php`, `editar_tour.php`, `editar_reserva.php`, `reservas.php`, `vender_hospedagem.php`, `admin.php`, `configuracoes2.php`, `gerir_hospedagem.php` |
| Admin | `adm/desativar_usuario.php`, `adm/verifica_vendedores.php` |

Já estes arquivos **só servem para executar uma ação**, então chamam `csrf_verificar();` **sem o `if`** (o que também os obriga a ser POST):

- `public/send_message2.php` e `public/vendedor/send_message.php` (chat)
- `public/vendedor/vender_tours2.php` (cadastrar passeio)
- `public/carrinho/efetuar_reserva.php` (finalizar reserva)

> **Caso especial:** `efetuar_reserva.php` antes rodava com **qualquer** requisição. Bastava abrir a URL para reservar tudo do carrinho. Agora só funciona com POST + token.

> **Caso especial:** `gerir_hospedagem.php` não usa porteiro (só checa `$_SESSION['id']`), então inclui o `csrf.php` diretamente.

### Parte C: os formulários enviam o token

Nos formulários em HTML, uma linha logo depois da tag `<form>`:

```php
<form action="excluir_tour.php" method="post">
    <?= csrf_campo() ?>
    ...
```

> `<?= ... ?>` é atalho para `<?php echo ...; ?>`.

Nos formulários montados com `echo` (listagens):

```php
echo '<form action="../carrinho/adicionar_ao_carrinho.php" method="post">';
echo csrf_campo();
```

Duas páginas públicas não abriam sessão e precisaram incluir o helper no topo, **antes de qualquer HTML** (sessão não pode ser iniciada depois que a saída começou):

- `public/voos/voos.php`
- `public/tours/pesquisa2_tours.php`

### Parte D: requisições AJAX também enviam o token

Requisições feitas por JavaScript não passam por um `<form>`, então o token vai no corpo:

**Chat** (`chat2.php`, `vendedor/chat.php`):
```js
fetch('send_message2.php', {
    method: 'POST',
    body: new URLSearchParams({
        'user_id': selectedUserId,
        'message': message,
        'csrf_token': '<?= csrf_token() ?>'   // ← novo
    })
})
```

**Configurações** (`configuracoes.php`, `configuracoes2.php`):
```js
xhr.send('toggle_notificacoes_ofertas=1&notificacoes_ofertas=' + newStatus
         + '&csrf_token=<?= csrf_token() ?>');   // ← novo
```

O PHP roda **antes** do JavaScript chegar ao navegador, então `<?= csrf_token() ?>` vira o valor real do token dentro do script.

**Pedidos de amizade** (`perfil.php`): usa `$(this).serialize()` do jQuery, que já inclui todos os campos do formulário, inclusive o `csrf_token`. Bastou colocar o campo no form.

---

## 6. O que ficou de fora (e por quê)

| O quê | Por quê |
|---|---|
| Formulários de **navegação** ("ver detalhes", filtro de categoria, `historico_tours`) | Não alteram nada no banco. Exigir token ali só criaria erros. |
| **Login e registro** | Esses arquivos chamam `session_start()` no meio do HTML. Precisam ser reorganizados antes. Está no TODO. |
| **Logout** | É um link GET usado até em páginas `.html` estáticas. Precisa virar POST. Está no TODO. |
| Formulários para arquivos que **não existem** (`adm/editar_reserva.php`, `subscribe.php`...) | Bug separado, registrado no TODO. |

---

## 7. Bug corrigido de brinde

Em `public/vendedor/configuracoes2.php`, os botões de notificação enviavam para `configuracoes.php`. Mas esse arquivo **não existe dentro de `vendedor/`**, então a configuração do vendedor nunca era salva. Agora aponta para `configuracoes2.php`.

---

## 8. Como foi testado

1. `php -l` (verificação de sintaxe) em todos os arquivos alterados: sem erros.
2. Teste isolado do `csrf_verificar()`:

| Cenário | Resultado |
|---|---|
| POST com token certo | ✅ passa |
| POST com token errado | ❌ 403 |
| POST sem token | ❌ 403 |
| POST com token como array | ❌ 403 |
| GET (mesmo com token) | ❌ 403 |

3. Uma varredura procurando formulários POST sem token: só sobraram os da seção 6.

**Ainda falta:** testar no navegador com o XAMPP ligado.

### Como você pode testar

1. Ligue o XAMPP, faça login, use o site normalmente (carrinho, reserva, chat, editar passeio, trocar senha). Tudo deve funcionar igual.
2. Simule um ataque: no navegador, abra o DevTools (F12) → Elements, ache um `<input name="csrf_token">`, apague o campo e envie o formulário. Deve aparecer **"Pedido inválido"**.
3. Ou crie um arquivo `ataque.html` fora do projeto com o formulário da seção 2, abra-o com o vendedor logado: deve dar **403** e o passeio continua lá.

---

## 9. Resumo para revisar

- **CSRF** = outro site usa a sessão da vítima para fazer ações no seu site.
- O **cookie vai sozinho**; por isso, só o login não basta.
- **Defesa**: token secreto na sessão + no formulário; o servidor compara os dois.
- O atacante **não consegue ler** o token (Same-Origin Policy).
- `random_bytes` → token imprevisível. `hash_equals` → comparação segura.
- Ações que alteram dados → **só POST**.
- Quatro partes: **helper** → **porteiros** → **verificação nos handlers** → **campo nos forms/AJAX**.

---

## 10. Perguntas para fixar

1. Se o atacante não sabe a senha da vítima, como ele consegue apagar um passeio?
2. Por que o site malicioso não pode simplesmente ler o token da sua página?
3. Qual a diferença entre `$a == $b` e `hash_equals($a, $b)` para comparar tokens?
4. Por que `efetuar_reserva.php` chama `csrf_verificar()` sem o `if (POST)`?
5. Por que `voos.php` precisou incluir o `csrf.php` **antes** do `<!DOCTYPE html>`?
6. O formulário "ver detalhes" (`detalhes_tours.php`) não tem token. Isso é um problema? Por quê?
7. Se você criar um novo formulário que apaga algo, quais **duas** coisas precisa lembrar de fazer?

<details>
<summary>Respostas</summary>

1. O navegador da vítima envia o cookie de sessão automaticamente para o BestWay, mesmo quando a requisição parte de outro site.
2. Same-Origin Policy: o navegador deixa outro domínio *enviar* requisições, mas não *ler* a resposta.
3. `==` pode parar no primeiro caractere diferente (vaza informação pelo tempo); `hash_equals` sempre leva o mesmo tempo.
4. O arquivo só executa uma ação. Sem o `if`, `csrf_verificar()` também rejeita GET, então abrir a URL não faz mais a reserva.
5. `session_start()` envia um cookie (cabeçalho HTTP), e cabeçalhos só podem ser enviados antes de qualquer saída HTML.
6. Não: ele só mostra informações, não altera nada. CSRF importa em ações com efeito.
7. Colocar `<?= csrf_campo() ?>` no formulário **e** chamar `csrf_verificar()` no arquivo que processa o POST.

</details>
