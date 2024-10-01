<?php
include 'valida_adm.php';
include '../config/liga_bd.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // Atualizar o tipo_user para 2
    $sql = "UPDATE t_user SET tipo_user = 2 WHERE id = ?";
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<script>alert('Vendedor ativado com sucesso!'); window.location.href='verifica_vendedores.php';</script>";
    } else {
        echo "<script>alert('Erro ao ativar vendedor.'); window.location.href='verifica_vendedores.php';</script>";
    }

    $stmt->close();
    $ligacao->close();
}
// Consulta para obter todos os usuários
include '../config/liga_bd.php';
$sql = "SELECT id, nick, nome, email, data_nasc, nome_marca, foto, tipo_user FROM t_user WHERE tipo_user = '3'";
$result = $ligacao->query($sql);

include 'views/header.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Gerir Novos Vendedores </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button"
                class="btn btn-sm btn-outline-secondary dropdown-toggle d-flex align-items-center gap-1">
                <svg class="bi">
                    <use xlink:href="#calendar3"/>
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
                    <th>Nick</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nome Marca</th>
                    <th>Foto</th>
                    <th>Tipo de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['nick']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['nome_marca']); ?></td>
                        <td><img src="../assets/images/pics/<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto"
                                width="50"></td>
                        <td class="text-center"><?php echo htmlspecialchars($row['tipo_user']); ?></td>
                        <td>
                            <form action="verifica_vendedores.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="btn ">Ativar</button>
                            </form>
                            <form action="desativar_usuario.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="btn btn-warning btn-sm">Desativar</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>
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