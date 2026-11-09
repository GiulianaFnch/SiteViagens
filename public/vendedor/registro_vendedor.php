<?php
// Registo de vendedores: a conta fica por confirmar (e-mail) e à espera da aprovação do administrador.
require_once __DIR__ . '/../../config/contas.php';
require_once __DIR__ . '/../../config/acesso.php'; // tipos de utilizador

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar(); // rejeita registos enviados por formulários de outros sites
    include __DIR__ . '/../../config/liga_bd.php';

    $nick = trim($_POST['nick'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nome_marca = trim($_POST['nome_marca'] ?? '');

    $erro = registo_erro($ligacao, $nick, $email) ?? senha_erro($_POST['pass'] ?? '', $_POST['pass2'] ?? '');
    if ($erro === null && ($nome === '' || $nome_marca === '')) {
        $erro = 'Preencha o nome e o nome da marca.';
    }
    if ($erro !== null) {
        $_SESSION['registo_valores'] = compact('nick', 'nome', 'email', 'nome_marca');
        conta_aviso($erro, 'erro', 'public/vendedor/registro_vendedor.php');
    }

    $hash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $tipo_user = TIPO_VENDEDOR_PENDENTE;
    $stmt = $ligacao->prepare("INSERT INTO t_user (nick, nome, email, nome_marca, pass, tipo_user, email_confirmado)
        VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("sssssi", $nick, $nome, $email, $nome_marca, $hash, $tipo_user);
    $stmt->execute();

    if (enviar_confirmacao_email($ligacao, $stmt->insert_id)) {
        conta_aviso("Pedido registado! Confirme o seu e-mail com o link que enviámos para $email. "
            . 'Depois, o administrador ainda tem de aprovar a conta de vendedor.', 'sucesso', 'public/login.php');
    }
    conta_aviso('Pedido registado, mas não foi possível enviar o e-mail de confirmação. Faça login para o pedir de novo.',
        'erro', 'public/login.php');
}

$antes = $_SESSION['registo_valores'] ?? [];
unset($_SESSION['registo_valores']);
$valor = fn($campo) => htmlspecialchars($antes[$campo] ?? '');

$conta_titulo = 'Registrar-se como vendedor';
$conta_subtitulo = 'Depois de confirmar o e-mail, o administrador aprova a sua conta de vendedor';
include __DIR__ . '/../../views/partials/conta_inicio.php';
?>
                <form method="post">
                    <?= csrf_campo() ?>
                    <div class="input-group">
                        <label for="nick">Nick:</label>
                        <input type="text" id="nick" name="nick" class="form-control" required maxlength="<?= NICK_MAX ?>"
                            autocomplete="username" value="<?= $valor('nick') ?>">
                    </div>
                    <div class="input-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" class="form-control" required value="<?= $valor('nome') ?>">
                    </div>
                    <div class="input-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" class="form-control" required maxlength="<?= EMAIL_MAX ?>"
                            value="<?= $valor('email') ?>">
                    </div>
                    <div class="input-group">
                        <label for="nome_marca">Nome da marca:</label>
                        <input type="text" id="nome_marca" name="nome_marca" class="form-control" required
                            value="<?= $valor('nome_marca') ?>">
                    </div>
                    <div class="input-group">
                        <label for="pass">Palavra-passe (mínimo <?= SENHA_MIN ?> caracteres):</label>
                        <input type="password" id="pass" name="pass" class="form-control" required minlength="<?= SENHA_MIN ?>"
                            autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <label for="pass2">Repetir a palavra-passe:</label>
                        <input type="password" id="pass2" name="pass2" class="form-control" required minlength="<?= SENHA_MIN ?>"
                            autocomplete="new-password">
                    </div>
                    <button type="submit" class="home-btn">Enviar pedido</button>
                </form>
<?php include __DIR__ . '/../../views/partials/conta_fim.php'; ?>
