<?php
include '../../config/valida.php';
include '../../config/liga_bd.php';

$user_id = $_SESSION['id'];

// Verifica se o método de requisição é POST e se o id_artigo está setado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_artigo'])) {
    $id_artigo = (int)$_POST['id_artigo'];

    // Remove o artigo da tabela de favoritos
    $sql = "DELETE FROM t_favoritos WHERE id_artigo = ? AND id_user = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("ii", $id_artigo, $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Artigo removido dos favoritos com sucesso!';
    } else {
        $_SESSION['message'] = 'Erro ao remover artigo dos favoritos.';
    }
}

header("Location: ../favoritos.php"); 
exit();
?>
