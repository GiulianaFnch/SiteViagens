<?php
include 'valida_adm.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
include '../config/liga_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int) $_POST['id'];
    $tipo_user = tipo_utilizador($ligacao, $id);

    if ($tipo_user === null || $tipo_user === TIPO_ADMIN) {
        // Administradores (incluindo o próprio) não podem ser bloqueados por aqui
        echo "<script>alert('Este utilizador não pode ser desativado.'); window.location.href='index.php';</script>";
        exit;
    }

    if ($tipo_user === TIPO_BLOQUEADO) {
        // Reativar: volta ao tipo que tinha antes do bloqueio (cliente, vendedor, ...)
        $sql = "UPDATE t_user SET tipo_user = COALESCE(tipo_antes_bloqueio, " . TIPO_CLIENTE . "), tipo_antes_bloqueio = NULL WHERE id = ?";
    } else {
        // Desativar: guarda o tipo atual para o poder repor
        $sql = "UPDATE t_user SET tipo_antes_bloqueio = tipo_user, tipo_user = " . TIPO_BLOQUEADO . " WHERE id = ?";
    }

    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Status do usuário atualizado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao atualizar status do usuário.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $ligacao->close();
}
?>