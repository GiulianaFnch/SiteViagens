<?php
// Pacotes do vendedor: criar, editar, ativar/desativar e apagar (só sem reservas).
include 'valida_vendedor.php'; // $ligacao e $id_user
require_once __DIR__ . '/../../config/pacotes.php';
require_once __DIR__ . '/../../config/upload.php';

$aviso = $_SESSION['pacotes_aviso'] ?? null;
unset($_SESSION['pacotes_aviso']);
$voltar = function ($texto, $tipo = 'success') {
    $_SESSION['pacotes_aviso'] = ['texto' => $texto, 'tipo' => $tipo];
    header('Location: pacotes.php');
    exit;
};

// Pacote do vendedor logado (ou null)
$meu_pacote = function ($id) use ($ligacao, $id_user) {
    $stmt = $ligacao->prepare("SELECT * FROM t_pacotes WHERE id = ? AND id_user = ?");
    $stmt->bind_param("ii", $id, $id_user);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
};

// Só apagamos fotos enviadas pelos vendedores (nome gerado por salvar_imagem), não as de exemplo
$apagar_foto = function ($foto) {
    if ($foto && preg_match('/^[0-9a-f]{16}\.[a-z]+$/', $foto) && is_file(PACOTE_PASTA_FOTOS . '/' . $foto)) {
        unlink(PACOTE_PASTA_FOTOS . '/' . $foto);
    }
};

