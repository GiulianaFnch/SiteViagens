<?php
session_start();
include '../../config/valida.php';
include '../../config/liga_bd.php';

$vendedor_id = $_SESSION['id'];

$sql = "SELECT r.id, r.item_id, r.tipo_reserva, r.data_reserva, r.quantidade, r.user_id,
        CASE 
            WHEN r.tipo_reserva = 'atividade' THEN (SELECT titulo FROM t_artigos WHERE id = r.item_id)
            /*WHEN r.tipo_reserva = 'voo' THEN (SELECT titulo FROM t_voos WHERE id = r.item_id)
            WHEN r.tipo_reserva = 'hospedagem' THEN (SELECT titulo FROM t_hospedagem WHERE id = r.item_id)
            WHEN r.tipo_reserva = 'pacote' THEN (SELECT titulo FROM t_pacotes WHERE id = r.item_id)*/
        END AS titulo
        FROM t_reservas r 
        WHERE r.tipo_reserva = 'atividade' AND r.item_id IN (SELECT id FROM t_artigos WHERE vendedor_id = ?)";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Atividades</title>
</head>
<body>
    <h2>Gerenciar Atividades</h2>
    <table>
        <tr>
            <th>Título</th>
            <th>Usuário</th>
            <th>Tipo de Reserva</th>
            <th>Data da Reserva</th>
            <th>Quantidade</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['titulo']); ?></td>
            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
            <td><?php echo htmlspecialchars($row['tipo_reserva']); ?></td>
            <td><?php echo htmlspecialchars($row['data_reserva']); ?></td>
            <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>