<?php
// Secção de avaliações das páginas de detalhes.
// Antes de incluir, definir: $ligacao, $avaliacao_tipo ('atividade' ou 'hospedagem') e $avaliacao_item_id.
if (!isset($ligacao, $avaliacao_tipo, $avaliacao_item_id)) {
    return; // aberto diretamente pelo navegador: não há nada a mostrar
}
require_once __DIR__ . '/../../config/avaliacoes.php';

$avaliacao_resumo = avaliacao_resumo($ligacao, $avaliacao_tipo, $avaliacao_item_id);
$avaliacao_id_user = (int) ($_SESSION['id'] ?? 0);
$avaliacao_admin = $avaliacao_id_user && avaliacao_e_admin($ligacao, $avaliacao_id_user);

$stmt = $ligacao->prepare("SELECT av.id, av.id_user, av.nota, av.comentario, av.data_atualizacao, u.nick, u.foto
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

$avaliacao_mensagem = $_SESSION['avaliacao_mensagem'] ?? null;
unset($_SESSION['avaliacao_mensagem']);
?>
<style>
    .avaliacoes { max-width: 900px; margin: 40px auto; padding: 0 16px; font-family: 'Poppins', sans-serif; }
    .avaliacoes h2 { font-size: 22px; color: #333; margin-bottom: 4px; }
    .avaliacoes .resumo { color: #6495ed; font-size: 18px; margin-bottom: 20px; }
    .avaliacoes .estrelas { color: #f5a623; letter-spacing: 2px; }
    .avaliacoes .mensagem { background: #eef4ff; border-left: 4px solid #6495ed; padding: 10px 14px; margin-bottom: 16px; }
    .avaliacoes .formulario, .avaliacoes .avaliacao { background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,.08); padding: 16px; margin-bottom: 14px; }
    .avaliacoes .escolher-nota { display: flex; flex-direction: row-reverse; justify-content: flex-end; font-size: 28px; margin: 6px 0; }
    .avaliacoes .escolher-nota input { display: none; }
    .avaliacoes .escolher-nota label { color: #ccc; cursor: pointer; padding: 0 2px; }
    .avaliacoes .escolher-nota input:checked ~ label,
    .avaliacoes .escolher-nota label:hover,
    .avaliacoes .escolher-nota label:hover ~ label { color: #f5a623; }
    .avaliacoes textarea { width: 100%; min-height: 80px; border: 1px solid #ddd; border-radius: 5px; padding: 8px; font-family: inherit; box-sizing: border-box; }
    .avaliacoes button { width: auto; align-self: flex-start; margin-top: 8px; padding: 8px 18px; background: #6495ed; color: #fff; border: none; border-radius: 5px; cursor: pointer; }
    .avaliacoes button:hover { background: #0056b3; }
    .avaliacoes button.apagar { background: none; color: #c0392b; padding: 0; margin-top: 4px; font-size: 14px; }
    .avaliacoes .cabecalho { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
    .avaliacoes .cabecalho img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
    .avaliacoes .data { color: #888; font-size: 13px; margin-left: auto; }
    .avaliacoes .comentario { color: #444; white-space: pre-line; }
</style>

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
        <p>Só quem reservou e já usou esta reserva pode avaliar.</p>
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
