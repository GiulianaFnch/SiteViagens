<?php
// Página inicial
$pagina_titulo = 'BestWay';
$cabecalho_sobre_imagem = true;
include __DIR__ . '/views/partials/pagina_inicio.php';
?>
    <!--Home section-->
    <section class="home" id="home">
        <div class="home-text">
            <h1>"Explore. Sonhe." <br> Viaje!</h1>
            <p>Atendimento exclusivo para garantir que você aproveite o melhor de cada destino.</p>
            <a href="<?= BASE_URL ?>public/login.php" class="home-btn">Vamos</a>
        </div>
    </section>

    <!--container-->
    <section class="container">
        <div class="text">
            <h2>Dê play nas suas férias <br> conosco!</h2>
        </div>

        <div class="row-items">
            <div class="container-box">
                <div class="conteiner-img">
                    <a href="<?= BASE_URL ?>public/voos/voos.php">
                        <img src="<?= BASE_URL ?>assets/images/aviaoicon.png">
                        <h4> Passagens </h4>
                        <p>25 Destinos</p>
                    </a>
                </div>
            </div>


            <div class="container-box">
                <div class="conteiner-img">
                    <a href="<?= BASE_URL ?>public/hotels/hotels.php"><img src="<?= BASE_URL ?>assets/images/hotelicon.png">
                        <h4>Hospedagens</h4>
                        <p>150 Propiedades</p>
                    </a>

                </div>

            </div>

            <div class="container-box">
                <div class="conteiner-img">
                    <a href="<?= BASE_URL ?>public/tours/tours.php"> <img src="<?= BASE_URL ?>assets/images/landicon.png">
                        <h4> Passeios</h4>
                        <p>100 Opções</p>
                    </a>

                </div>

            </div>


            <div class="container-box">
                <div class="conteiner-img">
                    <a href="<?= BASE_URL ?>public/pacotes/pacotes.php"><img src="<?= BASE_URL ?>assets/images/aviaoicon.png">
                        <img src="<?= BASE_URL ?>assets/images/hotelicon.png">
                        <img src="<?= BASE_URL ?>assets/images/landicon.png">
                        <h4>Pacotes</h4>
                        <p>30 Opções</p>
                    </a>

                </div>

            </div>

        </div>
    </section>

    <!--Package section-->
    <section class="package" id="package">
        <div class="title">
            <h2>Próximos Pacotes <br> de Viagens</h2>
        </div>

        <div class="package-content">
            <div class="box">
                <div class="thum">
                    <img src="<?= BASE_URL ?>assets/images/Rio de janeiro.jpg">

                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h4>Rio de Janeiro</h4>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <img src="<?= BASE_URL ?>assets/images/Tailandia.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h4>Bangkok</h4>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="thum">
                    <img src="<?= BASE_URL ?>assets/images/Viena.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h4>Vienna</h4>

                    </div>
                    <div class="stars">
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                        <a href="#"><i class='bx bxs-star'></i></a>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <!--destination section-->
    <section class="destination" id="destination">
        <div class="title">
            <h2>Destinos Populares</h2>
        </div>

        <div class="destination-content">
            <div class="col-content">
                <img src="<?= BASE_URL ?>assets/images/paris.jpg">
                <h5>Paris</h5>
                <p>França</p>
            </div>

            <div class="col-content">
                <img src="<?= BASE_URL ?>assets/images/roma.jpg">
                <h5>Roma</h5>
                <p>Itália</p>
            </div>

            <div class="col-content">
                <img
                    src="<?= BASE_URL ?>assets/images/boa-parte-de-nova-york-pode-ser-vista-da-estatua-da-liberdade-1665501171075_v2_1x1.jpg">
                <h5>Nova York</h5>
                <p>EUA</p>
            </div>

            <div class="col-content">
                <img src="<?= BASE_URL ?>assets/images/tokyo.jpg">
                <h5>Tokyo</h5>
                <p>Japão</p>
            </div>

            <div class="col-content">
                <img src="<?= BASE_URL ?>assets/images/sidney.jpg">
                <h5>Sydney</h5>
                <p>Austrália</p>
            </div>

            <div class="col-content">
                <img src="<?= BASE_URL ?>assets/images/londres.jpg">
                <h5>Londres</h5>
                <p>Inglaterra</p>
            </div>
        </div>
    </section>

<?php include __DIR__ . '/views/partials/pagina_fim.php'; ?>
