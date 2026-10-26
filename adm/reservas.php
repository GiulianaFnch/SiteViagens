<?php
include 'valida_adm.php';
include '../config/liga_bd.php';
include '../config/reservas.php';

// Cancelar uma reserva (fica no histórico e deixa de ocupar vagas)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar'])) {
    csrf_verificar();
    $id = (int) $_POST['id'];
    $stmt = $ligacao->prepare("UPDATE t_reservas SET estado = 'cancelada' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header('Location: reservas.php');
    exit;
}

// Consulta para obter todas as reservas com os nomes dos usuários e dos itens
$sql = "SELECT r.id, r.item_id, r.user_id, r.tipo_reserva, r.data_reserva, r.quantidade,
               r.data_inicio, r.data_fim, r.preco_total, r.estado,
               u.nome AS user_nome,
               CASE r.tipo_reserva
                   WHEN 'atividade' THEN a.titulo
                   WHEN 'hospedagem' THEN h.nome
                   WHEN 'voo' THEN CONCAT(v.airline, ' ', v.flight_number)
               END AS item_nome
        FROM t_reservas r
        JOIN t_user u ON r.user_id = u.id
        LEFT JOIN t_artigo a ON r.tipo_reserva = 'atividade' AND a.id = r.item_id
        LEFT JOIN t_hospedagem h ON r.tipo_reserva = 'hospedagem' AND h.id = r.item_id
        LEFT JOIN t_voos v ON r.tipo_reserva = 'voo' AND v.id = r.item_id
        ORDER BY r.id DESC";
$result = $ligacao->query($sql);

include 'views/header.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gestão de Reservas</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
                <svg class="bi">
                    <use xlink:href="#calendar3" />
                </svg>
                This week
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <br>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Item</th>
                    <th>Usuário</th>
                    <th>Tipo Reserva</th>
                    <th>Datas</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_nome'] ?? '(item removido)'); ?></td>
                        <td><?php echo htmlspecialchars($row['user_nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_reserva']); ?></td>
                        <td>
                            <?= reserva_formatar_data($row['data_inicio']) ?>
                            <?php if ($row['tipo_reserva'] == 'hospedagem' && $row['data_fim']): ?> a <?= reserva_formatar_data($row['data_fim']) ?><?php endif; ?>
                            <br><small class="text-muted">reservado em <?= reserva_formatar_data($row['data_reserva']) ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                        <td><?= reserva_formatar_preco($row['preco_total']) ?></td>
                        <td><?= $row['estado'] == 'cancelada' ? '<span class="badge text-bg-secondary">Cancelada</span>' : '<span class="badge text-bg-success">Confirmada</span>' ?></td>
                        <td>
                            <?php if ($row['estado'] == 'confirmada'): ?>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Cancelar esta reserva?');">
                                <?= csrf_campo() ?>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="cancelar" class="btn btn-danger btn-sm">Cancelar</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.2/dist/chart.umd.js"
    integrity="sha384-eI7PSr3L1XLISH8JdDII5YN/njoSsxfbrkCTnJrzXt+ENP5MOVBxD+l6sEG4zoLp"
    crossorigin="anonymous"></script>
</body>
</html>

<?php
$ligacao->close();
?>