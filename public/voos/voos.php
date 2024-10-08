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
            <li><a href="#contact">Pacotes</a></li>
            <li><a href="/SiteViagens/public/perfil.php"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <!--Home section-->
    <section class="home-voos" id="home">
        <div class="home-text">
            <h1 style="color: black">Voos</h1>

            <!-- Barra de pesquisa de hotéis -->
            <form class="hotels-search-bar" action="pesquisa_hospedagem.php" method="get">
                <div class="hotels-input-container">
                    <input type="text" placeholder="Para onde vai?" name="localizacao" />
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="data_inicio">
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="data_fim">
                </div>
                <div class="hotels-input-container">
                    <input type="number" placeholder="Nº quartos" name="quartos" />
                </div>
                <button class="hotels-search-button" type="submit">Pesquisar</button>
            </form>
        </div>
    </section>





    <!--Newsletter-->
    <section class="newsletter">
        <div class="news-text">
            <h2>Inscreva-se para receber nossas ofertas</h2>
            <p>
                Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Políticas
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
                        <li><a href="/SiteViagens/public/perfil.php">Meu Perfil</a></li>
                        <li><a href="/SiteViagens/public/reservas.php">Minhas Viagens</a></li>
                        <li><a href="/SiteViagens/public/backend/logout.php">Logout</a></li>
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
                        <li><a href="/SiteViagens/public/vendedor/registro_vendedor.php">Registrar como parceiro</a></li>
                        <li><a href="/SiteViagens/public/vendedor/admin.php">Acessar ao painel de vendedor</a></li>
                    </ul>
                </div>

                <div class="list">
                    <h4>Redes Sociais</h4>
                    <div class="social">
                        <ul>
                        <li><a href="#"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="#"><i class='bx bxl-instagram'></i></a></li>

                        <a href="#"></a>
                    </ul>
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