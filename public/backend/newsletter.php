<?php
// Inscrição na newsletter (formulário presente no rodapé de várias páginas, incluindo HTML estático).
// Não usa token CSRF porque as páginas estáticas não o podem gerar; o pior que outro site consegue
// fazer é inscrever um e-mail, e cada e-mail só é guardado uma vez.
require_once __DIR__ . '/../../config/config.php';

$mensagem = 'Insira um e-mail válido.';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 254) {
        include '../../config/liga_bd.php';
        $email = strtolower($email);
        $stmt = $ligacao->prepare("INSERT IGNORE INTO t_newsletter (email) VALUES (?)");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $mensagem = 'Inscrição feita! Vai receber as nossas ofertas em ' . $email . '.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Newsletter - BestWay</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        .message-container { max-width: 600px; margin: 160px auto 80px; padding: 0 16px; text-align: center; font-family: 'Poppins', sans-serif; }
        .message-container a { display: inline-block; margin-top: 16px; padding: 10px 20px; background: #6495ed; color: #fff; border-radius: 5px; }
    </style>
</head>

<body>
    <?php include '../../views/partials/header.php'; ?>
    <div class="message-container">
        <h2><?= htmlspecialchars($mensagem) ?></h2>
        <a href="<?= BASE_URL ?>index.html">Voltar ao início</a>
    </div>
</body>

</html>
