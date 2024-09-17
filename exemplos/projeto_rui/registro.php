<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == "POST") 
        include 'include/valida.php';
    ?>
</head>
<body>
<main>
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="img/iefp_png" alt="" width="80" height="auto">
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
        <div class="row g-5">
            <div class="col-md-7 col-lg-8">
                <h4 class="mb-3">Registro</h4>
                <form action="registro.php" method="post" enctype="multipart/form-data">
                    <div class="row g-3">
                        <label for="nick">Nick:</label>
                        <input type="text" name="nick" required>

                        <label for="nome">Nome:</label>
                        <input type="text" name="nome" required>

                        <label for="email">Email:</label>
                        <input type="email" name="email" required>

                        <label for="data_nasc">Data de Nascimento:</label>
                        <input type="date" name="data_nasc" required>

                        <label for="password">Senha:</label>
                        <input type="password" name="pass" required>

                        <label for="foto">Foto:</label>
                        <input type="file" name="ficheiro" required>
                    </div>
                    <hr>
                    <input type="submit" value="Registrar">
                </form>
            </div>
        </div>
    </main>
    <?php
}
?>
    
</body>
</html>