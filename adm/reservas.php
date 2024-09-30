<?php
include 'valida_adm.php';
include '../config/liga_bd.php';

// Consulta para obter todas as reservas com os nomes dos usuários e dos itens
$sql = "SELECT r.id, r.item_id, r.user_id, r.tipo_reserva, r.data_reserva, r.quantidade, 
               u.nome AS user_nome, a.titulo AS item_nome
        FROM t_reservas r
        JOIN t_user u ON r.user_id = u.id
        JOIN t_artigo a ON r.item_id = a.id";
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
                    <th>Data da Reserva</th>
                    <th>Quantidade</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['item_nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['user_nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['tipo_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($row['data_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantidade']); ?></td>
                        <td>
                            <form action="editar_reserva.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Editar</button>
                            </form>
                            <form action="excluir_reserva.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                            </form>
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