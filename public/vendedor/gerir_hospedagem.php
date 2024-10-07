<?php
include '../../config/liga_bd.php';

// Verifica se a sessão está iniciada e obtém o ID do usuário
session_start();
if (!isset($_SESSION['id'])) {
    die("Usuário não está logado.");
}

$vendedor_id = $_SESSION['id']; // ID do vendedor logado

// Obter as hospedagens do usuário
$sql = "SELECT * FROM t_hospedagem WHERE id_user = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result(); // O resultado é atribuído à variável $result
$stmt->close();

// Processar a atualização de uma hospedagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $id = $_POST['reserva_id'];

    // Obter campos enviados do formulário
    $nome = !empty($_POST['nome']) ? $_POST['nome'] : null;
    $descricao = !empty($_POST['descricao']) ? $_POST['descricao'] : null;
    $n_quartos = !empty($_POST['n_quartos']) ? $_POST['n_quartos'] : null;
    $preco_diaria = !empty($_POST['preco_diaria']) ? $_POST['preco_diaria'] : null;
    $local = !empty($_POST['local']) ? $_POST['local'] : null;
    $checkin = !empty($_POST['horario_checkin']) ? $_POST['horario_checkin'] : null;
    $checkout = !empty($_POST['horario_checkout']) ? $_POST['horario_checkout'] : null;
    $data_inicio = !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : null;
    $data_fim = !empty($_POST['data_fim']) ? $_POST['data_fim'] : null;

    // Criar a query base para atualização
    $sql = "UPDATE t_hospedagem SET ";
    $fields = [];
    $params = [];

    // Adiciona apenas os campos que não estão vazios
    if ($nome !== null) {
        $fields[] = "nome = ?";
        $params[] = $nome;
    }
    if ($descricao !== null) {
        $fields[] = "descricao = ?";
        $params[] = $descricao;
    }
    if ($n_quartos !== null) {
        $fields[] = "n_quartos = ?";
        $params[] = $n_quartos;
    }
    if ($preco_diaria !== null) {
        $fields[] = "preco_diaria = ?";
        $params[] = $preco_diaria;
    }
    if ($local !== null) {
        $fields[] = "localizacao = ?";
        $params[] = $local;
    }
    if ($checkin !== null) {
        $fields[] = "horario_checkin = ?";
        $params[] = $checkin;
    }
    if ($checkout !== null) {
        $fields[] = "horario_checkout = ?";
        $params[] = $checkout;
    }
    if ($data_inicio !== null) {
        $fields[] = "data_inicio = ?";
        $params[] = $data_inicio;
    }
    if ($data_fim !== null) {
        $fields[] = "data_fim = ?";
        $params[] = $data_fim;
    }

    // Verifica se há campos para atualizar
    if (!empty($fields)) {
        $sql .= implode(", ", $fields) . " WHERE id = ?";
        $params[] = $id;

        $stmt = $ligacao->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params); // Último parâmetro é o ID (inteiro)
        
        if ($stmt->execute()) {
            echo "Hospedagem atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Nenhuma alteração feita.";
    }
}

