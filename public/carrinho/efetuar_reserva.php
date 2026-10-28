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

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/mensagem.css">
</head>

<?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>
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

    // Bloqueia as hospedagens e passeios do carrinho para que duas pessoas não fiquem com o último quarto/vaga
    $bloquear = $ligacao->prepare("SELECT id FROM t_hospedagem WHERE id = ? FOR UPDATE");
    $bloquear_passeio = $ligacao->prepare("SELECT id FROM t_artigo WHERE id = ? FOR UPDATE");
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
        } elseif ($linha['tipo_item'] == 'atividade') {
            $bloquear_passeio->bind_param("i", $linha['id_artigo']);
            $bloquear_passeio->execute();
            $bloquear_passeio->get_result();
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