<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/csrf.php'; // inicia a sessão para o token do carrinho
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="backend/css.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
        <a href="<?= BASE_URL ?>" class="logo">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="<?= BASE_URL ?>public/hotels/hotels.php">Hospedagem</a></li>
            <li><a href="<?= BASE_URL ?>public/voos/voos.php">Passagens</a></li>
            <li><a href="<?= BASE_URL ?>public/tours/tours.php">Passeios</a></li>
            <li><a href="<?= BASE_URL ?>public/pacotes/pacotes.php">Pacotes</a></li>
            <li><a href="<?= BASE_URL ?>public/perfil.php"><i class='bx bx-user'></i></a></li>
            <li><a href="<?= BASE_URL ?>public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
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

    <div class="container" id="flights-results">
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

            $result = null;
            if (SERPAPI_KEY === '') {
                echo '<p>A pesquisa de voos não está configurada. Defina a chave da SerpAPI em config/config.local.php.</p>';
            } else {
                $search = new GoogleSearch(SERPAPI_KEY);
                $result = $search->get_json($query);
            }

            if ($result === null) {
                // sem chave configurada: a mensagem já foi exibida
            } elseif (isset($result->best_flights) || isset($result->other_flights)) {
                $flights = $result->best_flights ?? $result->other_flights;
                // Guarda só os resultados das últimas pesquisas (várias abas continuam a funcionar)
                $_SESSION['voos_pesquisados'] = array_slice($_SESSION['voos_pesquisados'] ?? [], -100, null, true);
                echo '<div class="flight-results">';
                echo '<h2>Melhores opções</h2>';
                echo '<p>Ordenado por preço</p>';
                foreach ($flights as $flight) {
                    foreach ($flight->flights as $leg) {
                        echo '<div class="flight-item">';
                        echo '<div class="details">';
                        echo '<img src="' . htmlspecialchars($leg->airline_logo) . '" alt="Airline logo" height="50" width="50">';
                        echo '<div class="info">';
                        echo '<p>' . htmlspecialchars($leg->departure_airport->time) . ' - ' . htmlspecialchars($leg->arrival_airport->time) . '</p>';
                        echo '<p>' . htmlspecialchars($leg->duration) . ' min</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="price">';
                        echo htmlspecialchars($flight->price) . '€';
                        echo '<span> round trip</span>';
                        echo '</div>';
                        // Os dados do voo (incluindo o preço) ficam na sessão; o formulário só envia uma
                        // referência, para que o preço não possa ser alterado no navegador
                        $ref = bin2hex(random_bytes(8));
                        $_SESSION['voos_pesquisados'][$ref] = [
                            'flight_number' => (string) $leg->flight_number,
                            'airline' => (string) $leg->airline,
                            'price' => (float) $flight->price,
                            'arrival' => (string) $leg->arrival_airport->name,
                        ];
                        echo '<form action="../carrinho/adicionar_ao_carrinho.php" method="post">';
                        echo csrf_campo();
                        echo '<input type="hidden" name="voo_ref" value="' . $ref . '">';
                        echo '<input type="hidden" name="tipo_item" value="voo">';
                        echo '<button type="submit" class="home-btn">Adicionar ao Carrinho</button>';
                        echo '</form>';
                        echo '</div>';
                    }
                }
                echo '</div>';
            } else {
                echo '<p>No flights found.</p>';
            }
        }
        ?>
    </div>

    <!--Newsletter-->
    <section class="newsletter">
        <div class="news-text">
            <h2>Inscreva-se para receber nossas ofertas</h2>
            <p>Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Políticas de privacidade.</a>.</p>
        </div>
        <div class="send">
            <form action="<?= BASE_URL ?>public/backend/newsletter.php" method="post">
                <input type="email" name="email" placeholder="Insira seu e-mail aqui" required>
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
                        <li><a href="<?= BASE_URL ?>public/perfil.php">Meu Perfil</a></li>
                        <li><a href="<?= BASE_URL ?>public/reservas.php">Minhas Viagens</a></li>
                        <li><a href="<?= BASE_URL ?>public/backend/logout.php">Logout</a></li>
                    </ul>
                </div>
                <div class="list">
                    <h4>Suporte</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>public/contatos/Contatos.html">Contatos</a></li>
                        <li><a href="#">Termos & Condições</a></li>
                        <li><a href="#">Politica de privacidade</a></li>
                    </ul>
                </div>
                <div class="list">
                    <h4>Trabalhe conosco</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>public/vendedor/registro_vendedor.php">Registrar como parceiro</a></li>
                        <li><a href="<?= BASE_URL ?>public/vendedor/admin.php">Acessar ao painel de vendedor</a></li>
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