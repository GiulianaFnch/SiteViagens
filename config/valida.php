<?php
// Exige um utilizador com sessão iniciada e não bloqueado.
// Deixa disponíveis $ligacao (ligação à BD) e $id_user.
require_once __DIR__ . '/acesso.php';

if (!isset($_SESSION['id'], $_SESSION['nick'])) {
    acesso_negado_sem_login();
}

include __DIR__ . '/liga_bd.php';

$id_user = $_SESSION['id'];
$tipo = tipo_utilizador($ligacao, $id_user);
if ($tipo === null) {
    acesso_negado_sem_login(); // a conta já não existe
}
if ($tipo === TIPO_BLOQUEADO) {
    acesso_negado_sem_permissao();
}
?>
