<?php
include '../../config/valida.php';
include '../../config/liga_bd.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    
    <title>Vender Tours</title>
    <script>
        function atualiza() {
            document.getElementById("f2").elements.namedItem("valor_cat").value =
                document.getElementById("f1").elements.namedItem("categoria").value;
            document.getElementById("f2").elements.namedItem("valor_subcat").value =
                document.getElementById("f1").elements.namedItem("subcategoria").value;
        }
        atualiza();

    </script>
</head>

<body onload="atualiza();">
    <h1 class="h2">Vender Passeios</h1>
    <?php

    $categoria = 1;
    /*
    $categoria = $_POST['categoria'];
    */
    ?>

    <form action="vender_tours.php" id="f1" method="post">
        Categoria: <select name="categoria" id="categoria" onchange="this.form.submit();">
            <?php
            $sql = "SELECT * FROM t_categoria";
            // a variavel resultado vai guardar todos os dados de todos os clientes
            $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
            //variavel para contar os registos
            //enquanto conseguir ler dados do array resultado imprime
            while ($linha = mysqli_fetch_assoc($resultado)) {
                if ($categoria == $linha['id'])
                    /*if ($_POST['categoria'] == $linha['id'])*/
                    echo "<option value='" . $linha['id'] . "' selected>" . $linha['categoria'] . "</option>";
                else
                    echo "<option value='" . $linha['id'] . "'>" . $linha['categoria'] . "</option>";
            }
            ?>
        </select>
        <br>Subcategoria: <select name="subcategoria" id="subcategoria" onchange="atualiza();">
            <?php
            $sql2 = "SELECT * FROM t_subcat where categoria=" . $categoria;
            /*
            $sql2 = "SELECT * FROM t_subcat where categoria=" . $_POST['categoria'];
            */
            // a variavel resultado vai guardar todas as subcategorias da categoria selecionada
            
            $resultado2 = mysqli_query($ligacao, $sql2) or die(mysqli_error($ligacao));
            //enquanto conseguir ler dados do array resultado imprime
            while ($linha2 = mysqli_fetch_assoc($resultado2)) {
                echo "<option value='" . $linha2['id'] . "'>" . $linha2['subcat'] . "</option>";
            }
            ?>
        </select>
    </form>
    <form action="vender_tours2.php" id="f2" method="post" enctype="multipart/form-data">
        <input type="hidden" size="28" name="id_user" value="<?php echo $_SESSION['id']; ?>" required>
        <input type="hidden" size="38" name="valor_cat" value="1" id="valor_cat" required>
        <input type="hidden" size="30" name="valor_subcat" value="1" required>
        Titulo:<input type="text" size="50" name="titulo" required><br /><br />
        Descrição:<br>
        <textarea cols="88" rows="5" name="descricao"></textarea><br /><br />
        Preco:<input type="text" size="18" name="preco" required><br /><br />
        Estado: <select name="estado">
            <option value="1"> 1 estrela</option>
            <option value="2"> 2 estrelas</option>
            <option value="3"> 3 estrelas</option>
            <option value="4"> 4 estrelas</option>
            <option value="5"> 5 estrelas</option>
        </select><br /><br />
        Foto1: <input type="file" name="ficheiro1"><br><br>
        Foto2:<input type="file" name="ficheiro2"><br><br>
        Foto3:<input type="file" name="ficheiro3"><br><br>
        <input type="submit" value="Vender">
    </form>
    <form action="vender_tours.php" method="post">
        <input type="hidden" name="categoria" value="1">
        <input type="submit" value="Limpar">
    </form>
</body>

</html>
<?php
//include_once "view/rodape.php";
?>