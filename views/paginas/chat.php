<?php
// Chat, igual para clientes e vendedores. Incluída por public/chat.php e public/vendedor/chat.php depois
// do validador de acesso, com $painel definido. As mensagens vêm de public/backend/chat_mensagens.php.
include __DIR__ . '/../../config/liga_bd.php';

$id_user = (int) $_SESSION['id'];
if ($painel === 'vendedor') {
    // O vendedor pode falar com qualquer utilizador
    $stmt = $ligacao->prepare("SELECT id, foto, nick FROM t_user WHERE id <> ? ORDER BY nick");
    $stmt->bind_param("i", $id_user);
} else {
    // O cliente fala com os amigos (amizade aceite) e com quem já lhe escreveu
    $stmt = $ligacao->prepare("SELECT u.id, u.foto, u.nick FROM t_user u
        WHERE u.id <> ? AND (
            u.id IN (SELECT IF(id_usuario1 = ?, id_usuario2, id_usuario1) FROM amizades
                     WHERE status = 'aceito' AND ? IN (id_usuario1, id_usuario2))
            OR u.id IN (SELECT id_remetente FROM t_art_comen WHERE id_destinatario = ?))
        ORDER BY u.nick");
    $stmt->bind_param("iiii", $id_user, $id_user, $id_user, $id_user);
}
$stmt->execute();
$contactos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$painel_titulo = 'Chat';
$painel_menu = 'chat';
$painel_css = ['chat.css'];
$painel_js = [BASE_URL . 'assets/js/chat.js'];
include __DIR__ . '/../partials/painel_inicio.php';
?>
                    <div id="chat-container" data-base="<?= BASE_URL ?>public/backend/" data-csrf="<?= csrf_token() ?>">
                        <div id="user-list">
                            <h2>Conversas</h2>
                            <?php if (!$contactos): ?>
                                <p class="text-muted">Ainda não tem conversas.</p>
                            <?php endif; ?>
                            <?php foreach ($contactos as $contacto): ?>
                                <button type="button" class="user-item" data-id="<?= (int) $contacto['id'] ?>">
                                    <img src="<?= BASE_URL ?>assets/images/pics/<?= htmlspecialchars($contacto['foto'] ?? '') ?>" alt="" class="user-icon">
                                    <span><?= htmlspecialchars($contacto['nick']) ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <div id="chat-box" hidden>
                            <button type="button" id="back-arrow" aria-label="Voltar às conversas"><i class="bi bi-arrow-left"></i></button>
                            <div id="messages"></div>
                            <form id="message-input">
                                <button type="button" id="abrir-emojis" aria-label="Emojis"><i class="bi bi-emoji-smile"></i></button>
                                <input type="text" id="message" placeholder="Escreva uma mensagem..." maxlength="1000" autocomplete="off">
                                <button type="submit" aria-label="Enviar"><i class="bi bi-send"></i></button>
                            </form>
                            <div id="emoji-panel" hidden>
                                <?php foreach (['😊', '🏳️‍🌈', '👍', '✈️'] as $emoji): ?>
                                    <button type="button"><?= $emoji ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
<?php include __DIR__ . '/../partials/painel_fim.php'; ?>
