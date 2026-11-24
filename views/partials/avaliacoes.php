<?php
// Secção de avaliações das páginas de detalhes.
// Antes de incluir, definir: $ligacao, $avaliacao_tipo ('atividade', 'hospedagem' ou 'pacote') e $avaliacao_item_id.
if (!isset($ligacao, $avaliacao_tipo, $avaliacao_item_id)) {
    return; // aberto diretamente pelo navegador: não há nada a mostrar
}
require_once __DIR__ . '/../../config/avaliacoes.php';

$avaliacao_resumo = avaliacao_resumo($ligacao, $avaliacao_tipo, $avaliacao_item_id);
$avaliacao_id_user = (int) ($_SESSION['id'] ?? 0);
$avaliacao_admin = $avaliacao_id_user && avaliacao_e_admin($ligacao, $avaliacao_id_user);

$stmt = $ligacao->prepare("SELECT av.id, av.id_user, av.nota, av.comentario, av.data_atualizacao, av.resposta, av.data_resposta, u.nick, u.foto
    FROM t_avaliacoes av JOIN t_user u ON u.id = av.id_user
    WHERE av.tipo_item = ? AND av.item_id = ?
    ORDER BY (av.id_user = ?) DESC, av.data_atualizacao DESC");
$stmt->bind_param("sii", $avaliacao_tipo, $avaliacao_item_id, $avaliacao_id_user);
$stmt->execute();
$avaliacoes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$minha_avaliacao = null;
foreach ($avaliacoes as $av) {
    if ((int) $av['id_user'] === $avaliacao_id_user) {
        $minha_avaliacao = $av;
    }
}
$pode_avaliar = $avaliacao_id_user && avaliacao_pode_avaliar($ligacao, $avaliacao_id_user, $avaliacao_tipo, $avaliacao_item_id);
$e_dono = $avaliacao_id_user && avaliacao_dono_item($ligacao, $avaliacao_tipo, $avaliacao_item_id) === $avaliacao_id_user;

$avaliacao_mensagem = $_SESSION['avaliacao_mensagem'] ?? null;
unset($_SESSION['avaliacao_mensagem']);
?>
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/avaliacoes.css">

<section class="avaliacoes" id="avaliacoes">
    <h2>Avaliações</h2>
    <div class="resumo">
        <?php if ($avaliacao_resumo['total'] > 0): ?>
            <span class="estrelas"><?= avaliacao_estrelas($avaliacao_resumo['media']) ?></span>
            <?= number_format($avaliacao_resumo['media'], 1, ',', '') ?> / 5
            · <?= $avaliacao_resumo['total'] ?> <?= $avaliacao_resumo['total'] == 1 ? 'avaliação' : 'avaliações' ?>
        <?php else: ?>
            Ainda não há avaliações.
        <?php endif; ?>
    </div>

    <?php if ($avaliacao_mensagem): ?>
        <div class="mensagem"><?= htmlspecialchars($avaliacao_mensagem) ?></div>
    <?php endif; ?>

    <?php if ($pode_avaliar): ?>
        <form class="formulario" method="post" action="<?= BASE_URL ?>public/backend/avaliar.php">
            <?= csrf_campo() ?>
            <input type="hidden" name="tipo_item" value="<?= htmlspecialchars($avaliacao_tipo) ?>">
            <input type="hidden" name="item_id" value="<?= (int) $avaliacao_item_id ?>">
            <strong><?= $minha_avaliacao ? 'Editar a sua avaliação' : 'Deixe a sua avaliação' ?></strong><br>
            <div class="escolher-nota">
                <?php for ($n = 5; $n >= 1; $n--): ?>
                    <input type="radio" name="nota" id="nota<?= $n ?>" value="<?= $n ?>" required
                        <?= $minha_avaliacao && (int) $minha_avaliacao['nota'] === $n ? 'checked' : '' ?>>
                    <label for="nota<?= $n ?>" title="<?= $n ?> estrela(s)">★</label>
                <?php endfor; ?>
            </div>
            <textarea name="comentario" maxlength="<?= AVALIACAO_MAX_COMENTARIO ?>"
                placeholder="Conte como foi (opcional)"><?= htmlspecialchars($minha_avaliacao['comentario'] ?? '') ?></textarea>
            <button type="submit"><?= $minha_avaliacao ? 'Guardar alterações' : 'Publicar avaliação' ?></button>
        </form>
    <?php elseif (!$minha_avaliacao): ?>
        <?php if (!$e_dono): ?>
            <p>Só quem reservou e já usou esta reserva pode avaliar.</p>
        <?php endif; ?>
    <?php endif; ?>

    <?php foreach ($avaliacoes as $av): ?>
        <div class="avaliacao">
            <div class="cabecalho">
                <?php if (!empty($av['foto'])): ?>
                    <img src="<?= BASE_URL ?>assets/images/pics/<?= htmlspecialchars($av['foto']) ?>" alt="">
                <?php endif; ?>
                <strong><?= htmlspecialchars($av['nick']) ?></strong>
                <span class="estrelas"><?= avaliacao_estrelas((int) $av['nota']) ?></span>
                <span class="data"><?= date('d/m/Y', strtotime($av['data_atualizacao'])) ?></span>
            </div>
            <?php if ($av['comentario'] !== null): ?>
                <div class="comentario"><?= htmlspecialchars($av['comentario']) ?></div>
            <?php endif; ?>
            <?php if ($av['resposta'] !== null): ?>
                <div class="resposta">
                    <strong>Resposta do vendedor</strong>
                    <span class="data"><?= date('d/m/Y', strtotime($av['data_resposta'])) ?></span>
                    <div class="comentario"><?= htmlspecialchars($av['resposta']) ?></div>
                </div>
            <?php endif; ?>
            <?php if ($e_dono): ?>
                <details>
                    <summary><?= $av['resposta'] !== null ? 'Editar resposta' : 'Responder' ?></summary>
                    <form method="post" action="<?= BASE_URL ?>public/backend/avaliar.php">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="tipo_item" value="<?= htmlspecialchars($avaliacao_tipo) ?>">
                        <input type="hidden" name="item_id" value="<?= (int) $avaliacao_item_id ?>">
                        <input type="hidden" name="id_avaliacao" value="<?= (int) $av['id'] ?>">
                        <textarea name="resposta" maxlength="<?= AVALIACAO_MAX_COMENTARIO ?>" required
                            placeholder="Responda a este cliente"><?= htmlspecialchars($av['resposta'] ?? '') ?></textarea>
                        <button type="submit" name="responder">Publicar resposta</button>
                        <?php if ($av['resposta'] !== null): ?>
                            <button type="submit" name="apagar_resposta" class="apagar" formnovalidate>Apagar resposta</button>
                        <?php endif; ?>
                    </form>
                </details>
            <?php endif; ?>
            <?php if ((int) $av['id_user'] === $avaliacao_id_user || $avaliacao_admin): ?>
                <form method="post" action="<?= BASE_URL ?>public/backend/avaliar.php" onsubmit="return confirm('Apagar esta avaliação?');">
                    <?= csrf_campo() ?>
                    <input type="hidden" name="tipo_item" value="<?= htmlspecialchars($avaliacao_tipo) ?>">
                    <input type="hidden" name="item_id" value="<?= (int) $avaliacao_item_id ?>">
                    <input type="hidden" name="id_avaliacao" value="<?= (int) $av['id'] ?>">
                    <button type="submit" name="apagar" class="apagar">Apagar</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</section>
