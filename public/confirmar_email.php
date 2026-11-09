<?php
// Confirmação do e-mail:
//   ?token=...  link enviado por e-mail → marca o e-mail como confirmado
//   sem token   depois de um login com o e-mail por confirmar (autenticar.php guarda o id na sessão):
//               deixa reenviar o link ou corrigir o endereço
require_once __DIR__ . '/../config/contas.php';
include __DIR__ . '/../config/liga_bd.php';

if (isset($_GET['token'])) {
    $id_user = token_utilizador($ligacao, $_GET['token'], 'confirmar_email');
    if ($id_user !== null) {
        $stmt = $ligacao->prepare("UPDATE t_user SET email_confirmado = 1 WHERE id = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        tokens_invalidar($ligacao, $id_user, 'confirmar_email');
        unset($_SESSION['confirmar_email_id']);
        conta_aviso('E-mail confirmado! Já pode fazer login.', 'sucesso', 'public/login.php');
    }
    conta_aviso('O link de confirmação é inválido ou expirou. Faça login para receber um novo.', 'erro', 'public/login.php');
}

$id_user = $_SESSION['confirmar_email_id'] ?? null;
if ($id_user === null) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit;
}

$stmt = $ligacao->prepare("SELECT email FROM t_user WHERE id = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$email_atual = $stmt->get_result()->fetch_row()[0] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $email = trim($_POST['email'] ?? '');
    if (strcasecmp($email, $email_atual) !== 0) {
        // Endereço corrigido: conta_mudar_email() envia o link para o novo
        $erro = conta_mudar_email($ligacao, $id_user, $email);
        conta_aviso($erro ?? "E-mail alterado. Enviámos um link para $email.", $erro ? 'erro' : 'sucesso',
            'public/confirmar_email.php');
    }
    if (enviar_confirmacao_email($ligacao, $id_user)) {
        conta_aviso("Enviámos um novo link para $email_atual.", 'sucesso', 'public/confirmar_email.php');
    }
    conta_aviso('Não foi possível enviar o e-mail agora (pediu demasiadas vezes?). Tente mais tarde.',
        'erro', 'public/confirmar_email.php');
}

$conta_titulo = 'Confirme o seu e-mail';
$conta_subtitulo = 'Para entrar, abra o link que enviámos para o seu e-mail. Não recebeu? Veja a pasta de spam ou peça outro.';
include __DIR__ . '/../views/partials/conta_inicio.php';
?>
                <form method="post">
                    <?= csrf_campo() ?>
                    <div class="input-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" class="form-control" required maxlength="<?= EMAIL_MAX ?>"
                            value="<?= htmlspecialchars($email_atual) ?>">
                    </div>
                    <p class="text-muted small">Se o endereço estiver errado, corrija-o antes de pedir o link.</p>
                    <button type="submit" class="home-btn">Enviar novo link</button>
                </form>
<?php include __DIR__ . '/../views/partials/conta_fim.php'; ?>
