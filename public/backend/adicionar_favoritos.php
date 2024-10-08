<?php
include '../../config/valida.php';
include '../../config/liga_bd.php';

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_artigo'])) {
    $id_artigo = (int)$_POST['id_artigo'];
    $tipo_reserva = $_POST['tipo_reserva'];

    // Verifica se o artigo já está nos favoritos para evitar duplicação
    $sql_check = "SELECT * FROM t_favoritos WHERE id_artigo = ? AND id_user = ?";
    $stmt_check = $ligacao->prepare($sql_check);
    $stmt_check->bind_param("ii", $id_artigo, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    // Se o artigo já estiver nos favoritos, retorna uma mensagem
    if ($result_check->num_rows > 0) {
        echo "<script>
                alert('Este artigo já está nos seus favoritos!');
                window.history.go(-2);
              </script>";
        exit;
    }

    // Caso contrário, insere o artigo como favorito
    $sql = "INSERT INTO t_favoritos (id_artigo, id_user, tipo_reserva) VALUES (?, ?, ?)";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("iis", $id_artigo, $user_id, $tipo_reserva);
    if ($stmt->execute()) {
        echo "<script>
                alert('Artigo adicionado aos favoritos com sucesso!');
                window.history.go(-2);
              </script>";
    } else {
        echo "<script>
                alert('Erro ao adicionar artigo aos favoritos.');
                window.history.go(-2);
              </script>";
    }
    exit;
} else {
    // Redireciona caso os dados não estejam corretos
    header("Location: ../public/tours/detalhes_tours.php");
    exit();
}
?>
