<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

// evitar a injeção de SQL
$stmt = $ligacao->prepare("SELECT * FROM t_artigo WHERE localizacao LIKE ? AND data_inicio <= ? AND data_fim >= ? AND vendido = 0");
$localizacao = "%" . $_GET['localizacao'] . "%";
$data = $_GET['data'];
$stmt->bind_param("sss", $localizacao, $data, $data);
$stmt->execute();
$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Pesquisa</title>
    <style>
        /* Esse é um exemplo de CSS para as divs em que as atividades vão repetir, podem alterar por favor :) */
        .box {
            display: flex;
            border: 1px solid black;
            margin: 10px;
            padding: 10px;
        }

        .thum {
            margin-right: 10px;
        }

        .thum img {
            width: 100px;
            height: 100px;
        }

        .dest-content {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body>
    <h2>Resultados da pesquisa</h2>
    <?php

    // enquanto houver atividades na variavel resultado, vai repetir o ciclo
    while ($linha = $resultado->fetch_array()) {

        // podem adicionar/alterar as divs, as classes a vontade para ficar bonito (!!)
        // mas tem que ficar dentro do while para repetir para cada atividade
        // e o que está dentro do echo é o que vai ser mostrado na página
    
        echo "<div class='box'>";
        echo "<div class='thum'><img src='imagens/" . $linha['foto1'] . "'></div>";
        echo "<div class='dest-content'>";
        echo "<h3>" . $linha['titulo'] . "</h3>";
        echo "<p>" . $linha['descricao'] . "</p>";
        echo "<p>Localização: " . $linha['localizacao'] . "</p>";
        echo "<p>Preço: " . $linha['preco'] . " €</p>";
        ?>
        <form action="detalhes_tours.php" id="f1" method="post">
            <input type="hidden" size="20" name="id_artigo" value="<?php echo $linha['id']; ?>">
            <input type="submit" value="Ver detalhes">
        </form>
        <form action="../carrinho/adicionar_ao_carrinho.php" id="f2" method="post">
            <input type="hidden" size="20" name="id_artigo" value="<?php echo $linha['id']; ?>">
            <input type="hidden" name="tipo_item" value="atividade">
            <input type="hidden" name="return_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <input type="submit" value="Adicionar ao carrinho">
        </form>
        <?php

        echo "</div>";
        echo "</div>";
    }
    $stmt->close();
    ?>

</body>

</html>