#!/usr/bin/env bash
# Testes de fumo do BestWay: abrem o site real (servidor PHP + base de dados) e verificam os
# fluxos principais — login, registo, reservas, avaliações, permissões e proteção contra XSS.
#
# Uso:  tests/smoke_test.sh [URL_BASE]
#   URL_BASE  endereço do site (por omissão http://127.0.0.1:8080)
# Variáveis de ambiente:
#   MYSQL    comando para a base de dados de teste (por omissão: mysql -uroot site_viagens)
#   PHP_LOG  ficheiro de log do servidor PHP; se existir, falha se aparecerem erros/avisos do PHP
#
# ATENÇÃO: altera dados (senhas dos utilizadores de exemplo, reservas, avaliações). Use só numa
# base de dados de teste, importada de database/site_viagens.sql.

set -u
cd "$(dirname "$0")/.."   # caminhos relativos à raiz do projeto
B="${1:-http://127.0.0.1:8080}"
B="${B%/}"
MYSQL="${MYSQL:-mysql -uroot site_viagens}"
T=$(mktemp -d)
trap 'rm -rf "$T"' EXIT

passou=0; falhou=0
ok()    { passou=$((passou + 1)); echo "  ok    $1"; }
falha() { falhou=$((falhou + 1)); echo "  FALHA $1"; }
verifica() { if eval "$2"; then ok "$1"; else falha "$1"; fi; }

q()   { $MYSQL -N -e "$1"; }
d()   { date -d "+$1 day" +%F; }
txt() { sed 's/<[^>]*>//g' | tr -s ' \t\n' ' '; }
# token CSRF de uma página (a sessão fica no ficheiro de cookies do utilizador)
token() { curl -s -b "$T/$1" -c "$T/$1" "$B/$2" | grep -o 'name="csrf_token" value="[^"]*"' | head -1 | sed 's/.*value="//;s/"//'; }
post()  { local u=$1 url=$2; shift 2; curl -s -b "$T/$u" -c "$T/$u" -d "csrf_token=$(token "$u" public/hotels/detalhes_hospedagem.php?id=2)" "$@" "$B/$url"; }
login() { curl -s -b "$T/$1" -c "$T/$1" -d "csrf_token=$(token "$1" public/login.php)&nick=$1&pass=teste123" "$B/public/backend/login2.php"; }
status(){ curl -s -o /dev/null -w '%{http_code}' -b "$T/$1" "$B/$2"; }

echo "== Preparação"
HASH=$(php -r 'echo password_hash("teste123", PASSWORD_DEFAULT);')
q "UPDATE t_user SET pass='$HASH' WHERE nick IN ('jujuba','Karol','vendedor','admin');
   DELETE FROM t_carrinho; DELETE FROM t_avaliacoes; UPDATE t_hospedagem SET n_quartos = 2 WHERE id = 2;"
touch "$T/anon"
for u in jujuba Karol vendedor admin; do
    verifica "login de $u" '[[ "$(login '"$u"')" == *"Bem vindo"* ]]'
done

echo "== Páginas abrem para cada tipo de utilizador"
PAGINAS="index.html public/login.php public/registro.php public/hotels/hotels.php public/hotels/listar_hospedagem.php
public/hotels/detalhes_hospedagem.php?id=2 public/tours/tours.php public/tours/listar_tours.php public/tours/detalhes_tours.php?id=1
public/voos/voos.php public/pacotes/pacotes.php public/perfil.php public/reservas.php public/favoritos.php public/chat2.php
public/configuracoes.php public/carrinho/carrinho.php public/vendedor/admin.php public/vendedor/gerenciar_reservas.php
public/vendedor/gestao_tours.php public/vendedor/gerir_hospedagem.php adm/index.php adm/reservas.php adm/vendedores.php"
for u in anon jujuba vendedor admin; do
    erros=""
    for p in $PAGINAS; do
        c=$(status $u "$p"); [[ "$c" =~ ^(200|403)$ ]] || erros="$erros $p($c)"
    done
    verifica "todas as páginas respondem 200/403 para $u" '[ -z "$erros" ] || { echo "       $erros"; false; }'
done

