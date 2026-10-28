<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/csrf.php'; // inicia a sessão

// O logout só acontece com um POST com token: assim outro site (ou uma imagem
// apontada para este endereço) não consegue terminar a sessão do utilizador.
// Os links "Logout" (incluindo os de páginas estáticas) abrem esta página, que pede confirmação.
$terminou = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    // Apaga todas as variáveis da sessão e o cookie
    $_SESSION = array();
    $cookie = session_get_cookie_params();
    setcookie(session_name(), '', time() - 3600, $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
    // Finalmente, destroi a sessão
    session_destroy();
    $terminou = true;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Logout</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/mensagem.css">
</head>

<body>

    <?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>

    <div class="message-container">
        <?php if ($terminou): ?>
            <h2>Sessão terminada com sucesso! Até à próxima</h2>
            <input type="button" value="Voltar ao início" onclick="window.open('../../index.html','_self')">
            <?php header("refresh:10;url=" . BASE_URL . "index.html"); ?>
        <?php elseif (isset($_SESSION['id'])): ?>
            <h2>Deseja terminar a sessão?</h2>
            <form method="post">
                <?= csrf_campo() ?>
                <input type="submit" value="Sair">
                <input type="button" value="Voltar" onclick="window.history.back()">
            </form>
        <?php else: ?>
            <h2>Não tem nenhuma sessão iniciada.</h2>
            <input type="button" value="Voltar ao início" onclick="window.open('../../index.html','_self')">
        <?php endif; ?>
    </div>

    <footer>
        <p>© 2024 BestWay. Todos os direitos reservados.</p>
    </footer>

</body>

</html>
