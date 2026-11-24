<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

// Obter o ID do usuário logado
$id_user_logado = $_SESSION['id'];

// Consulta para obter os artigos do usuário logado
$sql = "SELECT id, titulo, descricao, preco, localizacao, data_inicio, data_fim, vagas, foto1, foto2, foto3 
        FROM t_artigo 
        WHERE id_user = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $id_user_logado);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$painel = 'vendedor';
$painel_titulo = 'Gestão de passeios';
$painel_menu = 'gestao';
$painel_css = ['gestao.css'];
include __DIR__ . '/../../views/partials/painel_inicio.php';
?>
                    <div class="tab-content p-4">
                <h2>Gerenciar Passeios</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Descrição</th>
                                <th>Preço</th>
                                <th>Localização</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                                    <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                                    <td><?php echo htmlspecialchars($row['preco']); ?></td>
                                    <td><?php echo htmlspecialchars($row['localizacao']); ?></td>
                                    <td><?php echo htmlspecialchars($row['data_inicio']); ?></td>
                                    <td><?php echo htmlspecialchars($row['data_fim']); ?></td>
                                    <td class="action-btns">
                                        <button type="button" class="btn btn-edit" data-toggle="modal" data-target="#editModal" data-reserva-id="<?php echo $row['id']; ?>" data-titulo="<?php echo htmlspecialchars($row['titulo']); ?>" data-descricao="<?php echo htmlspecialchars($row['descricao']); ?>" data-preco="<?php echo htmlspecialchars($row['preco']); ?>" data-localizacao="<?php echo htmlspecialchars($row['localizacao']); ?>" data-data_inicio="<?php echo htmlspecialchars($row['data_inicio']); ?>" data-data_fim="<?php echo htmlspecialchars($row['data_fim']); ?>" data-vagas="<?php echo htmlspecialchars($row['vagas'] ?? ''); ?>">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#confirmDeleteModal" data-reserva-id="<?php echo $row['id']; ?>">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
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
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Tem certeza de que deseja excluir este passeio?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="post" action="excluir_tour.php">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id" id="reservaId">
                        <button type="submit" class="btn btn-delete">Excluir</button>
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
                    <h5 class="modal-title" id="editModalLabel">Editar Passeio</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="editar_tour.php">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="reserva_id" id="editReservaId">
                        <div class="form-group">
                            <label for="titulo">Título</label>
                            <input type="text" class="form-control" name="titulo" id="editTitulo" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" id="editDescricao" required>
                        </div>
                        <div class="form-group">
                            <label for="preco">Preço</label>
                            <input type="number" class="form-control" name="preco" id="editPreco" required>
                        </div>
                        <div class="form-group">
                            <label for="local">Local</label>
                            <input type="text" class="form-control" name="local" id="editLocal" required>
                        </div>
                        <div class="form-group">
                            <label for="data_inicio">Data Início</label>
                            <input type="date" class="form-control" name="data_inicio" id="editDataInicio" required>
                        </div>
                        <div class="form-group">
                            <label for="data_fim">Data Fim</label>
                            <input type="date" class="form-control" name="data_fim" id="editDataFim" required>
                        </div>
                        <div class="form-group">
                            <label for="editVagas">Vagas por dia (vazio = sem limite)</label>
                            <input type="number" class="form-control" name="vagas" id="editVagas" min="1">
                        </div>
                        <button type="submit" name="edit" class="btn btn-success">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        
    
        
    
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
    <script>
        // Configura o ID do passeio no modal de exclusão
        $('#confirmDeleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var passeioId = button.data('reserva-id');
            var modal = $(this);
            modal.find('#reservaId').val(passeioId);
        });

        // Configura os dados do passeio no modal de edição
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var passeioId = button.data('reserva-id');
            var titulo = button.data('titulo');
            var descricao = button.data('descricao');
            var preco = button.data('preco');
            var localizacao = button.data('localizacao');
            var dataInicio = button.data('data_inicio');
            var dataFim = button.data('data_fim');

            var modal = $(this);
            modal.find('#editReservaId').val(passeioId);
            modal.find('#editTitulo').val(titulo);
            modal.find('#editDescricao').val(descricao);
            modal.find('#editPreco').val(preco);
            modal.find('#editLocal').val(localizacao);
            modal.find('#editDataInicio').val(dataInicio);
            modal.find('#editDataFim').val(dataFim);
            modal.find('#editVagas').val(button.data('vagas'));
        });
    </script>

    <br><br><br><br>
<?php include __DIR__ . '/../../views/partials/painel_fim.php'; ?>
