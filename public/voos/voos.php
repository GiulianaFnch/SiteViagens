<?php
// Pesquisa de voos (SerpAPI, config/voos.php). Só ida: escolhe-se o voo e vai para o carrinho.
// Ida e volta: primeiro escolhe-se a ida, depois a volta (o preço mostrado é o da viagem completa).
require_once __DIR__ . '/../../config/voos.php';
require_once __DIR__ . '/../../config/reservas.php'; // reserva_formatar_preco
require_once __DIR__ . '/../../config/csrf.php'; // inicia a sessão para o token do carrinho

$pesquisa = null;
$erro = null;
$itinerarios = [];
$ida_escolhida = null; // na ida e volta, depois de escolher a ida
if (isset($_GET['origem'])) {
    [$pesquisa, $erro] = voos_ler_pesquisa($_GET);
    if ($erro === null && isset($_GET['ida_ref'])) {
        $ida_escolhida = $_SESSION['voos_idas'][$_GET['ida_ref']] ?? null;
        if (!$ida_escolhida || $ida_escolhida['pesquisa'] !== $pesquisa) {
            $erro = 'Esta escolha já expirou. Pesquise de novo.';
            $ida_escolhida = null;
        }
    }
    if ($erro === null) {
        try {
            $itinerarios = voos_pesquisar($pesquisa, $ida_escolhida['token'] ?? null);
        } catch (VoosErro $e) {
            $erro = $e->getMessage();
        }
    }
}
// Guarda só as escolhas das últimas pesquisas (várias abas continuam a funcionar)
$_SESSION['voos_idas'] = array_slice($_SESSION['voos_idas'] ?? [], -50, null, true);
$_SESSION['voos_pesquisados'] = array_slice($_SESSION['voos_pesquisados'] ?? [], -100, null, true);
$valor = fn($campo) => htmlspecialchars($pesquisa[$campo] ?? ($_GET[$campo] ?? ''));
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/voos.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <!--header-->
    <?php $cabecalho_sobre_imagem = true; include __DIR__ . '/../../views/partials/header.php'; ?>

    <!--Home section-->
    <section class="home-voos" id="home">
        <div class="home-text">
            <h1>As melhores ofertas e os menores preços.</h1>

            <!-- Pesquisa de voos -->
            <form class="hotels-search-bar" action="voos.php#flights-results" method="get">
                <div class="hotels-input-container">
                    <input type="text" placeholder="De (ex.: LIS)" name="origem" required maxlength="3" pattern="[A-Za-z]{3}"
                        title="Código do aeroporto, com 3 letras (ex.: LIS)" aria-label="Aeroporto de partida" value="<?= $valor('origem') ?>">
                </div>
                <div class="hotels-input-container">
                    <input type="text" placeholder="Para (ex.: MAD)" name="destino" required maxlength="3" pattern="[A-Za-z]{3}"
                        title="Código do aeroporto, com 3 letras (ex.: MAD)" aria-label="Aeroporto de chegada" value="<?= $valor('destino') ?>">
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="ida" required min="<?= date('Y-m-d') ?>" aria-label="Data de ida" value="<?= $valor('ida') ?>">
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="volta" min="<?= date('Y-m-d') ?>" aria-label="Data de volta (opcional)" title="Deixe vazio para só ida"
                        value="<?= $valor('volta') ?>">
                </div>
                <button class="hotels-search-button" type="submit">Pesquisar</button>
            </form>
        </div>
    </section>

    <div class="container" id="flights-results">
        <?php if ($erro !== null): ?>
            <div class="flight-results voos-aviso"><?= htmlspecialchars($erro) ?></div>
        <?php elseif ($pesquisa !== null): ?>
            <div class="flight-results">
                <?php if ($ida_escolhida): ?>
                    <?php $ida = $ida_escolhida['itinerario']; ?>
                    <p class="voos-passo">Ida escolhida: <strong><?= htmlspecialchars($ida['companhias']) ?></strong>,
                        <?= voos_hora($ida['partida']['hora']) ?> → <?= voos_hora($ida['chegada']['hora']) ?>
                        (<?= htmlspecialchars($ida['partida']['codigo']) ?> → <?= htmlspecialchars($ida['chegada']['codigo']) ?>)
                        · <a href="?<?= htmlspecialchars(http_build_query(array_diff_key($_GET, ['ida_ref' => 1]))) ?>#flights-results">mudar</a></p>
                    <h2>Escolha o voo de volta · <?= htmlspecialchars($pesquisa['destino']) ?> → <?= htmlspecialchars($pesquisa['origem']) ?></h2>
                <?php elseif ($pesquisa['volta'] !== null): ?>
                    <h2>Escolha o voo de ida · <?= htmlspecialchars($pesquisa['origem']) ?> → <?= htmlspecialchars($pesquisa['destino']) ?></h2>
                    <p>Preços da viagem de ida e volta, por pessoa. Depois escolhe o voo de volta.</p>
                <?php else: ?>
                    <h2>Voos · <?= htmlspecialchars($pesquisa['origem']) ?> → <?= htmlspecialchars($pesquisa['destino']) ?></h2>
                    <p>Só ida, preço por pessoa, do mais barato para o mais caro.</p>
                <?php endif; ?>

                <?php if (!$itinerarios): ?>
                    <p>Não encontrámos voos para essa pesquisa. Experimente outras datas ou aeroportos.</p>
                <?php endif; ?>

                <?php foreach ($itinerarios as $voo): ?>
                    <div class="flight-item">
                        <div class="details">
                            <?php if ($voo['logo']): ?>
                                <img src="<?= htmlspecialchars($voo['logo']) ?>" alt="" height="50" width="50">
                            <?php endif; ?>
                            <div class="info">
                                <p><strong><?= voos_hora($voo['partida']['hora']) ?> → <?= voos_hora($voo['chegada']['hora']) ?></strong>
                                    <?= htmlspecialchars($voo['partida']['codigo']) ?> → <?= htmlspecialchars($voo['chegada']['codigo']) ?></p>
                                <p><?= htmlspecialchars($voo['companhias']) ?> · <?= htmlspecialchars($voo['numeros']) ?></p>
                                <p><?= voos_formatar_duracao($voo['duracao']) ?> ·
                                    <?= $voo['escalas'] === 0 ? 'direto' : $voo['escalas'] . ($voo['escalas'] === 1 ? ' escala' : ' escalas') ?></p>
                            </div>
                        </div>
                        <div class="price">
                            <?= reserva_formatar_preco($voo['preco']) ?>
                            <span><?= $pesquisa['volta'] !== null ? 'ida e volta' : 'só ida' ?></span>
                        </div>
                        <?php if ($pesquisa['volta'] !== null && !$ida_escolhida): ?>
                            <?php
                            // Guarda a ida escolhível na sessão; o endereço só leva uma referência
                            $ref = bin2hex(random_bytes(8));
                            $_SESSION['voos_idas'][$ref] = ['token' => $voo['token'], 'itinerario' => $voo, 'pesquisa' => $pesquisa];
                            ?>
                            <?php if ($voo['token']): ?>
                                <a class="home-btn" href="?<?= htmlspecialchars(http_build_query(['origem' => $pesquisa['origem'],
                                    'destino' => $pesquisa['destino'], 'ida' => $pesquisa['ida'], 'volta' => $pesquisa['volta'],
                                    'ida_ref' => $ref])) ?>#flights-results">Escolher esta ida</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php
                            // Os dados do voo (incluindo o preço) ficam na sessão; o formulário só envia uma
                            // referência, para que o preço não possa ser alterado no navegador
                            $ref = bin2hex(random_bytes(8));
                            $ida = $ida_escolhida['itinerario'] ?? $voo;
                            $_SESSION['voos_pesquisados'][$ref] = [
                                'flight_number' => $ida_escolhida ? $ida['numeros'] . ' / ' . $voo['numeros'] : $voo['numeros'],
                                'airline' => $ida_escolhida && $ida['companhias'] !== $voo['companhias']
                                    ? $ida['companhias'] . ' / ' . $voo['companhias'] : $voo['companhias'],
                                'price' => $voo['preco'],
                                'origem' => trim($ida['partida']['nome'] . ' (' . $ida['partida']['codigo'] . ')'),
                                'arrival' => trim($ida['chegada']['nome'] . ' (' . $ida['chegada']['codigo'] . ')'),
                                'data_ida' => $pesquisa['ida'],
                                'data_volta' => $pesquisa['volta'],
                            ];
                            ?>
                            <form action="../carrinho/adicionar_ao_carrinho.php" method="post">
                                <?= csrf_campo() ?>
                                <input type="hidden" name="voo_ref" value="<?= $ref ?>">
                                <input type="hidden" name="tipo_item" value="voo">
                                <button type="submit" class="home-btn">Adicionar ao carrinho</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!--Newsletter-->
    <section class="newsletter">
        <div class="news-text">
            <h2>Inscreva-se para receber nossas ofertas</h2>
            <p>Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Políticas de privacidade.</a>.</p>
        </div>
        <div class="send">
            <form action="<?= BASE_URL ?>public/backend/newsletter.php" method="post">
                <input type="email" name="email" placeholder="Insira seu e-mail aqui" required>
                <input type="submit" value="Quero recebê-las!">
            </form>
        </div>
    </section>

    <?php include __DIR__ . '/../../views/partials/footer.php'; ?>

</body>

</html>