echo "== Permissões"
verifica "visitante não vê reservas (403)"          '[ "$(status anon public/reservas.php)" = 403 ]'
verifica "cliente não entra no painel do vendedor"  '[ "$(status jujuba public/vendedor/admin.php)" = 403 ]'
verifica "cliente não entra no painel do admin"     '[ "$(status jujuba adm/index.php)" = 403 ]'
verifica "vendedor não entra no painel do admin"    '[ "$(status vendedor adm/index.php)" = 403 ]'
verifica "vendedor entra no seu painel"             '[ "$(status vendedor public/vendedor/admin.php)" = 200 ]'
verifica "admin entra no painel"                    '[ "$(status admin adm/index.php)" = 200 ]'
verifica "POST sem token CSRF é recusado"           '[[ "$(curl -s -b $T/jujuba -d cancelar=1\&reserva_id=1 $B/public/reservas.php)" == *"token de segurança"* ]]'

echo "== Registo"
NICK="teste$RANDOM"
curl -s -b "$T/novo" -c "$T/novo" -o /dev/null "$B/public/registro.php"
R=$(curl -s -b "$T/novo" -c "$T/novo" -F "csrf_token=$(token novo public/registro.php)" -F nick=$NICK -F nome=Teste \
    -F email=$NICK@exemplo.com -F data_nasc=2000-01-01 -F pass=segredo -F "ficheiro=@public/hotels/imagens/hotel.jpg" "$B/public/registro2.php")
verifica "registo de cliente com foto"              '[[ "$R" == *"registado com sucesso"* ]]'
verifica "registo grava o utilizador"               '[ "$(q "SELECT COUNT(*) FROM t_user WHERE nick='"'$NICK'"'")" = 1 ]'
rm -f assets/images/pics/"$(q "SELECT foto FROM t_user WHERE nick='$NICK'")" 2>/dev/null
q "DELETE FROM t_user WHERE nick='$NICK'"

echo "== Reservas"
R=$(post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=hospedagem -d id_artigo=2 -d data_checkin=$(d 10) \
    -d data_checkout=$(d 12) -d n_quartos=1 -d total=1 | txt)
verifica "hospedagem no carrinho com preço do servidor (2 noites × 3000 €)" '[[ "$R" == *"Total: 6.000,00 €"* ]]'
R=$(post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=hospedagem -d id_artigo=2 -d data_checkin=$(d 12) \
    -d data_checkout=$(d 10) -d n_quartos=1 | txt)
verifica "datas invertidas são recusadas"           '[[ "$R" == *"check-out tem de ser depois"* ]]'
R=$(post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=atividade -d id_artigo=1 -d data_inicio=$(d 5) -d quantidade=3 | txt)
verifica "passeio para 3 pessoas (3 × 50 €)"        '[[ "$R" == *"Total: 150,00 €"* ]]'
R=$(post Karol public/carrinho/adicionar_ao_carrinho.php -d tipo_item=hospedagem -d id_artigo=2 -d data_checkin=$(d 11) \
    -d data_checkout=$(d 13) -d n_quartos=2 | txt)
verifica "outra cliente põe os 2 quartos no carrinho" '[[ "$R" == *"Total: 12.000,00 €"* ]]'
R=$(post Karol public/carrinho/efetuar_reserva.php | txt)
verifica "e reserva primeiro"                       '[[ "$R" == *"Reserva efetuada com sucesso"* ]]'
R=$(post jujuba public/carrinho/efetuar_reserva.php | txt)
verifica "o checkout da jujuba falha (sem quartos)" '[[ "$R" == *"Não há quartos livres"* ]]'
verifica "e nada fica reservado pela metade"        '[ "$(q "SELECT COUNT(*) FROM t_reservas WHERE user_id=6 AND data_inicio IS NOT NULL")" = 0 ]'
RID=$(q "SELECT id FROM t_reservas WHERE user_id=7 AND data_inicio IS NOT NULL ORDER BY id DESC LIMIT 1")
curl -s -o /dev/null -b "$T/Karol" -d "csrf_token=$(token Karol public/reservas.php)&cancelar=1&reserva_id=$RID" "$B/public/reservas.php"
verifica "cliente cancela a reserva"                '[ "$(q "SELECT estado FROM t_reservas WHERE id=$RID")" = cancelada ]'
R=$(post jujuba public/carrinho/efetuar_reserva.php | txt)
verifica "quartos libertados: agora a jujuba reserva" '[[ "$R" == *"Reserva efetuada com sucesso"* ]]'
verifica "o preço pago fica gravado"                '[ "$(q "SELECT preco_total FROM t_reservas WHERE user_id=6 AND tipo_reserva='"'hospedagem'"' AND data_inicio IS NOT NULL")" = 6000.00 ]'

