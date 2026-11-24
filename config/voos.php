<?php
// Pesquisa de voos com a API Google Flights da SerpAPI (https://serpapi.com/google-flights-api).
// A chave fica em config/config.local.php ou na variável de ambiente SERPAPI_KEY — nunca no código.
//
// Ida e volta funciona em dois passos, como no Google Flights: a primeira pesquisa devolve os voos de
// ida (com o preço total da viagem) e um departure_token; com esse token pedem-se os voos de volta.
require_once __DIR__ . '/config.php';

class VoosErro extends Exception
{
}

// Código IATA do aeroporto em maiúsculas (ex.: "lis" → "LIS"), ou null se não for um código válido
function voos_codigo($texto)
{
    $codigo = strtoupper(trim((string) $texto));
    return preg_match('/^[A-Z]{3}$/', $codigo) ? $codigo : null;
}

/**
 * Valida o formulário de pesquisa. Devolve [pesquisa, erro]; erro é null se estiver tudo bem.
 * pesquisa: origem, destino, ida, volta (null = só ida).
 */
function voos_ler_pesquisa($get)
{
    $pesquisa = [
        'origem' => voos_codigo($get['origem'] ?? ''),
        'destino' => voos_codigo($get['destino'] ?? ''),
        'ida' => $get['ida'] ?? '',
        'volta' => ($get['volta'] ?? '') === '' ? null : $get['volta'],
    ];
    if (!$pesquisa['origem'] || !$pesquisa['destino']) {
        return [$pesquisa, 'Indique os aeroportos pelo código de 3 letras (ex.: LIS para Lisboa, OPO para o Porto, MAD para Madrid).'];
    }
    if ($pesquisa['origem'] === $pesquisa['destino']) {
        return [$pesquisa, 'A partida e a chegada têm de ser aeroportos diferentes.'];
    }
    $hoje = date('Y-m-d');
    $ida = DateTime::createFromFormat('!Y-m-d', $pesquisa['ida']);
    if (!$ida || $ida->format('Y-m-d') !== $pesquisa['ida'] || $pesquisa['ida'] < $hoje) {
        return [$pesquisa, 'Escolha uma data de ida a partir de hoje.'];
    }
    if ($pesquisa['volta'] !== null) {
        $volta = DateTime::createFromFormat('!Y-m-d', $pesquisa['volta']);
        if (!$volta || $volta->format('Y-m-d') !== $pesquisa['volta'] || $pesquisa['volta'] < $pesquisa['ida']) {
            return [$pesquisa, 'A data de volta não pode ser antes da ida.'];
        }
    }
    return [$pesquisa, null];
}

/**
 * Pede os voos à SerpAPI. Com $departure_token devolve os voos de volta para a ida escolhida.
 * Devolve a lista de itinerários (ver voos_itinerarios), vazia se não houver voos.
 * Lança VoosErro com uma mensagem para mostrar ao utilizador; os detalhes vão para o log.
 */
