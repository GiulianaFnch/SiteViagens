<?php require_once __DIR__ . '/../config/config.php'; ?>
<?php 
include '../config/valida.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
include '../config/liga_bd.php';
include '../config/reservas.php';

$user_id = $_SESSION['id'];

$sql_user = "SELECT nome FROM t_user WHERE id = ?";
$stmt_user = $ligacao->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$linha = $result_user->fetch_assoc(); 

// Cancelar reserva: só reservas confirmadas que ainda não começaram.
// A reserva fica no histórico como "cancelada" e deixa de ocupar vagas.
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancelar'])) {
    $reserva_id = (int) $_POST['reserva_id'];
    $sql = "UPDATE t_reservas SET estado = 'cancelada'
            WHERE id = ? AND user_id = ? AND estado = 'confirmada'
              AND (data_inicio IS NULL OR data_inicio > CURDATE())";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("ii", $reserva_id, $user_id);
    $stmt->execute();
    $cancelada = $stmt->affected_rows > 0;

    header('Location: reservas.php?msg=' . ($cancelada ? 'cancelada' : 'nao_cancelada'));
    exit;
}

// Ajuste a consulta SQL para obter o ID do vendedor correto de acordo com o tipo de reserva
$sql = "SELECT r.id, r.item_id, r.tipo_reserva, r.data_reserva, r.quantidade,
        r.data_inicio, r.data_fim, r.preco_total, r.estado,
        (r.estado = 'confirmada' AND (r.data_inicio IS NULL OR r.data_inicio > CURDATE())) AS pode_cancelar, 
        " . reserva_sql_vendedor('r') . " AS vendedor_id,
        " . reserva_sql_titulo('r') . " AS titulo
        FROM t_reservas r 
        WHERE r.user_id = ?
        ORDER BY r.data_reserva DESC, r.id DESC";

$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$painel = 'cliente';
$painel_titulo = 'Reservas';
$painel_menu = 'reservas';
include __DIR__ . '/../views/partials/painel_inicio.php';
?>
                <div class="container light-style">
                    <h2>Minhas Reservas</h2>

                    <?php if (($_GET['msg'] ?? '') == 'cancelada'): ?>
                        <div class="alert alert-success">Reserva cancelada.</div>
                    <?php elseif (($_GET['msg'] ?? '') == 'nao_cancelada'): ?>
                        <div class="alert alert-warning">Esta reserva já não pode ser cancelada.</div>
                    <?php endif; ?>

                    <?php if (isset($result) && $result->num_rows > 0): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo de Reserva</th>
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
                                    <td><?php echo htmlspecialchars($row['titulo'] ?? '(item removido)'); ?></td>
                                    <td><?= htmlspecialchars(reserva_tipo_nome($row['tipo_reserva'])) ?></td>
                                    <td>
                                        <?= reserva_formatar_periodo($row['data_inicio'], $row['data_fim']) ?>
                                        <br><small class="text-muted">reservado em <?= reserva_formatar_data($row['data_reserva']) ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                                    <td><?= reserva_formatar_preco($row['preco_total']) ?></td>
                                    <td><?= $row['estado'] == 'cancelada' ? '<span class="badge badge-secondary">Cancelada</span>' : '<span class="badge badge-success">Confirmada</span>' ?></td>
                                    <td style="display: flex; gap: 10px;">
                                        <a href="perfil_vendedor.php?user_id=<?php echo (int) $row['vendedor_id']; ?>" class="btn "><i class="bi bi-person-square"></i></a>
                                        <?php if ($row['pode_cancelar']): ?>
                                        <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#confirmDeleteModal" data-reserva-id="<?php echo $row['id']; ?>">
                                            Cancelar
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhuma reserva encontrada.</p>
                    <?php endif; ?>
                </div>
            <!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Cancelar reserva</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Tem certeza de que deseja cancelar esta reserva?
            </div>
            <div class="modal-footer">
                <form method="POST">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="reserva_id" id="reserva_id">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    <button type="submit" name="cancelar" class="btn btn-danger">Cancelar reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>

        
    
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var reservaId = button.data('reserva-id');
        var modal = $(this);
        modal.find('#reserva_id').val(reservaId);
    });
</script>
<?php include __DIR__ . '/../views/partials/painel_fim.php'; ?>