// Excluir hospedagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM t_hospedagem WHERE id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Hospedagem excluída com sucesso!";
    } else {
        echo "Erro ao excluir: " . $stmt->error;
    }

    $stmt->close();
}
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
                     <nav class="menu">
                        <a class="menu-item" style="color: #3A506B"><strong>Painel de Vendedor</strong></a>
                        <a class="menu-item" href="admin.php" onclick="showContent('profile');"><i class="bi bi-person-circle"></i> Editar Perfil</a>
                        <a class="menu-item" href="vender_opcoes.php"><i class="bi bi-bag"></i> Vender</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i> Reservas</a>
                        <a class="menu-item" href="gestao_opcoes.php"><i class="bi bi-train-freight-front"></i> Gestão</a>
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
            </div>

            <div class="col-md-9 p-4">
                <h2>Gerenciar Hospedagem</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Nº Quartos</th>
                                <th>Diária</th>
                                <th>Localização</th>
                                <th>CheckIn</th>
                                <th>CheckOut</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                                    <td><?php echo htmlspecialchars($row['n_quartos']); ?></td>
                                    <td><?php echo htmlspecialchars($row['preco_diaria']); ?></td>
                                    <td><?php echo htmlspecialchars($row['localizacao']); ?></td>
                                    <td><?php echo htmlspecialchars($row['horario_checkin']); ?></td>
                                    <td><?php echo htmlspecialchars($row['horario_checkout']); ?></td>
                                    <td class="action-btns">
                                        <!-- Botões de ação -->
                                        <button type="button" class="btn btn-edit" data-toggle="modal" data-target="#editModal"
                                            data-reserva-id="<?php echo $row['id']; ?>"
                                            data-nome="<?php echo htmlspecialchars($row['nome']); ?>"
                                            data-n_quartos="<?php echo htmlspecialchars($row['n_quartos']); ?>"
                                            data-preco_diaria="<?php echo htmlspecialchars($row['preco_diaria']); ?>"
                                            data-localizacao="<?php echo htmlspecialchars($row['localizacao']); ?>"
                                            data-horario_checkin="<?php echo htmlspecialchars($row['horario_checkin']); ?>"
                                            data-horario_checkout="<?php echo htmlspecialchars($row['horario_checkout']); ?>">
                                            <i class="bi bi-pencil"></i> Editar
                                        </button>
                                        <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#confirmDeleteModal"
                                            data-reserva-id="<?php echo $row['id']; ?>">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="7">Nenhuma hospedagem encontrada.</td></tr>
                        <?php endif; ?>
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
                    <form method="POST" action="excluir_tour.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="delete" class="btn btn-delete">Excluir</button>
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
                    <h5 class="modal-title" id="editModalLabel">Editar Hospedagem</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" action="gerir_hospedagem.php">
                        <input type="hidden" name="reserva_id" id="editReservaId">
                        <div class="form-group">
                       <label for="nome">Título</label>
                     <input type="text" class="form-control" name="nome" id="editNome">
                        </div>
                        <div class="form-group">
                            <label for="descricao">Descrição</label>
                            <input type="text" class="form-control" name="descricao" id="editDescricao">
                        </div>
                        <div class="form-group">
                            <label for="quartos">Nº Quartos</label>
                            <input type="number" class="form-control" name="n_quartos" id="editQuartos">
                        </div>
                        <div class="form-group">
                            <label for="preco">Diária</label>
                            <input type="number" class="form-control" name="preco_diaria" id="editPreco">
                        </div>
                        <div class="form-group">
                            <label for="local">Local</label>
                            <input type="text" class="form-control" name="local" id="editLocal" >
                        </div>
                        <div class="form-group">
                            <label for="horario_checkin">Check In</label>
                            <input type="number" class="form-control" name="horario_checkin" id="editCheckIn" >
                        </div>
                        <div class="form-group">
                        <label for="horario_checkout">Check Out</label>
                        <input type="number" class="form-control" name="horario_checkout" id="editCheckOut" >
                        </div>

                        <div class="form-group">
                            <label for="data_inicio">Data Início</label>
                            <input type="date" class="form-control" name="data_inicio" id="editDataInicio" >
                        </div>
                        <div class="form-group">
                            <label for="data_fim">Data Fim</label>
                            <input type="date" class="form-control" name="data_fim" id="editDataFim" >
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
            var id = button.data('reserva-id');
            var nome = button.data('nome');
            var n_quartos = button.data('n_quartos');
            var preco_diaria = button.data('preco_diaria');
            var localizacao = button.data('localizacao');
            var horario_checkin = button.data('horario_checkin');
            var horario_checkout = button.data('horario_checkout');

            var modal = $(this);
            modal.find('#editReservaId').val(id);
            modal.find('#editNome').val(nome);
            modal.find('#editQuartos').val(n_quartos);
            modal.find('#editPreco').val(preco_diaria);
            modal.find('#editLocal').val(localizacao);
            modal.find('#editCheckIn').val(horario_checkin);
            modal.find('#editCheckOut').val(horario_checkout);
        });
    </script>

    <br><br><br><br>
    <?php include '../../views/partials/footer.php' ?>
</body>

</html>
