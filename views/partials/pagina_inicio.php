<?php
// Início de uma página pública simples (página inicial, contactos, termos, privacidade).
// Antes de incluir: $pagina_titulo e, opcionalmente, $pagina_css (ficheiros de assets/css/) e
// $cabecalho_sobre_imagem / $cabecalho_escuro (ver views/partials/header.php).
// Fechar com views/partials/pagina_fim.php.
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($pagina_titulo) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <?php foreach ($pagina_css ?? [] as $css): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/<?= htmlspecialchars($css) ?>">
    <?php endforeach; ?>
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>
