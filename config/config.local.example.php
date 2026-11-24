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
    // Chave da SerpAPI (https://serpapi.com/manage-api-key) usada na pesquisa de voos.
    // Este ficheiro (config.local.php) está no .gitignore: a chave fica só no seu computador/servidor.
    'serpapi_key' => '',
    // true mostra os detalhes dos erros na página. Use só no seu computador, nunca num servidor público.
    'debug'       => false,
    // Protocolo, servidor e porta do site, para os links dos e-mails (confirmar e-mail, recuperar senha).
    // O caminho (ex.: /SiteViagens/) vem de base_url. XAMPP: 'http://localhost'  |  Docker: 'http://localhost:8080'
    'url_site'    => 'http://localhost',

    // E-mails. 'ficheiro' (por omissão) grava cada e-mail em storage/emails/ para abrir no navegador;
    // 'smtp' envia mesmo. Exemplo com Gmail: crie uma "palavra-passe de aplicação" na conta Google
    // e use smtp.gmail.com, porta 587, segurança 'tls'.
    'email_modo'      => 'ficheiro',
    'email_remetente' => 'nao-responder@bestway.local',
    'smtp_host'       => '',
    'smtp_porta'      => 587,
    'smtp_seguranca'  => 'tls',
    'smtp_utilizador' => '',
    'smtp_senha'      => '',

    // Chave secreta para assinar links (ex.: cancelar as ofertas). Gere uma com: php -r "echo bin2hex(random_bytes(32));"
    // Vazia: é gerada automaticamente em storage/chave_app.txt.
    'chave_app'       => '',
];
