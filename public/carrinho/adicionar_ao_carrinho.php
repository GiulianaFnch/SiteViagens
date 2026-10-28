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
    // Rejeita pedidos POST sem o token CSRF da sessão
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        csrf_verificar();
    }
    include "../../config/liga_bd.php";

    include "../../config/reservas.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_user = $_SESSION['id'];
        $tipo_item = $_POST['tipo_item'] ?? 'atividade';
        $quantidade = (int) ($_POST['quantidade'] ?? 1);
        $data_inicio = null;
        $data_fim = null; // só as hospedagens têm data de fim (check-out)

        if ($tipo_item == 'hospedagem') {
            $id_artigo = (int) ($_POST['id_artigo'] ?? 0);
            $data_inicio = $_POST['data_checkin'] ?? '';
            $data_fim = $_POST['data_checkout'] ?? '';
            $quantidade = (int) ($_POST['n_quartos'] ?? 0);
        } else if ($tipo_item == 'voo') {
            // Os dados do voo vêm da pesquisa guardada na sessão (voos.php), nunca do formulário
            $voo = $_SESSION['voos_pesquisados'][$_POST['voo_ref'] ?? ''] ?? null;
            $id_artigo = 0;
            if ($voo) {
                $stmt = $ligacao->prepare("INSERT INTO t_voos (id_user, flight_number, airline, price, arrival) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issds", $id_user, $voo['flight_number'], $voo['airline'], $voo['price'], $voo['arrival']);
                $stmt->execute();
                $id_artigo = $stmt->insert_id; // Get the inserted ID
                $stmt->close();
            }
            $quantidade = 1;
        } else {
            $tipo_item = 'atividade';
            $id_artigo = (int) ($_POST['id_artigo'] ?? 0);
            $data_inicio = $_POST['data_inicio'] ?? '';
        }

        // Se o mesmo item (com as mesmas datas) já está no carrinho, soma a quantidade
        $stmt = $ligacao->prepare("SELECT id, quantidade FROM t_carrinho
            WHERE id_user = ? AND id_artigo = ? AND tipo_item = ? AND data_inicio <=> ? AND data_fim <=> ?");
        $stmt->bind_param("iisss", $id_user, $id_artigo, $tipo_item, $data_inicio, $data_fim);
        $stmt->execute();
        $existente = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($existente && $tipo_item != 'voo') {
            $quantidade += (int) $existente['quantidade'];
        }

        // Valida datas, quantidade e disponibilidade (o preço é sempre calculado no servidor)
        $item = reserva_preparar_item($ligacao, $tipo_item, $id_artigo, $quantidade, $data_inicio, $data_fim, $id_user);

        if ($item['erro'] !== null) {
            echo "<div class='message-container'>
                  <h2>" . htmlspecialchars($item['erro']) . "</h2>
                  <input type='button' value='Voltar' onclick='window.history.back()'>
                  </div>";
        } else {
            if ($existente) {
                $stmt = $ligacao->prepare("UPDATE t_carrinho SET quantidade = ? WHERE id = ?");
                $stmt->bind_param("ii", $item['quantidade'], $existente['id']);
            } else {
                $stmt = $ligacao->prepare("INSERT INTO t_carrinho (id_user, id_artigo, tipo_item, quantidade, data_inicio, data_fim) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("iisiss", $id_user, $id_artigo, $tipo_item, $item['quantidade'], $item['data_inicio'], $data_fim);
            }

            if ($stmt->execute()) {
                echo "<div class='message-container'>
                      <h2>" . htmlspecialchars($item['titulo']) . " adicionado ao carrinho</h2>
                      <p>Total: " . reserva_formatar_preco($item['preco_total']) . "</p>
                      <input type='button' value='Ir para o Carrinho' onclick='window.location.href=\"carrinho.php\"'>
                      <input type='button' value='Continuar Comprando' onclick='window.history.go(-2)'>
                      </div>";
            } else {
                echo "<h2>Erro ao adicionar item ao carrinho.</h2>";
            }
            $stmt->close();
        }
        $ligacao->close();
    }
    ?>

</body>

<footer>
    <p>© 2024 BestWay. Todos os direitos reservados.</p>
</footer>

</html>