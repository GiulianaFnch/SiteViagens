<?php
// Ver carrinho
include "../../config/valida.php";
include "../../config/liga_bd.php";

$id_user = $_SESSION['id'];

// Verifique se a ligação ao banco de dados está correta
if ($ligacao->connect_error) {
    die("Falha na ligação: " . $ligacao->connect_error);
}

// Consulta para obter itens do carrinho
$stmt = $ligacao->prepare("SELECT c.id, a.titulo, a.preco, c.quantidade, c.tipo_item, (a.preco * c.quantidade) AS total
                           FROM t_carrinho c
                           JOIN t_artigo a ON c.id_artigo = a.id
                           WHERE c.id_user = ?");
if (!$stmt) {
    die("Erro na preparação da consulta: " . $ligacao->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado_artigos = $stmt->get_result();

if (!$resultado_artigos) {
    die("Erro na execução da consulta: " . $stmt->error);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/carrinho.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <?php include '../../views/partials/header.php'; ?>

    <main class="container mt-5 main-content">
        <h2 class="mb-4">Meu Carrinho</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Tipo de Item</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($linha = $resultado_artigos->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($linha['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['preco']) . " €</td>";
                        echo "<td>" . htmlspecialchars($linha['quantidade']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['total']) . " €</td>";
                        echo "<td>" . htmlspecialchars($linha['tipo_item']) . "</td>";
                        echo "<td>";
                        ?>
                        <form action="remover_do_carrinho.php" method="post" style="display:inline;">
                            <input type="hidden" name="id_carrinho" value="<?php echo htmlspecialchars($linha['id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                        </form>
                        <?php
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Total do Carrinho:
                <?php echo isset($_SESSION['total_carrinho']) ? htmlspecialchars($_SESSION['total_carrinho']) . " €" : "0 €"; ?>
            </h3>
            <form action="efetuar_reserva.php" method="post">
                <button type="submit" class="btn btn-success">Finalizar Reserva</button>
            </form>
        </div>
    </main>
</body>

<?php include '../../views/partials/footer.php'; ?>
</html>

<?php
$stmt->close();
$ligacao->close();
?>