<?php
// Envio de e-mails (confirmação de conta, recuperação de palavra-passe).
// O modo vem de config/config.php ('email_modo'):
//   'ficheiro' — grava cada e-mail em storage/emails/ como .html (para desenvolvimento, sem servidor de e-mail)
//   'smtp'     — envia por SMTP (STARTTLS na porta 587, SSL na 465), com autenticação
require_once __DIR__ . '/config.php';

/**
 * Envia um e-mail em HTML. Devolve true se foi enviado (ou gravado, no modo 'ficheiro').
 * Os erros vão para o log do servidor; quem chama mostra uma mensagem genérica.
 */
function enviar_email($para, $assunto, $html)
{
    global $config;
    if (!filter_var($para, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    try {
        if ($config['email_modo'] === 'smtp') {
            email_smtp($para, $assunto, $html);
        } else {
            email_ficheiro($para, $assunto, $html);
        }
        return true;
    } catch (Exception $e) {
        error_log('BestWay: e-mail para ' . $para . ' não enviado: ' . $e->getMessage());
        return false;
    }
}

// Página HTML simples com o aspeto do site, para o corpo dos e-mails
function email_modelo($titulo, $paragrafos, $texto_botao = null, $link = null)
{
    $html = '<div style="font-family: Arial, sans-serif; max-width: 520px; margin: auto; color: #221314;">'
        . '<h2 style="color: #6495ed;">BestWay</h2>'
        . '<h3>' . htmlspecialchars($titulo) . '</h3>';
    foreach ($paragrafos as $p) {
        $html .= '<p>' . htmlspecialchars($p) . '</p>';
    }
    if ($link) {
        $html .= '<p><a href="' . htmlspecialchars($link) . '" style="display: inline-block; padding: 10px 20px; '
            . 'background: #6495ed; color: #fff; border-radius: 5px; text-decoration: none;">'
            . htmlspecialchars($texto_botao) . '</a></p>'
            . '<p style="font-size: 12px; color: #777;">Se o botão não funcionar, copie este endereço: '
            . htmlspecialchars($link) . '</p>';
    }
    return $html . '<p style="font-size: 12px; color: #777;">Se não foi você, ignore este e-mail.</p></div>';
}

function email_ficheiro($para, $assunto, $html)
{
    $pasta = __DIR__ . '/../storage/emails';
    if (!is_dir($pasta) && !mkdir($pasta, 0775, true)) {
        throw new Exception("não foi possível criar $pasta");
    }
    $nome = date('Ymd-His') . '-' . bin2hex(random_bytes(3)) . '.html';
    $cabecalho = '<p style="font: 12px monospace; background: #eee; padding: 8px;">Para: ' . htmlspecialchars($para)
        . '<br>Assunto: ' . htmlspecialchars($assunto) . '</p>';
    if (file_put_contents("$pasta/$nome", '<meta charset="utf-8">' . $cabecalho . $html) === false) {
        throw new Exception("não foi possível gravar $pasta/$nome");
    }
}

function email_smtp($para, $assunto, $html)
{
    global $config;
    $host = $config['smtp_host'];
    $seguranca = $config['smtp_seguranca'];
    $endereco = ($seguranca === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . (int) $config['smtp_porta'];
    $ligacao = @stream_socket_client($endereco, $erro_n, $erro, 15);
    if (!$ligacao) {
        throw new Exception("ligação a $endereco falhou: $erro");
    }
    stream_set_timeout($ligacao, 15);

    // $rotulo substitui o comando na mensagem de erro (para nunca registar credenciais nem o e-mail inteiro)
    $smtp = function ($comando, $esperado, $rotulo = null) use ($ligacao) {
        if ($comando !== null) {
            fwrite($ligacao, $comando . "\r\n");
        }
        // Lê a resposta (pode ter várias linhas "250-...", a última é "250 ...")
        $resposta = '';
        while (($linha = fgets($ligacao, 1024)) !== false) {
            $resposta .= $linha;
            if (strlen($linha) < 4 || $linha[3] !== '-') {
                break;
            }
        }
        if ((int) substr($resposta, 0, 3) !== $esperado) {
            throw new Exception("SMTP respondeu \"" . trim($resposta) . "\" a " . ($rotulo ?? $comando ?? 'ligação'));
        }
        return $resposta;
    };

    $dominio = parse_url($config['url_site'], PHP_URL_HOST) ?: 'localhost';
    $smtp(null, 220);
    $smtp("EHLO $dominio", 250);
    if ($seguranca === 'tls') {
        $smtp('STARTTLS', 220);
        if (!stream_socket_enable_crypto($ligacao, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
            throw new Exception('STARTTLS falhou');
        }
        $smtp("EHLO $dominio", 250);
    }
    if ($config['smtp_utilizador'] !== '') {
        $smtp('AUTH LOGIN', 334);
        $smtp(base64_encode($config['smtp_utilizador']), 334, 'utilizador SMTP');
        $smtp(base64_encode($config['smtp_senha']), 235, 'senha SMTP');
    }
    $remetente = $config['email_remetente'];
    $smtp("MAIL FROM:<$remetente>", 250);
    $smtp("RCPT TO:<$para>", 250);
    $smtp('DATA', 354);

    $mensagem = "From: BestWay <$remetente>\r\n"
        . "To: <$para>\r\n"
        . 'Subject: =?UTF-8?B?' . base64_encode($assunto) . "?=\r\n"
        . 'Date: ' . date('r') . "\r\n"
        . 'Message-ID: <' . bin2hex(random_bytes(12)) . "@$dominio>\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: text/html; charset=UTF-8\r\n"
        . "Content-Transfer-Encoding: base64\r\n\r\n"
        . chunk_split(base64_encode($html));
    $smtp($mensagem . "\r\n.", 250, 'conteúdo da mensagem');
    $smtp('QUIT', 221);
    fclose($ligacao);
}
