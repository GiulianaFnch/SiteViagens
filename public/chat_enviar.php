<?php
require_once '../config/valida.php';
csrf_verificar(); // só aceita POST com o token CSRF da sessão
require_once '../config/liga_bd.php';

$id_remetente = $_SESSION['id']; // ID do usuário logado
$id_destinatario = (int) ($_POST['user_id'] ?? 0); // ID do usuário para quem a mensagem é enviada
$message = trim($_POST['message'] ?? '');

// Salvando a mensagem no banco de dados
if ($id_destinatario > 0 && $message !== '') {
    $stmt = $ligacao->prepare("INSERT INTO t_art_comen (comentario, id_remetente, id_destinatario) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $message, $id_remetente, $id_destinatario);
    $stmt->execute();
}

// chat_mensagens.php lê o destinatário de $_GET
$_GET['user_id'] = $id_destinatario;

// Exibindo as mensagens atualizadas
include 'chat_mensagens.php';
?>
