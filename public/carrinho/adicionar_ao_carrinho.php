<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id']; // Assumindo que o ID do usuário está armazenado na sessão
    $id_artigo = $_POST['id_artigo']; 
    $tipo_item = isset($_POST['tipo_item']) ? $_POST['tipo_item'] : 'atividade'; // Verifica se o tipo_item está definido
    $quantidade = 1; // Você pode permitir que o usuário escolha a quantidade
    $return_url = $_POST['return_url']; // URL da página anterior

    // Verifica se o item já está no carrinho
    $stmt = $ligacao->prepare("SELECT * FROM t_carrinho WHERE id_user = ? AND id_artigo = ? AND tipo_item = ?");
    $stmt->bind_param("iis", $id_user, $id_artigo, $tipo_item);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Se o item já está no carrinho, atualiza a quantidade
        $stmt = $ligacao->prepare("UPDATE t_carrinho SET quantidade = quantidade + ? WHERE id_user = ? AND id_artigo = ? AND tipo_item = ?");
        $stmt->bind_param("iiis", $quantidade, $id_user, $id_artigo, $tipo_item);
    } else {
        // Se o item não está no carrinho, insere um novo registro
        $stmt = $ligacao->prepare("INSERT INTO t_carrinho (id_user, id_artigo, tipo_item, quantidade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $id_user, $id_artigo, $tipo_item, $quantidade);
    }

    if ($stmt->execute()) {
        // Atualiza o total do carrinho
        $stmt = $ligacao->prepare("SELECT SUM(a.preco * c.quantidade) AS total
                                   FROM t_carrinho c
                                   JOIN t_artigo a ON c.id_artigo = a.id
                                   WHERE c.id_user = ? AND c.tipo_item = 'atividade'");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $linha = $resultado->fetch_assoc();
        $_SESSION['total_carrinho'] = $linha['total'];

        // Exibe mensagem de sucesso e redireciona conforme a escolha do usuário
        echo "<script>
                if (confirm('Item adicionado ao carrinho com sucesso. Deseja ir para o carrinho?')) {
                    window.location.href = 'carrinho.php'; // Substitua pelo caminho correto para o carrinho
                } else {
                    setTimeout(function() {
                        window.location.href = '$return_url';
                    }, 1000);
                }
              </script>";
    } else {
        echo "Erro ao adicionar item ao carrinho: " . $stmt->error;
    }

    $stmt->close();
    $ligacao->close();
}
?>