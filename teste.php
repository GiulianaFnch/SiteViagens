<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesquisa de Voos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .flight-results {
            margin-top: 20px;
        }
        .flight-results div {
            background-color: #e4e4e4;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Pesquisa de Voos</h1>

    <!-- Formulário de Pesquisa -->
    <form method="GET" action="">
        <label for="departure">Aeroporto de Partida:</label>
        <input type="text" id="departure" name="departure" required placeholder="Código do Aeroporto">
        <br><br>
        <label for="arrival">Aeroporto de Chegada:</label>
        <input type="text" id="arrival" name="arrival" required placeholder="Código do Aeroporto">
        <br><br>
        <button type="submit">Pesquisar</button>
    </form>

    <div class="flight-results">
        <?php
        if (isset($_GET['departure']) && isset($_GET['arrival'])) {
            // API URL e chave
            $api_url = 'http://api.aviationstack.com/v1/flights';
            $api_key = '1a7b9060cb45a5b6805a545f73f2f2d2';

            // Parâmetros da pesquisa
            $departure = $_GET['departure'];
            $arrival = $_GET['arrival'];

            // Configuração do cURL para chamar a API
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $api_url . "?access_key=" . $api_key . "&dep_iata=" . $departure . "&arr_iata=" . $arrival,
                CURLOPT_RETURNTRANSFER => true,
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            // Decodifica a resposta JSON
            $flights = json_decode($response, true);

            // Verifica se houve resultados
            if (!empty($flights['data'])) {
                foreach ($flights['data'] as $flight) {
                    echo "<div>";
                    echo "<strong>Voo: </strong>" . $flight['flight']['iata'] . "<br>";
                    echo "<strong>Partida: </strong>" . $flight['departure']['airport'] . " (" . $flight['departure']['scheduled'] . ")<br>";
                    echo "<strong>Chegada: </strong>" . $flight['arrival']['airport'] . " (" . $flight['arrival']['scheduled'] . ")<br>";
                    echo "</div>";
                }
            } else {
                echo "<p>Não foram encontrados voos para essa rota.</p>";
            }
        }
        ?>
    </div>
</div>

</body>
</html>
