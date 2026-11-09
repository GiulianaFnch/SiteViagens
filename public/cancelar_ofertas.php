<?php
// Cancelar as ofertas por e-mail, a partir do link no fim de cada oferta.
// O link é assinado (config/ofertas.php), para que ninguém cancele os e-mails de outra pessoa.
// Abrir o link só mostra a pergunta: os programas de e-mail que abrem links sozinhos não cancelam nada.
require_once __DIR__ . '/../config/ofertas.php';
require_once __DIR__ . '/../config/contas.php'; // conta_aviso()

$email = strtolower(trim($_REQUEST['email'] ?? ''));
$assinatura = $_REQUEST['assinatura'] ?? '';
if (!ofertas_assinatura_valida($email, $assinatura)) {
    conta_aviso('O link para cancelar as ofertas é inválido. Use o link do último e-mail que recebeu.', 'erro', 'public/login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    include __DIR__ . '/../config/liga_bd.php';
    ofertas_cancelar($ligacao, $email);
    $cancelado = true;
}

$conta_titulo = 'Ofertas por e-mail';
include __DIR__ . '/../views/partials/conta_inicio.php';
?>
                <?php if (!empty($cancelado)): ?>
                    <div class="aviso-conta sucesso" role="alert">
                        Pronto: <?= htmlspecialchars($email) ?> deixa de receber as nossas ofertas.
                        Pode voltar a ativá-las nas configurações da sua conta.
                    </div>
                <?php else: ?>
                    <p>Deixar de receber as ofertas do BestWay em <strong><?= htmlspecialchars($email) ?></strong>?</p>
                    <form method="post">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                        <input type="hidden" name="assinatura" value="<?= htmlspecialchars($assinatura) ?>">
                        <button type="submit" class="home-btn">Não quero receber mais ofertas</button>
                    </form>
                <?php endif; ?>
<?php include __DIR__ . '/../views/partials/conta_fim.php'; ?>
