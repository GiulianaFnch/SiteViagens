<?php
// Função para obter o token de autenticação
function obterTokenAutenticacao($client_id, $client_secret) {
    $url = "https://test.api.amadeus.com/v1/security/oauth2/token";

    $data = "grant_type=client_credentials&client_id=$client_id&client_secret=$client_secret";

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/x-www-form-urlencoded"
        ),
    ));

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($http_code == 200) {
        // Converte a resposta JSON em um array associativo
        $result = json_decode($response, true);
        // Retorna o token de acesso
        return $result['access_token'] ?? null;
    } else {
        // Exibe a resposta para depuração
        echo "Erro ao obter o token de autenticação. HTTP Code: $http_code. Resposta: $response";
        return null;
    }
}

// Função para realizar a pesquisa de hotéis
function buscarHoteis($access_token, $cityCode, $checkInDate, $checkOutDate, $adults) {
    $url = "https://test.api.amadeus.com/v2/shopping/hotel-offers";
    $params = http_build_query([
        'cityCode' => $cityCode,
        'checkInDate' => $checkInDate,
        'checkOutDate' => $checkOutDate,
        'adults' => $adults
    ]);

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url . '?' . $params,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer $access_token"
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

// Substitua com suas credenciais da API Amadeus
$client_id = "YK6767KnqEhXtIZb2v4x5NLLYTRz0qFi";
$client_secret = "wEGlXk2ko3j3CXA8";  // Substitua pelo teu client_secret da Amadeus

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Parâmetros de pesquisa recebidos do formulário
    $cityCode = $_POST['cityCode'];
    $checkInDate = $_POST['checkInDate'];
    $checkOutDate = $_POST['checkOutDate'];
    $adults = $_POST['adults'];

    // Obter o token de autenticação
    $access_token = obterTokenAutenticacao($client_id, $client_secret);

    if ($access_token) {
        // Fazer a pesquisa de hotéis
        $resultado = buscarHoteis($access_token, $cityCode, $checkInDate, $checkOutDate, $adults);

        // Exibir o resultado
        echo "<h2>Resultados da Pesquisa:</h2>";
        echo "<pre>";
        print_r($resultado);
        echo "</pre>";
    } else {
        echo "Erro ao obter o token de autenticação.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Busca de Hotéis</title>
</head>
<body>

<h1>Pesquisar Hotéis</h1>

<!-- Formulário para a pesquisa de hotéis -->
<form method="POST" action="buscar_hoteis.php">
    <label for="cityCode">Código da Cidade (IATA):</label>
    <input type="text" id="cityCode" name="cityCode" required><br><br>

    <label for="checkInDate">Data de Check-in:</label>
    <input type="date" id="checkInDate" name="checkInDate" required><br><br>

    <label for="checkOutDate">Data de Check-out:</label>
    <input type="date" id="checkOutDate" name="checkOutDate" required><br><br>

    <label for="adults">Número de Adultos:</label>
    <input type="number" id="adults" name="adults" min="1" required><br><br>

    <input type="submit" value="Buscar Hotéis">
</form>

</body>
</html>