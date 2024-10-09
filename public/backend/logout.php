<?php
session_start();
// Apaga todas as variáveis da sessão
$_SESSION = array();
// Finalmente, destroi a sessão
session_destroy();
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

        input[type="button"] {
            padding: 10px 20px;
            background-color: #6495ed;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="button"]:hover {
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
        <a href="/SiteViagens/index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="/SiteViagens/index.html#home" style="color: black;">Hospedagem</a></li>
            <li><a href="#package" style="color: black;">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php" style="color: black;">Passeios</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
            <li><a href="public/login.php" style="color: black;"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <div class="message-container">
        <h1>Logout </h1>
        <h2>Sessão terminada com sucesso! Até à próxima</h2>
        <input type="button" value="Voltar ao início" onclick="window.open('../../index.html','_self')">
    </div>

    <?php header("refresh:10;url=/SiteViagens/index.html"); ?>

    <footer>
        <p>© 2024 BestWay. Todos os direitos reservados.</p>
    </footer>

</body>

</html>
