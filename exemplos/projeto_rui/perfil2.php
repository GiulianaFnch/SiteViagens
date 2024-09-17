<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horsed</title>
</head>

<body>
    <h1>Horsed</h1>
    <?php
    // ligação a base de dados
    include 'include/liga_bd.php';
    $tmp = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    // caso não tenha trocado a imagem
    if (empty($_FILES['ficheiro']['name'][0])) {
        $sql = "UPDATE t_user SET nick='" . $_POST['nick'] . "', nome='" . $_POST['nome'] . "', email='" . $_POST['email'] . "', data_nasc='" . $_POST['data_nasc'] . "', pass='" . $tmp . "' WHERE id=$_SESSION[id]";
        // caso consiga executar a ação mostra a mensagem de sucesso
        if (mysqli_query($ligacao, $sql))
            echo "<p>Registo alterado com sucesso</p>";
    }
    // caso tenha trocado a imagem
    else {
        include 'include/valida_foto.php';
        if ($uploadOk == 1) {
            // crio a instrucao sql para atualizar a base de dados
            $sql = "UPDATE t_user SET nick='" . $_POST['nick'] . "', nome='" . $_POST['nome'] . "', email='" . $_POST['email'] . "', data_nasc='" . $_POST['data_nasc'] . "', pass='" . $tmp . "', foto='" . $foto . "' WHERE id=$_SESSION[id]";
            // caso consiga executar a ação mostra a mensagem de sucesso
            if (mysqli_query($ligacao, $sql)) {
                echo '<p>Registo alterado com sucesso</p>';
                // primeiro envio a nova imagem
                move_uploaded_file($_FILES["ficheiro"]["tmp_name"], $target_file);
                // apago a imagem anterior
                unlink('pics/' . $_POST['nome_foto']);
            }
        }
    }
    mysqli_close($ligacao);
    echo "<br/>";
    ?>
    <br>
    <h4>Aguarde que será redirecionado</h4>
    <a href="listar.php" target="_self">Volta ao menu</a>
</body>

</html>