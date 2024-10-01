<?php
include '../../config/liga_bd.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Consulta SQL para buscar o passeio
    $sql = "SELECT id, titulo, descricao, preco, localizacao, data_inicio, data_fim FROM t_artigo WHERE id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $passeio = $result->fetch_assoc();
        echo json_encode($passeio);
    } else {
        echo json_encode(['error' => 'Passeio não encontrado.']);
    }
    
    $stmt->close();
}
?>
