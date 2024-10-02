<?php
session_start();

// Defina a URL base do seu site
define('BASE_URL', 'http://localhost/siteviagens/');

// Validação das variáveis de sessão
if (!isset($_SESSION['id']) || !isset($_SESSION['nick'])) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erro no Acesso</title>
        <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

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
                margin-top: 10px;
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
    <body>
        <div class="message-container">
            <h1>Erro no Acesso</h1>
            <h2>Você precisa estar logado para acessar esta página.</h2>
            <input type="button" value="Fazer login" onclick="window.location.href='<?php echo BASE_URL; ?>public/login.php'">
        </div>

        <footer>
            <p>© 2024 BestWay. Todos os direitos reservados.</p>
        </footer>
    </body>
    </html>
    <?php
    header("refresh:2;url=" . BASE_URL . "public/login.php");
    exit();
}

// Conexão com o banco de dados
include 'liga_bd.php';

// Verificação do tipo de usuário
$id_user = $_SESSION['id'];
$sql = "SELECT tipo_user FROM t_user WHERE id = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['tipo_user'] == 1) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acesso Bloqueado</title>
        <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

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
                margin-top: 10px;
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
    <body>
        <div class="message-container">
            <h1>Acesso Bloqueado</h1>
            <h2>Você não tem permissão para acessar esta página.</h2>
            <input type="button" value="Voltar ao Início" onclick="window.location.href='<?php echo BASE_URL; ?>index.html'">
        </div>

        <footer>
            <p>© 2024 BestWay. Todos os direitos reservados.</p>
        </footer>
    </body>
    </html>
    <?php
    header("refresh:2;url=" . BASE_URL . "public/login.php");
    exit();
}

$stmt->close();
?>
