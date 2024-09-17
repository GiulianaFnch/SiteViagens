<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/style.css">
    <!--<link rel="stylesheet" href="../assets/css/styleperfil.css"> -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST")
        include '../config/valida.php';
    ?>
</head>

<body>
    <?php
    include '../views/partials/header.php'; ?>
    <main>
        <div class="container-registro">
            <!-- Metade com o formulário -->
            <div class="formulario">
                <div class="py-5 text-center">
                    <h2>Login de Usuário</h2>
                    <p class="lead">Preencha esse formulário para registrar um novo usuário no banco de dados</p>
                </div>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    $nick = $_POST['nick'];
                    $pass = $_POST['pass'];

                    include '../config/liga_bd.php';

                    $sql = "SELECT * FROM t_user WHERE nick = '$_POST[nick]'";
                    $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
                    $linha = mysqli_fetch_array($resultado);

                    // caso o utilizador não exista na base de dados
                    if ($linha == NULL) {
                        echo "<h2>Utilizador não existe</h2>";
                        echo "<input type='button' value='Registrar-se!' onclick=window.open('registro.php','_self')> ";
                    } else {
                        //caso a senha esteja correta
                        if (password_verify($_POST['pass'], $linha['pass'])) {
                            //caso o utilizador tenha sido bloqueado pelo admin
                            if ($linha['tipo_user'] == 1) {
                                echo '<h2>Utilizador bloqueado</h2>';
                                echo "<img width='200' src='img/bloqueado.png'>";
                                ?>
                                <br>
                                <input type="button" value="Voltar a tentar" onclick="window.open('login.php','_self')">
                                <?php
                            } else {
                                echo "<h2>Bem vindo, " . $linha['nome'] . "</h2>";
                                $_SESSION['id'] = $linha['id'];
                                $_SESSION['nick'] = $linha['nick'];
                                echo "<h2>Utilizador validado!</h2>";
                                echo "<input type='button' value='Continuar' onclick=window.open('perfil.php','_self')> ";
                            }
                        }
                        //caso a senha esteja errada
                        else {
                            echo "<h2>Volte a tentar</h2>";
                        }
                    } 
                    mysqli_close($ligacao); // fecha a ligação à base de dados

                } else {
                    ?>
                    <form action="login.php" method="post" enctype="multipart/form-data">
                        <div class="input-group">
                            <label for="nick">Nick:</label>
                            <input type="text" id="nick" name="nick" class="form-control" required>
                        </div>
                        <div class="input-group">
                            <label for="pass">Senha:</label>
                            <input type="password" id="pass" name="pass" class="form-control" required>
                        </div>
                        <button type="submit" class="home-btn">Login</button>
                        <input type="button" value="Registrar" class="btn" onclick="window.open('registro.php', '_self')">
                    </form>
                    <?php
                }

                ?>

            </div>

            <!-- Metade com a imagem de fundo -->
            <div class="imagem-fundo"></div>
        </div>
    </main>

    <?php

    include '../views/partials/footer.php'; ?>

</body>

</html>