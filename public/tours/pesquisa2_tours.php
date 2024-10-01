<?php
/*include "../../config/valida.php";*/
include "../../config/liga_bd.php";

// Evitar a injeção de SQL
$stmt = $ligacao->prepare("SELECT * FROM t_artigo WHERE localizacao LIKE ? AND data_inicio <= ? AND data_fim >= ? AND vendido = 0");
$localizacao = "%" . $_GET['localizacao'] . "%";
$data = $_GET['data'];
$stmt->bind_param("sss", $localizacao, $data, $data);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisa de Atividades</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&family=Roboto:wght@400&display=swap" rel="stylesheet">

    <style>

        /* Header com fundo branco ao rolar */
        header.scrolled {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin: 20px 0;
        }

        .location {
            font-family: 'Roboto', sans-serif; /* Fonte diferente para a localização */
            font-weight: bold; /* Pode ser alterado conforme desejado */
            color: #2c3e50; /* Cor opcional para destacar a localização */
            text-transform: uppercase; /* Transforma o texto em maiúsculas */
        }

        .passeios-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin: 20px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .box:hover {
            transform: scale(1.05);
        }

        .thum img {
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 8px;
        }

        .dest-content {
            margin-top: 10px;
        }

        .dest-content h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .dest-content p {
            margin: 5px 0;
            color: #666;
        }

        .btn-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-comprar,
        .btn-ver-mais {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-align: center;
            color: white;
        }

        .btn-comprar {
            background-color: #6495ed;
        }

        .btn-comprar:hover {
            background-color: #218838;
        }

        .btn-ver-mais {
            background-color: #6495ed;
        }

        .btn-ver-mais:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .passeios-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .passeios-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="/SiteViagens/"  style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;" class="logo">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="/SiteViagens/public/hotels/hotels.php" style="color: black;">Hospedagem</a></li>
            <li><a href="#package" style="color: black;">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php" style="color: black;">Passeios</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
            <li><a href="public/perfil.php" style="color: black;"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>
<br><br><br><br><br><br>
    <!--container-->
        <section class="search-bar">
            <form action="pesquisa2_tours.php" method="get">
                <!-- Preserva os valores de localização e data nos campos de pesquisa -->
                <input type="text" placeholder="Para onde?" name="localizacao" value="<?php echo htmlspecialchars($_GET['localizacao'] ?? ''); ?>" required>
                <input type="date" placeholder="Selecione as datas" name="data" value="<?php echo htmlspecialchars($_GET['data'] ?? ''); ?>" required>
                <button style="color: grey;" type="submit">Pesquisar</button>
            </form>
            </section>
            <section class="destinos" id="destinos">
        <div class="title">
            <h2>
                Coisas para fazer em 
                <span class="location">
                    <?php
                        // Verifica se a localização foi definida
                        if (isset($_GET['localizacao']) && !empty($_GET['localizacao'])) {
                            echo htmlspecialchars($_GET['localizacao']);
                        } else {
                            echo "a sua localidade"; // Mensagem padrão se a localização não for especificada
                        }
                    ?>
                </span>
            </h2>
        </div>

        
    </section>

    <div class="passeios-grid">
        <?php
        while ($linha = $resultado->fetch_array()) {
            echo '<div class="box">';
            echo '    <div class="thum">';
            echo "        <img src='imagens/" . htmlspecialchars($linha['foto1']) . "' alt='Foto da atividade'>";
            echo '    </div>';
            echo '    <div class="dest-content">';
            echo '        <h3>' . htmlspecialchars($linha['titulo']) . '</h3>';
            echo '        <p>' . htmlspecialchars($linha['descricao']) . '</p>';
            echo '        <p>Localização: ' . htmlspecialchars($linha['localizacao']) . '</p>';
            echo '        <p>Preço: ' . htmlspecialchars($linha['preco']) . ' €</p>';

            echo '        <div class="btn-container">';
            echo '            <form action="detalhes_tours.php" method="post">';
            echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
            echo '                <input type="submit" value="Ver Detalhes" class="btn-ver-mais">';
            echo '            </form>';

            echo '            <form action="../carrinho/adicionar_ao_carrinho.php" method="post">';
            echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
            echo '                <input type="hidden" name="tipo_item" value="atividade">';
            echo '                <input type="hidden" name="return_url" value="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">';
            echo '                <input type="submit" value="Adicionar ao Carrinho" class="btn-comprar">';
            echo '            </form>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
        $stmt->close();
        ?>
    </div>

    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>

