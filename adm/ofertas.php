<?php
// Ofertas por e-mail: o administrador escreve a oferta, envia um teste para si e depois para todos
// os inscritos (newsletter + utilizadores que aceitam ofertas). Regras em config/ofertas.php.
include 'valida_adm.php';
include '../config/ofertas.php';

$assunto = trim($_POST['assunto'] ?? '');
$mensagem = trim($_POST['mensagem'] ?? '');
$link = trim($_POST['link'] ?? '');
$texto_botao = trim($_POST['texto_botao'] ?? '');
$aviso = $_SESSION['ofertas_aviso'] ?? null;
unset($_SESSION['ofertas_aviso']);

$destinatarios = ofertas_destinatarios($ligacao);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $erro = oferta_erro($assunto, $mensagem, $link, $texto_botao);
    if ($erro !== null) {
        $aviso = ['tipo' => 'danger', 'texto' => $erro];
    } elseif (isset($_POST['teste'])) {
        // Só para o e-mail do administrador, para ver como fica antes de enviar a todos
        $stmt = $ligacao->prepare("SELECT email FROM t_user WHERE id = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $email_admin = $stmt->get_result()->fetch_row()[0];
        [$enviados] = ofertas_enviar([$email_admin], '[Teste] ' . $assunto, $mensagem, $link, $texto_botao);
        $aviso = $enviados
            ? ['tipo' => 'info', 'texto' => "E-mail de teste enviado para $email_admin. Se estiver bem, envie a todos."]
            : ['tipo' => 'danger', 'texto' => 'Não foi possível enviar o e-mail de teste. Veja o log do servidor.'];
    } elseif (isset($_POST['enviar'])) {
        [$enviados, $falhados] = ofertas_enviar($destinatarios, $assunto, $mensagem, $link, $texto_botao);
        $link_guardar = $link === '' ? null : $link;
        $stmt = $ligacao->prepare("INSERT INTO t_ofertas (assunto, mensagem, link, enviados, falhados, id_admin) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiii", $assunto, $mensagem, $link_guardar, $enviados, $falhados, $id_user);
        $stmt->execute();
        $_SESSION['ofertas_aviso'] = $falhados
            ? ['tipo' => 'warning', 'texto' => "Oferta enviada para $enviados pessoas; $falhados e-mails falharam (veja o log do servidor)."]
            : ['tipo' => 'success', 'texto' => "Oferta enviada para $enviados pessoas."];
        header('Location: ofertas.php'); // evita reenviar a todos ao atualizar a página
        exit;
    }
}

$historico = $ligacao->query("SELECT o.assunto, o.enviados, o.falhados, o.data_envio, u.nick
    FROM t_ofertas o LEFT JOIN t_user u ON u.id = o.id_admin ORDER BY o.id DESC LIMIT 20")->fetch_all(MYSQLI_ASSOC);

include 'views/header.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Ofertas por e-mail</h1>
        <span class="text-muted"><?= count($destinatarios) ?> destinatários</span>
    </div>

    <?php if ($aviso): ?>
        <div class="alert alert-<?= $aviso['tipo'] ?>"><?= htmlspecialchars($aviso['texto']) ?></div>
    <?php endif; ?>

    <p class="text-muted">
        Recebem a oferta os inscritos na newsletter e os utilizadores que aceitam ofertas nas configurações e têm o
        e-mail confirmado. Cada e-mail inclui um link para deixar de receber ofertas.
    </p>

    <form method="post" class="mb-5" style="max-width: 720px;">
        <?= csrf_campo() ?>
        <div class="mb-3">
            <label for="assunto" class="form-label">Assunto</label>
            <input type="text" id="assunto" name="assunto" class="form-control" required maxlength="<?= OFERTA_MAX_ASSUNTO ?>"
                value="<?= htmlspecialchars($assunto) ?>">
        </div>
        <div class="mb-3">
            <label for="mensagem" class="form-label">Mensagem <small class="text-muted">(separe os parágrafos com uma linha em branco)</small></label>
            <textarea id="mensagem" name="mensagem" class="form-control" rows="8" required
                maxlength="<?= OFERTA_MAX_MENSAGEM ?>"><?= htmlspecialchars($mensagem) ?></textarea>
        </div>
        <div class="row">
            <div class="col-md-8 mb-3">
                <label for="link" class="form-label">Link (opcional)</label>
                <input type="url" id="link" name="link" class="form-control" placeholder="https://..." maxlength="255"
                    value="<?= htmlspecialchars($link) ?>">
            </div>
            <div class="col-md-4 mb-3">
                <label for="texto_botao" class="form-label">Texto do botão</label>
                <input type="text" id="texto_botao" name="texto_botao" class="form-control" placeholder="Ver oferta" maxlength="40"
                    value="<?= htmlspecialchars($texto_botao) ?>">
            </div>
        </div>
        <button type="submit" name="teste" class="btn btn-outline-primary">Enviar teste para mim</button>
        <button type="submit" name="enviar" class="btn btn-primary"
            onclick="return confirm('Enviar esta oferta para <?= count($destinatarios) ?> destinatários?');">
            Enviar a todos (<?= count($destinatarios) ?>)
        </button>
    </form>

    <h2 class="h4">Últimos envios</h2>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr><th>Data</th><th>Assunto</th><th>Enviados</th><th>Falhados</th><th>Por</th></tr>
            </thead>
            <tbody>
                <?php foreach ($historico as $oferta): ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($oferta['data_envio'])) ?></td>
                        <td><?= htmlspecialchars($oferta['assunto']) ?></td>
                        <td><?= (int) $oferta['enviados'] ?></td>
                        <td><?= (int) $oferta['falhados'] ?></td>
                        <td><?= htmlspecialchars($oferta['nick'] ?? '—') ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$historico): ?>
                    <tr><td colspan="5" class="text-muted">Ainda não foi enviada nenhuma oferta.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>
</html>
