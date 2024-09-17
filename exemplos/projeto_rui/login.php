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
    <?php
    include 'include/liga_bd.php';

    $sql = "SELECT * FROM t_user WHERE nick = '$_POST[nick]'";
    $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
    $linha = mysqli_fetch_array($resultado);

    // caso o utilizador não exista na base de dados
    if ($linha == NULL) {
        echo "<h2>Utilizador não existe</h2>";
        echo "<input type='button' value='Voltar a tentar' onclick=window.open('index.html','_self')> ";
    } else {
        //caso a senha esteja correta
        if (password_verify($_POST['pass'], $linha['pass'])) {
            //caso o utilizador tenha sido bloqueado pelo admin
            if ($linha['apagado'] == 1) {
                echo '<h1>Horsed</h1>';
                echo '<h2>Utilizador bloqueado</h2>';
                echo "<img width='200' src='img/bloqueado.png'>";
                ?>
                <br>
                <input type="button" value="Voltar a tentar" onclick="window.open('index.html','_self')">
                <?php
            } else {
                echo "<h1>Horsed</h1>";
                echo "<h2>Bem vindo, " . $linha['nome'] . "</h2>";
                $_SESSION['id'] = $linha['id'];
                $_SESSION['nick'] = $linha['nick'];
                echo "<h2>Utilizador validade</h2>";
                echo "<input type='button' value='Continuar' onclick=window.open('login2.php','_self')> ";
            }
        }
        //caso a senha esteja errada
        else {
            echo "<h2>Senha errada</h2>";
            echo "<input type='button' value='Voltar a tentar' onclick=window.open('index.html','_self')> ";
        }
    }

    mysqli_close($ligacao);
    ?>

</body>

</html>