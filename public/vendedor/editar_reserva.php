<?php
include 'valida_vendedor.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // Lógica para editar a reserva
        include '../../config/liga_bd.php';
        include '../../config/reservas.php';

        $reserva_id = (int) $_POST['reserva_id'];
        $quantidade = (int) $_POST['quantidade'];

        // Só altera reservas confirmadas de itens do vendedor logado
        $sql = "SELECT * FROM t_reservas WHERE id = ? AND estado = 'confirmada' AND (
                (tipo_reserva = 'atividade' AND item_id IN (SELECT id FROM t_artigo WHERE id_user = ?))
             OR (tipo_reserva = 'hospedagem' AND item_id IN (SELECT id FROM t_hospedagem WHERE id_user = ?)))";
        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param("iii", $reserva_id, $_SESSION['id'], $_SESSION['id']);
        $stmt->execute();
        $reserva = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        $erro = null;
        if (!$reserva) {
            $erro = 'Reserva não encontrada.';
        } elseif ($quantidade < 1) {
            $erro = 'A quantidade tem de ser pelo menos 1.';
        } elseif ($reserva['data_inicio'] !== null) {
            // Confirma que a nova quantidade cabe (sem contar a própria reserva) e recalcula o valor
            // com o preço unitário pago na altura
            $item = reserva_preparar_item($ligacao, $reserva['tipo_reserva'], $reserva['item_id'], $quantidade,
                $reserva['data_inicio'], $reserva['data_fim'], $reserva['user_id'], $reserva_id);
            $erro = $item['erro'];
        }

        if ($erro !== null) {
            header("Location: gerenciar_reservas.php?status=error&erro=" . urlencode($erro));
            exit();
        }

        $preco_total = null;
        if ($reserva['preco_unitario'] !== null) {
            $noites = $reserva['tipo_reserva'] == 'hospedagem' ? reserva_noites($reserva['data_inicio'], $reserva['data_fim']) : 1;
            $preco_total = round($reserva['preco_unitario'] * $noites * $quantidade, 2);
        }
        $stmt = $ligacao->prepare("UPDATE t_reservas SET quantidade = ?, preco_total = ? WHERE id = ?");
        $stmt->bind_param("idi", $quantidade, $preco_total, $reserva_id);
        if ($stmt->execute()) {
            // Redireciona de volta para a página de gerenciamento com sucesso
            header("Location: gerenciar_reservas.php?status=edited");
            exit();
        } else {
            // Erro ao editar
            echo "Erro ao editar a reserva.";
        }
    }
}
?>
