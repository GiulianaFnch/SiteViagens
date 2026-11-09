<?php
// Escolha de uma nova palavra-passe, a partir do link enviado por esqueci_senha.php.
// O link (token) só serve uma vez e expira ao fim de 1 hora.
require_once __DIR__ . '/../config/contas.php';
include __DIR__ . '/../config/liga_bd.php';

// O token vai no endereço: não o enviar a outros sites no cabeçalho Referer (fontes, CDN, ...)
header('Referrer-Policy: no-referrer');

$token = $_POST['token'] ?? $_GET['token'] ?? '';
$id_user = token_utilizador($ligacao, $token, 'recuperar_senha');
if ($id_user === null) {
    conta_aviso('O link para redefinir a palavra-passe é inválido, já foi usado ou expirou. Peça um novo.',
        'erro', 'public/esqueci_senha.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $erro = senha_erro($_POST['pass'] ?? '', $_POST['pass2'] ?? '');
    if ($erro !== null) {
        conta_aviso($erro, 'erro', 'public/redefinir_senha.php?token=' . $token);
    }
    // Quem abriu o link provou que o e-mail é seu: fica também confirmado
    $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $stmt = $ligacao->prepare("UPDATE t_user SET pass = ?, email_confirmado = 1 WHERE id = ?");
    $stmt->bind_param("si", $hash, $id_user);
    $stmt->execute();
    tokens_invalidar($ligacao, $id_user, 'recuperar_senha');
    tokens_invalidar($ligacao, $id_user, 'confirmar_email');
    conta_aviso('Palavra-passe alterada. Já pode fazer login.', 'sucesso', 'public/login.php');
}

$conta_titulo = 'Nova palavra-passe';
$conta_subtitulo = 'Escolha uma palavra-passe com pelo menos ' . SENHA_MIN . ' caracteres';
include __DIR__ . '/../views/partials/conta_inicio.php';
?>
                <form method="post">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                    <div class="input-group">
                        <label for="pass">Nova palavra-passe:</label>
                        <input type="password" id="pass" name="pass" class="form-control" required minlength="<?= SENHA_MIN ?>"
                            autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <label for="pass2">Repetir a palavra-passe:</label>
                        <input type="password" id="pass2" name="pass2" class="form-control" required minlength="<?= SENHA_MIN ?>"
                            autocomplete="new-password">
                    </div>
                    <button type="submit" class="home-btn">Guardar</button>
                </form>
<?php include __DIR__ . '/../views/partials/conta_fim.php'; ?>
