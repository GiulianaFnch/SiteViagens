<?php
// Configurações, iguais para clientes e vendedores: receber ofertas por e-mail, alterar a palavra-passe e
// terminar a sessão. Incluída por public/configuracoes.php e public/vendedor/configuracoes.php depois do
// validador de acesso, com $painel ('cliente' ou 'vendedor') já definido.
require_once __DIR__ . '/../../config/contas.php'; // senha_erro()
include __DIR__ . '/../../config/liga_bd.php';

$id = (int) $_SESSION['id'];
$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    if (isset($_POST['notificacoes_ofertas'])) {
        // Pedido AJAX do interruptor das ofertas (assets/js/configuracoes.js)
        $notificacoes_ofertas = $_POST['notificacoes_ofertas'] === '1' ? 1 : 0;
        $stmt = $ligacao->prepare("UPDATE t_user SET notificacoes_ofertas = ? WHERE id = ?");
        $stmt->bind_param("ii", $notificacoes_ofertas, $id);
        $stmt->execute();
        header('Content-Type: application/json');
        exit(json_encode(['notificacoes_ofertas' => $notificacoes_ofertas]));
    }
    if (isset($_POST['atualizar_senha'])) {
        $stmt = $ligacao->prepare("SELECT pass FROM t_user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $atual = $stmt->get_result()->fetch_row()[0];
        if (!password_verify($_POST['senha_atual'] ?? '', $atual)) {
            $mensagem_erro = 'A palavra-passe atual está errada.';
        } elseif ($erro = senha_erro($_POST['nova_senha'] ?? '', $_POST['confirmar_senha'] ?? '')) {
            $mensagem_erro = $erro;
        } else {
            $hash = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
            $stmt = $ligacao->prepare("UPDATE t_user SET pass = ? WHERE id = ?");
            $stmt->bind_param("si", $hash, $id);
            $stmt->execute();
            $mensagem_sucesso = 'Palavra-passe alterada com sucesso!';
        }
    }
}

$stmt = $ligacao->prepare("SELECT notificacoes_ofertas FROM t_user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$recebe_ofertas = (bool) $stmt->get_result()->fetch_row()[0];

$painel_titulo = 'Configurações';
$painel_menu = 'configuracoes';
$painel_css = ['configuracoes.css'];
$painel_js = [BASE_URL . 'assets/js/configuracoes.js'];
include __DIR__ . '/../partials/painel_inicio.php';
?>
                    <div class="settings-container">
                        <?php if ($mensagem_sucesso): ?>
                            <div class="alert alert-success" role="alert"><?= htmlspecialchars($mensagem_sucesso) ?></div>
                        <?php endif; ?>
                        <?php if ($mensagem_erro): ?>
                            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($mensagem_erro) ?></div>
                        <?php endif; ?>

                        <div id="lista-configuracoes" <?= $mensagem_erro ? 'hidden' : '' ?>>
                            <div class="settings-item" id="interruptor-ofertas" role="switch" tabindex="0"
                                aria-checked="<?= $recebe_ofertas ? 'true' : 'false' ?>" data-csrf="<?= csrf_token() ?>">
                                <div class="item-left">
                                    <i class="bi bi-envelope-paper"></i>
                                    <span class="item-label">Receber ofertas por e-mail</span>
                                </div>
                                <div class="item-right">
                                    <span class="interruptor"></span>
                                </div>
                            </div>

                            <div class="settings-item" id="abrir-senha" role="button" tabindex="0">
                                <div class="item-left">
                                    <i class="bi bi-shield-lock"></i>
                                    <span class="item-label">Alterar palavra-passe</span>
                                </div>
                                <div class="item-right"><i class="bi bi-chevron-right"></i></div>
                            </div>

                            <form method="post" action="<?= BASE_URL ?>public/backend/logout.php">
                                <?= csrf_campo() ?>
                                <button type="submit" class="settings-item">
                                    <span class="item-left">
                                        <i class="bi bi-box-arrow-right"></i>
                                        <span class="item-label">Terminar sessão</span>
                                    </span>
                                    <span class="item-right"><i class="bi bi-chevron-right"></i></span>
                                </button>
                            </form>
                        </div>

                        <div id="form-atualizar-senha" <?= $mensagem_erro ? '' : 'hidden' ?>>
                            <button type="button" id="voltar" class="btn btn-link"><i class="bi bi-chevron-left"></i> Voltar</button>
                            <form method="post">
                                <?= csrf_campo() ?>
                                <div class="form-group">
                                    <label for="senha_atual">Palavra-passe atual</label>
                                    <input type="password" id="senha_atual" name="senha_atual" class="form-control" required autocomplete="current-password">
                                </div>
                                <div class="form-group">
                                    <label for="nova_senha">Nova palavra-passe (mínimo <?= SENHA_MIN ?> caracteres)</label>
                                    <input type="password" id="nova_senha" name="nova_senha" class="form-control" required minlength="<?= SENHA_MIN ?>" autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <label for="confirmar_senha">Repetir a nova palavra-passe</label>
                                    <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required minlength="<?= SENHA_MIN ?>" autocomplete="new-password">
                                </div>
                                <button type="submit" name="atualizar_senha" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
<?php include __DIR__ . '/../partials/painel_fim.php'; ?>
