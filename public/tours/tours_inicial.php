<?php
include '../../config/valida.php';
include '../../config/liga_bd.php';

echo "";

$tipo = 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passeios</title>
    <h1>Passeios disponíveis</h1>
    <form action="tours_inicial.php" id="f1" method="post">
        <label for="tipo">Tipo de Artigo:</label>
        <select name="tipo" id="tipo" onchange="this.form.submit();">
            <option value="0" <?php if ($tipo == 0)
                echo "selected"; ?>>Todos</option>
            <option value="1" <?php if ($tipo == 1)
                echo "selected"; ?>>Pendentes</option>
            <option value="2" <?php if ($tipo == 2)
                echo "selected"; ?>>Vendidos</option>
        </select>
    </form>
    <h2>Produtos</h2>
    <table>
        <tr>
            <td>ID </td>
            <td>Título</td>
            <td>Descrição</td>
            <td>Preço</td>
            <td>Estado</td>
            <td>Fotos</td>
            <td>Detalhes</td>
        </tr>

        <?php

        if ($tipo == 0)
            $sql = "SELECT * FROM t_artigo WHERE id_user=" . $_SESSION['id'];
        if ($tipo == 1)
            $sql = "SELECT * FROM t_artigo WHERE id_user=" . $_SESSION['id'] . " AND vendido=0";
        if ($tipo == 2)
            $sql = "SELECT * FROM t_artigo WHERE id_user=" . $_SESSION['id'] . " AND vendido!=0";

        /*
        if ($_POST['tipo'] == 0)
            $sql = "SELECT * FROM t_artigo WHERE id_user=" . $_SESSION['id'];
        if ($_POST['tipo'] == 1)
            $sql = "SELECT * FROM t_artigo WHERE id_user=" . $_SESSION['id'] . " AND vendido=0";
        if ($_POST['tipo'] == 2)
            $sql = "SELECT * FROM t_artigo WHERE id_user=" . $_SESSION['id'] . " AND vendido!=0";
        */

        $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "<tr>";
            echo "<td>" . $linha['id'] . "</td>";
            echo "<td>" . $linha['titulo'] . "</td>";
            echo "<td>" . $linha['descricao'] . "</td>";
            echo "<td>" . $linha['preco'] . "€</td>";
            echo "<td>" . $linha['estado'] . " estrelas</td>";
            echo "<td><img src='imagens/" . $linha['foto1'] . "' width='50px'></td><td>";
            ?>
            <form action="historico2.php" id="f1" method="post">
                <input type="hidden" name="id" value="<?php echo $linha['id']; ?>">
                <input type="submit" value="Ver detalhes">
            </form>
            </td>
            </tr>
            <?php
        }
        ?>
    </table>
    <a href="login2.php" target="_self">Volta ao menu</a>
    </body>

</html>