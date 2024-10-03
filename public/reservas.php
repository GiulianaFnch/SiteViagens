<?php 
include '../config/valida.php';
include '../config/liga_bd.php';

$user_id = $_SESSION['id'];

$sql_user = "SELECT nome FROM t_user WHERE id = ?";
$stmt_user = $ligacao->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();
$linha = $result_user->fetch_assoc(); 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete'])) {
    $reserva_id = (int) $_POST['reserva_id'];
    $sql = "DELETE FROM t_reservas WHERE id = ? AND user_id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("ii", $reserva_id, $user_id);
    $stmt->execute();
    
    echo "<script>
            alert('Reserva excluída com sucesso!');
            window.history.go(-1); // Volta para a página anterior
          </script>";
    exit;
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
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <style>
         header{
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        z-index:100;
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
       
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card .tab-content {
            border-top: 1px solid #e0e0e0;
        }

        .tab-pane {
            padding: 20px;
        }

        /* Header */
        .navbar {
            display: flex;
        }

        .navbar a {
            color: var(--bg-color);
            font-size: var(--p-font);
            font-weight: 500;
            padding: 10px 22px;
            border-radius: 4px;
           
        }

        .navbar a:hover {
            background: var(--bg-color);
            color: var(--text-color);
            box-shadow: 5px 10px 30px rgb(85 85 85 / 20%);
            border-radius: 4px;
        }
    </style>
</head>

<body>
<header>
    <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
    <li><a href="/SiteViagens/public/hotels/hotels.php" style="color: black;" >Hospedagem</a></li>
            <li><a href="#package" style="color: black;" >Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php" style="color: black; ">Tours</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black !important;"><i class='bx bx-cart'></i></a></li>


    </ul>
</header>

<br><br><br><br><br><br>

<div class="container light-style flex-grow-1 container-p-y">
    <div class="card shadow-sm rounded-lg">
        <div class="row no-gutters">
            <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                <nav class="menu">
                    <?php echo htmlspecialchars($linha['nome']); ?>
                    <a class="menu-item" href="perfil.php#account-general"><i class="bi bi-person-circle"></i> Editar perfil</a>
                    <a class="menu-item" href="reservas.php"><i class="bi bi-clipboard2"></i> Reservas</a>
                    <a class="menu-item" href="favoritos.php"><i class="bi bi-heart"></i> Favoritos</a>
                    <a class="menu-item" href="chat2.php"><i class="bi bi-chat-dots"></i> Chat</a>
                    <a class="menu-item" href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a>
                </nav>
            </div>

            <div class="col-md-9 p-4">
                <div class="container light-style">
                    <h2>Minhas Reservas</h2>

                    <?php if (isset($result) && $result->num_rows > 0): ?>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Título</th>
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
                                        <td><?php echo htmlspecialchars($row['tipo_reserva']); ?></td>
                                        <td><?php echo htmlspecialchars($row['data_reserva']); ?></td>
                                        <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                                        <td style="display: flex; gap: 10px;">
                                            <button type="button" class="btn btn-delete" data-toggle="modal" data-target="#confirmDeleteModal" data-reserva-id="<?php echo $row['id']; ?>">
                                                Excluir
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Nenhuma reserva encontrada.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel"><i class="bi bi-luggage"></i> Confirmação de Exclusão</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja excluir sua reserva?
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>
<script>
    // Captura o clique no botão de excluir e configura o ID da reserva no modal
    $('#confirmDeleteModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Botão que acionou o modal
        var reservaId = button.data('reserva-id'); // Extrai o valor do ID da reserva
        var modal = $(this);
        modal.find('#reservaId').val(reservaId); // Define o ID no campo oculto do formulário
    });
</script>

<!--footer-->
<?php include '../views/partials/footer.php' ?>

</body>
</html>