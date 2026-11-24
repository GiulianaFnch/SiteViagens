<?php
// Editar o perfil, para clientes e vendedores (o vendedor tem também o nome da marca e a biografia, que
// aparecem em public/perfil_vendedor.php). Os clientes veem aqui os pedidos de amizade (usados no chat).
// Incluída por public/perfil.php e public/vendedor/admin.php depois do validador, com $painel definido.
require_once __DIR__ . '/../../config/contas.php';
require_once __DIR__ . '/../../config/upload.php';
include __DIR__ . '/../../config/liga_bd.php';

const PERFIL_PASTA_FOTOS = __DIR__ . '/../../assets/images/pics';
$id = (int) $_SESSION['id'];
$e_vendedor = $painel === 'vendedor';
$pagina = $e_vendedor ? 'admin.php' : 'perfil.php';

$stmt = $ligacao->prepare("SELECT * FROM t_user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$linha = $stmt->get_result()->fetch_assoc();

$aviso = $_SESSION['perfil_aviso'] ?? null;
unset($_SESSION['perfil_aviso']);
$voltar = function ($texto, $tipo = 'success') use ($pagina) {
    $_SESSION['perfil_aviso'] = ['texto' => $texto, 'tipo' => $tipo];
    header('Location: ' . $pagina);
    exit;
};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();

    // Pedidos de amizade: só quem recebeu o pedido o aceita ou recusa
    if (isset($_POST['id_solicitacao'])) {
        $id_pedido = (int) $_POST['id_solicitacao'];
        if (isset($_POST['aceitar'])) {
            $stmt = $ligacao->prepare("UPDATE amizades SET status = 'aceito', data_aceite = NOW()
                WHERE id = ? AND id_usuario2 = ? AND status = 'pendente'");
        } else {
            $stmt = $ligacao->prepare("DELETE FROM amizades WHERE id = ? AND id_usuario2 = ? AND status = 'pendente'");
        }
        $stmt->bind_param("ii", $id_pedido, $id);
        $stmt->execute();
        $voltar(isset($_POST['aceitar']) ? 'Pedido de amizade aceite: já pode conversar no chat.' : 'Pedido de amizade recusado.');
    }

    $nick = trim($_POST['nick'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $data_nasc = $_POST['data_nasc'] ?? '';
    $nome_marca = trim($_POST['nome_marca'] ?? $linha['nome_marca']);
    $biografia = trim($_POST['biografia'] ?? (string) $linha['biografia']);

    $stmt = $ligacao->prepare("SELECT 1 FROM t_user WHERE nick = ? AND id <> ?");
    $stmt->bind_param("si", $nick, $id);
    $stmt->execute();
    if ($nick === '' || mb_strlen($nick) > NICK_MAX || $nome === '') {
        $voltar('O nick (até ' . NICK_MAX . ' caracteres) e o nome são obrigatórios.', 'danger');
    }
    if ($stmt->get_result()->fetch_row()) {
        $voltar('Esse nick já está a ser usado. Escolha outro.', 'danger');
    }
    if ($e_vendedor && $nome_marca === '') {
        $voltar('O nome da marca é obrigatório.', 'danger');
    }
    // Mudar o e-mail (que serve para recuperar a conta) pede a palavra-passe atual
    if (strcasecmp($email, $linha['email']) !== 0 && !password_verify($_POST['pass'] ?? '', $linha['pass'])) {
        $voltar('Para mudar o e-mail, indique a sua palavra-passe atual.', 'danger');
    }

    $foto = $linha['foto'];
    if (!empty($_FILES['ficheiro']['name'])) {
        $nova = salvar_imagem($_FILES['ficheiro'], PERFIL_PASTA_FOTOS, 800 * 1024);
        if ($nova === null) {
            $voltar('A foto tem de ser uma imagem JPG, PNG, GIF, WEBP ou BMP com no máximo 800 KB.', 'danger');
        }
        if ($foto && basename($foto) === $foto && is_file(PERFIL_PASTA_FOTOS . '/' . $foto)) {
            unlink(PERFIL_PASTA_FOTOS . '/' . $foto);
        }
        $foto = $nova;
    }

    $erro_email = conta_mudar_email($ligacao, $id, $email); // um e-mail novo tem de ser confirmado
    if ($erro_email !== null) {
        $voltar($erro_email, 'danger');
    }
    $biografia = $biografia === '' ? null : $biografia;
    $data_nasc = $data_nasc === '' ? null : $data_nasc;
    $stmt = $ligacao->prepare("UPDATE t_user SET nick = ?, nome = ?, data_nasc = ?, foto = ?, nome_marca = ?, biografia = ? WHERE id = ?");
    $stmt->bind_param("ssssssi", $nick, $nome, $data_nasc, $foto, $nome_marca, $biografia, $id);
    $stmt->execute();
    $_SESSION['nick'] = $nick;
    $voltar(strcasecmp($email, $linha['email']) !== 0
        ? 'Perfil atualizado. Enviámos um link para confirmar o novo e-mail.'
        : 'Perfil atualizado.');
}

$pedidos = [];
if (!$e_vendedor) {
    $stmt = $ligacao->prepare("SELECT a.id, u.nick, u.foto FROM amizades a JOIN t_user u ON u.id = a.id_usuario1
        WHERE a.id_usuario2 = ? AND a.status = 'pendente' ORDER BY a.data_solicitacao");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $pedidos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$foto_url = $linha['foto'] ? BASE_URL . 'assets/images/pics/' . rawurlencode($linha['foto'])
    : 'https://bootdey.com/img/Content/avatar/avatar1.png';
$painel_titulo = 'Perfil';
$painel_menu = 'perfil';
$painel_css = ['perfil.css'];
$painel_js = [BASE_URL . 'assets/js/perfil.js'];
include __DIR__ . '/../partials/painel_inicio.php';
?>
                    <?php if ($aviso): ?>
                        <div class="alert alert-<?= $aviso['tipo'] ?>" role="alert"><?= htmlspecialchars($aviso['texto']) ?></div>
                    <?php endif; ?>

                    <?php if ($pedidos): ?>
                        <div class="pedidos-amizade">
                            <h2 class="h5">Pedidos de amizade</h2>
                            <?php foreach ($pedidos as $pedido): ?>
                                <form method="post" class="pedido">
                                    <?= csrf_campo() ?>
                                    <input type="hidden" name="id_solicitacao" value="<?= (int) $pedido['id'] ?>">
                                    <img src="<?= BASE_URL ?>assets/images/pics/<?= htmlspecialchars($pedido['foto'] ?? '') ?>" alt="">
                                    <strong><?= htmlspecialchars($pedido['nick']) ?></strong> quer conversar consigo
                                    <button type="submit" name="aceitar" class="btn btn-primary btn-sm rounded-pill">Aceitar</button>
                                    <button type="submit" name="rejeitar" class="btn btn-outline-secondary btn-sm rounded-pill">Recusar</button>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data">
                        <?= csrf_campo() ?>
                        <div class="media align-items-center mb-3">
                            <img src="<?= htmlspecialchars($foto_url) ?>" alt="A sua foto" class="rounded-circle mr-3 foto-perfil" width="80" height="80">
                            <div class="media-body">
                                <label class="btn btn-outline-primary rounded-pill">
                                    Carregar nova foto
                                    <input type="file" name="ficheiro" class="account-settings-fileinput" accept="image/*">
                                </label>
                                <span class="ficheiro-escolhido small"></span>
                                <div class="small text-muted mt-1">JPG, PNG, GIF, WEBP ou BMP, até 800 KB.</div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nick">Nick</label>
                                <input type="text" id="nick" class="form-control rounded-pill" name="nick" required maxlength="<?= NICK_MAX ?>"
                                    value="<?= htmlspecialchars($linha['nick']) ?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nome">Nome</label>
                                <input type="text" id="nome" class="form-control rounded-pill" name="nome" required
                                    value="<?= htmlspecialchars($linha['nome']) ?>">
                            </div>
                        </div>
                        <?php if ($e_vendedor): ?>
                            <div class="form-group">
                                <label for="nome_marca">Nome da marca</label>
                                <input type="text" id="nome_marca" class="form-control rounded-pill" name="nome_marca" required maxlength="150"
                                    value="<?= htmlspecialchars($linha['nome_marca']) ?>">
                            </div>
                            <div class="form-group">
                                <label for="biografia">Biografia <small class="text-muted">(aparece no seu perfil público)</small></label>
                                <textarea id="biografia" class="form-control" name="biografia" rows="4"><?= htmlspecialchars((string) $linha['biografia']) ?></textarea>
                            </div>
                        <?php endif; ?>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="email">E-mail</label>
                                <input type="email" id="email" class="form-control rounded-pill" name="email" required maxlength="<?= EMAIL_MAX ?>"
                                    value="<?= htmlspecialchars($linha['email']) ?>" data-original="<?= htmlspecialchars($linha['email']) ?>">
                                <?php if (!$linha['email_confirmado']): ?>
                                    <small class="text-danger">E-mail por confirmar: abra o link que lhe enviámos.</small>
                                <?php endif; ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="data_nasc">Data de nascimento</label>
                                <input type="date" id="data_nasc" class="form-control rounded-pill" name="data_nasc"
                                    value="<?= htmlspecialchars((string) $linha['data_nasc']) ?>">
                            </div>
                        </div>
                        <div class="form-group" id="campo-senha" hidden>
                            <label for="pass">Palavra-passe atual <small class="text-muted">(obrigatória para mudar o e-mail)</small></label>
                            <input type="password" id="pass" class="form-control rounded-pill" name="pass" autocomplete="current-password">
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill">Guardar alterações</button>
                        </div>
                    </form>

                    <!-- Foto em tamanho grande, ao clicar na foto do perfil -->
                    <div class="popup-background"></div>
                    <div class="popup" role="dialog" aria-label="Foto do perfil">
                        <button type="button" class="close-btn" aria-label="Fechar"></button>
                        <img class="large-image" src="<?= htmlspecialchars($foto_url) ?>" alt="">
                    </div>
<?php include __DIR__ . '/../partials/painel_fim.php'; ?>
