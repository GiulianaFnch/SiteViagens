<?php
include 'valida_vendedor.php';
// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // Lógica para excluir a reserva
        include '../../config/liga_bd.php';
        
        $reserva_id = $_POST['reserva_id'];
        
        // Executa a exclusão da reserva no banco de dados
        // Só exclui reservas de itens do vendedor logado
        $sql = "DELETE FROM t_reservas WHERE id = ? AND (
                (tipo_reserva = 'atividade' AND item_id IN (SELECT id FROM t_artigo WHERE id_user = ?))
             OR (tipo_reserva = 'hospedagem' AND item_id IN (SELECT id FROM t_hospedagem WHERE id_user = ?)))";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("iii", $reserva_id, $_SESSION['id'], $_SESSION['id']);
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
