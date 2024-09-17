<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../assets/css/style.css">
    <!--<link rel="stylesheet" href="../assets/css/styleperfil.css"> -->
        
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") 
        include 'include/valida.php';
    ?>
</head>
<body>
    <?php include '../views/partials/header.php'; ?>
<main>
    <div class="container-registro">
        <!-- Metade com o formulário -->
        <div class="formulario">
            <div class="py-5 text-center">
                <h2>Registrar Usuário</h2>
                <p class="lead">Preencha esse formulário para registrar um novo usuário no banco de dados</p>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                include 'include/liga_bd.php';
                include 'include/valida_foto.php';

                if ($uploadOk == 0) {
                    echo "<p>Erro no upload da foto</p>";
                } else {
                    if ($uploadOk == 1) {
                        move_uploaded_file($_FILES['ficheiro']['tmp_name'], $target_file);
                        $tmp = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                        $sql = "INSERT INTO t_user (nick, nome, email, data_nasc, pass, foto) VALUES ('" . $_POST['nick'] . "', '" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $_POST['data_nasc'] . "', '" . $tmp . "', '" . $foto . "')";
                        if (mysqli_query($ligacao, $sql)) {
                            echo "<h2>Utilizador registado com sucesso</h2>";
                            mysqli_close($ligacao);
                        }
                    }
                }
            } else {
            ?>
            <form action="registro.php" method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="nick">Nick:</label>
                    <input type="text" id="nick" name="nick" class="form-control" required>
                </div>
                <div class="input-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="input-group">
                    <label for="data_nasc">Data de Nascimento:</label>
                    <input type="date" id="data_nasc" name="data_nasc" class="form-control" required>
                </div>
                <div class="input-group">
                    <label for="pass">Senha:</label>
                    <input type="password" id="pass" name="pass" class="form-control" required>
                </div>
                <div class="input-group">
                    <label for="ficheiro">Foto:</label>
                    <input type="file" id="ficheiro" name="ficheiro" class="form-control" required>
                </div>
                <button type="submit" class="home-btn">Registrar</button>
            </form>
            <?php
            }
            ?>
        </div>

        <!-- Metade com a imagem de fundo -->
        <div class="imagem-fundo"></div>
    </div>
</main>

<?php include '../views/partials/footer.php'; ?>

</body>
</html>