$erro = null;
$dados = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
    $id = (int) ($_POST['id'] ?? 0);

    if (isset($_POST['ativar']) || isset($_POST['desativar'])) {
        $ativo = isset($_POST['ativar']) ? 1 : 0;
        $stmt = $ligacao->prepare("UPDATE t_pacotes SET ativo = ? WHERE id = ? AND id_user = ?");
        $stmt->bind_param("iii", $ativo, $id, $id_user);
        $stmt->execute();
        $voltar($ativo ? 'Pacote ativado: já aparece na página de pacotes.' : 'Pacote desativado: deixa de aparecer e de poder ser reservado.');
    }

    if (isset($_POST['apagar'])) {
        $pacote = $meu_pacote($id);
        $stmt = $ligacao->prepare("SELECT COUNT(*) FROM t_reservas WHERE tipo_reserva = 'pacote' AND item_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        if (!$pacote) {
            $voltar('Pacote não encontrado.', 'danger');
        }
        if ($stmt->get_result()->fetch_row()[0] > 0) {
            $voltar('Este pacote já tem reservas e não pode ser apagado (ficariam sem nome). Desative-o.', 'danger');
        }
        $stmt = $ligacao->prepare("DELETE FROM t_carrinho WHERE tipo_item = 'pacote' AND id_artigo = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt = $ligacao->prepare("DELETE FROM t_pacotes WHERE id = ? AND id_user = ?");
        $stmt->bind_param("ii", $id, $id_user);
        $stmt->execute();
        $apagar_foto($pacote['foto']);
        $voltar('Pacote apagado.');
    }

    // Criar ou guardar alterações
    [$dados, $erro] = pacote_ler_formulario($_POST);
    $pacote = $id ? $meu_pacote($id) : null;
    if ($id && !$pacote) {
        $voltar('Pacote não encontrado.', 'danger');
    }
    $foto = $pacote['foto'] ?? null;
    if ($erro === null && !empty($_FILES['foto']['name'])) {
        $nova = salvar_imagem($_FILES['foto'], PACOTE_PASTA_FOTOS);
        if ($nova === null) {
            $erro = 'A foto tem de ser uma imagem JPG, PNG, GIF, WEBP ou BMP com no máximo 5MB.';
        } else {
            $apagar_foto($foto);
            $foto = $nova;
        }
    }
    if ($erro === null) {
        if ($pacote) {
            $stmt = $ligacao->prepare("UPDATE t_pacotes SET titulo = ?, destino = ?, descricao = ?, inclui = ?, foto = ?, preco = ?,
                duracao_dias = ?, data_inicio = ?, data_fim = ?, vagas = ? WHERE id = ? AND id_user = ?");
            $stmt->bind_param("sssssdissiii", $dados['titulo'], $dados['destino'], $dados['descricao'], $dados['inclui'], $foto,
                $dados['preco'], $dados['duracao_dias'], $dados['data_inicio'], $dados['data_fim'], $dados['vagas'], $id, $id_user);
            $stmt->execute();
            $voltar('Alterações guardadas. As reservas já feitas mantêm o preço e as datas que tinham.');
        }
        $stmt = $ligacao->prepare("INSERT INTO t_pacotes (id_user, titulo, destino, descricao, inclui, foto, preco, duracao_dias,
            data_inicio, data_fim, vagas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssdissi", $id_user, $dados['titulo'], $dados['destino'], $dados['descricao'], $dados['inclui'], $foto,
            $dados['preco'], $dados['duracao_dias'], $dados['data_inicio'], $dados['data_fim'], $dados['vagas']);
        $stmt->execute();
        $voltar('Pacote criado! Já aparece na página de pacotes.');
    }
    $dados['id'] = $id;
} elseif (isset($_GET['editar'])) {
    $dados = $meu_pacote((int) $_GET['editar']);
} elseif (isset($_GET['novo'])) {
    $dados = ['id' => 0];
}

$stmt = $ligacao->prepare("SELECT p.*,
        (SELECT COALESCE(SUM(quantidade), 0) FROM t_reservas r
         WHERE r.tipo_reserva = 'pacote' AND r.item_id = p.id AND r.estado = 'confirmada') AS pessoas_reservadas
    FROM t_pacotes p WHERE p.id_user = ? ORDER BY p.id DESC");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$pacotes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$v = fn($campo) => htmlspecialchars((string) ($dados[$campo] ?? ''));
$vendedor_titulo = 'Pacotes';
$vendedor_menu = 'gestao';
include __DIR__ . '/../../views/partials/vendedor_inicio.php';
?>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 mb-0">Os meus pacotes</h2>
                        <a class="btn btn-primary btn-sm" href="pacotes.php?novo=1#formulario"><i class="bi bi-plus-circle"></i> Novo pacote</a>
                    </div>

                    <?php if ($aviso): ?>
                        <div class="alert alert-<?= $aviso['tipo'] ?>"><?= htmlspecialchars($aviso['texto']) ?></div>
                    <?php endif; ?>

                    <?php if (!$pacotes): ?>
                        <p class="text-muted">Ainda não tem pacotes. Crie o primeiro com o botão "Novo pacote".</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr><th></th><th>Pacote</th><th>Preço</th><th>Partidas</th><th>Reservas</th><th>Estado</th><th></th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pacotes as $p): ?>
                                        <tr>
                                            <td><img src="<?= pacote_foto_url($p['foto']) ?>" alt="" width="60" height="45" style="object-fit: cover; border-radius: 4px;"></td>
                                            <td>
                                                <a href="<?= BASE_URL ?>public/pacotes/detalhes_pacote.php?id=<?= (int) $p['id'] ?>"><?= htmlspecialchars($p['titulo']) ?></a>
                                                <br><small class="text-muted"><?= htmlspecialchars($p['destino']) ?> · <?= (int) $p['duracao_dias'] ?> dias</small>
                                            </td>
                                            <td><?= reserva_formatar_preco($p['preco']) ?></td>
                                            <td>
                                                <?= reserva_formatar_periodo($p['data_inicio'], $p['data_fim']) ?>
                                                <br><small class="text-muted"><?= $p['vagas'] === null ? 'sem limite de vagas' : (int) $p['vagas'] . ' vagas por partida' ?></small>
                                            </td>
                                            <td><?= (int) $p['pessoas_reservadas'] ?> pessoa(s)</td>
                                            <td><?= $p['ativo'] ? '<span class="badge badge-success">Ativo</span>' : '<span class="badge badge-secondary">Desativado</span>' ?></td>
                                            <td class="text-nowrap">
                                                <a class="btn btn-outline-primary btn-sm" href="pacotes.php?editar=<?= (int) $p['id'] ?>#formulario">Editar</a>
                                                <form method="post" style="display: inline;">
                                                    <?= csrf_campo() ?>
                                                    <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
                                                    <?php if ($p['ativo']): ?>
                                                        <button type="submit" name="desativar" class="btn btn-outline-secondary btn-sm">Desativar</button>
                                                    <?php else: ?>
                                                        <button type="submit" name="ativar" class="btn btn-outline-success btn-sm">Ativar</button>
                                                    <?php endif; ?>
                                                    <button type="submit" name="apagar" class="btn btn-outline-danger btn-sm"
                                                        onclick="return confirm('Apagar este pacote?');">Apagar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <?php if ($dados !== null): ?>
                        <hr>
                        <h2 class="h4" id="formulario"><?= !empty($dados['id']) ? 'Editar pacote' : 'Novo pacote' ?></h2>
                        <?php if ($erro): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>
                        <form method="post" enctype="multipart/form-data">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id" value="<?= (int) ($dados['id'] ?? 0) ?>">
                            <div class="form-row">
                                <div class="form-group col-md-7">
                                    <label for="titulo">Título</label>
                                    <input type="text" id="titulo" name="titulo" class="form-control" required maxlength="100" value="<?= $v('titulo') ?>">
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="destino">Destino</label>
                                    <input type="text" id="destino" name="destino" class="form-control" required maxlength="100"
                                        placeholder="ex.: Itália" value="<?= $v('destino') ?>">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea id="descricao" name="descricao" class="form-control" rows="4" required maxlength="5000"><?= $v('descricao') ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="inclui">O que inclui <small class="text-muted">(um item por linha)</small></label>
                                <textarea id="inclui" name="inclui" class="form-control" rows="4" maxlength="2000"
                                    placeholder="Voo de ida e volta&#10;Hotel 4* (6 noites)&#10;Passeio de barco"><?= $v('inclui') ?></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="preco">Preço por pessoa (€)</label>
                                    <input type="number" id="preco" name="preco" class="form-control" required min="1" step="0.01" value="<?= $v('preco') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="duracao_dias">Duração (dias)</label>
                                    <input type="number" id="duracao_dias" name="duracao_dias" class="form-control" required min="1"
                                        max="<?= PACOTE_MAX_DIAS ?>" value="<?= $v('duracao_dias') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="vagas">Vagas por partida</label>
                                    <input type="number" id="vagas" name="vagas" class="form-control" min="1" placeholder="sem limite" value="<?= $v('vagas') ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="data_inicio">Primeira partida</label>
                                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" required value="<?= $v('data_inicio') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="data_fim">Última partida</label>
                                    <input type="date" id="data_fim" name="data_fim" class="form-control" required value="<?= $v('data_fim') ?>">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="foto">Foto<?= !empty($dados['foto']) ? ' (deixe vazio para manter)' : '' ?></label>
                                    <input type="file" id="foto" name="foto" class="form-control-file" accept="image/*">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><?= !empty($dados['id']) ? 'Guardar alterações' : 'Criar pacote' ?></button>
                            <a href="pacotes.php" class="btn btn-link">Cancelar</a>
                        </form>
                    <?php endif; ?>
<?php include __DIR__ . '/../../views/partials/vendedor_fim.php'; ?>
