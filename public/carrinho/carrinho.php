<?php
// Ver carrinho
include "../../config/valida.php";
include "../../config/liga_bd.php";

$id_user = $_SESSION['id']; // Assumindo que o ID do usuário está armazenado na sessão

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
    <title>Meu Carrinho</title>

    <link rel="stylesheet" href="../../assets/css/style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }
        main {
            padding-top: 100px; /* pra evitar a sobreposição do header */
        }
    </style>
</head>

<body>
    <?php include '../../views/partials/header.php'; ?>
    
    <main>
        <h2>Meu Carrinho</h2>
        <table>
            <tr>
                <th>Título</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Total</th>
                <th>Tipo de Item</th>
                <th>Ações</th>
            </tr>
            <?php
<<<<<<< Updated upstream
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
                    <input type="submit" value="Remover">
                </form>
                <?php
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </table>
        <h3>Total do Carrinho:
            <?php echo isset($_SESSION['total_carrinho']) ? htmlspecialchars($_SESSION['total_carrinho']) . " €" : "0 €"; ?>
        </h3>
    </main>
=======
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <h3>Total do Carrinho:
        <?php echo isset($_SESSION['total_carrinho']) ? htmlspecialchars($_SESSION['total_carrinho']) . " €" : "0 €"; ?>
    </h3>
    <form action="efetuar_reserva.php" method="post">
        <input type="submit" value="Efetuar reserva">
    </form>
>>>>>>> Stashed changes
</body>

</html>

<?php
$stmt->close();
$ligacao->close();
?>