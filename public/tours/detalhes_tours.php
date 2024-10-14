<?php
include_once "../../config/valida.php";
include_once "../../config/liga_bd.php";

// Verificar se o id_artigo foi passado por POST ou GET
if (isset($_POST['id_artigo'])) {
    $id_artigo = $_POST['id_artigo'];
} elseif (isset($_GET['id'])) {
    $id_artigo = $_GET['id'];
} else {
    // Redirecionar ou exibir uma mensagem de erro se o id_artigo não estiver definido
    die("ID do artigo não fornecido.");
}

$sql = "SELECT * FROM t_artigo WHERE id=" . $id_artigo;

// a variável resultado vai guardar todos os dados de todos os artigos
$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
$linha = mysqli_fetch_array($resultado);
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

    <!-- Estilos do Slick Carousel -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

        <header>
        <a href="/SiteViagens/index.html"
            style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="/SiteViagens/index.html#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Passeios</a></li>
            <li><a href="#contact">Pacotes</a></li>
            <li><a href="public/login.php"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black;"><i class='bx bx-cart'></i></a>
            </li>
        </ul>
    </header>
    
    <style>
        /* Header com fundo branco ao rolar */
        header.scrolled {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
        }

        .navbar {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }

        main {
            display: flex;
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .carousel-container {
            width: 70%;
            padding: 20px;
        }

        img.artigo-img {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 8px;
        }

        .info-container {
            width: 40%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 22px;
            color: #333;
        }

        .rating,
        .reviews {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .price-container {
            margin: 20px 0;
            font-size: 20px;
            color: #6495ed;
            font-weight: bold;
        }

        .info-item {
            margin-bottom: 10px;
            font-size: 16px;
            color: #555;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .button-container button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .buy-button {
            background-color: #6495ed;
            /* Cor de fundo azul */
            color: white;
            /* Cor do texto branco */
            width: 100%;
            /* O botão ocupa 100% da largura do container */
            border: none;
            /* Remove a borda */
            padding: 10px 0;
            /* Adiciona preenchimento vertical ao botão */
            font-size: 16px;
            /* Define o tamanho da fonte */
            cursor: pointer;
            /* Mostra o ícone de cursor de "mão" ao passar o mouse */
            text-align: center;
            /* Centraliza o texto */
            border-radius: 5px;
            /* Bordas arredondadas */
            transition: background-color 0.3s ease;
            /* Efeito de transição na mudança de cor */
        }

        .buy-button:hover {
            background-color: #4169e1;
            /* Cor de fundo mais escura ao passar o mouse */
        }

        .back-button {
            background-color: #d1d1d1;
            color: white;
            width: 100%;
            border: none;
            padding: 10px 0;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #4169e1;
        }
    </style>
</head>

<body>

    
    
    <main>
        <div class="carousel-container">
            <div class="artigo-carousel">
                <div><img src="imagens/<?php echo $linha['foto1']; ?>" class="artigo-img" alt="Imagem 1"></div>

                <?php if ($linha['foto2'] != NULL) { ?>
                    <div><img src="imagens/<?php echo $linha['foto2']; ?>" class="artigo-img" alt="Imagem 2"></div>
                <?php } ?>

                <?php if ($linha['foto3'] != NULL) { ?>
                    <div><img src="imagens/<?php echo $linha['foto3']; ?>" class="artigo-img" alt="Imagem 3"></div>
                <?php } ?>
            </div>
        </div>

        <div class="info-container">
            <h2><?php echo $linha['titulo']; ?></h2>

            <!-- Avaliações -->
            <div class="rating">
                <span>&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                <span>4.5 / 5</span>
            </div>

            <!-- Descrição -->
            <div class="info-item"><?php echo $linha['descricao']; ?></div>

            <!-- Preço -->
            <div class="price-container"> € <?php echo number_format($linha['preco'], 2, ',', '.'); ?></div>

            <!-- Datas e Localização -->
            <div class="info-item">
                <i class='bx bx-calendar'></i>
                <strong>Data de início:</strong> <?php echo date('d/m/Y', strtotime($linha['data_inicio'])); ?>
            </div>

            <div class="info-item">
                <i class='bx bx-calendar-check'></i>
                <strong>Data de fim:</strong> <?php echo date('d/m/Y', strtotime($linha['data_fim'])); ?>
            </div>

            <div class="info-item">
                <i class='bx bx-map'></i>
                <strong>Localização:</strong> <?php echo $linha['localizacao']; ?>
            </div>

            <!-- Botões de Comprar, Voltar e Favoritos-->
            <div class="button-container">
                <form action="../backend/adicionar_favoritos.php" method="post">
                    <input type="hidden" name="id_artigo" value="<?php echo $linha['id']; ?>">
                    <input type="hidden" name="id_user" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="tipo_reserva" value="atividade">
                    <input type="submit" class="buy-button" value="Adicionar aos Favoritos">
                </form>
            </div>

            <div class="button-container">
                <form action="../carrinho/adicionar_ao_carrinho.php" id="f2" method="post">
                    <input type="hidden" name="id_artigo" value="<?php echo $linha['id']; ?>">
                    <input type="hidden" name="tipo_item" value="atividade">
                    <input type="hidden" name="return_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="submit" class="buy-button" value="Adicionar ao carrinho">
                </form>
            </div>
            <br>
            <button class="back-button" onclick="history.back()">Voltar</button>
        </div>
        </div>
    </main>
    <!--Package section-->
    <section class="package" id="package">
        <div class="title">
            <h2>Você também pode gostar...</h2>
        </div>
        <div class="package-content2">
            <div class="box">
                <div class="thum">
                    <img src="imagens/australia.jpg">

                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;">Gold Coast: aula de surf</h3>
                        <h5>Experimente a emoção de pegar uma onda com uma aula de surf na Gold Coast. </h5><br><br>

                    </div>
                    <div class="stars">
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 41,98 € por pessoa</h4>
                        </a><br>


                    </div>
                </div>
            </div>


            <div class="box">
                <div class="thum">
                    <img src="imagens/frança.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Chamonix: Dia de ski em.</h3>
                        <h5>Experimente esquiar nos Alpes franceses, no famoso resort de Chamonix.</h5><br><br>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 41,98 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="thum">
                    <img src="imagens/Antalya.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Antalya: Safári de Buggy no Deserto</h3>
                        <h5>Experimente a emoção de percorrer paisagens desérticas</h5><br><br>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 46,43 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <img src="imagens/dubai.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Dubai: Paraquedismo no deserto de Dubai</h3>
                        <h5>Contemple as dunas de Dubai enquanto despenca do céu a uma velocidade de 192 km/h.</h5>
                        <br>
                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 41,98 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--Newsletter-->
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



    <!-- Script do Slick Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.artigo-carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true
            });
        });
    </script>

</body>

</html>