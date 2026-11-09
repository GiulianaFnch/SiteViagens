<?php
// Contas de utilizador: validação do registo e das palavras-passe, e os links enviados por
// e-mail para confirmar o endereço e para recuperar a palavra-passe (tabela t_tokens).
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/email.php';
require_once __DIR__ . '/csrf.php'; // inicia a sessão (avisos entre páginas)

const SENHA_MIN = 8;
const NICK_MAX = 20;  // tamanho de t_user.nick
const EMAIL_MAX = 50; // tamanho de t_user.email

const TOKEN_VALIDADE = [
    'confirmar_email' => 48 * 3600, // 48 horas
    'recuperar_senha' => 3600,      // 1 hora
];
const TOKEN_MAX_POR_HORA = 3; // e-mails de cada tipo por conta, para não servir para enviar spam

// Mensagem de erro da palavra-passe, ou null se for válida
function senha_erro($senha, $confirmacao)
{
    if (!is_string($senha) || mb_strlen($senha) < SENHA_MIN) {
        return 'A palavra-passe tem de ter pelo menos ' . SENHA_MIN . ' caracteres.';
    }
    if ($senha !== $confirmacao) {
        return 'As palavras-passe não coincidem.';
    }
    return null;
}

// Mensagem de erro do nick e do e-mail de um registo novo, ou null se forem válidos
function registo_erro($ligacao, $nick, $email)
{
    if (!is_string($nick) || trim($nick) === '' || mb_strlen($nick) > NICK_MAX) {
        return 'O nick tem de ter entre 1 e ' . NICK_MAX . ' caracteres.';
    }
    if (!is_string($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX) {
        return 'Indique um e-mail válido (até ' . EMAIL_MAX . ' caracteres).';
    }
    $stmt = $ligacao->prepare("SELECT 1 FROM t_user WHERE nick = ?");
    $stmt->bind_param("s", $nick);
    $stmt->execute();
    if ($stmt->get_result()->fetch_row()) {
        return 'Esse nick já está a ser usado. Escolha outro.';
    }
    return null;
}

/**
 * Cria um link de uso único para o utilizador e devolve o token (64 caracteres hexadecimais),
 * ou null se já foram pedidos TOKEN_MAX_POR_HORA na última hora.
 * Na base de dados fica só o hash do token.
 */
function token_criar($ligacao, $id_user, $tipo)
{
    $ligacao->query("DELETE FROM t_tokens WHERE data_criacao < NOW() - INTERVAL 1 DAY AND expira < NOW()");

    $stmt = $ligacao->prepare("SELECT COUNT(*) FROM t_tokens
        WHERE id_user = ? AND tipo = ? AND data_criacao > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("is", $id_user, $tipo);
    $stmt->execute();
    if ($stmt->get_result()->fetch_row()[0] >= TOKEN_MAX_POR_HORA) {
        return null;
    }

    $token = bin2hex(random_bytes(32));
    $hash = hash('sha256', $token);
    $validade = TOKEN_VALIDADE[$tipo];
    $stmt = $ligacao->prepare("INSERT INTO t_tokens (id_user, tipo, token_hash, expira)
        VALUES (?, ?, ?, NOW() + INTERVAL ? SECOND)");
    $stmt->bind_param("issi", $id_user, $tipo, $hash, $validade);
    $stmt->execute();
    return $token;
}

// id do utilizador a quem pertence um token válido (não expirado nem usado), ou null
function token_utilizador($ligacao, $token, $tipo)
{
    if (!is_string($token) || !preg_match('/^[0-9a-f]{64}$/', $token)) {
        return null;
    }
    $hash = hash('sha256', $token);
    $stmt = $ligacao->prepare("SELECT id_user FROM t_tokens WHERE token_hash = ? AND tipo = ? AND expira > NOW()");
    $stmt->bind_param("ss", $hash, $tipo);
    $stmt->execute();
    $linha = $stmt->get_result()->fetch_row();
    return $linha ? (int) $linha[0] : null;
}

// Invalida todos os links de um tipo do utilizador (ficam na tabela para contar no limite por hora)
function tokens_invalidar($ligacao, $id_user, $tipo)
{
    $stmt = $ligacao->prepare("UPDATE t_tokens SET expira = NOW() WHERE id_user = ? AND tipo = ? AND expira > NOW()");
    $stmt->bind_param("is", $id_user, $tipo);
    $stmt->execute();
}

// Envia o link de confirmação para o e-mail atual do utilizador. Devolve false se não foi possível.
function enviar_confirmacao_email($ligacao, $id_user)
{
    $stmt = $ligacao->prepare("SELECT nome, email FROM t_user WHERE id = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $utilizador = $stmt->get_result()->fetch_assoc();
    if (!$utilizador || !($token = token_criar($ligacao, $id_user, 'confirmar_email'))) {
        return false;
    }
    $link = url_absoluta('public/confirmar_email.php?token=' . $token);
    return enviar_email($utilizador['email'], 'Confirme o seu e-mail — BestWay', email_modelo(
        'Olá, ' . $utilizador['nome'] . '!',
        ['Obrigado por se registar no BestWay. Para ativar a sua conta, confirme o seu e-mail.',
         'O link é válido durante 48 horas.'],
        'Confirmar e-mail',
        $link
    ));
}

// Envia um link de recuperação a cada conta com este e-mail (podem existir várias).
// Não diz a quem chama se o e-mail existe: a resposta ao utilizador é sempre a mesma.
function enviar_recuperacao_senha($ligacao, $email)
{
    $stmt = $ligacao->prepare("SELECT id, nick, nome FROM t_user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    foreach ($stmt->get_result()->fetch_all(MYSQLI_ASSOC) as $utilizador) {
        $token = token_criar($ligacao, (int) $utilizador['id'], 'recuperar_senha');
        if (!$token) {
            continue;
        }
        $link = url_absoluta('public/redefinir_senha.php?token=' . $token);
        enviar_email($email, 'Recuperar a palavra-passe — BestWay', email_modelo(
            'Olá, ' . $utilizador['nome'] . '!',
            ['Recebemos um pedido para redefinir a palavra-passe da conta "' . $utilizador['nick'] . '".',
             'O link é válido durante 1 hora e só pode ser usado uma vez.'],
            'Escolher nova palavra-passe',
            $link
        ));
    }
}

// Guarda um aviso para a próxima página de conta (views/partials/conta_inicio.php) e redireciona
function conta_aviso($texto, $tipo, $destino)
{
    $_SESSION['conta_aviso'] = ['texto' => $texto, 'tipo' => $tipo];
    header('Location: ' . BASE_URL . $destino);
    exit;
}

// Muda o e-mail de um utilizador (página do perfil). Um e-mail novo fica por confirmar até o
// utilizador abrir o link enviado para ele. Devolve uma mensagem de erro, ou null.
function conta_mudar_email($ligacao, $id_user, $email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > EMAIL_MAX) {
        return 'Indique um e-mail válido (até ' . EMAIL_MAX . ' caracteres).';
    }
    $stmt = $ligacao->prepare("UPDATE t_user SET email = ?, email_confirmado = 0 WHERE id = ? AND email <> ?");
    $stmt->bind_param("sis", $email, $id_user, $email);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        tokens_invalidar($ligacao, $id_user, 'confirmar_email'); // os links enviados para o e-mail antigo deixam de servir
        enviar_confirmacao_email($ligacao, $id_user);
    }
    return null;
}
