<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Vendedor</title>

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="../../assets/css/style.css">
    <!--<link rel="stylesheet" href="../assets/css/styleperfil.css"> -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <?php include '../../views/partials/header.php'; ?>
    <main>
        <div class="container-registro">
            <!-- Metade com o formulário -->
            <div class="formulario">
                <div class="py-5 text-center">
                    <h2>Registrar-se como vendedor</h2>
                    <p class="lead">Preencha esse formulário para registrar um novo usuário no banco de dados</p>
                </div>

                <?php
                if ($_SERVER['REQUEST_METHOD'] == "POST") {
                    include '../../config/liga_bd.php';

                    // Definir tipo_user como 3 por padrão (vendedor não validado)
                    $tipo_user = 3;

                    $tmp = password_hash($_POST['pass'], PASSWORD_DEFAULT);
                    $sql = "INSERT INTO t_user (nick, nome, email, nome_marca, pass, tipo_user) VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($ligacao, $sql);
                    mysqli_stmt_bind_param($stmt, 'sssssi', $_POST['nick'], $_POST['nome'], $_POST['email'], $_POST['nome_marca'], $tmp, $tipo_user);

                    if (mysqli_stmt_execute($stmt)) {
                        echo "<h2>Utilizador registado com sucesso</h2>";
                        echo "<p>Por favor, aguarde a validação do administrador</p>";
                    } else {
                        echo "<h2>Erro ao registar utilizador</h2>";
                    }

                    mysqli_stmt_close($stmt);
                    mysqli_close($ligacao);
                } else {
                    ?>
                    <form action="registro_vendedor.php" method="post" enctype="multipart/form-data">
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
                            <label for="nome_marca">Nome da marca:</label>
                            <input type="text" id="nome_marca" name="nome_marca" class="form-control" required>
                        </div>
                        <div class="input-group">
                            <label for="pass">Senha:</label>
                            <input type="password" id="pass" name="pass" class="form-control" required>
                        </div>

                        <button type="submit" class="home-btn">Enviar pedido</button>
                    </form>
                    <?php
                }
                ?>
            </div>

            <!-- Metade com a imagem de fundo -->
            <div class="imagem-fundo"></div>
        </div>
    </main>

    <?php include '../../views/partials/footer.php'; ?>

</body>

</html>