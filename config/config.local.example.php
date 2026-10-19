<?php
// Copie este ficheiro para config/config.local.php e ajuste os valores.
// O config.local.php não é enviado para o Git.
return [
    'db_host'     => 'localhost',
    'db_user'     => 'root',
    'db_pass'     => '',
    'db_name'     => 'site_viagens',
    // Caminho do site no servidor. Deixe null para detetar automaticamente.
    'base_url'    => null,
    // Chave da SerpAPI (https://serpapi.com) usada na pesquisa de voos.
    'serpapi_key' => '',
];
