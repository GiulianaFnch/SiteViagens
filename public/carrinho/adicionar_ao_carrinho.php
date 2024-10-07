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
<br><br><br><br><br><br><br><br><br>
<body>
<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_user = $_SESSION['id'];
    $tipo_item = isset($_POST['tipo_item']) ? $_POST['tipo_item'] : 'atividade'; // Verifica se o tipo_item está definido
    $quantidade = 1; // Quantidade fixa, pode ser alterada se desejado
    $return_url = $_POST['return_url']; // URL da página anterior

    // Verifica se o tipo_item é hospedagem ou atividade e define o id_artigo correspondente
    if ($tipo_item == 'hospedagem') {
        $id_artigo = $_POST['id_hospedagem'];
    } else {
        $id_artigo = $_POST['id_artigo'];
    }

    // Verifica se o item já está no carrinho
    $stmt = $ligacao->prepare("SELECT * FROM t_carrinho WHERE id_user = ? AND id_artigo = ? AND tipo_item = ?");
    $stmt->bind_param("iis", $id_user, $id_artigo, $tipo_item);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Atualiza a quantidade se o item já está no carrinho
        $stmt = $ligacao->prepare("UPDATE t_carrinho SET quantidade = quantidade + ? WHERE id_user = ? AND id_artigo = ? AND tipo_item = ?");
        $stmt->bind_param("iiis", $quantidade, $id_user, $id_artigo, $tipo_item);
    } else {
        // Insere um novo registro se o item não está no carrinho
        $stmt = $ligacao->prepare("INSERT INTO t_carrinho (id_user, id_artigo, tipo_item, quantidade) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $id_user, $id_artigo, $tipo_item, $quantidade);
    }

    if ($stmt->execute()) {
        // Atualiza o total do carrinho
        if ($tipo_item == 'hospedagem') {
            $stmt = $ligacao->prepare("SELECT SUM(h.preco_diaria * c.quantidade * h.n_quartos) AS total
                                       FROM t_carrinho c
                                       JOIN t_hospedagem h ON c.id_artigo = h.id
                                       WHERE c.id_user = ? AND c.tipo_item = 'hospedagem'");
        } else {
            $stmt = $ligacao->prepare("SELECT SUM(a.preco * c.quantidade) AS total
                                       FROM t_carrinho c
                                       JOIN t_artigo a ON c.id_artigo = a.id
                                       WHERE c.id_user = ?");
        }
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $linha = $resultado->fetch_assoc();
        $_SESSION['total_carrinho'] = $linha['total'];

        // Exibe a mensagem de sucesso e redireciona
        echo "<div class='message-container'>
                <h2>Item adicionado ao carrinho com sucesso!</h2>
                <input type='button' value='Ir para o Carrinho' onclick='window.location.href=\"carrinho.php\"'>
                <input type='button' value='Continuar Comprando' onclick='setTimeout(function(){window.location.href=\"$return_url\"}, 1000);'>
              </div>";
    } else {
        echo "<h2>Erro ao adicionar item ao carrinho: " . $stmt->error . "</h2>";
    }

    $stmt->close();
    $ligacao->close();
}
?>

</body>

<footer>
    <p>© 2024 BestWay. Todos os direitos reservados.</p>
</footer>

</html>