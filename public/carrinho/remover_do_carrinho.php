<?php
include "../../config/valida.php";
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
include "../../config/liga_bd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_carrinho'])) {
        $id_user = $_SESSION['id']; // Assumindo que o ID do usuário está armazenado na sessão
        $id_carrinho = $_POST['id_carrinho'];

        // Remove o item do carrinho
        $stmt = $ligacao->prepare("DELETE FROM t_carrinho WHERE id = ? AND id_user = ?");
        $stmt->bind_param("ii", $id_carrinho, $id_user);

        if ($stmt->execute()) {
            // Redireciona de volta para a página do carrinho
            header('Location: carrinho.php');
            exit();
        } else {
            error_log("remover_do_carrinho: " . $stmt->error);
            echo "Erro ao remover item do carrinho.";
        }

        $stmt->close();
    }
}

$ligacao->close();
?>