# TODO

Lista do que ainda falta, por prioridade. Gerada a partir de uma revisão do código depois do PR #11.

## Segurança

- [x] **Proteção CSRF** nas ações de utilizadores autenticados — `config/csrf.php` (`csrf_campo()` nos
      formulários, `csrf_verificar()` nos ficheiros que alteram dados, token também nos pedidos AJAX do chat e das definições).
- [x] CSRF no login e nos registos (`login.php`/`login2.php`, `registro.php`/`backend/criar_conta.php`, `registro_vendedor.php`).
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
- [x] Lotação dos passeios: `t_artigo.vagas` (pessoas por dia, vazio = sem limite), definida pelo vendedor (migração 007).
- [x] N+1 queries em `listar_tours.php` e `listar_hospedagem.php` — agora uma só consulta com `JOIN`.
- [x] `return_url` removido dos formulários (o servidor já não o usava).
- [x] Formulários e links para ficheiros inexistentes — a newsletter passa a funcionar (`public/backend/newsletter.php`,
      tabela `t_newsletter`) nas 12 páginas; links "Passagens"/"Pacotes" do menu, links `public/...` relativos, scripts,
      CSS e imagens com caminho ou maiúsculas erradas corrigidos (verificado com um rastreio de links em 39 páginas).
- [x] Removido o botão "serviço de localização" das configurações (não fazia nada no servidor).
- [x] Redirecionamento para o login falhava em páginas que já tinham começado a escrever HTML — agora o header é enviado antes.
- [x] Links quebrados no cabeçalho de `efetuar_reserva.php` e de ~25 outras páginas.

## Funcionalidades em falta

- [ ] **Pacotes** (`public/pacotes/pacotes.php`) é só HTML estático — não há tabela nem gestão de pacotes.
- [ ] Pagamento (atualmente "reservar" não cobra nada).
- [x] Cancelamento de reserva pelo cliente (antes do início), vendedor e admin — a reserva fica com `estado = 'cancelada'`.
- [ ] Recuperação de palavra-passe / confirmação de e-mail.
- [x] Avaliações dos passeios e hospedagens (`config/avaliacoes.php`, `views/partials/avaliacoes.php`) — só quem já usou a reserva avalia.
- [x] Resposta do vendedor às avaliações (só o vendedor dono do item; migração 008).
- [x] Paginação (`config/paginacao.php`): 12 por página nas listagens de passeios e hospedagens, 25 nos painéis do admin.
- [ ] Envio de e-mails de ofertas (para `t_newsletter` e utilizadores com `notificacoes_ofertas`) — falta um serviço de e-mail.

## Organização do código

- [x] Cabeçalho e rodapé comuns (`views/partials/header.php` e `footer.php`) em todas as páginas PHP; CSS das páginas de
      mensagem em `assets/css/mensagem.css`. Ficam de fora as páginas estáticas (`index.html`, `public/contatos/*.html`).
- [ ] O CSS específico de cada página continua dentro de `<style>` na própria página.
- [x] Página de acesso negado única (`config/acesso.php`), usada pelos três validadores, com HTTP 403.
- [x] Constantes `TIPO_CLIENTE`, `TIPO_BLOQUEADO`, `TIPO_VENDEDOR`, `TIPO_VENDEDOR_PENDENTE`, `TIPO_ADMIN` (`config/acesso.php`).
- [x] Reativar um vendedor bloqueado tornava-o cliente — o tipo anterior fica em `tipo_antes_bloqueio` (migração 006);
      administradores já não podem ser bloqueados; "aprovar vendedor" só afeta registos pendentes.
- [x] Ficheiros com nomes "2" renomeados pelo que fazem (`backend/autenticar.php`, `backend/criar_conta.php`, `chat.php`,
      `chat_mensagens.php`, `chat_enviar.php`, `vendedor/guardar_tour.php`, `vendedor/configuracoes.php`, `tours/pesquisa_tours.php`).
- [x] Testes de fumo (`tests/smoke_test.sh`, 36 verificações) correm no CI com PHP 8.3 + MariaDB a cada push.
