#!/usr/bin/env bash
# Testes de fumo do BestWay: abrem o site real (servidor PHP + base de dados) e verificam os
# fluxos principais — login, registo e recuperação de palavra-passe, reservas, avaliações, permissões e proteção contra XSS.
#
# Uso:  tests/smoke_test.sh [URL_BASE]
#   URL_BASE  endereço do site (por omissão http://127.0.0.1:8080)
# Variáveis de ambiente:
#   MYSQL    comando para a base de dados de teste (por omissão: mysql -uroot site_viagens)
#   PHP_LOG  ficheiro de log do servidor PHP; se existir, falha se aparecerem erros/avisos do PHP
#   EMAILS   pasta onde o site grava os e-mails (modo 'ficheiro'; por omissão storage/emails)
#   SERPAPI_FALSO  endereço do servidor falso da SerpAPI (tests/serpapi_falso.php) com que o site foi arrancado
#            (SERPAPI_URL=<este endereço> SERPAPI_KEY=chave-de-teste); sem ele, os testes dos voos são saltados
#
# ATENÇÃO: altera dados (senhas dos utilizadores de exemplo, reservas, avaliações). Use só numa
# base de dados de teste, importada de database/site_viagens.sql.

set -u
cd "$(dirname "$0")/.."   # caminhos relativos à raiz do projeto
B="${1:-http://127.0.0.1:8080}"
B="${B%/}"
MYSQL="${MYSQL:-mysql -uroot site_viagens}"
EMAILS="${EMAILS:-storage/emails}"
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
login() { curl -s -b "$T/$1" -c "$T/$1" -d "csrf_token=$(token "$1" public/login.php)&nick=$1&pass=teste123" "$B/public/backend/autenticar.php"; }
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
public/voos/voos.php public/pacotes/pacotes.php public/perfil.php public/reservas.php public/favoritos.php public/chat.php
public/configuracoes.php public/carrinho/carrinho.php public/vendedor/admin.php public/vendedor/gerenciar_reservas.php
public/vendedor/gestao_tours.php public/vendedor/gerir_hospedagem.php public/vendedor/pacotes.php
public/vendedor/vender_opcoes.php public/vendedor/gestao_opcoes.php public/pacotes/detalhes_pacote.php?id=1 adm/index.php adm/reservas.php adm/vendedores.php adm/ofertas.php"
for u in anon jujuba vendedor admin; do
    erros=""
    for p in $PAGINAS; do
        c=$(status $u "$p"); [[ "$c" =~ ^(200|403)$ ]] || erros="$erros $p($c)"
    done
    verifica "todas as páginas respondem 200/403 para $u" '[ -z "$erros" ] || { echo "       $erros"; false; }'
done

echo "== Paginação"
TOTAL=$(q "SELECT COUNT(*) FROM t_artigo a JOIN t_user u ON u.id=a.id_user WHERE a.vendido=0")
SOMA=0
for p in 1 2 3 4 5; do SOMA=$((SOMA + $(curl -s "$B/public/tours/listar_tours.php?pagina=$p" | grep -o "<h3>" | wc -l))); done
verifica "páginas de passeios somam o total ($TOTAL)" '[ "$SOMA" = "$TOTAL" ]'
verifica "no máximo 12 passeios por página"          '[ "$(curl -s $B/public/tours/listar_tours.php | grep -o "<h3>" | wc -l)" -le 12 ]'

echo "== Permissões"
verifica "visitante não vê reservas (403)"          '[ "$(status anon public/reservas.php)" = 403 ]'
verifica "cliente não entra no painel do vendedor"  '[ "$(status jujuba public/vendedor/admin.php)" = 403 ]'
verifica "cliente não entra no painel do admin"     '[ "$(status jujuba adm/index.php)" = 403 ]'
verifica "vendedor não entra no painel do admin"    '[ "$(status vendedor adm/index.php)" = 403 ]'
verifica "vendedor entra no seu painel"             '[ "$(status vendedor public/vendedor/admin.php)" = 200 ]'
verifica "admin entra no painel"                    '[ "$(status admin adm/index.php)" = 200 ]'
verifica "POST sem token CSRF é recusado"           '[[ "$(curl -s -b $T/jujuba -d cancelar=1\&reserva_id=1 $B/public/reservas.php)" == *"token de segurança"* ]]'

