<?php
// Início das páginas dos painéis do cliente (perfil, reservas, favoritos, chat, configurações) e do
// vendedor: cabeçalho, menu lateral e abertura da área principal. Fechar com views/partials/painel_fim.php.
// Antes de incluir:
//   $painel        'cliente' ou 'vendedor'
//   $painel_titulo título da página
//   $painel_menu   item ativo do menu (chave de $itens_menu abaixo)
//   $painel_css    (opcional) outros ficheiros de assets/css/ desta página, ex.: ['chat.css']
require_once __DIR__ . '/../../config/config.php';

if (($painel ?? 'cliente') === 'vendedor') {
    $pasta_menu = 'public/vendedor/';
    $titulo_menu = 'Painel de Vendedor';
    $itens_menu = [
        'perfil' => ['admin.php', 'bi-person-circle', 'Editar Perfil'],
        'vender' => ['vender_opcoes.php', 'bi-bag', 'Vender'],
        'reservas' => ['gerenciar_reservas.php', 'bi-magic', 'Reservas'],
        'gestao' => ['gestao_opcoes.php', 'bi-train-freight-front', 'Gestão'],
        'chat' => ['chat.php', 'bi-chat-dots', 'Chat'],
        'configuracoes' => ['configuracoes.php', 'bi-gear', 'Configurações'],
    ];
} else {
    $pasta_menu = 'public/';
    $titulo_menu = 'A minha conta';
    $itens_menu = [
        'perfil' => ['perfil.php', 'bi-person-circle', 'Editar perfil'],
        'reservas' => ['reservas.php', 'bi-clipboard2', 'Reservas'],
        'favoritos' => ['favoritos.php', 'bi-heart', 'Favoritos'],
        'chat' => ['chat.php', 'bi-chat-dots', 'Chat'],
        'configuracoes' => ['configuracoes.php', 'bi-gear', 'Configurações'],
    ];
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($painel_titulo) ?> - BestWay</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- depois do Bootstrap: repõe o cabeçalho do site (o Bootstrap altera header e .navbar) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/painel.css">
    <?php foreach ($painel_css ?? [] as $css): ?>
        <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/<?= htmlspecialchars($css) ?>">
    <?php endforeach; ?>
</head>

<body class="pagina-painel">
    <?php $cabecalho_escuro = true; include __DIR__ . '/header.php'; ?>

    <div class="container painel">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light menu-container">
                    <nav class="menu">
                        <span class="menu-titulo"><?= htmlspecialchars($titulo_menu) ?></span>
                        <?php foreach ($itens_menu as $chave => [$pagina, $icone, $texto]): ?>
                            <a class="menu-item<?= ($painel_menu ?? '') === $chave ? ' active' : '' ?>"
                                href="<?= BASE_URL . $pasta_menu . $pagina ?>"><i class="bi <?= $icone ?>"></i> <?= $texto ?></a>
                        <?php endforeach; ?>
                    </nav>
                </div>
                <div class="col-md-9 p-4">
