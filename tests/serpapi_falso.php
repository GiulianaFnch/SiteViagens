<?php
// Servidor falso da API Google Flights da SerpAPI, para os testes (tests/smoke_test.sh) não gastarem
// pesquisas nem precisarem da chave verdadeira. Responde como a SerpAPI aos casos que o site trata:
//   php -S 127.0.0.1:8081 tests/serpapi_falso.php
// e no site: SERPAPI_URL=http://127.0.0.1:8081/search.json SERPAPI_KEY=chave-de-teste
header('Content-Type: application/json');
$q = $_GET;
$erro = function ($codigo, $mensagem) {
    http_response_code($codigo);
    exit(json_encode(['error' => $mensagem]));
};

if (($q['api_key'] ?? '') !== 'chave-de-teste') {
    $erro(401, 'Invalid API key. Your API key should be here: https://serpapi.com/manage-api-key');
}
if (($q['engine'] ?? '') !== 'google_flights' || !preg_match('/^[A-Z]{3}$/', $q['departure_id'] ?? '')
    || !preg_match('/^[A-Z]{3}$/', $q['arrival_id'] ?? '')) {
    $erro(400, 'Unsupported `departure_id` or `arrival_id`.');
}
if (($q['type'] ?? '1') === '1' && empty($q['return_date'])) {
    $erro(400, '`return_date` is required if `type` is `1` (Round trip).');
}
if ($q['arrival_id'] === 'ZZZ') {
    $erro(200, "Google Flights hasn't returned any results for this query.");
}

$troco = fn($de, $para, $partida, $chegada, $numero, $minutos) => [
    'departure_airport' => ['name' => "Aeroporto $de", 'id' => $de, 'time' => $partida],
    'arrival_airport' => ['name' => "Aeroporto $para", 'id' => $para, 'time' => $chegada],
    'duration' => $minutos, 'airline' => 'TAP Air Portugal', 'flight_number' => $numero,
    'airline_logo' => 'https://www.gstatic.com/flights/airline_logos/70px/TP.png',
];
[$de, $para, $dia] = [$q['departure_id'], $q['arrival_id'], $q['outbound_date']];
if (!empty($q['departure_token'])) {
    // Voos de volta para a ida escolhida (o preço é o total da viagem)
    [$de, $para, $dia] = [$para, $de, $q['return_date']];
    $voos = [['flights' => [$troco($de, $para, "$dia 18:00", "$dia 18:20", 'TP 1027', 80)], 'total_duration' => 80, 'price' => 245]];
} else {
    $voos = [
        ['flights' => [$troco($de, $para, "$dia 07:05", "$dia 09:25", 'TP 1026', 80)], 'total_duration' => 80, 'price' => 180],
        ['flights' => [$troco($de, 'OPO', "$dia 06:00", "$dia 07:00", 'TP 1920', 60), $troco('OPO', $para, "$dia 08:30", "$dia 10:45", 'TP 1980', 75)],
         'layovers' => [['duration' => 90, 'name' => 'Aeroporto OPO', 'id' => 'OPO']], 'total_duration' => 225, 'price' => 150],
    ];
    if (($q['type'] ?? '1') === '1') {
        foreach ($voos as $i => &$v) {
            $v['departure_token'] = "token-ida-$i";
            $v['price'] += 120;
        }
        unset($v);
    }
}
echo json_encode(['best_flights' => [$voos[0]], 'other_flights' => array_slice($voos, 1)]);
