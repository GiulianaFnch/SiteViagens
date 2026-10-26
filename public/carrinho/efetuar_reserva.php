<?php require_once __DIR__ . '/../../config/config.php'; ?>
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
    <a href="<?= BASE_URL ?>index.html"
        style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
    <ul class="navbar">
        <li><a href="<?= BASE_URL ?>public/hotels/hotels.php" style="color: black;">Hospedagem</a></li>
        <li><a href="<?= BASE_URL ?>public/voos/voos.php" style="color: black;">Passagens</a></li>
        <li><a href="<?= BASE_URL ?>public/tours/tours.php" style="color: black;">Passeios</a></li>
        <li><a href="<?= BASE_URL ?>public/pacotes/pacotes.php" style="color: black;">Pacotes</a></li>
        <li><a href="<?= BASE_URL ?>public/login.php" style="color: black;"><i class='bx bx-user'></i></a></li>
        <li><a href="<?= BASE_URL ?>public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a>
        </li>
    </ul>
</header>
<br><br><br><br><br><br><br><br><br>

<body>
<?php
include "../../config/valida.php";
csrf_verificar(); // só aceita POST com o token CSRF da sessão
include "../../config/liga_bd.php";
include "../../config/reservas.php";

$id_user = $_SESSION['id'];
$erro = null;

// Tudo numa transação: ou todas as reservas são criadas e o carrinho é limpo, ou nada muda
$ligacao->begin_transaction();
try {
    $stmt = $ligacao->prepare("SELECT id, id_artigo, tipo_item, quantidade, data_inicio, data_fim
                               FROM t_carrinho WHERE id_user = ? ORDER BY id FOR UPDATE");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $linhas_carrinho = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (!$linhas_carrinho) {
        $erro = "O seu carrinho está vazio.";
    }

    // Bloqueia as hospedagens do carrinho para que duas pessoas não fiquem com o último quarto
    $bloquear = $ligacao->prepare("SELECT id FROM t_hospedagem WHERE id = ? FOR UPDATE");
    $inserir = $ligacao->prepare("INSERT INTO t_reservas
        (item_id, user_id, tipo_reserva, data_reserva, quantidade, data_inicio, data_fim, preco_unitario, preco_total, estado)
        VALUES (?, ?, ?, CURDATE(), ?, ?, ?, ?, ?, 'confirmada')");

    foreach ($linhas_carrinho as $linha) {
        if ($erro !== null) {
            break;
        }
        if ($linha['tipo_item'] == 'hospedagem') {
            $bloquear->bind_param("i", $linha['id_artigo']);
            $bloquear->execute();
            $bloquear->get_result();
        }
        // Valida de novo: entretanto o preço ou a disponibilidade podem ter mudado
        $item = reserva_preparar_item($ligacao, $linha['tipo_item'], $linha['id_artigo'], $linha['quantidade'],
            $linha['data_inicio'], $linha['data_fim'], $id_user);
        if ($item['erro'] !== null) {
            $erro = $item['titulo'] . ": " . $item['erro'];
            break;
        }
        $inserir->bind_param("iisissdd", $linha['id_artigo'], $id_user, $linha['tipo_item'], $item['quantidade'],
            $item['data_inicio'], $item['data_fim'], $item['preco_unitario'], $item['preco_total']);
        $inserir->execute();
    }

    if ($erro === null) {
        // Limpa o carrinho após a reserva
        $stmt = $ligacao->prepare("DELETE FROM t_carrinho WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $ligacao->commit();
    } else {
        $ligacao->rollback();
    }
} catch (mysqli_sql_exception $e) {
    $ligacao->rollback();
    error_log("efetuar_reserva: " . $e->getMessage());
    $erro = "Não foi possível concluir a reserva. Tente novamente.";
}

echo "<div class='message-container'>";
if ($erro === null) {
    echo "<h2>Reserva efetuada com sucesso!</h2>";
    echo "<p>Redirecionando para a página de reservas...</p>";
    header("refresh:2;url=../reservas.php");
} else {
    echo "<h2>A reserva não foi efetuada</h2>";
    echo "<p>" . htmlspecialchars($erro) . "</p>";
    echo "<input type='button' value='Voltar ao carrinho' onclick='window.location.href=\"carrinho.php\"'>";
}
echo "</div>";

$ligacao->close();
?>

</body>

<footer>
    <p>© 2024 BestWay. Todos os direitos reservados.</p>
</footer>

</html>