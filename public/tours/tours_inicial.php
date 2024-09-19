<?php
include '../../config/valida.php';
include '../../config/liga_bd.php';

echo "<h2>Bem vindo, " . $_SESSION['nick'] . "</h2>";

$categoria = 0;
// $categoria = $_POST['categoria'];
?>
<form action="tours_inicial.php" method="post">
    Categoria: <select name="categoria" id="categoria" onchange="this.form.submit();">
        <?php
        $sql = "SELECT * FROM t_categoria";
        // a variavel resultado vai guardar todos os dados de todos os clientes
        $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
        // variavel para contar os registros
        // enquanto conseguir ler dados do array resultado imprime
        echo "<option value='0'>Todos</option>";
        while ($linha = mysqli_fetch_array($resultado)) {
            if ($_POST['categoria'] == $linha['id'])
                echo "<option value='" . $linha['id'] . "' selected>" . $linha['categoria'] . "</option>";
            else
                echo "<option value='" . $linha['id'] . "'>" . $linha['categoria'] . "</option>";
        }
        ?>
    </select>
</form>
<h2>Produtos</h2>
<table>
    <tr>
        <td>ID</td>
        <td>Título</td>
        <td>Descrição</td>
        <td>Preço</td>
        <td>Estado</td>
        <td>Utilizador</td>
        <td>Fotos</td>
        <td>Comprar</td>
    </tr>
    <?php
    if ($_POST['categoria'] == 0)
        $sql = "SELECT * FROM t_artigo WHERE vendido=0";
    else
        $sql = "SELECT * FROM t_artigo WHERE vendido=0 AND cat=" . $_POST['categoria'];
    
    // a variavel resultado vai guardar todos os dados de todos os clientes
    $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
    // variavel para contar os registros
    // enquanto conseguir ler dados do array resultado imprime
    while ($linha = mysqli_fetch_array($resultado)) {

        $sql_user = "SELECT nick, email FROM t_user WHERE id=" . $linha['id_user'];

        $res_user = mysqli_query($ligacao, $sql_user) or die(mysqli_error($ligacao));
        $linha_user = mysqli_fetch_assoc($res_user);
        // Verificar se $linha_user não é null
        if ($linha_user) {
            echo "<tr>";
            echo "<td>" . $linha['id'] . "</td>";
            echo "<td>" . $linha['titulo'] . "</td>";
            echo "<td>" . $linha['descricao'] . "</td>";
            echo "<td>" . $linha['preco'] . "</td>";
            echo "<td>" . $linha['estado'] . "</td>";
            echo "<td>" . $linha['id_user'] . "- " . $linha_user['nick'] . "</td>";
            echo "<td><img src='imagens/" . $linha['foto1'] . "' width='100px' ></td><td>";
            ?>
            <form action="comprar2.php" method="post">
                <input type="hidden" name="id_artigo" value="<?php echo $linha['id']; ?>">
                <input type="submit" value="Ver comentários / Comprar">
            </form>
            <?php
            echo "</td>";
            echo "</tr>";
        } else {
            // Adicionar depuração para entender o problema
            echo "<tr>";
            echo "<td colspan='8'>Erro ao buscar dados do usuário com ID: " . $linha['id_user'] . "</td>";
            echo "</tr>";
        }
    }
    ?>
</table>

</body>
</main>

</html>