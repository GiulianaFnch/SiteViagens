<?php
session_start();
// Apaga todas as variáveis da sessão
$_SESSION = array();
// Finalmente, destroi a sessao
session_destroy();
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Best Way</title>
</head>

<body>
    <h1>Logout - Best Way</h1>
    <h2>Sessão terminada com sucesso! Até à próxima</h2>
    <input type="button" value="Voltar ao início" onclick="window.open('../../index.html','_self')">
    <?php header("refresh:2;url=../../index.html"); ?>
</body>

</html>