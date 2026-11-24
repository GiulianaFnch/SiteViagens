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
- [x] **Pesquisa de voos falhava** — sem data de volta (faltava `type`), com nomes de cidades em vez de códigos IATA e em
      qualquer erro da API (página cortada); a biblioteca desligava a verificação HTTPS. Reescrita em `config/voos.php`,
      com ida e volta em dois passos e testes contra um servidor SerpAPI falso.
- [x] **Chave da SerpAPI no histórico do Git** (commit `23a6ca9`, repositório público) — tem de ser trocada em serpapi.com.
      O CI passa a recusar chaves no código e `config.local.php`/`.env` no repositório.
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

- [x] **Pacotes** — tabela `t_pacotes` (migração 011), listagem com pesquisa, detalhes com reserva por pessoa e vagas
      por partida, gestão pelo vendedor (`public/vendedor/pacotes.php`) e avaliações (migração 013).
- [ ] Pagamento (atualmente "reservar" não cobra nada).
- [x] Cancelamento de reserva pelo cliente (antes do início), vendedor e admin — a reserva fica com `estado = 'cancelada'`.
- [x] Recuperação de palavra-passe e confirmação de e-mail (`config/contas.php`, `config/email.php`, migração 009).
- [x] Avaliações dos passeios e hospedagens (`config/avaliacoes.php`, `views/partials/avaliacoes.php`) — só quem já usou a reserva avalia.
- [x] Resposta do vendedor às avaliações (só o vendedor dono do item; migração 008).
- [x] Paginação (`config/paginacao.php`): 12 por página nas listagens de passeios e hospedagens, 25 nos painéis do admin.
- [x] Envio de e-mails de ofertas pelo administrador (`adm/ofertas.php`, `config/ofertas.php`), com link para cancelar a subscrição.

## Organização do código

- [x] Cabeçalho, rodapé e newsletter comuns (`views/partials/header.php`, `footer.php`, `newsletter.php`) em todas as
      páginas; a página inicial e as de contactos, termos e privacidade deixaram de ser HTML estático (`index.php`,
      `public/contatos/*.php`) e usam `views/partials/pagina_inicio.php`/`pagina_fim.php`.
- [x] Layouts comuns: páginas de conta (`conta_inicio/fim`) e painéis do cliente e do vendedor (`painel_inicio/fim`,
      `assets/css/painel.css`) — menu lateral, cabeçalho e cartão num só sítio.
- [x] Perfil, chat e configurações do cliente e do vendedor eram cópias quase iguais: uma só implementação em
      `views/paginas/`. Os ficheiros de mensagens do chat estão em `public/backend/` (um para os dois chats).
- [x] Sem `<style>` nas páginas: o CSS está em `assets/css/` (um ficheiro por página ou grupo) e o JavaScript do chat,
      perfil e configurações em `assets/js/`. Mudanças verificadas com capturas de todas as páginas antes e depois.
- [x] Reservas: título e vendedor de cada tipo de reserva, e o período das datas, numa só função
      (`reserva_sql_titulo`, `reserva_sql_vendedor`, `reserva_formatar_periodo` em `config/reservas.php`).
- [x] Página de acesso negado única (`config/acesso.php`), usada pelos três validadores, com HTTP 403.
- [x] Constantes `TIPO_CLIENTE`, `TIPO_BLOQUEADO`, `TIPO_VENDEDOR`, `TIPO_VENDEDOR_PENDENTE`, `TIPO_ADMIN` (`config/acesso.php`).
- [x] Reativar um vendedor bloqueado tornava-o cliente — o tipo anterior fica em `tipo_antes_bloqueio` (migração 006);
      administradores já não podem ser bloqueados; "aprovar vendedor" só afeta registos pendentes.
- [x] Ficheiros com nomes "2" renomeados pelo que fazem (`backend/autenticar.php`, `backend/criar_conta.php`, `chat.php`,
      `vendedor/guardar_tour.php`, `vendedor/configuracoes.php`, `tours/pesquisa_tours.php`).
- [x] Testes de fumo (`tests/smoke_test.sh`, 117 verificações) correm no CI com PHP 8.3 + MariaDB a cada push.
- [ ] As páginas de passeios, hospedagens, voos e pacotes ainda montam o próprio `<head>`; poderiam usar
      `pagina_inicio.php`. Restam alguns `style="..."` em elementos soltos.
