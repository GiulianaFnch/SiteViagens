<?php
// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Lógica para excluir a reserva
        include '../../config/liga_bd.php';
        
        $reserva_id = $_POST['reserva_id'];
        
        // Executa a exclusão da reserva no banco de dados
        $sql = "DELETE FROM t_reservas WHERE id = ?";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("i", $reserva_id);
        if ($stmt->execute()) {
            // Redireciona de volta para a página de gerenciamento com sucesso
            header("Location: gerenciar_reservas.php?status=deleted");
            exit();
        } else {
            // Erro ao excluir
            echo "Erro ao excluir a reserva.";
        }
    }
}
?>
