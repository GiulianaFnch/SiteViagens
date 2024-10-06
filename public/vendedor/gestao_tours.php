<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

// Obter o ID do usuário logado
$id_user_logado = $_SESSION['id'];

// Consulta para obter os artigos do usuário logado
$sql = "SELECT id, titulo, descricao, preco, localizacao, data_inicio, data_fim, foto1, foto2, foto3 
        FROM t_artigo 
        WHERE id_user = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $id_user_logado);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Gestão Passeios</title>
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<br><br><br>
<style>
    body {
        margin-top: 100px;
        font-family: 'Poppins', sans-serif;
    }

    header {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 30px 18%;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .navbar {
        display: flex;
    }

    .navbar a {
        color: black;
    }

    .card {
        border-radius: 15px;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 20px;
        margin-bottom: 20px;
    }

    .menu-container {
        background-color: #f8f9fa;
        border-right: 1px solid #e0e0e0;
    }

    .menu {
        display: flex;
        flex-direction: column;
    }

    .menu-item {
        color: #007AFF;
        padding: 10px;
        margin: 5px 0;
        text-decoration: none;
        font-size: 18px;
        border-radius: 10px;
        transition: background-color 0.3s ease, transform 0.2s ease-in-out;
    }

    .menu-item:hover {
        background-color: #f0f0f0;
        transform: translateX(8px);
    }

    .menu-item i {
        margin-right: 8px;
        font-size: 20px;
    }

    .menu-item.active {
        background-color: #dfe4ea;
        font-weight: bold;
    }

    .container {
        overflow: hidden;
        
        padding: 0 15px;
    }

    .table-responsive {
    overflow-x: auto; 
    -webkit-overflow-scrolling: touch; 
    max-width: 100%; 
}

    .table {
        width: 100%;
        table-layout: fixed;
    }



    .table th,
    .table td {
        word-wrap: break-word;
        overflow: hidden;
        text-overflow: ellipsis; 
        white-space: nowrap;
    }

    .btn-edit {
        border: 3px solid #00d7c3;
        border-radius: 20px;
    }

    .btn-delete {
        background-color: #dc3545;
        border-radius: 20px;
    }

    .action-btns {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
</style>

<body>
    <header>
        <a href="../../index.html" style="font-size: 35px; font-weight: 600; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="#destination">Tours</a></li>
            <li><a href="#contact">Pacotes</a></li>
        </ul>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-md-3 menu-container">
                <div class="menu">
                        <a class="menu-item" style="color: #3A506B"><strong>Painel de Vendedor</strong></a>
                        <a class="menu-item" href="admin.php" onclick="showContent('profile');"><i class="bi bi-person-circle"></i> Editar Perfil</a>
                        <a class="menu-item" href="vender_opcoes.php" onclick="showContent('vender-tours');"><i class="bi bi-bag"></i> Vender</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i> Gestão de Reservas</a>
                        <a class="menu-item" href="gestao_tours.php"><i class="bi bi-train-freight-front"></i> Gestão de Tours</a>      
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                </div>
            </div>

            <div class="col-md-9 p-4">
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
                                        <button type="button" class="btn btn-edit" data-toggle="modal" data-target="#editModal" data-reserva-id="<?php echo $row['id']; ?>" data-titulo="<?php echo htmlspecialchars($row['titulo']); ?>" data-descricao="<?php echo htmlspecialchars($row['descricao']); ?>" data-preco="<?php echo htmlspecialchars($row['preco']); ?>" data-localizacao="<?php echo htmlspecialchars($row['localizacao']); ?>" data-data_inicio="<?php echo htmlspecialchars($row['data_inicio']); ?>" data-data_fim="<?php echo htmlspecialchars($row['data_fim']); ?>">
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
                        <button type="submit" name="edit" class="btn btn-success">Salvar Alterações</button>
                    </form>
                </div>
            </div>
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
        });
    </script>

    <br><br><br><br>
    <?php include '../../views/partials/footer.php' ?>
</body>

</html>
