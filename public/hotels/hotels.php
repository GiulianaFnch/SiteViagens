
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
        <a href="/SiteViagens/" class="logo">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="/SiteViagens/public/hotels/hotels.php">Hospedagem</a></li>
            <li><a href="/SiteViagens/public/voos/voos.php">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Passeios</a></li>
            <li><a href="/SiteViagens/public/pacotes/pacotes.php">Pacotes</a></li>
            <li><a href="public/perfil.php"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <!--Home section-->
<section class="home-hotels" id="home">
    <div class="home-text">
        <h1>Seu próximo cantinho para relaxar está a um clique!</h1>
        
        <!-- Barra de pesquisa de hotéis -->
        <form class="hotels-search-bar" action="pesquisa_hospedagem.php" method="get">
            <div class="hotels-input-container">
                <input type="text" placeholder="Para onde vai?" name="localizacao" />
            </div>
            <div class="hotels-input-container">
                <input type="date"
                name="data_inicio">
            </div>
            <div class="hotels-input-container">
                <input type="date"
                name="data_fim">
            </div>
            <div class="hotels-input-container">
                <input type="number" placeholder="Nº quartos" name="quartos"/>
            </div>
            <button class="hotels-search-button" type="submit">Pesquisar</button>
        </form>
    </div>
</section>

    <!--container-->
    <section class="container-hotel"><br>
        <div class="text">
            <h2>Pesquisar por tipo </h2>
        </div>

        <div class="row-items">
        <div class="container-box-hotel">
            <div class="conteiner-img">
                <a href="listar_hospedagem.php?tipo_hospedagem=apartamento">
                    <img src="imagens/apartamento.jpg" style="width: 300px; height: auto;">
                </a>
            </div>
            <h4>Apartamentos</h4>
        </div>
        <div class="container-box-hotel">
            <div class="conteiner-img">
                <a href="listar_hospedagem.php?tipo_hospedagem=hotel">
                    <img src="imagens/hotel.jpg" style="width: 300px; height: auto;">
                </a>
            </div>
            <h4>Hotéis</h4>
        </div>
        <div class="container-box-hotel">
            <div class="conteiner-img">
                <a href="listar_hospedagem.php?tipo_hospedagem=hostel">
                    <img src="imagens/hostel.jpg" style="width: 300px; height: auto;">
                </a>
            </div>
            <h4>Hostel</h4>
        </div>
        <div class="container-box-hotel">
            <div class="conteiner-img">
                <a href="listar_hospedagem.php?tipo_hospedagem=castelo">
                    <img src="imagens/castelo.jpg" style="width: 300px; height: auto;">
                </a>
            </div>
            <h4>Castelo</h4>
        </div>

            <div class="container-box-hotel">
                <div class="conteiner-img">
                <a href="listar_hospedagem.php">
                    <img src="imagens/cabana.jpg" style="width: 300px; height: auto;">
                </a>
                     
                </div>
                <h4>Cabanas</h4>
               
            </div>
            <div class="container-box-hotel">
                <div class="conteiner-img">
                    <img src="imagens/resort.jpg" style="width: 300px; height: auto;">
                     
                </div>
                <h4>Resorts</h4>
               
            </div>



        </div>
    </section>

   <!--Package section-->
<section class="package" id="package">
    <div class="title">
        <h2>Escolha entre os <br> melhores</h2>
    </div>

    <div class="package-content">
       <!-- Package 1 -->
<div class="box">
            <div class="thum">
                <img src="imagens/paris.jpg" alt="Crowne Plaza Paris République">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3 >Crowne Plaza Paris République</h3>
                </div>
                <p> Paris, França </p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                </div>
            </div>
        </div>

        <!-- Package 2 -->
<div class="box">
            <div class="thum">
                <img src="imagens/novayork.jpg" alt="The Ritz-Carlton, Central Park">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3 >The Ritz-Carlton, Central Park</h3>
                </div>
                <p> New York, EUA </p>
                
                <div class="stars">
                    
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                </div>
            </div>
        </div>
 <!-- Package 3 -->
<div class="box">
            <div class="thum">
                <img src="imagens/porto.jpg" alt="Torel Avantgarde">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3 >Torel Avantgarde</h3>
                </div>
                <p> Porto, Portugal </p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                </div>
            </div>
        </div>

<!-- Package 4 -->
<div class="box">
            <div class="thum">
                <img src="imagens/seul.jpg" alt="Signiel Seoul">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3>Signiel Seoul</h3>
                </div>
                <p>  Seul, Coreia do Sul  </p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    
                </div>
            </div>
        </div>

<!-- Package 5 -->
<div class="box">
            <div class="thum">
                <img src="imagens/londres.jpg" alt="7 Four Seasons">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3>7 Four Seasons</h3>
                </div>
                <p> Londres, Reino Unido </p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                   
                </div>
            </div>
        </div>

<!-- Package 6 -->
<div class="box">
            <div class="thum">
                <img src="imagens/Marrakech.jpg" alt="Pestana CR7 ">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3>Pestana CR7</h3>
                </div>
                <p>  Marrakech, Marrocos</p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                </div>
            </div>
        </div>
<!-- Package 7 -->
<div class="box">
            <div class="thum">
                <img src="imagens/mexico.jpg" alt="Majestic Elegance Costa Mujeres - All Inclusive">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3>Majestic Elegance Costa Mujeres</h3>
                </div>
                <p> Cancun, Mexico </p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    
                </div>
            </div>
        </div>
<!-- Package 8 -->
<div class="box">
            <div class="thum">
                <img src="imagens/suica.jpg" alt="Hotel Walhalla">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i><br>
                        <h3>Hotel Walhalla</h3>
                </div>
                <p> Gallen, Suíça      </p><br>
                
                <div class="stars">
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                </div>
            </div>
        </div>
       <!-- Package 9 -->
<div class="box">
            <div class="thum">
                <img src="imagens/maldivas.jpg" alt="Kandima Maldives">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3>Kandima Maldives</h3>
                </div>
                <p> Kudahuvadhoo, Maldivas </p>
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                   
                </div>
            </div>
        </div>
       <!-- Package 10 -->
<div class="box">
            <div class="thum">
                <img src="imagens/turquia.jpg" alt="Pera Palace Hotel">
            </div>
            <div class="dest-content">
            <div class="calendar">
                    <a href="#"><i class='bx bxs-calendar-star bx-tada bx-rotate-90' ></i>
                        <h3>Pera Palace Hotel</h3>
                        <p> Istambu, Turquia </p>
                </div>
                
                
                <div class="stars">
                    
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
                    <a href="#"><i class='bx bxs-star'></i></a>
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
Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Politica de privacidade.</a>.</p>
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
