<?php
/*include '../../config/valida.php';*/
include '../../config/liga_bd.php';
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
    </style>
</head>

<body>
    <!--header-->
    <header>
        <a href="/SiteViagens/" class="logo">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="/SiteViagens/public/hotels/hotels.php">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Passeios</a></li>
            <li><a href="#contact">Pacotes</a></li>
            <li><a href="public/perfil.php"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>
    <!--container-->
    <section class="listar-tours" id="home">
        <div class="home-text2">
            <h1>Atividades <br> Mais <br> Procuradas.</h1>
            <p style="color: aliceblue;">"Encontre destinos e experiências que combinam com você!"</p>
        </div>
    </section>

    <!-- Container de Título -->
    <section class="container">
        <div class="text">
            <center>
                <h1>Atividades mais procuradas.</h1>
            </center>
        </div>
    </section>

    <!-- Formulário de seleção de categorias -->
    <form action="" method="post">
        Categoria:
        <select name="categoria" id="categoria" onchange="this.form.submit();">
            <?php
            // Conecte-se ao banco de dados e busque as categorias
            $sql = "SELECT * FROM t_categoria";
            $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

            // Adiciona a opção "Todos" com valor 0
            echo "<option value='0'>Todos</option>";

            // Preenche as opções do select com as categorias vindas do banco de dados
            while ($linha = mysqli_fetch_array($resultado)) {
                // Verifica se a categoria selecionada é a atual
                if (isset($_POST['categoria']) && $_POST['categoria'] == $linha['id'])
                    echo "<option value='" . $linha['id'] . "' selected>" . $linha['categoria'] . "</option>";
                else
                    echo "<option value='" . $linha['id'] . "'>" . $linha['categoria'] . "</option>";
            }
            ?>
        </select>
    </form>

    <!-- Lista de passeios -->
    <div class="passeios-grid">
        <?php
        // Verifica se uma categoria foi selecionada no formulário
        $categoria = isset($_POST['categoria']) ? (int) $_POST['categoria'] : 0;

        // Define o SQL de acordo com a categoria selecionada
        if ($categoria == 0) {
            // Se "Todos" for selecionado, exibe todos os artigos não vendidos
            $sql = "SELECT * FROM t_artigo WHERE vendido = 0";
        } else {
            // Caso contrário, exibe apenas os artigos da categoria selecionada
            $sql = "SELECT * FROM t_artigo WHERE vendido = 0 AND cat = " . $categoria;
        }

        // Executa a consulta
        $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

        // Verifica se existem passeios disponíveis
        if (mysqli_num_rows($resultado) > 0) {
            // Itera sobre os resultados e exibe cada passeio
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
                    echo '            <a href="#"><i class="bx bxs-star"></i></a>';
                    echo '            <a href="#"><i class="bx bxs-star"></i></a>';
                    echo '            <a href="#"><i class="bx bxs-star"></i></a>';
                    echo '            <a href="#"><i class="bx bxs-star"></i></a>';
                    echo "            <h4>A partir de " . htmlspecialchars($linha['preco']) . " € por pessoa</h4>";
                    echo '        </div>';
                    echo '        <div class="btn-container">';

                    // Botão "Comprar" que redireciona para a página carrinho.php
                    echo '            <form action="carrinho.php" method="post">';
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
    <script type="text/javascript" src="assets/js/script.js"></script>

    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

</body>

</html>