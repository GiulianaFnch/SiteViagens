<?php
// Exige um vendedor aprovado com sessão iniciada.
// Deixa disponíveis $ligacao (ligação à BD) e $id_user.
require_once __DIR__ . '/../../config/acesso.php';

if (!isset($_SESSION['id'], $_SESSION['nick'])) {
    acesso_negado_sem_login();
}

include __DIR__ . '/../../config/liga_bd.php';

$id_user = $_SESSION['id'];
$tipo = tipo_utilizador($ligacao, $id_user);
if ($tipo === null) {
    acesso_negado_sem_login();
}
if ($tipo === TIPO_VENDEDOR_PENDENTE) {
    acesso_negado('Registo em análise', 'O seu registo como vendedor ainda aguarda aprovação do administrador.', 'Voltar ao Início', 'index.html');
}
if ($tipo !== TIPO_VENDEDOR) {
    acesso_negado_sem_permissao();
}
?>
