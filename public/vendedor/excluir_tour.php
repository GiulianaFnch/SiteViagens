<?php
include '../../config/liga_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Validação básica
    if (!empty($id)) {
        $sql = "DELETE FROM t_artigo WHERE id = ?";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "Sucesso";
        } else {
            echo "Erro";
        }
        $stmt->close();
    } else {
        echo "ID inválido";
    }
}
?>
