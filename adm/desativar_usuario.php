<?php
include 'valida_adm.php';
include '../config/liga_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Atualizar o tipo_user para 1 (desativado)
    $sql = "UPDATE t_user SET tipo_user = 1 WHERE id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Usuário desativado com sucesso!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Erro ao desativar usuário.'); window.location.href='index.php';</script>";
    }

    $stmt->close();
    $ligacao->close();
}
?>