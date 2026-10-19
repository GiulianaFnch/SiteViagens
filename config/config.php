<?php
// Configuração central do BestWay.
// Os valores podem ser sobrescritos por variáveis de ambiente ou por
// config/config.local.php (não versionado — copie config.local.example.php).

if (defined('BESTWAY_CONFIG_LOADED')) {
    return;
}
define('BESTWAY_CONFIG_LOADED', true);

$config = [
    'db_host'     => getenv('DB_HOST') ?: 'localhost',
    'db_user'     => getenv('DB_USER') ?: 'root',
    'db_pass'     => getenv('DB_PASS') ?: '',
    'db_name'     => getenv('DB_NAME') ?: 'site_viagens',
    'base_url'    => getenv('BASE_URL') ?: null,
    'serpapi_key' => getenv('SERPAPI_KEY') ?: '',
];

if (file_exists(__DIR__ . '/config.local.php')) {
    $config = array_merge($config, require __DIR__ . '/config.local.php');
}

// Caminho base do site (ex.: "/SiteViagens/"). Se não estiver configurado,
// é calculado a partir da pasta do projeto dentro do DOCUMENT_ROOT.
if (empty($config['base_url'])) {
    $raiz_projeto = realpath(dirname(__DIR__));
    $doc_root = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;
    if ($doc_root && $raiz_projeto && strpos($raiz_projeto, $doc_root) === 0) {
        $config['base_url'] = str_replace('\\', '/', substr($raiz_projeto, strlen($doc_root))) . '/';
    } else {
        $config['base_url'] = '/SiteViagens/';
    }
}
$config['base_url'] = '/' . trim($config['base_url'], '/') . '/';
$config['base_url'] = preg_replace('#^/+#', '/', $config['base_url']);

define('BASE_URL', $config['base_url']);
define('SERPAPI_KEY', $config['serpapi_key']);
