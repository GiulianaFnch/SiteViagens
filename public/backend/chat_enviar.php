<?php
// Envia uma mensagem do chat e devolve a conversa atualizada (o mesmo HTML de chat_mensagens.php).
require_once __DIR__ . '/../../config/valida.php';
csrf_verificar(); // só aceita POST com o token CSRF da sessão
require_once __DIR__ . '/../../config/liga_bd.php';

$id_remetente = (int) $_SESSION['id'];
$id_destinatario = (int) ($_POST['user_id'] ?? 0);
$mensagem = trim($_POST['message'] ?? '');
if ($id_destinatario > 0 && $id_destinatario !== $id_remetente && $mensagem !== '' && mb_strlen($mensagem) <= 1000) {
    $stmt = $ligacao->prepare("INSERT INTO t_art_comen (comentario, id_remetente, id_destinatario) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $mensagem, $id_remetente, $id_destinatario);
    $stmt->execute();
}

$_GET['user_id'] = $id_destinatario;
include __DIR__ . '/chat_mensagens.php';
