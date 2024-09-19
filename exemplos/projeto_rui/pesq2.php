<?php
session_start();
include_once "view/topo.php";
include_once "view/menu.php";


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Horsed</title>
    <link href="style/dashboard.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1 class="h2">Vender Artigos</h1>
    <?php
    //include 'valida.php';
    //estabelece a conexão base de dados
    include 'include/liga_bd.php';

    ?>
    <h2>Pesquisa de produtos</h2>
    <table>
        <tr>
            <td>ID</td>
            <td>Título</td>
            <td>Descrição</td>
            <td>Preço</td>
            <td>Estado</td>
            <td>Fotos</td>
            <td>Comprar</td>
        </tr>
        <?php
        // caso a categoria seja todas
        if ($_POST['valor_cat'] == 0) {
            $sql = "SELECT * FROM t_artigo where vendido=0";
        } else {
            // caso tenha escolhido uma categoria, verifica se escolheu alguma subcategoria
            if ($_POST['valor_subcat'] == 0)
                $sql = "SELECT * FROM t_artigo WHERE vendido=0 AND cat=" . $_POST['valor_cat'];
            else
                $sql = "SELECT * FROM t_artigo WHERE vendido=0 and cat=" . $_POST['valor_cat'] . " AND subcat=" . $_POST['valor_subcat'];
        }
        // caso tenha optado por filtrar por algum campo contendo um texto ou expressão
        if ($_POST['campo'] == 1)
            $sql = $sql . " AND titulo LIKE '%" . $_POST['texto'] . "%'";
        if ($_POST['campo'] == 2)
            $sql = $sql . " AND descricao LIKE '%" . $_POST['texto'] . "%'";
        // filtro para acrescentar os preços
        $sql = $sql . " AND preco > " . $_POST['preco_min'] . " AND preco < " . $_POST['preco_max'];
        // condição para acrescentar o estado, caso tenha escolhido algum
        if ($_POST['estado'] > 0)
            $sql = $sql . " AND estado = " . $_POST['estado'];

        // a variavel resultado vai guardar o resultado de todos os clientes
        $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
        // enquanto houver clientes na variavel resultado
        while ($linha = mysqli_fetch_array($resultado)) {
            echo "<tr>";
            echo "<td>" . $linha['id'] . "</td>";
            echo "<td>" . $linha['titulo'] . "</td>";
            echo "<td>" . $linha['descricao'] . "</td>";
            echo "<td>" . $linha['preco'] . "</td>";
            echo "<td>" . $linha['estado'] . "</td>";
            echo "<td><img src='artigos/" . $linha['foto1'] . "' width='50px'></td>";
            ?>
            <form action="comprar2.php" id="f1" method="post">
                <input type="hidden" size="20" name="id_artigo" value="<?php echo $linha['id']; ?>">
                <td><input type="submit" value="Ver comentários/Comprar"></td>
                </tr>
                <?php
        }

        echo $sql;
        ?>
    </table>
    <input type="button" value="Voltar" onclick="window.open('login2.php','_self')">

</body>

<?php
include_once "view/rodape.php";
?>