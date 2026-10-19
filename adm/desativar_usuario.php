<?php
include 'valida_adm.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
include '../config/liga_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Verificar o estado atual do tipo_user
    $sql = "SELECT tipo_user FROM t_user WHERE id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($tipo_user);
    $stmt->fetch();
    $stmt->close();

    // Atualizar o tipo_user com base no estado atual
    if ($tipo_user == 1) {
        // Se o usuário estiver desativado, ativar (tipo_user = 0)
        $sql = "UPDATE t_user SET tipo_user = 0 WHERE id = ?";
    } else {
        // Se o usuário estiver ativo, desativar (tipo_user = 1)
        $sql = "UPDATE t_user SET tipo_user = 1 WHERE id = ?";
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