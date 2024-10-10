<?php
include '../config/valida.php';
include '../config/liga_bd.php';

if (!isset($_SESSION['id'])) {
    header("Location: /SiteViagens/public/login.php");
    exit();
}

$user_id = $_SESSION['id'];

$sql = "SELECT t_artigo.* FROM t_favoritos 
        INNER JOIN t_artigo ON t_favoritos.id_artigo = t_artigo.id 
        WHERE t_favoritos.id_user = '$user_id'"; 

$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Favoritos - BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <h1>Meus Favoritos</h1>
    </header>

    <main>
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <ul>
                <?php while ($linha = mysqli_fetch_array($resultado)): ?>
                    <li>
                        <h2><?php echo $linha['titulo']; ?></h2>
                        <img src="imagens/<?php echo $linha['foto1']; ?>" alt="Imagem do artigo">
                        <p>Preço: € <?php echo number_format($linha['preco'], 2, ',', '.'); ?></p>

                        <form action="remover_favorito.php" method="post">
                            <input type="hidden" name="id_artigo" value="<?php echo $linha['id']; ?>">
                            <input type="submit" value="Remover">
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não tem artigos favoritos.</p>
        <?php endif; ?>
    </main>
</body>
</html>
