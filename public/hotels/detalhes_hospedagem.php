<?php
include_once "../../config/valida.php";
include_once "../../config/liga_bd.php";

$id_hospedagem = $_POST['id_hospedagem'];
$sql = "SELECT * FROM t_hospedagem WHERE id=" . $id_hospedagem;

// A variável resultado vai guardar todos os dados da hospedagem
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
            width: 50%;
        }

        .carousel-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .details-container {
            padding: 20px;
            width: 50%;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 22px;
        }

        .price-container {
            margin: 20px 0;
            font-size: 20px;
            color: #333;
        }

        .description {
            font-size: 16px;
            color: #555;
        }

        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .buy-button {
            background-color: #6495ed;
            color: white;
            width: 100%;
            border: none;
            padding: 10px 0;
            cursor: pointer;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .buy-button:hover {
            background-color: #4169e1;
        }
    </style>
</head>

<body>
    <header>
        <a href="/SiteViagens/index.html"
            style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="/SiteViagens/index.html#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Passeios</a></li>
            <li><a href="#contact">Pacotes</a></li>
        </ul>
    </header>

    <main>
        <div class="carousel-container">
            <div class="artigo-carousel">
                <div><img src="../../public/hotels/imagens/<?php echo htmlspecialchars($linha['foto1']); ?>"
                        alt="Foto 1"></div>
                <?php if (!empty($linha['foto2'])): ?>
                    <div><img src="../../public/hotels/imagens/<?php echo htmlspecialchars($linha['foto2']); ?>"
                            alt="Foto 2"></div>
                <?php endif; ?>
                <?php if (!empty($linha['foto3'])): ?>
                    <div><img src="../../public/hotels/imagens/<?php echo htmlspecialchars($linha['foto3']); ?>"
                            alt="Foto 3"></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="details-container">
            <h2><?php echo htmlspecialchars($linha['nome']); ?></h2>
            <div class="price-container">A partir de <?php echo htmlspecialchars($linha['preco_diaria']); ?> € por noite
            </div>
            <div class="description"><?php echo htmlspecialchars($linha['descricao']); ?></div>
            <div class="description">Classificação: <?php echo htmlspecialchars($linha['classificacao']); ?> estrelas
            </div>
            <div class="description">Quartos disponíveis: <?php echo htmlspecialchars($linha['n_quartos']); ?></div>
            <div class="button-container">
                <form action="../carrinho/adicionar_ao_carrinho.php" method="post">
                    <input type="hidden" name="id_hospedagem" value="<?php echo htmlspecialchars($linha['id']); ?>">

                    <input type="hidden" name="return_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="hidden" name="tipo_item" value="hospedagem">
                    <input type="submit" value="Adicionar ao carrinho" class="buy-button">
                </form>
            </div>
        </div>
    </main>

    <section class="newsletter">
        <div class="news-text">
            <h2>Inscreva-se para receber nossas ofertas</h2>
            <p>Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Politica de
                    privacidade.</a>.</p>
        </div>
        <div class="send">
            <form>
                <input type="email" placeholder="Insira seu e-mail aqui" required>
                <input type="submit" value="Quero recebê-las!">
            </form>
        </div>
    </section>

    <!--footer-->
    <section id="contact">
        <div class="footer">
            <div class="main">
                <div class="list">
                    <h4>Minha Conta</h4>
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