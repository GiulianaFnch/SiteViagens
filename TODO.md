# TODO

Lista do que ainda falta, por prioridade. Gerada a partir de uma revisão do código depois do PR #11.

## Segurança

- [x] **Proteção CSRF** nas ações de utilizadores autenticados — `config/csrf.php` (`csrf_campo()` nos
      formulários, `csrf_verificar()` nos ficheiros que alteram dados, token também nos pedidos AJAX do chat e das definições).
- [ ] CSRF no login e nos registos (`public/backend/login2.php`, `public/registro2.php`,
      `public/vendedor/registro_vendedor.php`) — exige primeiro mover o `session_start()` para antes do HTML.
- [ ] Logout por GET (`public/backend/logout.php`) — qualquer página pode terminar a sessão do utilizador.
      Passar a POST com token (os links estão também em páginas estáticas como `index.html`).
- [x] **XSS** — os textos vindos da BD (títulos, descrições, nomes, nicks, fotos, categorias) passam por
      `htmlspecialchars` em todas as páginas. Testado com títulos/nomes contendo `<script>` em 19 páginas.
- [ ] **Preço do voo vem do cliente** — `public/carrinho/adicionar_ao_carrinho.php` grava em `t_voos` o
      `price` enviado no POST. Recalcular/validar no servidor (ou guardar o resultado da SerpAPI na sessão).
- [x] **`total` vem do cliente** no mesmo ficheiro — o preço passa a ser calculado no servidor (`config/reservas.php`).
- [ ] **Login sem `session_regenerate_id(true)`** em `public/backend/login2.php` (fixação de sessão).
- [x] **Quantidade sem validação** — carrinho e `editar_reserva.php` validam quartos (1 a `n_quartos`) e pessoas (1 a 20).
- [ ] Mensagens de erro expõem detalhes da BD (`or die(mysqli_error(...))` em vários ficheiros). Trocar por
      mensagem genérica + `error_log`.
- [ ] Consultas ainda montadas com concatenação (hoje seguras porque os valores são `int`/sessão, mas frágeis):
      `public/tours/historico_tours.php`, `public/tours/listar_tours.php`, `public/hotels/listar_hospedagem.php`
      (usa `real_escape_string`), `public/vendedor/admin.php`, `public/vendedor/vender_tours.php`. Passar a prepared statements.
- [ ] `public/vendedor/gestao_opcoes.php` e `vender_opcoes.php` não exigem login de vendedor
      (só mostram menus, mas devem incluir `valida_vendedor.php`).

## Bugs / lógica

- [x] Reserva verifica datas e quartos livres, e `efetuar_reserva.php` usa uma transação.
- [x] Reserva guarda o preço pago (`preco_unitario`, `preco_total`).
- [ ] Passeios não têm lotação máxima (só se limita a 20 pessoas por reserva). Falta uma coluna de vagas em `t_artigo`.
- [ ] N+1 queries em `listar_tours.php` e `listar_hospedagem.php` (uma consulta de utilizador por item) — usar `JOIN`.
- [ ] `adicionar_ao_carrinho.php` lê `return_url` mas convém garantir que só redireciona para caminhos internos.
- [ ] Formulários que apontam para ficheiros inexistentes:
      `public/hotels/listar_hospedagem.php` (`carrinho.php`), `public/voos/voos.php` (`subscribe.php`).
- [ ] O "serviço de localização" em `configuracoes.php`/`configuracoes2.php` envia `toggle_location_service`, mas o servidor não trata esse campo.
- [ ] Links quebrados no cabeçalho de `efetuar_reserva.php` (`#package`, `#contact`, `public/login.php` relativo).

## Funcionalidades em falta

- [ ] **Pacotes** (`public/pacotes/pacotes.php`) é só HTML estático — não há tabela nem gestão de pacotes.
- [ ] Pagamento (atualmente "reservar" não cobra nada).
- [x] Cancelamento de reserva pelo cliente (antes do início), vendedor e admin — a reserva fica com `estado = 'cancelada'`.
- [ ] Recuperação de palavra-passe / confirmação de e-mail.
- [ ] Avaliações/comentários dos passeios e hospedagens.
- [ ] Paginação nas listagens e no painel de administração.
- [ ] Notificações de ofertas (`notificacoes_ofertas` existe na BD mas nada envia e-mails).

## Organização do código

- [ ] Cabeçalho, rodapé e CSS repetidos inline em quase todas as páginas — usar `views/partials/header.php` e `footer.php`.
- [ ] Páginas de erro de acesso duplicadas em `config/valida.php`, `adm/valida_adm.php` e `public/vendedor/valida_vendedor.php`.
- [ ] Números mágicos de `tipo_user` (0, 1 = bloqueado, 2 = vendedor, 3, 4 = admin) — definir constantes em `config/config.php`.
- [ ] Ficheiros com nomes "2" (`chat2`, `registro2`, `login2`, `vender_tours2`, `configuracoes2`, `pesquisa2_tours`) — renomear pelo que fazem.
- [ ] Testes: o CI só faz lint. Adicionar pelo menos testes de fumo (login, registo, reservar).
