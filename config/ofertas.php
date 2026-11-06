<?php
// Ofertas por e-mail: quem as recebe, o envio feito pelo administrador (adm/ofertas.php)
// e o link assinado para cancelar a subscrição (public/cancelar_ofertas.php).
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/email.php';
require_once __DIR__ . '/acesso.php'; // TIPO_BLOQUEADO

const OFERTA_MAX_ASSUNTO = 150;
const OFERTA_MAX_MENSAGEM = 5000;

/**
 * E-mails que recebem as ofertas, sem repetidos: os inscritos na newsletter e os utilizadores
 * que aceitam ofertas (configurações), com o e-mail confirmado e a conta ativa.
 */
function ofertas_destinatarios($ligacao)
{
    $resultado = $ligacao->query("SELECT LOWER(email) FROM t_newsletter
        UNION
        SELECT LOWER(email) FROM t_user
        WHERE notificacoes_ofertas = 1 AND email_confirmado = 1 AND tipo_user <> " . TIPO_BLOQUEADO);
    return array_column($resultado->fetch_all(), 0);
}

// Assinatura do link de cancelamento: só quem recebeu o e-mail consegue cancelar aquele endereço
function ofertas_assinatura($email)
{
    return hash_hmac('sha256', 'cancelar_ofertas|' . strtolower($email), chave_app());
}

function ofertas_link_cancelar($email)
{
    return url_absoluta('public/cancelar_ofertas.php?' . http_build_query([
        'email' => strtolower($email),
        'assinatura' => ofertas_assinatura($email),
    ]));
}

function ofertas_assinatura_valida($email, $assinatura)
{
    return is_string($email) && is_string($assinatura) && hash_equals(ofertas_assinatura($email), $assinatura);
}

// Deixa de enviar ofertas para este e-mail (newsletter e contas com este e-mail)
function ofertas_cancelar($ligacao, $email)
{
    $stmt = $ligacao->prepare("DELETE FROM t_newsletter WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt = $ligacao->prepare("UPDATE t_user SET notificacoes_ofertas = 0 WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
}

// Mensagem de erro dos campos da oferta, ou null se estiverem bem
function oferta_erro($assunto, $mensagem, $link, $texto_botao)
{
    if ($assunto === '' || mb_strlen($assunto) > OFERTA_MAX_ASSUNTO) {
        return 'O assunto é obrigatório (até ' . OFERTA_MAX_ASSUNTO . ' caracteres).';
    }
    if ($mensagem === '' || mb_strlen($mensagem) > OFERTA_MAX_MENSAGEM) {
        return 'A mensagem é obrigatória (até ' . OFERTA_MAX_MENSAGEM . ' caracteres).';
    }
    if ($link !== '' && (!filter_var($link, FILTER_VALIDATE_URL) || !preg_match('#^https?://#i', $link) || strlen($link) > 255)) {
        return 'O link tem de ser um endereço completo, começado por http:// ou https://.';
    }
    if ($link !== '' && $texto_botao === '') {
        return 'Indique o texto do botão do link.';
    }
    return null;
}

/**
 * Envia a oferta a cada destinatário (um e-mail por pessoa, com o seu link de cancelamento).
 * Devolve [enviados, falhados].
 */
function ofertas_enviar($destinatarios, $assunto, $mensagem, $link, $texto_botao)
{
    // Parágrafos separados por linhas em branco
    $paragrafos = array_values(array_filter(array_map('trim', preg_split('/\R\s*\R/', $mensagem))));
    $enviados = $falhados = 0;
    set_time_limit(0);
    foreach ($destinatarios as $email) {
        $cancelar = ofertas_link_cancelar($email);
        $rodape = 'Recebe este e-mail porque aceitou receber ofertas do BestWay. '
            . '<a href="' . htmlspecialchars($cancelar) . '" style="color: #777;">Não quero receber mais ofertas</a>.';
        $html = email_modelo($assunto, $paragrafos, $link !== '' ? $texto_botao : null, $link !== '' ? $link : null, $rodape);
        if (enviar_email($email, $assunto, $html, ['List-Unsubscribe' => "<$cancelar>"])) {
            $enviados++;
        } else {
            $falhados++;
        }
    }
    return [$enviados, $falhados];
}
