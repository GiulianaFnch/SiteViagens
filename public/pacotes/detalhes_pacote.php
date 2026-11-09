<?php
// Detalhes de um pacote e escolha da data de partida e do nº de pessoas (vai para o carrinho).
require_once __DIR__ . '/../../config/pacotes.php';
require_once __DIR__ . '/../../config/csrf.php'; // sessão e token do formulário
include __DIR__ . '/../../config/liga_bd.php';

$id = (int) ($_GET['id'] ?? 0);
$stmt = $ligacao->prepare("SELECT p.*, u.nome_marca, u.nick FROM t_pacotes p JOIN t_user u ON u.id = p.id_user
    WHERE p.id = ? AND p.ativo = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$p = $stmt->get_result()->fetch_assoc();
if (!$p) {
    http_response_code(404);
}

// Primeira partida possível: amanhã, ou o início do período se for depois
$primeira = $p ? max(date('Y-m-d', strtotime('+1 day')), $p['data_inicio']) : null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($p['titulo'] ?? 'Pacote') ?> - BestWay</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>

    <?php if (!$p): ?>
        <div class="detalhes-pacote" style="display: block; text-align: center;">
            <h1>Pacote não encontrado</h1>
            <p>Este pacote não existe ou já não está disponível.</p>
            <p><a href="pacotes.php">Ver todos os pacotes</a></p>
        </div>
    <?php else: ?>
        <main class="detalhes-pacote">
            <div>
                <img class="foto-pacote" src="<?= pacote_foto_url($p['foto']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>">
                <h1><?= htmlspecialchars($p['titulo']) ?></h1>
                <p class="pacote-destino">
                    <i class='bx bx-map'></i> <?= htmlspecialchars($p['destino']) ?> ·
                    <?= (int) $p['duracao_dias'] ?> dias ·
                    por <a href="<?= BASE_URL ?>public/perfil_vendedor.php?user_id=<?= (int) $p['id_user'] ?>"><?= htmlspecialchars($p['nome_marca'] ?: $p['nick']) ?></a>
                </p>
                <p class="descricao"><?= htmlspecialchars($p['descricao']) ?></p>

                <?php if ($inclui = pacote_inclui($p['inclui'])): ?>
                    <h2>O que inclui</h2>
                    <ul class="inclui">
                        <?php foreach ($inclui as $linha): ?>
                            <li><i class='bx bx-check-circle'></i><?= htmlspecialchars($linha) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <aside class="reserva-pacote">
                <span class="pacote-preco"><?= reserva_formatar_preco($p['preco']) ?> <small>/pessoa</small></span>
                <?php if ($primeira > $p['data_fim']): ?>
                    <p class="nota">Já não há partidas para este pacote.</p>
                <?php else: ?>
                    <form method="post" action="<?= BASE_URL ?>public/carrinho/adicionar_ao_carrinho.php">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="tipo_item" value="pacote">
                        <input type="hidden" name="id_artigo" value="<?= (int) $p['id'] ?>">
                        <label for="data_inicio">Data de partida</label>
                        <input type="date" id="data_inicio" name="data_inicio" required
                            min="<?= $primeira ?>" max="<?= htmlspecialchars($p['data_fim']) ?>">
                        <label for="quantidade">Pessoas</label>
                        <input type="number" id="quantidade" name="quantidade" value="2" min="1"
                            max="<?= min(RESERVA_MAX_PESSOAS, $p['vagas'] ?? RESERVA_MAX_PESSOAS) ?>" required>
                        <button type="submit">Adicionar ao carrinho</button>
                    </form>
                    <p class="nota">
                        Partidas de <?= reserva_formatar_data($primeira) ?> a <?= reserva_formatar_data($p['data_fim']) ?>.
                        <?php if ($p['vagas'] !== null): ?>Máximo de <?= (int) $p['vagas'] ?> pessoas por partida.<?php endif; ?>
                        <?php if (!isset($_SESSION['id'])): ?><br>Precisa de <a href="<?= BASE_URL ?>public/login.php">iniciar sessão</a> para reservar.<?php endif; ?>
                    </p>
                <?php endif; ?>
            </aside>
        </main>
    <?php endif; ?>

    <?php include __DIR__ . '/../../views/partials/footer.php'; ?>
</body>

</html>
