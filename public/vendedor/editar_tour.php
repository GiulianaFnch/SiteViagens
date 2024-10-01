<?php
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
    $sql = "UPDATE t_artigo SET titulo = ?, descricao = ?, preco = ?, localizacao = ?, data_inicio = ?, data_fim = ? WHERE id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("ssssssi", $titulo, $descricao, $preco, $local, $data_inicio, $data_fim, $id);
    
    if ($stmt->execute()) {
        // Redirecionar após a atualização
        header('Location: gestao_tours.php');
    } else {
        echo "Erro ao atualizar o passeio.";
    }

    $stmt->close();
}
?>
