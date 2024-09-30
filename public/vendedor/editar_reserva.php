<?php
// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // Lógica para editar a reserva
        include '../../config/liga_bd.php';

        $reserva_id = $_POST['reserva_id'];
        $quantidade = $_POST['quantidade'];

        // Atualiza a quantidade da reserva no banco de dados
        $sql = "UPDATE t_reservas SET quantidade = ? WHERE id = ?";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("ii", $quantidade, $reserva_id);
        if ($stmt->execute()) {
            // Redireciona de volta para a página de gerenciamento com sucesso
            header("Location: gerenciar_reservas.php?status=edited");
            exit();
        } else {
            // Erro ao editar
            echo "Erro ao editar a reserva.";
        }
    }
}
?>
