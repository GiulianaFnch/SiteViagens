<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewpoert" content="width=device-width, inicial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="backend/css.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }

        .container {
            width: 90%;
            margin: 20px auto;
        }

        .header,
        .filters,
        .flight-results,
        .other-flights {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header select,
        .header input {
            margin-right: 10px;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filters button {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
        }

        .filters button:hover {
            background-color: #f1f1f1;
        }

        .flight-results h2,
        .other-flights h2 {
            margin-top: 0;
        }

        .flight-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .flight-item .details {
            display: flex;
            align-items: center;
        }

        .flight-item .details img {
            width: 50px;
            height: 50px;
            margin-right: 10px;
        }

        .flight-item .details .info {
            margin-right: 20px;
        }

        .flight-item .details .info p {
            margin: 5px 0;
        }

        .flight-item .details .info .co2 {
            color: green;
        }

        .flight-item .price {
            font-size: 18px;
            font-weight: bold;
        }

        .flight-item .price span {
            color: #888;
            font-size: 14px;
        }

        .flight-item .price .high {
            color: red;
        }

        .flight-item .price .low {
            color: green;
        }

        .other-flights .flight-item {
            background-color: #f1f1f1;
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
            <li><a href="/SiteViagens/public/voos/voos.php">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Passeios</a></li>
            <li><a href="/SiteViagens/public/pacotes/pacotes.php">Pacotes</a></li>
            <li><a href="/SiteViagens/public/perfil.php"><i class='bx bx-user'></i></a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <!--Home section-->
    <section class="home-voos" id="home">
        <div class="home-text">
            <h1>As melhores ofertas e os menores preços.</h1>

            <!-- Barra de pesquisa de hotéis -->
            <form class="hotels-search-bar" action="voos.php#flights-results" method="get">
                <div class="hotels-input-container">
                    <input type="text" placeholder="Partida" name="departure_id" required />
                </div>
                <div class="hotels-input-container">
                    <input type="text" placeholder="Chegada" name="arrival_id" required />
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="outbound_date" required>
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="return_date">
                </div>
                <button class="hotels-search-button" type="submit">Pesquisar</button>
            </form>
        </div>
    </section>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['departure_id']) && isset($_GET['arrival_id']) && isset($_GET['outbound_date'])) {
        require 'backend/google-search-results.php';
        require 'backend/restclient.php';

        $query = [
            "engine" => "google_flights",
            "hl" => "pt",
            "gl" => "pt",
            "departure_id" => $_GET['departure_id'],
            "arrival_id" => $_GET['arrival_id'],
            "outbound_date" => $_GET['outbound_date'],
            "return_date" => $_GET['return_date'] ?? '',
            "currency" => "EUR"
        ];

        $search = new GoogleSearch('f38710f1433e7f8a9771e521339b6be72239435c9d95f5b2249ffd7c812d9819');
        $result = $search->get_json($query);

        if (isset($result->best_flights) || isset($result->other_flights)) {
            $flights = $result->best_flights ?? $result->other_flights;
            echo '<section class="flights-results">';
            echo '<h2>Melhores opções</h2>';
            echo '<p>Ordenado por preço</p>';
            echo '<div class="flight-item">';
            foreach ($flights as $flight) {
                foreach ($flight->flights as $leg) {
                    echo '<div class="details">';
                    echo '<h3>' . htmlspecialchars($leg->airline) . '</h3>';
                    echo '<p>Flight Number: ' . htmlspecialchars($leg->flight_number) . '</p>';
                    echo '<p>Departure: ' . htmlspecialchars($leg->departure_airport->name) . ' (' . htmlspecialchars($leg->departure_airport->id) . ') at ' . htmlspecialchars($leg->departure_airport->time) . '</p>';
                    echo '<p>Arrival: ' . htmlspecialchars($leg->arrival_airport->name) . ' (' . htmlspecialchars($leg->arrival_airport->id) . ') at ' . htmlspecialchars($leg->arrival_airport->time) . '</p>';
                    echo '<p>Duration: ' . htmlspecialchars($leg->duration) . ' minutes</p>';
                    echo '<p>Airplane: ' . htmlspecialchars($leg->airplane) . '</p>';
                    echo '<p>Travel Class: ' . htmlspecialchars($leg->travel_class) . '</p>';
                    echo '</div>';
                    echo '<div class="price">';
                    echo '<p>Price: ' . htmlspecialchars($flight->price) . '€</p>';
                    echo '</div>';
                    echo '<form action="../carrinho/adicionar_ao_carrinho.php" method="post">';
                    echo '<input type="hidden" name="flight_number" value="' . htmlspecialchars($leg->flight_number) . '">';
                    echo '<input type="hidden" name="airline" value="' . htmlspecialchars($leg->airline) . '">';
                    echo '<input type="hidden" name="price" value="' . htmlspecialchars($flight->price) . '">';
                    echo '<input type="hidden" name="arrival" value="' . htmlspecialchars($leg->arrival_airport->name) . '">';
                    echo '<input type="hidden" name="tipo_item" value="voo">';
                    echo '<input type="hidden" name="return_url" value="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">';
                    echo '<button type="submit" class="btn-comprar">Adicionar ao Carrinho</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }
            echo '</div>';
            echo '<hr>';
            echo '</section>';
            echo '<hr>';
        } else {
            echo '<p>No flights found.</p>';
        }
    }
    ?>

    <hr>




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
                        <li><a href="/SiteViagens/public/vendedor/registro_vendedor.php">Registrar como parceiro</a>
                        </li>
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