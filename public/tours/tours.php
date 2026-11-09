<?php
require_once __DIR__ . '/../../config/config.php';
include '../../config/liga_bd.php';
/*include '../../config/valida.php';*/
?>

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
    <?php $cabecalho_sobre_imagem = true; include __DIR__ . '/../../views/partials/header.php'; ?>

    <!--Home section-->
    <section class="home-tours" id="home">
        <!--colocar o nome grande -->
        <div class="home-text2">
            <h1>"Atrações. Atividades." <br> Experiências!</h1>
            <p style="color: aliceblue;">Explore novas atrações e vivências que se ajustam aos seus interesses e ao seu
                jeito de viajar..</p>
            <!-- Search Section botão pesquisar  -->

            <section class="search-bar">
                <form action="pesquisa_tours.php" method="get">
                    <input type="text" placeholder="Para onde?" name="localizacao" required>
                    <input type="date" placeholder="Selecione as datas " name="data" required>
                    <button style="color: grey;" type="submit">Pesquisar</button>
                </form>
            </section>

        </div>
    </section>

    <!--container-->
    <section class="destinos" id="destinos">
        <div class="title">
            <h2><a href="listar_tours.php" class="link-titulopasseios">Passeios mais procurados</a></h2>

        </div>

        <div class="linha">
            <div class="destino">
                <img src="imagens/coliseu.jpg" alt="Tour Coliseu">
                <div class="descricao">
                    <h3>Visite o icônico Coliseu de Roma, um marco da história.</h3>
                </div>
            </div>

            <div class="destino">
                <img src="imagens/riosena.jpg" alt="Passeio de barco pelo Rio Sena">
                <div class="descricao">
                    <h3>Desfrute de um passeio romântico pelo Rio Sena.</h3>
                </div>
            </div>

            <div class="destino">
                <img src="imagens/cristoredentor.jpg" alt="Cristo Redentor">
                <div class="descricao">
                    <h3>Admire o Cristo Redentor, uma das maravilhas do mundo moderno.</h3>
                </div>
            </div>
        </div> <!-- Fim da primeira linha -->

        <div class="linha">
            <div class="destino">
                <img src="imagens/piramides.jpg" alt="Pirâmides de Gizé">
                <div class="descricao">
                    <h3>Descubra as enigmáticas Pirâmides de Gizé.</h3>
                </div>
            </div>

            <div class="destino">
                <img src="imagens/centralpark.jpg" alt="Tour pelo Central Park">
                <div class="descricao">
                    <h3>Caminhe pelo famoso Central Park em Nova York </h3>
                </div>
            </div>

            <div class="destino">
                <img src="imagens/santorini.jpg" alt="Cruzeiro pelas ilhas vulcânicas">
                <div class="descricao">
                    <h3>Cruzeiro pelas ilhas vulcânicas de Santorini</h3>
                </div>
            </div>
        </div> <!-- Fim da segunda linha -->
        <a href="listar_tours.php" class="botao-vertodostours">Ver todos</a>

    </section>


    </section>



    <!--Package section-->
    <section class="package" id="package">
        <div class="title">
            <h2>Aventuras de tirar o fôlego</h2>
        </div>

        <div class="package-content2">
            <div class="box">
                <div class="thum">
                    <a href="detalhes_tours.php?id=29"><img src="imagens/australia.jpg" alt="Gold Coast: aula de surf"></a>
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
                    <a href="detalhes_tours.php?id=30"><img src="imagens/frança.jpg" alt="Chamonix: Dia de ski"></a>
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
                    <a href="detalhes_tours.php?id=31"><img src="imagens/antalya.jpg" alt="Antalya: Safári de Buggy no Deserto"></a>
                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Antalya: Safári de Buggy no Deserto</h3>
                        <h5>Experimente a emoção de percorrer paisagens desérticas</h5><br>

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
                    <a href="detalhes_tours.php?id=32"><img src="imagens/dubai.jpg" alt="Dubai: Paraquedismo no deserto de Dubai"></a>
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
                        <a href="#">
                            <h4> A partir de 41,98 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <a href="detalhes_tours.php?id=34"><img src="imagens/saara.jpg" alt="Saara: Passeio de quadriciclo pelo deserto"></a>
                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;"> Saara: Passeio de quadriciclo pelo deserto.</h3>
                        <h5>Explore as paisagens deslumbrantes de Erg Chebbi em uma emocionante aventura de quadriciclo
                        </h5>

                    </div>
                    <div class="stars">
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 266,00 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <a href="detalhes_tours.php?id=35"><img src="imagens/Geysir.jpg" alt="Geysir: snowmobile na geleira Langjökull"></a>
                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;">Geysir: snowmobile na geleira Langjökull</h3>
                        <h5>Embarque em um passeio de snowmobiling em Geysir
                        </h5><br><br>

                    </div>
                    <div class="stars">
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 208,00 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <a href="detalhes_tours.php?id=36"><img src="imagens/tenerife.jpg" alt="Tenerife: Voo duplo de parapente"></a>
                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;">Tenerife: Voo duplo de parapente</h3>
                        <h5>Experimente o incrível e emocionante mundo do parapente com uma equipe experiente e
                            profissional.
                        </h5>

                    </div>
                    <div class="stars">
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 110,00 € por pessoa</h4>
                        </a><br>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <a href="detalhes_tours.php?id=37"><img src="imagens/puntacana.jpg" alt="Punta Cana: Jet Ski a melhor experiência de adrenalina"></a>
                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;">Punta Cana: Jet Ski a melhor experiência de adrenalina</h3>
                        <h5>levará a lugares deslumbrantes como Cayo Bocaina, Cayo Esteban, a praia El Mono e a piscina
                            natural de Miches.
                        </h5>

                    </div>
                    <div class="stars">
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <i class='bx bxs-star'></i></a>
                        <a href="#">
                            <h4> A partir de 266,00 € por pessoa</h4>
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
            <form action="<?= BASE_URL ?>public/backend/newsletter.php" method="post">
                <input type="email" name="email" placeholder="Insira seu e-mail aqui" required>
                <input type="submit" value="Quero recebê-las!">
            </form>
        </div>
    </section>

    <?php include __DIR__ . '/../../views/partials/footer.php'; ?>



</body>

</html>