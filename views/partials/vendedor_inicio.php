<?php
// Início das páginas do painel do vendedor: cabeçalho, menu lateral e abertura da área principal.
// Antes de incluir: $vendedor_titulo e $vendedor_menu (item ativo: 'perfil', 'vender', 'reservas',
// 'gestao', 'chat' ou 'configuracoes'). Fechar com views/partials/vendedor_fim.php.
$itens_menu = [
    'perfil' => ['admin.php', 'bi-person-circle', 'Editar Perfil'],
    'vender' => ['vender_opcoes.php', 'bi-bag', 'Vender'],
    'reservas' => ['gerenciar_reservas.php', 'bi-magic', 'Reservas'],
    'gestao' => ['gestao_opcoes.php', 'bi-train-freight-front', 'Gestão'],
    'chat' => ['chat.php', 'bi-chat-dots', 'Chat'],
    'configuracoes' => ['configuracoes.php', 'bi-gear', 'Configurações'],
];
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($vendedor_titulo) ?> - Painel do Vendedor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- depois do Bootstrap, para que o cabeçalho do site não seja alterado por ele -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/vendedor.css">
</head>

<body>
    <?php $cabecalho_escuro = true; include __DIR__ . '/header.php'; ?>

    <div class="container painel-vendedor">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light menu-container">
                    <nav class="menu">
                        <span class="menu-item" style="color: #3A506B"><strong>Painel de Vendedor</strong></span>
                        <?php foreach ($itens_menu as $chave => [$pagina, $icone, $texto]): ?>
                            <a class="menu-item<?= ($vendedor_menu ?? '') === $chave ? ' ativo' : '' ?>"
                                href="<?= BASE_URL ?>public/vendedor/<?= $pagina ?>"><i class="bi <?= $icone ?>"></i> <?= $texto ?></a>
                        <?php endforeach; ?>
                    </nav>
                </div>
                <div class="col-md-9 p-4">
