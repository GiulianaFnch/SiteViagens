<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/acesso.php'; // inicia a sessão e define os tipos de utilizador
csrf_verificar(); // só aceita o formulário de login.php (impede logins forçados por outros sites)
?>
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

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/mensagem.css">
</head>

<?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>
<br><br><br><br><br><br><br><br><br>
<body>
    <div class="message-container">
        <?php
        $nick = $_POST['nick'] ?? '';
        $pass = $_POST['pass'] ?? '';

        include '../../config/liga_bd.php';

        $stmt = $ligacao->prepare("SELECT * FROM t_user WHERE nick = ?");
        $stmt->bind_param("s", $nick);
        $stmt->execute();
        $linha = $stmt->get_result()->fetch_array();

        // caso o utilizador não exista na base de dados
        if ($linha == NULL) {
            echo "<h2>Utilizador não existe</h2>";
            echo "<input type='button' value='Registrar-se' onclick=window.open('../registro.php','_self')>";
            header("refresh:5;url=../registro.php");
        } else {
            // caso a senha esteja correta
            if (password_verify($pass, $linha['pass'])) {
                // caso o utilizador tenha sido bloqueado pelo admin
                if ($linha['tipo_user'] == TIPO_BLOQUEADO) {
                    echo '<h2>Utilizador bloqueado</h2>';
                    ?>
                    <br>
                    <input type="button" value="Voltar a tentar" onclick="window.open('../login.php','_self')">
                    <?php
                    header("refresh:2;url=../login.php");
                } else {
                    echo "<h2>Bem vindo(a), " . htmlspecialchars($linha['nome']) .  "!</h2>";
                    // Novo ID de sessão a cada login, para que um ID conhecido antes do login não sirva depois
                    session_regenerate_id(true);
                    unset($_SESSION['csrf_token']);
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


</html>
