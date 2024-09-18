<?php
include '../../config/liga_bd.php';
include '../../config/valida_foto.php';

if ($uploadOk == 0) {
    echo "<p>Erro no upload da foto</p>";
    
} else {
    if ($uploadOk == 1) {
        move_uploaded_file($_FILES['ficheiro']['tmp_name'], $target_file);
        $tmp = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO t_user (nick, nome, email, data_nasc, pass, foto) VALUES ('" . $_POST['nick'] . "', '" . $_POST['nome'] . "', '" . $_POST['email'] . "', '" . $_POST['data_nasc'] . "', '" . $tmp . "', '" . $foto . "')";
        if (mysqli_query($ligacao, $sql)) {
            echo "<h2>Utilizador registado com sucesso</h2>";
            header("refresh:2;url=../perfil.php");

            mysqli_close($ligacao);
        }
    }
}
?>