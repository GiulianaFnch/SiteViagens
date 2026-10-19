<?php
// Proteção contra CSRF.
// Cada sessão recebe um token aleatório; os formulários que alteram dados enviam-no
// num campo escondido (csrf_campo()) ou, nos pedidos AJAX, no corpo do pedido.
// Os ficheiros que tratam esses formulários chamam csrf_verificar() antes de alterar a BD.

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Campo escondido para colocar dentro de um <form method="post">
function csrf_campo()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

// Termina o pedido se não for POST ou se o token não corresponder ao da sessão
function csrf_verificar()
{
    $enviado = $_POST['csrf_token'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !is_string($enviado) || !hash_equals(csrf_token(), $enviado)) {
        http_response_code(403);
        exit('Pedido inválido: o token de segurança está em falta ou expirou. Volte à página anterior, recarregue-a e tente novamente.');
    }
}
