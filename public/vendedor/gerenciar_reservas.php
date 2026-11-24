<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';
include '../../config/reservas.php';

$vendedor_id = $_SESSION['id'];

// Reservas dos passeios, hospedagens e pacotes do vendedor logado
$sql = "SELECT r.id, r.item_id, r.tipo_reserva, r.data_reserva, r.quantidade,
               r.data_inicio, r.data_fim, r.preco_total, r.estado, u.nome AS user_nome,
               " . reserva_sql_titulo('r') . " AS titulo
        FROM t_reservas r
        JOIN t_user u ON r.user_id = u.id
        WHERE " . reserva_sql_vendedor('r') . " = ?
        ORDER BY (r.data_inicio IS NULL), r.data_inicio DESC, r.id DESC";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
$painel = 'vendedor';
$painel_titulo = 'Reservas';
$painel_menu = 'reservas';
$painel_css = ['vendedor_reservas.css'];
include __DIR__ . '/../../views/partials/painel_inicio.php';
?>
                    <h2>Gerenciar Reservas</h2>
                    <?php $mensagens = [
                        'edited' => ['success', 'Reserva atualizada.'],
                        'cancelled' => ['success', 'Reserva cancelada.'],
                        'error' => ['danger', $_GET['erro'] ?? 'Não foi possível alterar a reserva.'],
                    ]; ?>
                    <?php if (isset($mensagens[$_GET['status'] ?? ''])): [$classe, $texto] = $mensagens[$_GET['status']]; ?>
                        <div class="alert alert-<?= $classe ?>"><?= htmlspecialchars($texto) ?></div>
                    <?php endif; ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Usuário</th>
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
                                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_nome']); ?></td>
                                <td><?= htmlspecialchars(reserva_tipo_nome($row['tipo_reserva'])) ?></td>
                                <td>
                                    <?= reserva_formatar_periodo($row['data_inicio'], $row['data_fim']) ?>
                                    <br><small class="text-muted">reservado em <?= reserva_formatar_data($row['data_reserva']) ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                                <td><?= reserva_formatar_preco($row['preco_total']) ?></td>
                                <td><?= $row['estado'] == 'cancelada' ? '<span class="badge badge-secondary">Cancelada</span>' : '<span class="badge badge-success">Confirmada</span>' ?></td>
                            <td class="action-btns">
                                <?php if ($row['estado'] == 'confirmada'): ?>
                                <!-- Botão de Editar -->
                                <button type="button" class="btn btn-edit" data-toggle="modal" data-target="#editModal" data-reserva-id="<?php echo $row['id']; ?>" data-quantidade="<?= (int) $row['quantidade'] ?>">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>

                                    <!-- Botão de Cancelar -->
                                    <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#confirmDeleteModal" data-reserva-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de confirmação de exclusão -->
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
                    Você tem certeza que deseja cancelar esta reserva? O cliente verá a reserva como cancelada.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Voltar</button>
                    <form id="deleteForm" method="post" action="reservas.php">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="reserva_id" id="reservaId">
                        <button type="submit" name="cancelar" class="btn btn-danger">Cancelar reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edição -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Reserva</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulário de edição -->
                    <form id="editForm" method="post" action="editar_reserva.php">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="reserva_id" id="editReservaId">

                        <div class="form-group">
                            <label for="quantidade">Quantidade (quartos ou pessoas)</label>
                            <input type="number" class="form-control" name="quantidade" id="quantidade" min="1" required>
                            <small class="form-text text-muted">O valor da reserva é recalculado com o preço pago originalmente.</small>
                        </div>

                        <button type="submit" name="edit" class="btn btn-success">Salvar Alterações</button>
                    </form>
                
            
        
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Captura o clique no botão de excluir e configura o ID da reserva no modal de exclusão
       // Configura o ID da reserva no modal de exclusão
        $('#confirmDeleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reservaId = button.data('reserva-id');
            var modal = $(this);
            modal.find('#reservaId').val(reservaId);
        });

        // Captura o clique no botão de editar e configura o ID da reserva no modal de edição
       
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var reservaId = button.data('reserva-id');
            var modal = $(this);
            modal.find('#editReservaId').val(reservaId);
            modal.find('#quantidade').val(button.data('quantidade'));
        });
    </script>

    <br><br>
<?php include __DIR__ . '/../../views/partials/painel_fim.php'; ?>