echo "== Lotação dos passeios"
TOK=$(token vendedor public/vendedor/gestao_tours.php)
TID=$(q "SELECT id FROM t_artigo WHERE id_user=9 LIMIT 1")
curl -s -o /dev/null -b "$T/vendedor" -d "csrf_token=$TOK&edit=1&reserva_id=$TID&titulo=Passeio&descricao=d&preco=10&local=Porto&data_inicio=$(d 0)&data_fim=$(d 60)&vagas=3" "$B/public/vendedor/editar_tour.php"
verifica "vendedor define 3 vagas por dia"          '[ "$(q "SELECT vagas FROM t_artigo WHERE id=$TID")" = 3 ]'
post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=atividade -d id_artigo=$TID -d data_inicio=$(d 20) -d quantidade=2 >/dev/null
R=$(post jujuba public/carrinho/efetuar_reserva.php | txt)
verifica "reserva de 2 pessoas"                     '[[ "$R" == *"Reserva efetuada com sucesso"* ]]'
R=$(post Karol public/carrinho/adicionar_ao_carrinho.php -d tipo_item=atividade -d id_artigo=$TID -d data_inicio=$(d 20) -d quantidade=2 | txt)
verifica "mais 2 pessoas no mesmo dia são recusadas" '[[ "$R" == *"Só há 1 vaga(s) nesse dia"* ]]'
R=$(post Karol public/carrinho/adicionar_ao_carrinho.php -d tipo_item=atividade -d id_artigo=$TID -d data_inicio=$(d 21) -d quantidade=2 | txt)
verifica "noutro dia há vagas"                      '[[ "$R" == *"adicionado ao carrinho"* ]]'
q "DELETE FROM t_carrinho"

echo "== Avaliações"
q "INSERT INTO t_reservas (item_id,user_id,tipo_reserva,data_reserva,quantidade,data_inicio,data_fim,estado)
   VALUES (4,6,'hospedagem',CURDATE(),1,'$(d -10)','$(d -8)','confirmada')"
R=$(curl -s -b "$T/jujuba" "$B/public/hotels/detalhes_hospedagem.php?id=4" | txt)
verifica "quem já usou a reserva vê o formulário"   '[[ "$R" == *"Deixe a sua avaliação"* ]]'
post jujuba public/backend/avaliar.php -d tipo_item=hospedagem -d item_id=4 -d nota=4 --data-urlencode "comentario=Ótimo <script>alert(1)</script>" >/dev/null
H=$(curl -s -b "$T/jujuba" "$B/public/hotels/detalhes_hospedagem.php?id=4")
verifica "avaliação aparece com a média"            '[[ "$(echo "$H" | txt)" == *"4,0 / 5"* ]]'
verifica "comentário com <script> sai escapado"     '[[ "$H" == *"&lt;script&gt;alert(1)"* && "$H" != *"<script>alert(1)"* ]]'
post Karol public/backend/avaliar.php -d tipo_item=hospedagem -d item_id=4 -d nota=5 >/dev/null
verifica "quem não reservou não consegue avaliar"   '[ "$(q "SELECT COUNT(*) FROM t_avaliacoes WHERE item_id=4")" = 1 ]'
verifica "listagem mostra a média"                  '[[ "$(curl -s $B/public/hotels/listar_hospedagem.php)" == *"4,0 (1 avaliação)"* ]]'

echo "== Newsletter e logout"
verifica "inscrição na newsletter"                  '[[ "$(curl -s -d email=smoke@exemplo.com $B/public/backend/newsletter.php)" == *"Inscrição feita"* ]]'
q "DELETE FROM t_newsletter WHERE email='smoke@exemplo.com'"
verifica "link de logout só pede confirmação"       '[[ "$(curl -s -b $T/jujuba $B/public/backend/logout.php)" == *"Deseja terminar a sessão"* ]]'
curl -s -o /dev/null -b "$T/jujuba" -c "$T/jujuba" -d "csrf_token=$(token jujuba public/backend/logout.php)" "$B/public/backend/logout.php"
verifica "logout com token termina a sessão"        '[ "$(status jujuba public/reservas.php)" = 403 ]'

if [ -n "${PHP_LOG:-}" ] && [ -f "$PHP_LOG" ]; then
    echo "== Log do PHP"
    erros=$(grep -aE "PHP (Fatal|Warning|Deprecated|Notice)|BestWay:" "$PHP_LOG" | sed 's/.*PHP //' | sort -u)
    verifica "sem erros nem avisos do PHP" '[ -z "$erros" ] || { echo "$erros" | head -20; false; }'
fi

echo
echo "$passou passaram, $falhou falharam"
[ "$falhou" = 0 ]
