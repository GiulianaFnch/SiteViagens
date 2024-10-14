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
    <br><br><br><br><br>
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
            <br>
            <div class="description">Classificação: <?php echo htmlspecialchars($linha['classificacao']); ?> estrelas
            </div>
            <br>
            <div class="description">Quartos disponíveis: <?php echo htmlspecialchars($linha['n_quartos']); ?></div>
            <br>
            <div class="description">Localização: <?php echo htmlspecialchars($linha['localizacao']); ?></div>
            <br>
            <div class="description">Horário Check-in: <?php echo htmlspecialchars($linha['horario_checkin']); ?></div>
            <br>
            <div class="description">Horário Check-out: <?php echo htmlspecialchars($linha['horario_checkout']); ?>
            </div>
        </div>
    </main>
    <main>

        <!-- Novo contêiner para data e número de quartos -->
        <div class="booking-container">
            <form action="../carrinho/adicionar_ao_carrinho.php" method="post" id="reservaForm">
                <input type="hidden" name="id_artigo" value="<?php echo htmlspecialchars($linha['id']); ?>">
                <input type="hidden" name="tipo_item" value="hospedagem">
                <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                <input type="hidden" name="total" id="total" value="">
                <input type="hidden" name="dias_quartos" id="dias_quartos" value="">

                <div class="form-group-container">
                    <div class="form-group">
                        <label for="data-checkin">Check-in:</label>
                        <input type="date" id="data-checkin" name="data_checkin" required>
                    </div>

                    <div class="form-group">
                        <label for="data-checkout">Check-out:</label>
                        <input type="date" id="data-checkout" name="data_checkout" required>
                    </div>

                    <div class="form-group">
                        <label for="n_quartos">Quartos:</label>
                        <select id="n_quartos" name="n_quartos" required>
                            <?php
                            for ($i = 1; $i <= $linha['n_quartos']; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <input type="submit" value="Adicionar ao carrinho" class="buy-button">
            </form>

            <div class="button-container">
                <form action="../backend/adicionar_favoritos.php" method="post">
                    <input type="hidden" name="id_artigo" value="<?php echo $linha['id']; ?>">
                    <input type="hidden" name="id_user" value="<?php echo $_SESSION['id']; ?>">
                    <input type="hidden" name="tipo_reserva" value="hospedagem">
                    <input type="submit" class="buy-button" value="Adicionar aos Favoritos">
                </form>
            </div>

        </div>
    </main>



    <!-- Scripts do Slick Carousel -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
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

        document.getElementById('reservaForm').addEventListener('submit', function (e) {
            const checkin = new Date(document.getElementById('data-checkin').value);
            const checkout = new Date(document.getElementById('data-checkout').value);
            const dailyRate = <?php echo $linha['preco_diaria']; ?>;
            const numQuartos = document.getElementById('n_quartos').value;

            // Calcular a diferença de dias
            const timeDifference = checkout.getTime() - checkin.getTime();
            const days = timeDifference / (1000 * 3600 * 24); // Converter milissegundos em dias

            if (days <= 0) {
                alert("A data de término deve ser posterior à data de início.");
                e.preventDefault();
                return;
            }

            const total = dailyRate * days * numQuartos;
            const dias_quartos = days * numQuartos;

            // Definir o valor do campo oculto 'total'
            document.getElementById('total').value = total;

            // Definir o valor do campo oculto 'dias_quartos'
            document.getElementById('dias_quartos').value = dias_quartos;

            // Confirmação para o usuário
            const confirmMessage = `Você está prestes a adicionar ao carrinho:\n${days} dias de hospedagem, com ${numQuartos} quarto(s).\nTotal: €${total.toFixed(2)}.`;
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    </script>
    <!--Package section-->
    <section class="package" id="package">
        <div class="title">
            <h2>Você também pode gostar...</h2>
        </div>
        <div class="package-content2">
            <div class="box">
                <div class="thum">
                    <img src="imagens/montefiore.jpg">

                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;">Montefiore Hotel</h3>
                        <h5>Jerusalém | Israel</h5>
                        <h5>Experimente a emoção de se hospedar na Terra Santa </h5><br><br>

                    </div>
                    <div class="stars">
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> Diária a partir de 81,98€</h4>
                        </a><br>


                    </div>
                </div>
            </div>


            <div class="box">
                <div class="thum">
                    <img src="imagens/star_in.jpeg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Star In Porto</h3>
                        <h5>Porto | Portugal</h5>
                        <h5>Experimente o melhor da Cidade Invicta</h5><br><br>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> Diária a partir de 61,90€</h4>
                        </a><br>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="thum">
                    <img src="imagens/hilton.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Hilton</h3>
                        <h5>Rio de Janeiro | Brasil</h5>
                        <h5>Experimente uma bela vista mar na Cidade Maravilhosa</h5><br><br>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> Diária a partir de 270€</h4>
                        </a><br>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <img src="imagens/hiad.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Riad Hadda</h3>
                        <h5>Marrakech | Marrocos</h5>
                        <h5>Experimente um autêntico Riad no coração da Cidade Vermelha</h5>
                        <br>
                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> Diária a partir de 21,80€</h4>
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
</body>

</html>