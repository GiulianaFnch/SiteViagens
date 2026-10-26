<?php
// Exige um administrador com sessão iniciada.
// Deixa disponíveis $ligacao (ligação à BD) e $id_user.
require_once __DIR__ . '/../config/acesso.php';

if (!isset($_SESSION['id'], $_SESSION['nick'])) {
    acesso_negado_sem_login();
}

include __DIR__ . '/../config/liga_bd.php';

$id_user = $_SESSION['id'];
if (tipo_utilizador($ligacao, $id_user) !== TIPO_ADMIN) {
    acesso_negado_sem_permissao();
}
?>
