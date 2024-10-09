<?php
include '../config/liga_bd.php';
include '../config/valida_foto.php';

if ($uploadOk == 0) {
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erro no Upload</title>
        <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

        <style>
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
            .message-container {
                text-align: center;
                flex-grow: 1;
                padding: 20px;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                margin: 20px;
                max-width: 400px;
            }
            h1 {
                font-size: 36px;
                margin-bottom: 20px;
                font-weight: 600;
                color: #333;
            }
            h2 {
                font-size: 24px;
                margin-bottom: 20px;
                font-weight: 400;
                color: #555;
            }
            input[type="button"] {
                padding: 10px 20px;
                background-color: #6495ed;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                margin-top: 10px;
                transition: background-color 0.3s;
            }
            input[type="button"]:hover {
                background-color: #0056b3;
            }
            footer {
                width: 100%;
                background-color: #6495ed;
                color: white;
                padding: 20px 0;
                position: relative;
                bottom: 0;
                text-align: center;
            }
            footer p {
                margin: 0;
            }
        </style>
    </head>
    <body>
        <div class="message-container">
            <h1>Erro no Upload</h1>
            <h2>Ocorreu um erro no upload da foto. Tente novamente.</h2>
            <input type="button" value="Voltar" onclick="window.history.back();">
        </div>
        <footer>
            <p>© 2024 BestWay. Todos os direitos reservados.</p>
        </footer>
    </body>
    </html>
    <?php
} else {
    if ($uploadOk == 1) {
        move_uploaded_file($_FILES['ficheiro']['tmp_name'], $target_file);
        $tmp = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO t_user (nick, nome, email, data_nasc, pass, foto) VALUES ('" . $_POST['nick'] . "', '" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $_POST['data_nasc'] . "', '" . $tmp . "', '" . $foto . "')";
        
        if (mysqli_query($ligacao, $sql)) {
            ?>
            <!DOCTYPE html>
            <html lang="pt-BR">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Sucesso no Registro</title>
                <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

                <style>
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
                    .message-container {
                        text-align: center;
                        flex-grow: 1;
                        padding: 20px;
                        background-color: white;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                        margin: 20px;
                        max-width: 400px;
                    }
                    h1 {
                        font-size: 36px;
                        margin-bottom: 20px;
                        font-weight: 600;
                        color: #333;
                    }
                    h2 {
                        font-size: 24px;
                        margin-bottom: 20px;
                        font-weight: 400;
                        color: #555;
                    }
                    input[type="button"] {
                        padding: 10px 20px;
                        background-color: #6495ed;
                        color: white;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        margin-top: 10px;
                        transition: background-color 0.3s;
                    }
                    input[type="button"]:hover {
                        background-color: #0056b3;
                    }
                    footer {
                        width: 100%;
                        background-color: #6495ed;
                        color: white;
                        padding: 20px 0;
                        position: relative;
                        bottom: 0;
                        text-align: center;
                    }
                    footer p {
                        margin: 0;
                    }
                </style>
            </head>
            <body>
                <div class="message-container">
                    <h1>Registro Bem Sucedido</h1>
                    <h2>Utilizador registado com sucesso!</h2>
                    <input type="button" value="Ir para o perfil" onclick="window.location.href='perfil.php';">
                </div>
                <footer>
                    <p>© 2024 BestWay. Todos os direitos reservados.</p>
                </footer>
            </body>
            </html>
            <?php
            header("refresh:2;url=perfil.php");
            mysqli_close($ligacao);
        }
    }
}
?>
