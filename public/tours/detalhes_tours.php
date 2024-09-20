<?php
include_once "../../config/valida.php";
include_once "../../config/liga_bd.php";

//$id_artigo = $_POST['id_artigo'];

$id_artigo = 1;
?>

<?php
$sql = "SELECT * FROM t_artigo WHERE id=" . $id_artigo;
// echo $sql;
// a variavel resultado vai guardar todos os dados de todos os clientes
$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
$linha = mysqli_fetch_array($resultado);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Comprar</title>

    <!-- 
        Exemplo de como incluir o CSS
    -->

    <style>
        img.artigo-img {
            max-width: 200px;
            /* Limita a largura máxima */
            max-height: 200px;
            /* Limita a altura máxima */
            width: auto;
            /* Mantém a proporção da largura */
            height: auto;
            /* Mantém a proporção da altura */
        }
    </style>
</head>
<main>
    <!-- 
        Aqui é que precisa ajustar para ficar bonito

    -->
    <h2>Comprar</h2>
    ID: <?php echo $linha['id']; ?><br>
    Título: <?php echo $linha['titulo']; ?><br>
    Descrição: <?php echo $linha['descricao']; ?><br>
    Preço: <?php echo $linha['preco']; ?><br>
    Estado: <?php echo $linha['estado']; ?><br>

    <img src="artigos/<?php echo $linha['foto1']; ?>" class="artigo-img"><br>
    <?php
    if ($linha['foto2'] != NULL)
        echo "<img src='artigos/" . $linha['foto2'] . "' class='artigo-img'><br>";
    if ($linha['foto3'] != NULL)
        echo "<img src='artigos/" . $linha['foto3'] . "' class='artigo-img'><br>";
    ?>

    <!--
<h3>Comentários</h3>
<table>
    <tr>
        <td>ID</td>
        <td>Comentário</td>
        <td>Classificação</td>
        <td>Data</td>
        <td>Utilizador</td>
    </tr>

    <?php
    /*
    $sql = "SELECT * FROM t_art_comen WHERE publico=0 AND id_artigo=" . $id_artigo;
    // a variavel resultado vai guardar todos os dados de todos os clientes
    $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
    // variavel para contar os registros
    // enquanto conseguir ler dados do array resultado imprime
    while ($linha = mysqli_fetch_array($resultado)) {
        echo "<tr>";
        echo "<td>" . $linha['id'] . "</td>";
        echo "<td>" . $linha['comentario'] . "</td>";
        echo "<td>" . $linha['avaliacao'] . "</td>";
        echo "<td>" . $linha['data'] . "</td>";
        echo "<td>" . $linha['id_user'] . "</td>";
        echo "</tr>";
    }
        */
    ?>

</table>
<form action="comprar3.php" method="post">
    <input type="hidden" name="id_artigo" value="<?php echo $id_artigo; ?>">
    Novo comentário:<br> <textarea cols="80" rows="5" name="comentario"></textarea><br>
    Classificação: <input type="number" min="1" max="5" name="classificacao" required><br>
    Data: <input type="text" readonly name="data" value="<?php echo date("h:i:sa"); ?>"><br>
    Público: <select name="publico">
        <option value="0">Público</option>
        <option value="1">Privado</option>
    </select>
    <input type="submit" value="Comentar">

-->
</main>

</html>