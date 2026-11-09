<?php
// Login (formulário de public/login.php). Os erros voltam ao formulário com um aviso.
require_once __DIR__ . '/../../config/contas.php';
require_once __DIR__ . '/../../config/acesso.php'; // tipos de utilizador
csrf_verificar(); // só aceita o formulário de login.php (impede logins forçados por outros sites)
include __DIR__ . '/../../config/liga_bd.php';

$stmt = $ligacao->prepare("SELECT id, nick, nome, pass, tipo_user, email_confirmado FROM t_user WHERE nick = ?");
$nick = $_POST['nick'] ?? '';
$stmt->bind_param("s", $nick);
$stmt->execute();
$linha = $stmt->get_result()->fetch_assoc();

// A mesma mensagem para nick inexistente e palavra-passe errada
if (!$linha || !password_verify($_POST['pass'] ?? '', $linha['pass'])) {
    conta_aviso('Nick ou palavra-passe errados.', 'erro', 'public/login.php');
}
if ((int) $linha['tipo_user'] === TIPO_BLOQUEADO) {
    conta_aviso('Esta conta foi bloqueada pelo administrador.', 'erro', 'public/login.php');
}
if (!$linha['email_confirmado']) {
    // Ainda sem sessão iniciada: a página de confirmação deixa reenviar o e-mail a esta conta
    $_SESSION['confirmar_email_id'] = (int) $linha['id'];
    header('Location: ' . BASE_URL . 'public/confirmar_email.php');
    exit;
}

// Novo ID de sessão a cada login, para que um ID conhecido antes do login não sirva depois
session_regenerate_id(true);
unset($_SESSION['csrf_token'], $_SESSION['confirmar_email_id']);
$_SESSION['id'] = $linha['id'];
$_SESSION['nick'] = $linha['nick'];
$_SESSION['logged_in'] = true;
mysqli_close($ligacao);
header('Refresh: 2; url=' . BASE_URL . 'index.html');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/mensagem.css">
</head>

<body>
    <?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>
    <br><br><br><br><br><br><br><br><br>
    <div class="message-container">
        <h2>Bem vindo(a), <?= htmlspecialchars($linha['nome']) ?>!</h2>
        <input type="button" value="Continuar" onclick="window.open('<?= BASE_URL ?>index.html','_self')">
    </div>

    <footer>
        <p>© 2024 BestWay. Todos os direitos reservados.</p>
    </footer>
</body>

</html>
