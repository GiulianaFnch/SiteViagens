<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&display=swap" rel="stylesheet">

    <style>
        /* Estilos para garantir a estrutura da página */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
        }

        /* Conteúdo principal */
        .message-container {
            text-align: center;
            flex-grow: 1;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        input[type="button"] {
            padding: 10px 20px;
            background-color: #6495ed;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="button"]:hover {
            background-color: #0056b3;
        }

        /* Estilo do footer fixo na parte inferior */
        footer {
            width: 100%;
            background-color: #6495ed;
            color: white;
            padding: 20px 0;
            position: fixed;
            bottom: 0;
            text-align: center;
        }

        footer p {
            margin: 0;
            color: white;
        }
    </style>
</head>

<header>
    <a href="/SiteViagens/index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
    <ul class="navbar">
        <li><a href="/SiteViagens/index.html#home" style="color: black;">Hospedagem</a></li>
        <li><a href="#package" style="color: black;">Passagens</a></li>
        <li><a href="/SiteViagens/public/tours/tours.php" style="color: black;">Passeios</a></li>
        <li><a href="#contact" style="color: black;">Pacotes</a></li>
        <li><a href="public/login.php" style="color: black;"><i class='bx bx-user'></i></a></li>
        <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a></li>
    </ul>
</header>
<br><br><br><br><br><br><br><br><br>
<body>
    <div class="message-container">
        <?php
        session_start(); // inicia a sessão

        $nick = $_POST['nick'];
        $pass = $_POST['pass'];

        include '../../config/liga_bd.php';

        $sql = "SELECT * FROM t_user WHERE nick = '$_POST[nick]'";
        $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
        $linha = mysqli_fetch_array($resultado);

        // caso o utilizador não exista na base de dados
        if ($linha == NULL) {
            echo "<h2>Utilizador não existe</h2>";
            echo "<input type='button' value='Registrar-se' onclick=window.open('../registro.php','_self')>";
            header("refresh:5;url=../registro.php");
        } else {
            // caso a senha esteja correta
            if (password_verify($_POST['pass'], $linha['pass'])) {
                // caso o utilizador tenha sido bloqueado pelo admin
                if ($linha['tipo_user'] == 1) {
                    echo '<h2>Utilizador bloqueado</h2>';
                    ?>
                    <br>
                    <input type="button" value="Voltar a tentar" onclick="window.open('../login.php','_self')">
                    <?php
                    header("refresh:2;url=../login.php");
                } else {
                    echo "<h2>Bem vindo(a), " . $linha['nome'] .  "!</h2>";
                    $_SESSION['id'] = $linha['id'];
                    $_SESSION['nick'] = $linha['nick'];
                    $_SESSION['logged_in'] = true; // Define a variável de sessão logged_in como true
                    
                    echo "<h2>Utilizador validado!</h2>";
                    echo "<input type='button' value='Continuar' onclick=window.open('../../index.html','_self')>";
                    header("refresh:2;url=../../index.html");
                }
            }
            // caso a senha esteja errada
            else {
                echo "<h2>Volte a tentar</h2>";
                header("refresh:2;url=../login.php");
            }
        }
        mysqli_close($ligacao); // fecha a ligação à base de dados
        ?>
    </div>
</body>

<!-- Footer fixo -->
<footer>
    <p>© 2024 BestWay. Todos os direitos reservados.</p>
</footer>

<!-- link to js -->
<script type="text/javascript" src="../../assets/js/script.js"></script>

</html>
