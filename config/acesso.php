<?php
// Controlo de acesso: tipos de utilizador e a página de "acesso negado" usada por
// config/valida.php, public/vendedor/valida_vendedor.php e adm/valida_adm.php.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/csrf.php'; // inicia a sessão

// Valores de t_user.tipo_user
const TIPO_CLIENTE = 0;
const TIPO_BLOQUEADO = 1;
const TIPO_VENDEDOR = 2;
const TIPO_VENDEDOR_PENDENTE = 3; // registou-se como vendedor e aguarda aprovação do administrador
const TIPO_ADMIN = 4;

const TIPOS_UTILIZADOR = [
    TIPO_CLIENTE => 'Cliente',
    TIPO_BLOQUEADO => 'Bloqueado',
    TIPO_VENDEDOR => 'Vendedor',
    TIPO_VENDEDOR_PENDENTE => 'Vendedor por aprovar',
    TIPO_ADMIN => 'Administrador',
];

function tipo_utilizador_nome($tipo)
{
    return TIPOS_UTILIZADOR[(int) $tipo] ?? 'Desconhecido';
}

// tipo_user do utilizador, ou null se já não existir
function tipo_utilizador($ligacao, $id_user)
{
    $stmt = $ligacao->prepare("SELECT tipo_user FROM t_user WHERE id = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $linha = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $linha ? (int) $linha['tipo_user'] : null;
}

/**
 * Mostra a página de acesso negado e termina o pedido.
 * O redirecionamento é enviado antes de qualquer HTML, para funcionar mesmo
 * quando a página que incluiu o validador já tinha começado a escrever.
 */
function acesso_negado($titulo, $mensagem, $texto_botao, $destino)
{
    $url = BASE_URL . $destino;
    if (!headers_sent()) {
        http_response_code(403);
        header('Refresh: 3; url=' . $url);
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>BestWay - <?= htmlspecialchars($titulo) ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { margin: 0; min-height: 100vh; display: flex; flex-direction: column; justify-content: center;
                   align-items: center; font-family: 'Poppins', sans-serif; background-color: #f9f9f9; }
            .message-container { text-align: center; padding: 20px; background-color: white; border-radius: 8px;
                   box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); margin: 20px; max-width: 400px; }
            h1 { font-size: 32px; margin-bottom: 16px; font-weight: 600; color: #333; }
            h2 { font-size: 20px; margin-bottom: 20px; font-weight: 400; color: #555; }
            a.botao { display: inline-block; padding: 10px 20px; background-color: #6495ed; color: white;
                   border-radius: 5px; text-decoration: none; transition: background-color 0.3s; }
            a.botao:hover { background-color: #0056b3; }
        </style>
    </head>
    <body>
        <div class="message-container">
            <h1><?= htmlspecialchars($titulo) ?></h1>
            <h2><?= htmlspecialchars($mensagem) ?></h2>
            <a class="botao" href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($texto_botao) ?></a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

function acesso_negado_sem_login()
{
    acesso_negado('Erro no Acesso', 'Você precisa estar logado para acessar esta página.', 'Fazer login', 'public/login.php');
}

function acesso_negado_sem_permissao()
{
    acesso_negado('Acesso Bloqueado', 'Você não tem permissão para acessar esta página.', 'Voltar ao Início', 'index.html');
}
