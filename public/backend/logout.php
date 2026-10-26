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

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        .message-container {
            text-align: center;
            flex-grow: 1;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            font-weight: 400;
        }

        input[type="button"],
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #6495ed;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="button"]:hover,
        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        footer {
            width: 100%;
            background-color: #6495ed;
            color: white;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            color: white;
        }
    </style>
</head>

<body>

    <header>
        <a href="<?= BASE_URL ?>index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="<?= BASE_URL ?>public/hotels/hotels.php" style="color: black;">Hospedagem</a></li>
            <li><a href="<?= BASE_URL ?>public/voos/voos.php" style="color: black;">Passagens</a></li>
            <li><a href="<?= BASE_URL ?>public/tours/tours.php" style="color: black;">Passeios</a></li>
            <li><a href="<?= BASE_URL ?>public/pacotes/pacotes.php" style="color: black;">Pacotes</a></li>
            <li><a href="<?= BASE_URL ?>public/login.php" style="color: black;"><i class='bx bx-user'></i></a></li>
            <li><a href="<?= BASE_URL ?>public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

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
