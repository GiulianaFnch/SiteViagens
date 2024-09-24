<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewpoert" content="width=device-width, inicial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <!--header-->
    <header>
        <a href="#" class="logo">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="#package">Tours</a></li>
            <li><a href="#contact">Pacotes</a></li>
            <li><a href="public/login.php">Fazer login</a></li>
        </ul>
    </header>

    <!--Home section-->
    <section class="listar-tours" id="home">
        <!--colocar o nome grande -->
        <div class="home-text2">
            <h1>Atividades <br> Mais <br> Procuradas.</h1>
            <p style="color: aliceblue;">"Encontre destinos e experiências que combinam com você!"</p>
        </div>
    </section>

    <!--container-->
    <section class="container">
        <div class="text">
          <center><h2>Atividades mais procuradas. </h2>

    </section>
    <?php
include '../../config/valida.php';
include '../../config/liga_bd.php';

$categoria = isset($_POST['categoria']) ? (int)$_POST['categoria'] : 0;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Passeios</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .passeios-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr); /* 3 passeios por linha */
            gap: 20px;
            margin: 20px 0;
        }
        .box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .thum img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
        .dest-content {
            margin-top: 10px;
        }
        .location h3 {
            color: gray;
            margin: 0;
        }
        .location h5 {
            margin: 5px 0;
            color: #555;
        }
        .stars h4 {
            color: #333;
            margin: 10px 0 0;
        }
        .stars i {
            color: #f39c12;
        }
        .btn-comprar {
            margin-top: 10px;
        }
        @media (max-width: 768px) {
            .passeios-grid {
                grid-template-columns: repeat(2, 1fr); /* 2 por linha em telas menores */
            }
        }
        @media (max-width: 480px) {
            .passeios-grid {
                grid-template-columns: 1fr; /* 1 por linha em telas bem pequenas */
            }
        }
    </style>
</head>
<body>

<h2>Selecione uma Categoria</h2>
<form action="listar_tours.php" method="post">
    <label for="categoria">Categoria:</label>
    <select name="categoria" id="categoria" onchange="this.form.submit();">
        <option value="0">Todos</option>
        <?php
        $sql = "SELECT * FROM t_categoria";
        $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

        while ($linha = mysqli_fetch_array($resultado)) {
            $selected = ($categoria == $linha['id']) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($linha['id']) . "' $selected>" . htmlspecialchars($linha['categoria']) . "</option>";
        }
        ?>
    </select>
    <br>
    <br>

</form>

<div class="passeios-grid">
<?php
$sql = "SELECT * FROM t_artigo WHERE vendido = 0";
if ($categoria != 0) {
    $sql .= " AND cat = " . $categoria;
}

$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

if (mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_array($resultado)) {
        $sql_user = "SELECT nick, email FROM t_user WHERE id = " . $linha['id_user'];
        $res_user = mysqli_query($ligacao, $sql_user) or die(mysqli_error($ligacao));
        $linha_user = mysqli_fetch_assoc($res_user);

        if ($linha_user) {
            echo '<div class="box">';
            echo '    <div class="thum">';
            echo "        <img src='imagens/" . htmlspecialchars($linha['foto1']) . "' alt='Foto do passeio'>";
            echo '    </div>';
            echo '    <div class="dest-content">';
            echo '        <div class="location">';
            echo "            <h3>" . htmlspecialchars($linha['titulo']) . "</h3>";
            echo "            <h5>" . htmlspecialchars($linha['descricao']) . "</h5>";
            echo '        </div>';
            echo '        <div class="stars">';
            // Exemplo para exibir estrelas (pode adaptar para mostrar baseadas em avaliação)
            echo '            <a href="#"><i class="bx bxs-star"></i></a>';
            echo '            <a href="#"><i class="bx bxs-star"></i></a>';
            echo '            <a href="#"><i class="bx bxs-star"></i></a>';
            echo '            <a href="#"><i class="bx bxs-star"></i></a>';
            echo "            <h4>A partir de " . htmlspecialchars($linha['preco']) . " € por pessoa</h4>";
            echo '        </div>';
            echo '        <div class="btn-comprar">';
            echo '            <form action="detalhes_tours.php" method="post">';
            echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
            echo '                <input type="submit" value="Ver comentários / Comprar">';
            echo '            </form>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        } else {
            echo "<div class='box'><p>Erro ao buscar dados do utilizador com ID: " . htmlspecialchars($linha['id_user']) . "</p></div>";
        }
    }
} else {
    echo "<p>Nenhum passeio encontrado.</p>";
}
?>
</div>

</body>
</html>
