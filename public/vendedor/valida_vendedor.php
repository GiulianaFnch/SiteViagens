<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Defina a URL base do seu site
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/siteviagens/');
}

// Validação das variáveis de sessão
if (!isset($_SESSION['id']) || !isset($_SESSION['nick'])) {
    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>BestWay - Acesso Restrito</title>
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
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                margin: 20px;
                max-width: 400px;
            }

            h1 {
                font-size: 36px;
                margin-bottom: 20px;
                font-weight: 600;
                color: #333;
            }

            h2 {
                font-size: 24px;
                margin-bottom: 20px;
                font-weight: 400;
                color: #555;
            }

            input[type="button"] {
                padding: 10px 20px;
                background-color: #6495ed;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin: 5px;
                transition: background-color 0.3s;
            }

            input[type="button"]:hover {
                background-color: #0056b3;
            }

            footer {
                width: 100%;
                background-color: #6495ed;
                color: white;
                padding: 20px 0;
                position: relative;
                bottom: 0;
                text-align: center;
            }

            footer p {
                margin: 0;
            }
        </style>
    </head>

    <header>
        <a href="/SiteViagens/index.html"
            style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="/SiteViagens/index.html#home" style="color: black;">Hospedagem</a></li>
            <li><a href="#package" style="color: black;">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php" style="color: black;">Passeios</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
            <li><a href="/SiteViagens/public/login.php" style="color: black;"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a>
            </li>
        </ul>
    </header>

    <br><br>

    <body>
        <div class="message-container">
            <div class='message-container'>
                <h1>Erro no acesso!</h1>
                <h2>Você precisa estar logado para acessar esta página.</h2>
                <input type='button' value='Voltar para Login' onclick=\"window.open('" . BASE_URL
                    . "public/login.php','_self')\">
            </div>
            <?php header("refresh:5;url=" . BASE_URL . "public/login.php");

            ?>
        </div>
    </body>

    <footer>
        <p>© 2024 BestWay. Todos os direitos reservados.</p>
    </footer>

    <!-- link to js -->
    <script type="text/javascript" src="../../assets/js/script.js"></script>

    </html>

    <?php
    exit(); // Certifique-se de sair após o redirecionamento
}

// Conexão com o banco de dados
include '../../config/liga_bd.php';

// Verificação do tipo de usuário
$id_user = $_SESSION['id'];
$sql = "SELECT tipo_user FROM t_user WHERE id = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['tipo_user'] != 2) {
    echo "<div class='message-container'>";
    echo "<h1>Erro no acesso!</h1>";
    echo "<h2>Você não tem permissão para acessar esta página.</h2>";
    echo "<input type='button' value='Voltar para Login' onclick=\"window.open('" . BASE_URL . "public/login.php','_self')\">";
    echo "</div>";
    header("refresh:5;url=" . BASE_URL . "public/login.php");
    exit(); // Certifique-se de sair após o redirecionamento
}



$stmt->close();
$ligacao->close();
?>