<?php
// Mensagens da conversa com outro utilizador (HTML), pedidas pelo chat (assets/js/chat.js) de 3 em 3 segundos.
require_once __DIR__ . '/../../config/valida.php'; // utilizador com sessão iniciada (cliente ou vendedor)
require_once __DIR__ . '/../../config/liga_bd.php';

$id_user = (int) $_SESSION['id'];
$id_destinatario = (int) ($_GET['user_id'] ?? 0);

$stmt = $ligacao->prepare("SELECT c.id_remetente, c.comentario, u.nick, u.foto
    FROM t_art_comen c JOIN t_user u ON u.id = c.id_remetente
    WHERE (c.id_remetente = ? AND c.id_destinatario = ?) OR (c.id_remetente = ? AND c.id_destinatario = ?)
    ORDER BY c.id ASC");
$stmt->bind_param("iiii", $id_user, $id_destinatario, $id_destinatario, $id_user);
$stmt->execute();
foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $m) {
    $classe = (int) $m['id_remetente'] === $id_user ? 'sent-message' : 'received-message';
    echo "<div class='message $classe'>"
        . "<img src='" . BASE_URL . 'assets/images/pics/' . htmlspecialchars($m['foto'] ?? '') . "' class='user-icon-message' alt=''>"
        . '<b>' . htmlspecialchars($m['nick']) . ':</b>&nbsp;' . htmlspecialchars($m['comentario']) . '</div>';
}
