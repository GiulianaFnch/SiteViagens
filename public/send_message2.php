<?php
include '../config/valida.php';
include '../config/liga_bd.php';

$id_remetente = $_SESSION['id']; // ID do usuário logado
$id_destinatario = $_POST['user_id']; // ID do usuário para quem a mensagem é enviada
$message = $_POST['message'];

// Salvando a mensagem no banco de dados
$sql = "INSERT INTO t_art_comen (comentario, id_remetente, id_destinatario) 
        VALUES ('$message', $id_remetente, $id_destinatario)";

mysqli_query($ligacao, $sql);

// Exibindo as mensagens atualizadas
include 'get_chat2.php';
?>

