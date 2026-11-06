<?php
include 'valida_vendedor.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancelar'])) {
        // Lógica para cancelar a reserva (fica no histórico e deixa de ocupar vagas)
        include '../../config/liga_bd.php';
        include '../../config/reservas.php';
        
        $reserva_id = $_POST['reserva_id'];
        
        // Só cancela reservas de itens do vendedor logado
        $sql = "UPDATE t_reservas r SET estado = 'cancelada' WHERE id = ? AND " . reserva_sql_vendedor('r') . " = ?";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("ii", $reserva_id, $_SESSION['id']);
        if ($stmt->execute()) {
            // Redireciona de volta para a página de gerenciamento com sucesso
            header("Location: gerenciar_reservas.php?status=cancelled");
            exit();
        } else {
            // Erro ao cancelar
            echo "Erro ao cancelar a reserva.";
        }
    }
}
?>
