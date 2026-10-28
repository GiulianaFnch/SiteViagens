<?php
// Registo de clientes (formulário de public/registro.php). A conta fica por confirmar até o
// utilizador abrir o link enviado por e-mail.
require_once __DIR__ . '/../../config/contas.php';
require_once __DIR__ . '/../../config/upload.php';
csrf_verificar(); // só aceita o formulário de registro.php
include __DIR__ . '/../../config/liga_bd.php';

$nick = trim($_POST['nick'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$data_nasc = $_POST['data_nasc'] ?? '';

// Em caso de erro, volta ao formulário com os valores já preenchidos (exceto a palavra-passe)
$recusar = function ($mensagem) use ($nick, $nome, $email, $data_nasc) {
    $_SESSION['registo_valores'] = compact('nick', 'nome', 'email', 'data_nasc');
    conta_aviso($mensagem, 'erro', 'public/registro.php');
};

$erro = registo_erro($ligacao, $nick, $email) ?? senha_erro($_POST['pass'] ?? '', $_POST['pass2'] ?? '');
if ($erro === null && ($nome === '' || !DateTime::createFromFormat('!Y-m-d', $data_nasc))) {
    $erro = 'Preencha o nome e a data de nascimento.';
}
if ($erro !== null) {
    $recusar($erro);
}

$foto = salvar_imagem($_FILES['ficheiro'] ?? [], __DIR__ . '/../../assets/images/pics/');
if ($foto === null) {
    $recusar('Só são permitidas imagens JPG, PNG, GIF, WEBP ou BMP com no máximo 5MB.');
}

$hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$stmt = $ligacao->prepare("INSERT INTO t_user (nick, nome, email, nome_marca, data_nasc, pass, foto, email_confirmado)
    VALUES (?, ?, ?, '', ?, ?, ?, 0)");
$stmt->bind_param("ssssss", $nick, $nome, $email, $data_nasc, $hash, $foto);
try {
    $stmt->execute();
} catch (mysqli_sql_exception $e) {
    unlink(__DIR__ . '/../../assets/images/pics/' . $foto);
    if ($e->getCode() === 1062) { // outro registo ficou com o mesmo nick entretanto
        $recusar('Esse nick já está a ser usado. Escolha outro.');
    }
    throw $e;
}

if (enviar_confirmacao_email($ligacao, $stmt->insert_id)) {
    conta_aviso("Conta criada! Enviámos um e-mail para $email: abra o link para ativar a conta e depois faça login.",
        'sucesso', 'public/login.php');
}
conta_aviso('Conta criada, mas não foi possível enviar o e-mail de confirmação. Faça login para o pedir de novo.',
    'erro', 'public/login.php');
