# TODO

Lista do que ainda falta, por prioridade. Gerada a partir de uma revisão do código depois do PR #11.

## Segurança

- [x] **Proteção CSRF** nas ações de utilizadores autenticados — `config/csrf.php` (`csrf_campo()` nos
      formulários, `csrf_verificar()` nos ficheiros que alteram dados, token também nos pedidos AJAX do chat e das definições).
- [x] CSRF no login e nos registos (`login.php`/`login2.php`, `registro.php`/`registro2.php`, `registro_vendedor.php`).
- [x] Logout só por POST com token — os links "Logout" abrem uma página de confirmação.
- [x] **XSS** — os textos vindos da BD (títulos, descrições, nomes, nicks, fotos, categorias) passam por
      `htmlspecialchars` em todas as páginas. Testado com títulos/nomes contendo `<script>` em 19 páginas.
- [x] **Preço do voo vem do cliente** — os resultados da SerpAPI ficam na sessão; o formulário só envia uma referência.
- [x] **`total` vem do cliente** no mesmo ficheiro — o preço passa a ser calculado no servidor (`config/reservas.php`).
- [x] **Fixação de sessão** — novo ID de sessão a cada login; cookie de sessão `HttpOnly`, `SameSite=Lax` e
      `use_strict_mode` (`config/csrf.php`).
- [x] **Quantidade sem validação** — carrinho e `editar_reserva.php` validam quartos (1 a `n_quartos`) e pessoas (1 a 20).
- [x] Mensagens de erro já não expõem detalhes da BD — tratador de exceções em `config/config.php` (mensagem genérica
      + `error_log`; `'debug' => true` em `config.local.php` mostra os detalhes).
- [x] Consultas com concatenação passaram a prepared statements (`historico_tours`, `listar_tours`, `listar_hospedagem`,
      `favoritos`, `vendedor/admin`, `vender_tours`).
- [x] `gestao_opcoes.php`, `vender_opcoes.php` e `gerir_hospedagem.php` exigem login de vendedor.

## Bugs / lógica

- [x] Reserva verifica datas e quartos livres, e `efetuar_reserva.php` usa uma transação.
- [x] Reserva guarda o preço pago (`preco_unitario`, `preco_total`).
- [ ] Passeios não têm lotação máxima (só se limita a 20 pessoas por reserva). Falta uma coluna de vagas em `t_artigo`.
- [x] N+1 queries em `listar_tours.php` e `listar_hospedagem.php` — agora uma só consulta com `JOIN`.
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
