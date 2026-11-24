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
    // Endereço da API de voos (só se muda nos testes, para um servidor falso: tests/serpapi_falso.php)
    'serpapi_url' => getenv('SERPAPI_URL') ?: 'https://serpapi.com/search.json',
    // true mostra os detalhes dos erros na página (só para desenvolvimento)
    'debug'       => filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN),
    // Protocolo, servidor e porta do site (ex.: http://localhost, https://bestway.pt), usados com BASE_URL nos links dos e-mails.
    // Nunca é deduzido do pedido: um cabeçalho Host falso poria links para outro site nos e-mails.
    'url_site'    => getenv('SITE_URL') ?: 'http://localhost',
    // E-mails: 'ficheiro' grava cada e-mail em storage/emails/ (desenvolvimento), 'smtp' envia mesmo
    'email_modo'      => getenv('EMAIL_MODO') ?: 'ficheiro',
    'email_remetente' => getenv('EMAIL_REMETENTE') ?: 'nao-responder@bestway.local',
    'smtp_host'       => getenv('SMTP_HOST') ?: '',
    'smtp_porta'      => (int) (getenv('SMTP_PORTA') ?: 587),
    'smtp_seguranca'  => getenv('SMTP_SEGURANCA') ?: 'tls', // 'tls' (STARTTLS, porta 587), 'ssl' (porta 465) ou 'nenhuma'
    'smtp_utilizador' => getenv('SMTP_UTILIZADOR') ?: '',
    'smtp_senha'      => getenv('SMTP_SENHA') ?: '',
    // Chave secreta para assinar links (ex.: cancelar as ofertas). Vazia: é gerada em storage/chave_app.txt
    'chave_app'       => getenv('APP_KEY') ?: '',
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
define('APP_DEBUG', (bool) $config['debug']);

// Endereço absoluto de uma página do site (para links em e-mails), ex.: url_absoluta('public/login.php')
function url_absoluta($caminho)
{
    global $config;
    return rtrim($config['url_site'], '/') . BASE_URL . ltrim($caminho, '/');
}

// Erros não tratados (ex.: falha numa consulta à BD): os detalhes vão para o log do servidor
// e o visitante vê só uma mensagem genérica, sem SQL, nomes de tabelas ou caminhos de ficheiros.
if (!APP_DEBUG) {
    set_exception_handler(function ($e) {
        error_log('BestWay: ' . $e);
        if (!headers_sent()) {
            http_response_code(500);
        }
        echo '<p style="font-family: sans-serif; text-align: center; margin: 40px;">'
            . 'Ocorreu um erro inesperado. Tente novamente mais tarde.</p>';
    });
}

// Chave secreta da aplicação: a da configuração ou, se não houver, uma gerada na primeira utilização
function chave_app()
{
    global $config;
    if ($config['chave_app'] === '') {
        $ficheiro = __DIR__ . '/../storage/chave_app.txt';
        if (!is_file($ficheiro)) {
            if (!is_dir(dirname($ficheiro))) {
                mkdir(dirname($ficheiro), 0775, true);
            }
            file_put_contents($ficheiro, bin2hex(random_bytes(32)), LOCK_EX);
            chmod($ficheiro, 0600);
        }
        $config['chave_app'] = trim(file_get_contents($ficheiro));
    }
    return $config['chave_app'];
}
