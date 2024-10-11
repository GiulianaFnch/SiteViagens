<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BestWay</title>
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

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
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

<header>
    <a href="/SiteViagens/index.html"
        style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
    <ul class="navbar">
        <li><a href="/SiteViagens/index.html#home" style="color: black;">Hospedagem</a></li>
        <li><a href="#package" style="color: black;">Passagens</a></li>
        <li><a href="/SiteViagens/public/tours/tours.php" style="color: black;">Passeios</a></li>
        <li><a href="#contact" style="color: black;">Pacotes</a></li>
        <li><a href="public/login.php" style="color: black;"><i class='bx bx-user'></i></a></li>
        <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a>
        </li>
    </ul>
</header>
<br><br><br><br><br><br><br><br><br>

<body>
<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

$id_user = $_SESSION['id']; // Assumindo que o ID do usuário está armazenado na sessão

// Verifique se a ligação ao banco de dados está correta
if ($ligacao->connect_error) {
    die("Falha na ligação: " . $ligacao->connect_error);
}

// Consulta para obter itens do carrinho
$stmt = $ligacao->prepare("SELECT c.id, c.id_artigo, c.tipo_item, c.quantidade
                           FROM t_carrinho c
                           WHERE c.id_user = ?");
if (!$stmt) {
    die("Erro na preparação da consulta: " . $ligacao->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado_artigos = $stmt->get_result();

if (!$resultado_artigos) {
    die("Erro na execução da consulta: " . $stmt->error);
}

// Processa cada item do carrinho e insere na tabela de reservas
while ($linha = $resultado_artigos->fetch_assoc()) {
    $id_artigo = $linha['id_artigo'];
    $tipo_item = $linha['tipo_item'];
    $quantidade = $linha['quantidade'];
    $data_reserva = date('Y-m-d'); // Data atual

    $stmt_reserva = $ligacao->prepare("INSERT INTO t_reservas (item_id, user_id, tipo_reserva, data_reserva, quantidade) VALUES (?, ?, ?, ?, ?)");
    $stmt_reserva->bind_param("iissi", $id_artigo, $id_user, $tipo_item, $data_reserva, $quantidade);
    $stmt_reserva->execute();
}

// Limpa o carrinho após a reserva
$stmt_limpar = $ligacao->prepare("DELETE FROM t_carrinho WHERE id_user = ?");
$stmt_limpar->bind_param("i", $id_user);
$stmt_limpar->execute();

// Zera a variável de sessão do total do carrinho
$_SESSION['total_carrinho'] = 0;

echo "<div class='message-container'>";
echo "<h2>Reserva efetuada com sucesso!</h2>";
echo "<p>Redirecionando para a página de reservas...</p>";
header("refresh:2;url=../reservas.php");
echo "</div>";

$stmt->close();
$stmt_reserva->close();
$stmt_limpar->close();
$ligacao->close();
?>

</body>

<footer>
    <p>© 2024 BestWay. Todos os direitos reservados.</p>
</footer>

</html>