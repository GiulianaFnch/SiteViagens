<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_carrinho'])) {
        $id_user = $_SESSION['id']; // Assumindo que o ID do usuário está armazenado na sessão
        $id_carrinho = $_POST['id_carrinho'];

        // Remove o item do carrinho
        $stmt = $ligacao->prepare("DELETE FROM t_carrinho WHERE id = ? AND id_user = ?");
        $stmt->bind_param("ii", $id_carrinho, $id_user);

        if ($stmt->execute()) {
            // Atualiza o total do carrinho
            $stmt = $ligacao->prepare("SELECT SUM(a.preco * c.quantidade) AS total
                                       FROM t_carrinho c
                                       JOIN t_artigo a ON c.id_artigo = a.id
                                       WHERE c.id_user = ?");
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $linha = $resultado->fetch_assoc();
            $_SESSION['total_carrinho'] = $linha['total'] ?? 0;

            // Redireciona de volta para a página do carrinho
            header('Location: carrinho.php');
            exit();
        } else {
            echo "Erro ao remover item do carrinho: " . $stmt->error;
        }

        $stmt->close();
    }
}

$ligacao->close();
?>