<?php
include '../config/valida.php';
include '../config/liga_bd.php';

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $reserva_id = (int) $_POST['reserva_id'];
    $sql = "DELETE FROM t_reservas WHERE id = ? AND user_id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("ii", $reserva_id, $user_id);
    $stmt->execute();
    echo "Reserva excluída com sucesso!";
}

$sql = "SELECT r.id, r.item_id, r.tipo_reserva, r.data_reserva, r.quantidade, 
        CASE 
            WHEN r.tipo_reserva = 'atividade' THEN (SELECT titulo FROM t_artigo WHERE id = r.item_id)
            /*WHEN r.tipo_reserva = 'voo' THEN (SELECT titulo FROM t_voos WHERE id = r.item_id)
            WHEN r.tipo_reserva = 'hospedagem' THEN (SELECT titulo FROM t_hospedagem WHERE id = r.item_id)
            WHEN r.tipo_reserva = 'pacote' THEN (SELECT titulo FROM t_pacotes WHERE id = r.item_id)*/
        END AS titulo
        FROM t_reservas r 
        WHERE r.user_id = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Reservas</title>
</head>

<body>
    <h2>Minhas Reservas</h2>
    <table>
        <tr>
            <th>Título</th>
            <th>Tipo de Reserva</th>
            <th>Data da Reserva</th>
            <th>Quantidade</th>
            <th>Ações</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                <td><?php echo htmlspecialchars($row['tipo_reserva']); ?></td>
                <td><?php echo htmlspecialchars($row['data_reserva']); ?></td>
                <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                <td>
                    <form method="post" action="minhas_reservas.php" style="display:inline;">
                        <input type="hidden" name="reserva_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" name="delete" value="Excluir">
                    </form>
                    <form method="get" action="editar_reserva.php" style="display:inline;">
                        <input type="hidden" name="reserva_id" value="<?php echo $row['id']; ?>">
                        <input type="submit" value="Editar">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>