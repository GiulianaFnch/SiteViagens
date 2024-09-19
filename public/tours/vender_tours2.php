<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Vender Passeios</title>
</head>

<body>
    <h1>Registo de venda</h1>
    <?php
    include '../../config/liga_bd.php';

    // Inicializar a variável $uploadOk
    $uploadOk = 1;

    // Verificar e validar a primeira foto
    $_FILES["ficheiro"] = $_FILES["ficheiro1"];
    include 'valida_fotoa.php';

    if ($uploadOk == 0) {
        echo "O seu ficheiro não foi enviado.";
    } else {
        if ($uploadOk == 1) {
            move_uploaded_file($_FILES["ficheiro"]["tmp_name"], $target_file);
            //crio a instrução sql para adicionar um registo na base de dados
            $sql = "INSERT INTO t_artigo (id_user, cat, subcat, titulo, descricao, preco, estado, foto1) VALUES ($_POST[id_user], $_POST[valor_cat], $_POST[valor_subcat], '$_POST[titulo]', '$_POST[descricao]', $_POST[preco], $_POST[estado], '" . $foto . "');";
            // tento inserir na base de dados
            //echo $sql;

            if (mysqli_query($ligacao, $sql))
                echo "<h2>Registo efetuado com sucesso! </h2>";

            // Validação da foto 2
            if (!empty($_FILES['ficheiro2']['name'])) {
                $sql = "SELECT id FROM t_artigo ORDER BY id DESC LIMIT 1";
                $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
                $linha = mysqli_fetch_assoc($resultado);
                $_FILES["ficheiro"] = $_FILES["ficheiro2"];
                include 'valida_fotoa.php';
                if ($uploadOk == 1) {
                    move_uploaded_file($_FILES["ficheiro"]["tmp_name"], $target_file);
                    $sql2 = "UPDATE t_artigo SET foto2='" . $foto . "' WHERE id= $linha[id];";
                    //echo $sql2;
                    mysqli_query($ligacao, $sql2);
                }
            }

            // Validação da foto 3
            if (!empty($_FILES['ficheiro3']['name'])) {
                $sql = "SELECT id FROM t_artigo ORDER BY id DESC LIMIT 1";
                $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
                $linha = mysqli_fetch_assoc($resultado);
                $_FILES["ficheiro"] = $_FILES["ficheiro3"];
                include 'valida_fotoa.php';
                if ($uploadOk == 1) {
                    move_uploaded_file($_FILES["ficheiro"]["tmp_name"], $target_file);
                    $sql3 = "UPDATE t_artigo SET foto3='" . $foto . "' WHERE id= $linha[id];";
                    mysqli_query($ligacao, $sql3);
                }
            }
        }
    }

    mysqli_close($ligacao);
    ?>
    <br />
    <a href="tours_inicial.php" target="_self">Volta ao Menu</a>
</body>

</html>