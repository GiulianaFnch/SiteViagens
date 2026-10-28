<?php
// Início das páginas de conta (login, registo, recuperar palavra-passe): formulário à esquerda,
// imagem à direita. Antes de incluir, definir $conta_titulo e, opcionalmente, $conta_subtitulo.
// Mostra também o aviso guardado com conta_aviso() (ex.: "Palavra-passe alterada").
// Fechar com views/partials/conta_fim.php.
require_once __DIR__ . '/../../config/csrf.php'; // inicia a sessão (token dos formulários)

$conta_aviso = $_SESSION['conta_aviso'] ?? null;
unset($_SESSION['conta_aviso']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BestWay - <?= htmlspecialchars($conta_titulo) ?></title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
        <div class="container-registro">
            <!-- Metade com o formulário -->
            <div class="formulario">
                <div class="py-5 text-center">
                    <h2><?= htmlspecialchars($conta_titulo) ?></h2>
                    <?php if (!empty($conta_subtitulo)): ?>
                        <p class="lead"><?= htmlspecialchars($conta_subtitulo) ?></p>
                    <?php endif; ?>
                </div>
                <?php if ($conta_aviso): ?>
                    <div class="aviso-conta <?= $conta_aviso['tipo'] === 'erro' ? 'erro' : 'sucesso' ?>" role="alert">
                        <?= htmlspecialchars($conta_aviso['texto']) ?>
                    </div>
                <?php endif; ?>
