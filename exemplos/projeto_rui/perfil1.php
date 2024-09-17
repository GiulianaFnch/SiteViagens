<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horsed</title>
</head>
<body>
    <h1>Horsed</h1>
    <?php
    // ligação a base de dados
    include 'include/liga_bd.php';
    // crio a instrucao sql para selecionar todos os registros
    $sql = "SELECT * FROM t_user WHERE id=$_SESSION[id]";
    // a variavel resultado guarda todos os dados vindos dos clientes
    $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
    $linha = mysqli_fetch_array($resultado); ?>
    <!-- o metodo post envia os dados de uma página para  aoutra de forma escondida 
     o metodo get envia os dados para a página seguinte pela barra de endereço -->
    <form action="perfil2.php" method="post" enctype="multipart/form-data">
        <p>ID: <input type="text" name="id" size="10" readonly value="<?php echo $linha['id'];?>"></p>
        <p>Nick: <input type="text" name="nick" size="50" value="<?php echo $linha['nick'];?>"></p>
        <p>Nome: <input type="text" name="nome" size="50" value="<?php echo $linha['nome'];?>"></p>
        <p>E-mail: <input type="text" name="email" size="50" value="<?php echo $linha['email'];?>"></p>
        <p>Data de nascimento: <input type="date" name="data_nasc" value="<?php echo $linha['data_nasc'];?>"></p>
        <p>Senha: <input type="password" name="pass" size="20" required></p><br><br>
        Foto:<br><img src="pics/<?php echo $linha['foto'];?>" width="80">
            <input type="hidden" name="nome_foto" value="<?php echo $linha['foto'];?>"><br><br>
        Nova foto: <input type="file" name="ficheiro"><br><br>
        <input type="submit" value="Alterar"><br><br>
        <a href="index.html" target="_self">Voltar ao menu</a>
    </form>
    <?php
    mysqli_close($ligacao);
    ?>
        
</body>
</html>