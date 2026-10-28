<?php
// Pedido de recuperação da palavra-passe: envia um link para o e-mail da conta.
// A resposta é sempre a mesma, para não revelar que e-mails estão registados.
require_once __DIR__ . '/../config/contas.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $email = trim($_POST['email'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        include __DIR__ . '/../config/liga_bd.php';
        enviar_recuperacao_senha($ligacao, $email);
    }
    conta_aviso('Se existir uma conta com esse e-mail, enviámos um link para escolher uma nova palavra-passe. '
        . 'O link é válido durante 1 hora.', 'sucesso', 'public/login.php');
}

$conta_titulo = 'Recuperar a palavra-passe';
$conta_subtitulo = 'Indique o e-mail da sua conta e enviamos-lhe um link para escolher uma nova palavra-passe';
include __DIR__ . '/../views/partials/conta_inicio.php';
?>
                <form method="post">
                    <?= csrf_campo() ?>
                    <div class="input-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" class="form-control" required autocomplete="email">
                    </div>
                    <button type="submit" class="home-btn">Enviar link</button>
                    <input type="button" value="Voltar ao login" class="btn" onclick="window.open('login.php', '_self')">
                </form>
<?php include __DIR__ . '/../views/partials/conta_fim.php'; ?>
