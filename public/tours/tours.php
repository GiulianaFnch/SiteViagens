<?php
include '../../config/valida.php';
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
    <section class="home-tours" id="home">
 <!--colocar o nome grande -->  
        <div class="home-text2">
            <h1>"Atrações. Atividades. " <br> Experiências</h1>
            <p>Explore novas atrações e vivências que se ajustam aos seus interesses e ao seu jeito de viajar..</p>
            <!-- Search Section -->
            <section class="search-bar">
            <!-- 
            Aqui vai ficar como o arquivo do projeto de exemplo pesq.php

            tem que escolher o que vai pesquisar
            lugar e data ou nome da atividade e data, por exemplo

            e ele vai mandar para um outro arquivo que vai fazer a pesquisa e mostrar os resultados 
            pode ser pesquisa_tours.php por exemplo, que vai ser php do pesq2.php do projeto de exemplo
            -->
                <form action="#z" method="GET">
                    <input type="text" placeholder="O que deseja fazer?" name="destination" required>
                    <input type="date" placeholder="Selecione as datas " name="dates" required>
                    <button type="submit">Search</button>
                </form>
            </section>
        </div>
    </section>

    <!--container-->
    <section class="container">
        <div class="text">
            <h2>Atividades mais procuradas. </h2>

    </section>

    <!--Package section-->
    <section class="package" id="package">
        <div class="title">
            <h2>Aventuras de tirar o fôlego</h2>
        </div>
        <div class="package-content">
            <div class="box">
                <div class="thum">
                    <img src="imagens/australia.jpg">

                </div>

                <div class="dest-content">
                    <div class="stars">
                        <h3 style="color: gray;">Gold Coast: aula de surf</h3>
                        <h5>Experimente a emoção de pegar uma onda com uma aula de surf na Gold Coast. </h5>

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
                        <h3 style="color: gray;">Dia de ski em Chamonix</h3>
                        <h5>Experimente esquiar nos Alpes franceses, no famoso resort de Chamonix.</h5>

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
                    <img src="imagens/dubai.jpg">

                </div>

                <div class="dest-content">
                    <div class="location">
                        <h3 style="color: gray;">Paraquedismo no deserto de Dubai</h3>
                        <h5>Contemple as dunas de Dubai enquanto despenca do céu a uma velocidade de 192 km/h.</h5>

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

    <!--destination section-->
    <section class="destination" id="destination">
        <div class="title">
            <h2>Tours Incr</h2>
        </div>

        <div class="destination-content">
            <div class="col-content">
                <img src="../../assets/images/paris.jpg">
                <h5>Paris</h5>
                <p>França</p>
            </div>

            <div class="col-content">
                <img src="../../assets/images/roma.jpg">
                <h5>Roma</h5>
                <p>Itália</p>
            </div>

            <div class="col-content">
                <img
                    src="../../assets/images/boa-parte-de-nova-york-pode-ser-vista-da-estatua-da-liberdade-1665501171075_v2_1x1.jpg">
                <h5>Nova York</h5>
                <p>EUA</p>
            </div>

            <div class="col-content">
                <img src="../../assets/images/tokyo.jpg">
                <h5>Tokyo</h5>
                <p>Japão</p>
            </div>

            <div class="col-content">
                <img src="../../assets/images/sidney.jpg">
                <h5>Sydney</h5>
                <p>Austrália</p>
            </div>

            <div class="col-content">
                <img src="../../assets/images/londres.jpg">
                <h5>Londres</h5>
                <p>Inglaterra</p>
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
                        <li><a href="#">Como criador de conteúdo</a></li>

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

</body>

</html>
