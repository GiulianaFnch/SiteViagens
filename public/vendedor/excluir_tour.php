<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Validação básica
    if (!empty($id)) {
        $sql = "DELETE FROM t_artigo WHERE id = ? AND id_user = ?";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['id']);
        if ($stmt->execute()) {
            echo "Sucesso";
            header("refresh:1;url=gestao_tours.php");

        } else {
            echo "Erro";
        }
        $stmt->close();
    } else {
        echo "ID inválido";
    }
}
?>
