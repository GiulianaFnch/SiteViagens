<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horsed</title>
    <style>
        /* Estilos CSS */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }

        h1 {
            color: #333;
        }

        h2 {
            color: #665;
        }

        .celula {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            margin-top: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
</head>

<body>    
    <h1>Horsed</h1>
    <h2>Registro de utilizadores</h2>
    
    <?php 

    include 'include/liga_bd.php';
    include 'include/valida_foto.php';

    if($uploadOk == 0){
        echo "<p>Erro no upload da foto</p>";
    } else {
        if($uploadOk==1){
            move_uploaded_file($_FILES['ficheiro']['tmp_name'], $target_file);
            $tmp = password_hash($_POST['pass'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO t_user (nick, nome, email, data_nasc, pass, foto) VALUES ('".$_POST['nick']."', '".$_POST['nome']."', '".$_POST['email']."', '".$_POST['data_nasc']."', '".$tmp."', '".$foto."')";
            if(mysqli_query($ligacao,$sql)){
                echo "<p>Utilizador registado com sucesso</p>";
                mysqli_close($ligacao);
            } 
        }
    }
    ?>
    <br/>
    <input type="button" value="Voltar ao menu" onclick="window.open('index.html','_self')">

</body>

</html>