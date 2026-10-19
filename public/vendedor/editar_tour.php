<?php
include 'valida_vendedor.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
include '../../config/liga_bd.php';

if (isset($_POST['edit'])) {
    $id = $_POST['reserva_id'];
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $local = $_POST['local'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];

    // Atualizar o passeio no banco de dados
    $sql = "UPDATE t_artigo SET titulo = ?, descricao = ?, preco = ?, localizacao = ?, data_inicio = ?, data_fim = ? WHERE id = ? AND id_user = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("ssssssii", $titulo, $descricao, $preco, $local, $data_inicio, $data_fim, $id, $_SESSION['id']);
    
    if ($stmt->execute()) {
        // Redirecionar após a atualização
        header('Location: gestao_tours.php');
    } else {
        echo "Erro ao atualizar o passeio.";
    }

    $stmt->close();
}
?>