function voos_pesquisar($pesquisa, $departure_token = null)
{
    global $config;
    if ($config['serpapi_key'] === '') {
        throw new VoosErro('A pesquisa de voos ainda não está configurada (falta a chave da SerpAPI).');
    }
    $parametros = [
        'engine' => 'google_flights',
        'departure_id' => $pesquisa['origem'],
        'arrival_id' => $pesquisa['destino'],
        'outbound_date' => $pesquisa['ida'],
        'type' => $pesquisa['volta'] === null ? 2 : 1, // 1 = ida e volta, 2 = só ida
        'currency' => 'EUR',
        'hl' => 'pt',
        'gl' => 'pt',
        'api_key' => $config['serpapi_key'],
    ];
    if ($pesquisa['volta'] !== null) {
        $parametros['return_date'] = $pesquisa['volta'];
    }
    if ($departure_token !== null) {
        $parametros['departure_token'] = $departure_token;
    }

    $pedido = curl_init($config['serpapi_url'] . '?' . http_build_query($parametros));
    curl_setopt_array($pedido, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_TIMEOUT => 40,
        CURLOPT_USERAGENT => 'BestWay',
        // A verificação do certificado fica ligada (é o que impede que alguém no caminho leia a chave)
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $corpo = curl_exec($pedido);
    $estado = curl_getinfo($pedido, CURLINFO_HTTP_CODE);
    $erro_rede = curl_error($pedido);
    curl_close($pedido);

    // Nunca registar o endereço do pedido: leva a chave
    if ($corpo === false) {
        error_log("BestWay: SerpAPI sem resposta: $erro_rede");
        throw new VoosErro('Não foi possível contactar o serviço de voos. Tente novamente daqui a pouco.');
    }
    $resposta = json_decode($corpo, true);
    $erro = is_array($resposta) ? ($resposta['error'] ?? null) : 'resposta inválida';
    if ($erro !== null || $estado !== 200) {
        if (is_string($erro) && stripos($erro, "hasn't returned any results") !== false) {
            return [];
        }
        error_log("BestWay: SerpAPI respondeu $estado: " . (is_string($erro) ? $erro : 'sem mensagem'));
        if ($estado === 401 || (is_string($erro) && stripos($erro, 'api key') !== false)) {
            throw new VoosErro('A pesquisa de voos não está disponível: a chave da SerpAPI é inválida.');
        }
        if ($estado === 429 || (is_string($erro) && stripos($erro, 'run out of searches') !== false)) {
            throw new VoosErro('A pesquisa de voos atingiu o limite de pesquisas do plano. Tente mais tarde.');
        }
        throw new VoosErro('Não foi possível pesquisar voos com esses dados. Confirme os códigos dos aeroportos e as datas.');
    }
    return voos_itinerarios($resposta);
}

/**
 * Converte a resposta da SerpAPI numa lista de itinerários, do mais barato para o mais caro. Cada um tem:
 * preco, duracao (minutos), escalas, companhias, numeros (ex.: "TP 1026 + TP 204"), logo,
 * partida e chegada (codigo, nome, hora) e token (departure_token, só nas idas de ida e volta).
 */
function voos_itinerarios($resposta)
{
    $itinerarios = [];
    foreach (array_merge($resposta['best_flights'] ?? [], $resposta['other_flights'] ?? []) as $opcao) {
        $trocos = $opcao['flights'] ?? [];
        if (!isset($opcao['price']) || !$trocos) {
            continue; // sem preço não se pode vender
        }
        $primeiro = $trocos[0];
        $ultimo = $trocos[count($trocos) - 1];
        $logo = $opcao['airline_logo'] ?? $primeiro['airline_logo'] ?? '';
        $itinerarios[] = [
            'preco' => (float) $opcao['price'],
            'duracao' => (int) ($opcao['total_duration'] ?? array_sum(array_column($trocos, 'duration'))),
            'escalas' => count($trocos) - 1,
            'companhias' => implode(' + ', array_unique(array_column($trocos, 'airline'))),
            'numeros' => implode(' + ', array_column($trocos, 'flight_number')),
            'logo' => preg_match('#^https://#', $logo) ? $logo : '',
            'partida' => [
                'codigo' => $primeiro['departure_airport']['id'] ?? '',
                'nome' => $primeiro['departure_airport']['name'] ?? '',
                'hora' => $primeiro['departure_airport']['time'] ?? '',
            ],
            'chegada' => [
                'codigo' => $ultimo['arrival_airport']['id'] ?? '',
                'nome' => $ultimo['arrival_airport']['name'] ?? '',
                'hora' => $ultimo['arrival_airport']['time'] ?? '',
            ],
            'token' => $opcao['departure_token'] ?? null,
        ];
    }
    usort($itinerarios, fn($a, $b) => $a['preco'] <=> $b['preco']);
    return $itinerarios;
}

// 155 → "2 h 35 min"
function voos_formatar_duracao($minutos)
{
    return intdiv($minutos, 60) . ' h' . ($minutos % 60 ? ' ' . ($minutos % 60) . ' min' : '');
}

// "2026-12-10 07:05" → "07:05"
function voos_hora($data_hora)
{
    return substr((string) $data_hora, -5);
}
