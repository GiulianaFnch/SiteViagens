<?php
require_once __DIR__ . '/../../config/config.php';
/*include '../../config/valida.php';*/
include '../../config/liga_bd.php';
include '../../config/avaliacoes.php';
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

    <style>
        /* Header com fundo branco e letras pretas ao rolar */
        header.scrolled {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Links e logo no header ficam pretos quando a classe 'scrolled' está ativa */
        header.scrolled a,
        header.scrolled .logo {
            color: black;
        }

        header a,
        header .logo {
            color: white;
            /* Cor branca para o estado normal (sem scroll) */
        }

        body {
            font-family: Arial, sans-serif;
        }

        .passeios-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* 3 passeios por linha */
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
            color: black;
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
            color: #ffcf00;
        }

        /* Estilo dos botões */
        .btn-container {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .btn-container form,
        .btn-container button {
            width: 100%;
        }

        .btn-comprar,
        .btn-ver-comentarios {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            text-align: center;
        }

        .btn-comprar {
            background-color: #6495ed;
            color: white;
        }

        .btn-comprar:hover {
            background-color: #218838;
        }

        .btn-ver-comentarios {
            background-color: #6495ed;
            color: white;
        }

        .btn-ver-comentarios:hover {
            background-color: #0056b3;
        }

        @media (max-width: 768px) {
            .passeios-grid {
                grid-template-columns: repeat(2, 1fr);
                /* 2 por linha em telas menores */
            }
        }

        @media (max-width: 480px) {
            .passeios-grid {
                grid-template-columns: 1fr;
                /* 1 por linha em telas bem pequenas */
            }
        }
        form {
    font-family: Arial, sans-serif;
    font-size: 16px;
    margin: 20px 0;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

select {
    width: 100%;
    max-width: 300px;
    padding: 10px;
    font-size: 16px;
    border-radius: 6px;
    border: 1px solid #ccc;
    background-color: #f9f9f9;
    color: #333;
    outline: none;
    transition: border-color 0.3s ease;
}

select:hover {
    border-color: #888;
}

select:focus {
    border-color: #5a9;
    background-color: #fff;
}

option {
    padding: 10px;
    background-color: #fff;
    color: #333;
}

/* Adicionando estilo ao próprio formulário */
form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Responsividade */
@media (max-width: 768px) {
    select {
        max-width: 100%;
    }
}

    </style>
</head>

<body>
    <!--header-->
    <header>
        <a href="<?= BASE_URL ?>" class="logo">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="<?= BASE_URL ?>public/hotels/hotels.php">Hospedagem</a></li>
            <li><a href="<?= BASE_URL ?>public/voos/voos.php">Passagens</a></li>
            <li><a href="<?= BASE_URL ?>public/tours/tours.php">Passeios</a></li>
            <li><a href="<?= BASE_URL ?>public/pacotes/pacotes.php">Pacotes</a></li>
            <li><a href="<?= BASE_URL ?>public/perfil.php"><i class='bx bx-user'></i></a></li>
            <li><a href="<?= BASE_URL ?>public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>
    <!--container-->
    <section class="listar-tours" id="home">
        <div class="home-text2">
            <h1>Atividades <br> Mais <br> Procuradas.</h1>
            <p style="color: aliceblue;">"Encontre destinos e experiências que combinam com você!"</p>
        </div>
    </section>
<br><br>
    <!-- Formulário de seleção de categorias -->
    <center><form action="" method="post"><h1>
        Encontre seu destino por categorias:</h1>
       <center> <select name="categoria" id="categoria" onchange="this.form.submit();">
            <?php
            // Conecte-se ao banco de dados e busque as categorias
            $sql = "SELECT * FROM t_categoria";
            $resultado = mysqli_query($ligacao, $sql);

            // Adiciona a opção "Todos" com valor 0
            echo "<option value='0'>Todos</option>";

            // Preenche as opções do select com as categorias vindas do banco de dados
            while ($linha = mysqli_fetch_array($resultado)) {
                // Verifica se a categoria selecionada é a atual
                if (isset($_POST['categoria']) && $_POST['categoria'] == $linha['id'])
                    echo "<option value='" . (int) $linha['id'] . "' selected>" . htmlspecialchars($linha['categoria']) . "</option>";
                else
                    echo "<option value='" . (int) $linha['id'] . "'>" . htmlspecialchars($linha['categoria']) . "</option>";
            }
            ?>
        </select></center>
    </form></center>
<br><br>
    <!-- Lista de passeios -->
    <div class="passeios-grid">
        <?php
        // Verifica se uma categoria foi selecionada no formulário
        $categoria = isset($_POST['categoria']) ? (int) $_POST['categoria'] : 0;

        // Define o SQL de acordo com a categoria selecionada
        // Artigos não vendidos (com o nick e email do vendedor numa só consulta);
        // a categoria 0 ("Todos") mostra todas
        $stmt = $ligacao->prepare("SELECT a.*, u.nick, u.email, av.media_avaliacoes, COALESCE(av.total_avaliacoes, 0) AS total_avaliacoes
                                   FROM t_artigo a
                                   JOIN t_user u ON u.id = a.id_user
                                   LEFT JOIN (SELECT item_id, AVG(nota) AS media_avaliacoes, COUNT(*) AS total_avaliacoes
                                              FROM t_avaliacoes WHERE tipo_item = 'atividade' GROUP BY item_id) av ON av.item_id = a.id
                                   WHERE a.vendido = 0 AND (? = 0 OR a.cat = ?)");
        $stmt->bind_param("ii", $categoria, $categoria);

        // Executa a consulta
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verifica se existem passeios disponíveis
        if (mysqli_num_rows($resultado) > 0) {
            // Itera sobre os resultados e exibe cada passeio
            while ($linha = mysqli_fetch_array($resultado)) {
                $linha_user = $linha; // nick e email do vendedor já vêm na consulta

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
                    echo '            <span style="color: #f5a623;">' . avaliacao_texto($linha['media_avaliacoes'] === null ? null : (float) $linha['media_avaliacoes'], (int) $linha['total_avaliacoes']) . '</span>';
                    echo "            <h4>A partir de " . htmlspecialchars($linha['preco']) . " € por pessoa</h4>";
                    echo '        </div>';
                    echo '        <div class="btn-container">';

                    // Botão "Comprar": abre os detalhes, onde se escolhe a data e o nº de pessoas
                    echo '            <form action="detalhes_tours.php" method="post">';
                    echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
                    echo '                <input type="submit" value="Comprar" class="btn-comprar">';
                    echo '            </form>';

                    // Botão "Ver mais" que redireciona para a página detalhes_tour.php
                    echo '            <form action="detalhes_tours.php" method="post">';
                    echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
                    echo '                <input type="submit" value="Ver Mais" class="btn-ver-comentarios">';
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

    </div>

    </div>
    <section class="newsletter">
        <div class="news-text">
            <h2>Inscreva-se para receber nossas ofertas</h2>
            <p>
                Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Politica
                    de privacidade.</a>.</p>
        </div>


        <div class="send">
            <form>
                <input type text="email" placeholder="Insira seu e-mail aqui" required>
                <input type="submit" value="Quero recebê-las!">
            </form>
        </div>
    </section>

    <!--footer-->
    <section id="contact">
        <div class="footer">
            <div class="main">
                <div class="list">

                    <h4> Minha Conta</h4>
                    <ul>
                        <li><a href="#">Minhas Viagens</a></li>
                        <li><a href="public/perfil.php">Meu Perfil</a></li>
                        <li><a href="#">Deletar minha conta</a></li>

                    </ul>
                </div>

                <div class="list">
                    <h4>Suporte</h4>
                    <ul>
                        <li><a href="#">Contatos</a></li>
                        <li><a href="#">Termos & Condições</a></li>
                        <li><a href="#">Politica de privacidade</a></li>

                    </ul>
                </div>

                <div class="list">
                    <h4>Trabalhe conosco</h4>
                    <ul>
                        <li><a href="public/vendedor/registro_vendedor.php">Como Parceiro Fornecedor</a></li>
                        <li><a href="public/vendedor/admin.php">Acessar ao painel de vendedor</a></li>
                    </ul>
                </div>

                <div class="list">
                    <h4>Connect</h4>
                    <div class="social">
                        <a href="#"><i class='bx bxl-facebook'></i></a>
                        <a href="#"><i class='bx bxl-instagram'></i></a>

                        <a href="#"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="end-text">
            <p>© 2024 BestWay. Todos os direitos reservados.</p>
        </div>
    </section>

    <!--link to js-->
    <script type="text/javascript" src="../../assets/js/script.js"></script>

    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

</body>

</html>