<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

$vendedor_id = $_SESSION['id'];

$sql = "SELECT r.id, r.item_id, r.tipo_reserva, r.data_reserva, r.quantidade, r.user_id,
        CASE 
            WHEN r.tipo_reserva = 'atividade' THEN (SELECT titulo FROM t_artigo WHERE id = r.item_id)
            /*WHEN r.tipo_reserva = 'voo' THEN (SELECT titulo FROM t_voos WHERE id = r.item_id)
            WHEN r.tipo_reserva = 'hospedagem' THEN (SELECT titulo FROM t_hospedagem WHERE id = r.item_id)
            WHEN r.tipo_reserva = 'pacote' THEN (SELECT titulo FROM t_pacotes WHERE id = r.item_id)*/
        END AS titulo
        FROM t_reservas r 
        WHERE r.tipo_reserva = 'atividade' AND r.item_id IN (SELECT id FROM t_artigo WHERE id_user = ?)";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $vendedor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Vendedor</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
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
        .card {
            border-radius: 15px;
            overflow: hidden;
        }
        .tab-pane {
            padding: 20px;
        }
        .table {
            margin-top: 20px;
        }

        .btn-edit {
            background-color: transparent;
            color: #28a745;
            border-radius: 50px;
            margin-right: 5px;
            background-color: transparent;
            border: 3px solid #00d7c3;

        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
            border-radius: 50px;
            margin-right: 5px;
        }
        .action-btns {
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <header>
        <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="#home" style="color: black;">Hospedagem</a></li>
            <li><a href="#package" style="color: black;">Passagens</a></li>
            <li><a href="#destination" style="color: black;">Tours</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
        </ul>
    </header>

    <br><br><br><br><br><br><br>

    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <a class="menu-item" href="admin.php" onclick="showContent('profile');"><i class="bi bi-person-circle"></i> Editar Perfil</a>
                        <a class="menu-item" href="../../index.html"><i class="bi bi-house-door"></i> Página Inicial</a>
                        <a class="menu-item" href="vender_tours.php" onclick="showContent('vender-tours');"><i class="bi bi-bag"></i> Vender Tours</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i> Gestão de Reservas</a>
                        <a class="menu-item" href="gestao_tours.php"><i class="bi bi-train-freight-front"></i> Gestão de Tours</a>      
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9 p-4">
                    <h2>Gerenciar Reservas</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Usuário</th>
                                <th>Tipo de Reserva</th>
                                <th>Data da Reserva</th>
                                <th>Quantidade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_reserva']); ?></td>
                                <td><?php echo htmlspecialchars($row['data_reserva']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                            <td class="action-btns">
                                <!-- Botão de Editar -->
                                <button type="button" class="btn btn-edit" data-toggle="modal" data-target="#editModal" data-reserva-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>

                                    <!-- Botão de Excluir -->
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
                    Você tem certeza que deseja excluir esta reserva?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <form id="deleteForm" method="post" action="reservas.php">
                        <input type="hidden" name="reserva_id" id="reservaId">
                        <button type="submit" name="delete" class="btn btn-danger">Excluir</button>
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
                        <input type="hidden" name="reserva_id" id="editReservaId">

                        <div class="form-group">




                            <label for="quantidade">Quantidade</label>
                            <input type="number" class="form-control" name="quantidade" id="quantidade" required>
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
        });
    </script>

    <br><br>
    <?php include '../../views/partials/footer.php' ?>
</body>
</html>