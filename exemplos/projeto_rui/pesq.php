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
    <script type="text/javascript">
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
    <h1 class="h2">Pesquisar Artigos</h1>
    <?php
    //include 'valida.php';
    //estabelece a conexão base de dados
    include 'include/liga_bd.php';

    $categoria = $_POST['categoria'];
    ?>

    <form action="pesq.php" id="f1" method="post">
        Categoria: <select name="categoria" id="categoria" onchange="this.form.submit();">
            <?php
            // esta condicional acrescenta a categoria todos ao select
            if ($categoria == 0)
                echo "<option value='0' selected>Todos</option>";
            else
                echo "<option value='0'>Todos</option>";

            $sql = "SELECT * FROM t_categoria";
            // a variavel resultado vai guardar todos os dados de todos os clientes
            $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
            //variavel para contar os registos
            //enquanto conseguir ler dados do array resultado imprime
            while ($linha = mysqli_fetch_assoc($resultado)) {
                if ($_POST['categoria'] == $linha['id'])
                    echo "<option value='" . $linha['id'] . "' selected>" . $linha['categoria'] . "</option>";
                else
                    echo "<option value='" . $linha['id'] . "'>" . $linha['categoria'] . "</option>";
            }
            ?>
        </select><br>
        <br>Subcategoria: <select name="subcategoria" id="subcategoria" onchange="atualiza();">
            <?php
            // acrescenta a opção todos nas subcategorias
            if ($categoria == 0)
                echo "<option value='0' selected>Todos</option>";
            else {
                echo "<option value='0'>Todos</option>";

                $sql2 = "SELECT * FROM t_subcat where categoria=" . $_POST['categoria'];
                // a variavel resultado vai guardar todas as subcategorias da categoria selecionada
                $resultado2 = mysqli_query($ligacao, $sql2) or die(mysqli_error($ligacao));
                //enquanto conseguir ler dados do array resultado imprime
                while ($linha2 = mysqli_fetch_assoc($resultado2)) {
                    echo "<option value='" . $linha2['id'] . "'>" . $linha2['subcat'] . "</option>";
                }
            }
            ?>
        </select>
    </form>
    <form action="pesq2.php" id="f2" method="post" enctype="multipart/form-data">
        <!-- os dois campos que seguem são atualizados automaticamente pelos selects dos forms-->
        <input type="hidden" size="30" name="valor_cat" value="0" id="valor_cat" required>
        <input type="hidden" size="30" name="valor_subcat" value="0" required><br>
        <!-- escolha o campo a pesquisar-->
        Campo a pesquisar:<select name="campo">
            <option value="0">Nenhum</option>
            <option value="1">Título</option>
            <option value="2">Descrição</option>
        </select><br /><br />

        Texto a pesquisar: <input type="text" size="50" name="texto"><br /><br />
        Preco mínimo:<input type="text" size="10" name="preco_min" required value="0"><br /><br />
        Preco máximo:<input type="text" size="10" name="preco_max" required value="99999"><br /><br />


        Estado: <select name="estado">
            <option value="0">Não aplicável</option>
            <option value="1"> 1 estrela</option>
            <option value="2"> 2 estrelas</option>
            <option value="3"> 3 estrelas</option>
            <option value="4"> 4 estrelas</option>
            <option value="5"> 5 estrelas</option>
        </select><br /><br />
        <input type="submit" value="Pesquisar">
    </form>
    <form action="pesq.php" method="post">
        <input type="hidden" name="categoria" value="0">
        <input type="submit" value="Limpar">
    </form>
    <br></br>
    <input type="button" value="Voltar" onclick="window.open('login2.php','_self')">
</body>

<?php
include_once "view/rodape.php";
?>