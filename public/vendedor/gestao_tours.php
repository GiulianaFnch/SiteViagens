<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Gestão Passeios</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>

        body {
            margin-top: 100px;
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
            /* Define a cor padrão dos links */
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

        .menu-item:active {
            transform: scale(0.98);
            /* Efeito de clique estilo iOS */
        }

        .settings-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .settings-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #d1d1d6;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .settings-item:hover {
            background-color: #f0f0f5;
            cursor: pointer;
        }

        .settings-item i {
            font-size: 20px;
            color: #007AFF;
            margin-right: 15px;
        }

        .settings-item .item-label {
            font-size: 17px;
        }

        .settings-item .item-right {
            display: flex;
            align-items: center;
        }

        .settings-item .item-right i {
            color: #c7c7cc;
        }

        #form-atualizar-senha {
            display: none;
            transition: opacity 0.3s ease;
        }

        #voltar-icon {
            display: none;
            font-size: 24px;
            color: #007AFF;
            cursor: pointer;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .menu-container {
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }

            .col-md-3 {
                width: 100%;
                padding: 0;
            }

            .settings-container {
                margin: 10px;
            }
        }
    </style>

    <script>
        function atualiza() {
            var categoria = document.getElementById("categoria").value;
            var subcategoria = document.getElementById("subcategoria").value;

            document.getElementById("valor_cat").value = categoria;
            document.getElementById("valor_subcat").value = subcategoria;
        }
        window.onload = atualiza; 
    </script>
</head>

<body>
    <header>
        <a href="../index.html" style="font-size: 35px; font-weight: 600; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="#destination">Tours</a></li>
            <li><a href="#contact">Pacotes</a></li>
        </ul>
    </header>

    <br><br><br>

    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <a class="menu-item" href="../vendedor/admin.php"><i class="bi bi-person-circle"></i> Editar perfil</a>
                        <a class="menu-item" href="../../../SiteViagens-main/index.html"><i class="bi bi-house-door"></i> Página Inicial</a>
                        <a class="menu-item" href="vender_tours.php"><i class="bi bi-bag"></i> Vender Tours</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i> Gestão de Rervas</a>
                        <a class="menu-item" href="gestao_tours.php"><i class="bi bi-train-freight-front"></i> Gestão de Tours</a>
                        <a class="menu-item" href="#chat"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9 p-4">
                    <h2>Gerenciar Passeios</h2>
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