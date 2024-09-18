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
    //caso a senha esteja correta
    if (password_verify($_POST['pass'], $linha['pass'])) {
        //caso o utilizador tenha sido bloqueado pelo admin
        if ($linha['tipo_user'] == 1) {
            echo '<h2>Utilizador bloqueado</h2>';
            ?>
            <br>
            <input type="button" value="Voltar a tentar" onclick="window.open('../login.php','_self')">
            <?php
            header("refresh:2;url=../login.php"); 
        } else {
            echo "<h2>Bem vindo, " . $linha['nome'] . "</h2>";
            $_SESSION['id'] = $linha['id'];
            $_SESSION['nick'] = $linha['nick'];
            echo "<h2>Utilizador validado!</h2>";
            echo "<input type='button' value='Continuar' onclick=window.open('../perfil.php','_self')>";
            //header("refresh:2;url=../perfil.php"); 
        }
    }
    //caso a senha esteja errada
    else {
        echo "<h2>Volte a tentar</h2>";
        header("refresh:2;url=../login.php"); 
    }
}
mysqli_close($ligacao); // fecha a ligação à base de dados

?>