echo "== Registo e confirmação do e-mail"
NICK="teste$RANDOM"; EMAIL="$NICK@exemplo.com"
# último link de um tipo enviado para um e-mail (no modo 'ficheiro' os e-mails ficam em storage/emails)
link() { local f; f=$(grep -l "$1" "$EMAILS"/*.html 2>/dev/null | xargs -r ls -t | head -1)
         [ -n "$f" ] && grep -o "public/$2?token=[0-9a-f]*" "$f" | head -1; }
registo() { curl -s -L -b "$T/novo" -c "$T/novo" -F "csrf_token=$(token novo public/registro.php)" -F nick=$NICK -F nome=Teste \
    -F email=$EMAIL -F data_nasc=2000-01-01 "$@" -F "ficheiro=@public/hotels/imagens/hotel.jpg" "$B/public/backend/criar_conta.php" | txt; }
entrar() { curl -s -L -b "$T/novo" -c "$T/novo" -d "csrf_token=$(token novo public/login.php)" -d nick=$NICK --data-urlencode "pass=$1" \
    "$B/public/backend/autenticar.php" | txt; }
verifica "palavra-passe curta é recusada"           '[[ "$(registo -F pass=curta -F pass2=curta)" == *"pelo menos 8 caracteres"* ]]'
verifica "palavras-passe diferentes são recusadas"  '[[ "$(registo -F pass=segredo123 -F pass2=segredo124)" == *"não coincidem"* ]]'
verifica "registo de cliente com foto"              '[[ "$(registo -F pass=segredo123 -F pass2=segredo123)" == *"Conta criada"* ]]'
verifica "registo grava o utilizador por confirmar" '[ "$(q "SELECT email_confirmado FROM t_user WHERE nick='"'$NICK'"'")" = 0 ]'
verifica "nick repetido é recusado"                 '[[ "$(registo -F pass=segredo123 -F pass2=segredo123)" == *"nick já está a ser usado"* ]]'
verifica "nick ou senha errados: mensagem genérica" '[[ "$(entrar errada123)" == *"Nick ou palavra-passe errados"* ]]'
verifica "sem confirmar o e-mail não entra"         '[[ "$(entrar segredo123)" == *"Confirme o seu e-mail"* && "$(status novo public/reservas.php)" = 403 ]]'
L=$(link "$EMAIL" confirmar_email.php)
verifica "e-mail de confirmação enviado"            '[ -n "$L" ]'
verifica "link de confirmação falso é recusado"     '[[ "$(curl -s -L -b $T/novo -c $T/novo "$B/public/confirmar_email.php?token=$(printf %064d 0)" | txt)" == *"inválido"* ]]'
verifica "link de confirmação ativa a conta"        '[[ "$(curl -s -L -b $T/novo -c $T/novo "$B/$L" | txt)" == *"E-mail confirmado"* ]]'
verifica "o link de confirmação só serve uma vez"   '[[ "$(curl -s -L -b $T/novo -c $T/novo "$B/$L" | txt)" == *"inválido"* ]]'
verifica "depois de confirmar já entra"             '[[ "$(entrar segredo123)" == *"Bem vindo"* ]]'

echo "== Recuperação da palavra-passe"
esqueci() { curl -s -L -b "$T/anon" -c "$T/anon" -d "csrf_token=$(token anon public/esqueci_senha.php)" -d "email=$1" "$B/public/esqueci_senha.php" | txt; }
verifica "pedido com e-mail registado"              '[[ "$(esqueci $EMAIL)" == *"Se existir uma conta"* ]]'
verifica "e-mail desconhecido: a mesma resposta"    '[[ "$(esqueci ninguem$RANDOM@exemplo.com)" == *"Se existir uma conta"* ]]'
L=$(link "$EMAIL" redefinir_senha.php)
verifica "e-mail de recuperação enviado"            '[ -n "$L" ]'
verifica "o link abre o formulário"                 '[[ "$(curl -s -b $T/anon -c $T/anon "$B/$L" | txt)" == *"Nova palavra-passe"* ]]'
redefinir() { curl -s -L -b "$T/anon" -c "$T/anon" -d "csrf_token=$(token anon "$L")" -d "token=${L##*=}" -d "pass=$1" -d "pass2=$2" "$B/public/redefinir_senha.php" | txt; }
verifica "nova senha tem de ser repetida igual"     '[[ "$(redefinir novasenha1 outrasenha)" == *"não coincidem"* ]]'
verifica "nova palavra-passe guardada"              '[[ "$(redefinir novasenha1 novasenha1)" == *"Palavra-passe alterada"* ]]'
verifica "o link de recuperação só serve uma vez"   '[[ "$(redefinir outrasenha2 outrasenha2)" == *"inválido, já foi usado"* ]]'
verifica "a senha antiga deixa de servir"           '[[ "$(entrar segredo123)" == *"Nick ou palavra-passe errados"* ]]'
verifica "entra com a senha nova"                   '[[ "$(entrar novasenha1)" == *"Bem vindo"* ]]'
for i in 1 2 3; do esqueci $EMAIL >/dev/null; done
verifica "no máximo 3 e-mails de recuperação por hora" '[ "$(q "SELECT COUNT(*) FROM t_tokens t JOIN t_user u ON u.id=t.id_user
    WHERE u.nick='"'$NICK'"' AND t.tipo='"'recuperar_senha'"'")" = 3 ]'
rm -f assets/images/pics/"$(q "SELECT foto FROM t_user WHERE nick='$NICK'")" 2>/dev/null
grep -l "$EMAIL" "$EMAILS"/*.html 2>/dev/null | xargs -r rm -f
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

echo "== Pacotes"
verifica "a página lista os pacotes de exemplo"     '[ "$(curl -s $B/public/pacotes/pacotes.php | grep -c "class=\"pacote-card\"")" -ge 6 ]'
verifica "pesquisa por destino"                     '[[ "$(curl -s "$B/public/pacotes/pacotes.php?destino=It%C3%A1lia" | txt)" == *"1 pacote(s) encontrado(s)"*"Costa Amalfitana"* ]]'
PTIT="Pacote smoke $RANDOM"
pacote_form() { curl -s -L -b "$T/vendedor" -c "$T/vendedor" -F "csrf_token=$(token vendedor public/vendedor/pacotes.php)" -F "titulo=$PTIT" \
    -F destino=Açores -F "descricao=Ilhas e vulcões" -F "inclui=Voo
Hotel" -F preco=100 -F data_inicio=$(d 1) -F data_fim=$(d 90) -F vagas=3 "$@" "$B/public/vendedor/pacotes.php" | txt; }
verifica "duração inválida é recusada"              '[[ "$(pacote_form -F duracao_dias=0)" == *"duração tem de ser entre 1 e"* ]]'
verifica "vendedor cria um pacote com foto"         '[[ "$(pacote_form -F duracao_dias=5 -F "foto=@public/hotels/imagens/hotel.jpg")" == *"Pacote criado"* ]]'
PID=$(q "SELECT id FROM t_pacotes WHERE titulo='$PTIT' AND id_user=9")
verifica "pacote aparece na pesquisa"               '[[ "$(curl -s "$B/public/pacotes/pacotes.php?destino=A%C3%A7ores" | txt)" == *"$PTIT"* ]]'
verifica "cliente não entra na gestão de pacotes"   '[ "$(status jujuba public/vendedor/pacotes.php)" = 403 ]'
R=$(post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=pacote -d id_artigo=$PID -d data_inicio=$(d 30) -d quantidade=2 | txt)
verifica "pacote para 2 pessoas (2 × 100 €)"        '[[ "$R" == *"Total: 200,00 €"* ]]'
verifica "carrinho mostra a partida e o regresso"   '[[ "$(curl -s -b $T/jujuba $B/public/carrinho/carrinho.php | txt)" == *"$(date -d +30day +%d/%m/%Y) a $(date -d +34day +%d/%m/%Y)"* ]]'
R=$(post jujuba public/carrinho/efetuar_reserva.php | txt)
verifica "reserva do pacote"                        '[[ "$R" == *"Reserva efetuada com sucesso"* && "$(q "SELECT data_fim FROM t_reservas WHERE tipo_reserva='"'pacote'"' AND item_id=$PID")" = "$(d 34)" ]]'
R=$(post Karol public/carrinho/adicionar_ao_carrinho.php -d tipo_item=pacote -d id_artigo=$PID -d data_inicio=$(d 30) -d quantidade=2 | txt)
verifica "sem vagas para mais 2 na mesma partida"   '[[ "$R" == *"Só há 1 vaga(s) nessa partida"* ]]'
verifica "a reserva aparece ao vendedor"            '[[ "$(curl -s -b $T/vendedor $B/public/vendedor/gerenciar_reservas.php | txt)" == *"$PTIT"*"Pacote"* ]]'
verifica "a reserva aparece ao cliente"             '[[ "$(curl -s -b $T/jujuba $B/public/reservas.php | txt)" == *"$PTIT"* ]]'
gerir() { curl -s -L -b "$T/vendedor" -c "$T/vendedor" -d "csrf_token=$(token vendedor public/vendedor/pacotes.php)" -d id=$PID -d "$1=1" "$B/public/vendedor/pacotes.php" | txt; }
verifica "com reservas não se apaga"                '[[ "$(gerir apagar)" == *"não pode ser apagado"* ]]'
verifica "desativar esconde o pacote"               '[[ "$(gerir desativar)" == *"Pacote desativado"* && "$(status anon "public/pacotes/detalhes_pacote.php?id=$PID")" = 404 ]]'
R=$(post Karol public/carrinho/adicionar_ao_carrinho.php -d tipo_item=pacote -d id_artigo=$PID -d data_inicio=$(d 40) -d quantidade=1 | txt)
verifica "pacote desativado não se reserva"         '[[ "$R" == *"já não está disponível"* ]]'
q "DELETE FROM t_reservas WHERE tipo_reserva='pacote' AND item_id=$PID"
verifica "sem reservas, o vendedor apaga o pacote"  '[[ "$(gerir apagar)" == *"Pacote apagado"* && "$(q "SELECT COUNT(*) FROM t_pacotes WHERE id=$PID")" = 0 ]]'
q "DELETE FROM t_carrinho"

echo "== Voos"
if [ -z "${SERPAPI_FALSO:-}" ]; then
    echo "  (saltado: defina SERPAPI_FALSO — ver o início deste ficheiro)"
else
    IDA=$(d 20); VOLTA=$(d 27)
    voos() { curl -s -b "$T/jujuba" -c "$T/jujuba" "$B/public/voos/voos.php?$1"; }
    verifica "só ida: um cartão por voo, com escalas"   '[[ "$(voos "origem=lis&destino=mad&ida=$IDA" | txt)" == *"TP 1920 + TP 1980"*"1 escala"*"150,00 €"*"TP 1026"*"direto"*"180,00 €"* ]]'
    verifica "nomes de cidades pedem o código"          '[[ "$(voos "origem=Lisboa&destino=Madrid&ida=$IDA" | txt)" == *"código de 3 letras"* ]]'
    verifica "sem voos: mensagem, não erro"             '[[ "$(voos "origem=LIS&destino=ZZZ&ida=$IDA" | txt)" == *"Não encontrámos voos"* ]]'
    verifica "volta antes da ida é recusada"            '[[ "$(voos "origem=LIS&destino=MAD&ida=$VOLTA&volta=$IDA" | txt)" == *"não pode ser antes da ida"* ]]'
    H=$(voos "origem=LIS&destino=MAD&ida=$IDA&volta=$VOLTA")
    verifica "ida e volta: primeiro escolhe-se a ida"   '[[ "$(echo "$H" | txt)" == *"Escolha o voo de ida"*"300,00 €"* ]]'
    L=$(echo "$H" | grep -o '?origem=[^"]*ida_ref=[0-9a-f]*' | tail -1 | sed 's/&amp;/\&/g')
    H=$(voos "${L#\?}")
    verifica "depois a volta, com o preço total"        '[[ "$(echo "$H" | txt)" == *"Escolha o voo de volta"*"TP 1027"*"245,00 €"* ]]'
    REF=$(echo "$H" | grep -o 'name="voo_ref" value="[0-9a-f]*"' | head -1 | sed 's/.*value="//;s/"//')
    R=$(post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=voo -d voo_ref=$REF | txt)
    verifica "voo de ida e volta vai para o carrinho"   '[[ "$R" == *"TP 1026 / TP 1027"*"LIS) → "*"MAD)"*"Total: 245,00 €"* ]]'
    verifica "referência inventada não cria voos"       '[[ "$(post jujuba public/carrinho/adicionar_ao_carrinho.php -d tipo_item=voo -d voo_ref=123 | txt)" == *"já não existe"* ]]'
    R=$(post jujuba public/carrinho/efetuar_reserva.php | txt)
    verifica "reserva guarda as datas do voo"           '[[ "$R" == *"Reserva efetuada"* && "$(q "SELECT CONCAT(data_inicio,'"' '"',data_fim,'"' '"',preco_total) FROM t_reservas WHERE tipo_reserva='"'voo'"' ORDER BY id DESC LIMIT 1")" = "$IDA $VOLTA 245.00" ]]'
    erro_api() { SERPAPI_KEY=$1 SERPAPI_URL=$2 php -r 'require "config/voos.php"; try { voos_pesquisar(["origem"=>"LIS","destino"=>"MAD","ida"=>date("Y-m-d"),"volta"=>null]); echo "sem erro"; } catch (VoosErro $e) { echo $e->getMessage(); }' 2>/dev/null; }
    verifica "chave errada: mensagem clara"             '[[ "$(erro_api errada "$SERPAPI_FALSO")" == *"chave da SerpAPI é inválida"* ]]'
    verifica "serviço em baixo: mensagem clara"         '[[ "$(erro_api chave-de-teste http://127.0.0.1:9/search.json)" == *"Não foi possível contactar"* ]]'
fi

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
AID=$(q "SELECT id FROM t_avaliacoes WHERE item_id=4")
post vendedor public/backend/avaliar.php -d tipo_item=hospedagem -d item_id=4 -d id_avaliacao=$AID -d responder=1 -d "resposta=Obrigado pela visita" >/dev/null
verifica "vendedor dono responde à avaliação"       '[[ "$(curl -s -b $T/jujuba $B/public/hotels/detalhes_hospedagem.php?id=4 | txt)" == *"Resposta do vendedor"*"Obrigado pela visita"* ]]'
post Karol public/backend/avaliar.php -d tipo_item=hospedagem -d item_id=4 -d id_avaliacao=$AID -d responder=1 -d resposta=spam >/dev/null
verifica "quem não é o vendedor não responde"       '[ "$(q "SELECT resposta FROM t_avaliacoes WHERE id=$AID")" = "Obrigado pela visita" ]'

echo "== Ofertas por e-mail"
ASSUNTO="Oferta smoke $RANDOM"; INSCRITO="ofertas$RANDOM@exemplo.com"
curl -s -o /dev/null -d email=$INSCRITO "$B/public/backend/newsletter.php"
oferta() { post admin adm/ofertas.php -L --data-urlencode "assunto=$1" --data-urlencode "mensagem=Primeiro parágrafo.

Segundo <b>parágrafo</b>." -d link=https://exemplo.com/promo -d texto_botao=Ver "${@:2}" | txt; }
emails_com() { grep -l "$1" "$EMAILS"/*.html 2>/dev/null | wc -l; }
verifica "cliente não abre a página das ofertas"    '[ "$(status jujuba adm/ofertas.php)" = 403 ]'
verifica "oferta sem assunto é recusada"            '[[ "$(oferta "" -d teste=1)" == *"assunto é obrigatório"* ]]'
verifica "envio de teste só para o admin"           '[[ "$(oferta "$ASSUNTO" -d teste=1)" == *"teste enviado para adm.bestway@bestway.com"* && $(emails_com "\[Teste\] $ASSUNTO") = 1 ]]'
N=$(q "SELECT COUNT(*) FROM (SELECT LOWER(email) FROM t_newsletter UNION SELECT LOWER(email) FROM t_user
       WHERE notificacoes_ofertas=1 AND email_confirmado=1 AND tipo_user<>1) d")
verifica "oferta enviada a todos os destinatários ($N)" '[[ "$(oferta "$ASSUNTO" -d enviar=1)" == *"Oferta enviada para $N pessoas"* && $(emails_com "Assunto: $ASSUNTO") = $N ]]'
verifica "envio fica no histórico"                  '[ "$(q "SELECT enviados FROM t_ofertas WHERE assunto='"'$ASSUNTO'"'")" = "$N" ]'
F=$(grep -l "$INSCRITO" $(grep -l "Assunto: $ASSUNTO" "$EMAILS"/*.html) | head -1)
verifica "mensagem com HTML sai escapada"           '[[ "$(cat "$F")" == *"Segundo &lt;b&gt;parágrafo"* ]]'
L=$(grep -o 'public/cancelar_ofertas.php?[^"]*' "$F" | head -1 | sed 's/&amp;/\&/g')
verifica "link de cancelar com assinatura falsa é recusado" '[[ "$(curl -s -L -b $T/anon -c $T/anon "$B/${L%=*}=$(printf %064d 0)" | txt)" == *"inválido"* ]]'
verifica "abrir o link só pergunta"                 '[[ "$(curl -s -b $T/anon -c $T/anon "$B/$L" | txt)" == *"Deixar de receber"* && "$(q "SELECT COUNT(*) FROM t_newsletter WHERE email='"'$INSCRITO'"'")" = 1 ]]'
verifica "confirmar cancela a subscrição"           '[[ "$(curl -s -b $T/anon -c $T/anon -d "csrf_token=$(token anon "$L")" -d "email=$INSCRITO" -d "assinatura=${L##*=}" "$B/public/cancelar_ofertas.php" | txt)" == *"deixa de receber"* && "$(q "SELECT COUNT(*) FROM t_newsletter WHERE email='"'$INSCRITO'"'")" = 0 ]]'
grep -l "$ASSUNTO" "$EMAILS"/*.html 2>/dev/null | xargs -r rm -f
q "DELETE FROM t_ofertas WHERE assunto='$ASSUNTO'"

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
