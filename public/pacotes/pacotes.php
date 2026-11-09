<?php
// Pacotes de viagem dos vendedores, com pesquisa por destino, data de partida e nº de pessoas.
require_once __DIR__ . '/../../config/pacotes.php';
require_once __DIR__ . '/../../config/paginacao.php';
include __DIR__ . '/../../config/liga_bd.php';

$destino = trim($_GET['destino'] ?? '');
$data = reserva_data($_GET['data'] ?? '') ? $_GET['data'] : null;
$pessoas = min(RESERVA_MAX_PESSOAS, max(0, (int) ($_GET['pessoas'] ?? 0)));
$a_pesquisar = $destino !== '' || $data !== null || $pessoas > 0;

$por_pagina = 12;
$pagina = pagina_atual();
[$pacotes, $total] = pacotes_pesquisar($ligacao, $destino, $data, $pessoas, $por_pagina, ($pagina - 1) * $por_pagina);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pacotes - BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <!--header-->
    <?php $cabecalho_sobre_imagem = true; include __DIR__ . '/../../views/partials/header.php'; ?>

    <!--Home section-->
    <section class="home-pacotes" id="home">
        <div class="home-text">
            <h1>Os melhores pacotes, estão a um click.</h1>

            <!-- Pesquisa de pacotes -->
            <form class="hotels-search-bar" method="get" action="pacotes.php#resultados">
                <div class="hotels-input-container">
                    <input type="text" name="destino" placeholder="Para onde vai?" aria-label="Destino"
                        value="<?= htmlspecialchars($destino) ?>">
                </div>
                <div class="hotels-input-container">
                    <input type="date" name="data" aria-label="Data de partida" min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                        value="<?= htmlspecialchars($data ?? '') ?>">
                </div>
                <div class="hotels-input-container">
                    <input type="number" name="pessoas" placeholder="Quantas pessoas" aria-label="Pessoas" min="1"
                        max="<?= RESERVA_MAX_PESSOAS ?>" value="<?= $pessoas ?: '' ?>">
                </div>
                <button class="hotels-search-button" type="submit">Pesquisar</button>
            </form>
        </div>
    </section>

    <!-- Pacotes dos vendedores -->
    <section class="lista-pacotes" id="resultados">
        <h2><?= $a_pesquisar ? "$total pacote(s) encontrado(s)" : 'Confira nossas ofertas!' ?></h2>
        <?php if ($a_pesquisar): ?>
            <p><a href="pacotes.php#resultados">Limpar a pesquisa</a></p>
        <?php endif; ?>

        <?php if (!$pacotes): ?>
            <p class="sem-pacotes">Não encontrámos pacotes com esses critérios. Experimente outro destino ou outra data.</p>
        <?php endif; ?>

        <div class="grelha-pacotes">
            <?php foreach ($pacotes as $p): ?>
                <a class="pacote-card" href="detalhes_pacote.php?id=<?= (int) $p['id'] ?>">
                    <img src="<?= pacote_foto_url($p['foto']) ?>" alt="<?= htmlspecialchars($p['titulo']) ?>" loading="lazy">
                    <div class="pacote-info">
                        <span class="pacote-destino"><i class='bx bx-map'></i> <?= htmlspecialchars($p['destino']) ?></span>
                        <h3><?= htmlspecialchars($p['titulo']) ?></h3>
                        <span class="pacote-duracao"><?= (int) $p['duracao_dias'] ?> dias · <?= htmlspecialchars($p['nome_marca'] ?: $p['nick']) ?></span>
                        <span class="pacote-preco"><?= reserva_formatar_preco($p['preco']) ?> <small>/pessoa</small></span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
        <?= paginacao_html($total, $por_pagina, $pagina) ?>
    </section>

    <section class="travel-suggestions">
        <h2>Outras sugestões de viagens</h2>
        <h1>Conheça pacotes incríveis em nossos conteúdos exclusivos</h1>

        <div class="carrossel-container">
            <button class="prev">&lt;</button>
            <div class="destinos-container">
                <div class="destino-card">
                    <img src="images/13.jpg" alt="Imagem 1">
                    <div class="destino-nome"> Grand Canyon</div>
                </div>
                <div class="destino-card">
                    <img src="images/14.jpg" alt="Imagem 2">
                    <div class="destino-nome"> Caverna azul </div>
                </div>
                <div class="destino-card">
                    <img src="images/15.jpg" alt="Imagem 3">
                    <div class="destino-nome"> Acrópole</div>
                </div>
                <div class="destino-card">
                    <img src="images/16.jpg" alt="Imagem 4">
                    <div class="destino-nome">Sagrada Família</div>
                </div>
                <div class="destino-card">
                    <img src="images/17.jpg" alt="Imagem 5">
                    <div class="destino-nome"> Lagoa Azul</div>
                </div>
                <div class="destino-card">
                    <img src="images/18.jpg" alt="Imagem 6.">
                    <div class="destino-nome"> Burj Khalifa</div>
                </div>
                <div class="destino-card">
                    <img src="images/19.jpg" alt="Imagem 7">
                    <div class="destino-nome">Vaticano</div>
                </div>
                <div class="destino-card">
                    <img src="images/20.jpg" alt="Imagem 8">
                    <div class="destino-nome">Amsterdã</div>
                </div>
                <div class="destino-card">
                    <img src="images/21.jpg" alt="Imagem 9">
                    <div class="destino-nome"> Palácios Nasridas</div>
                </div>

                <!-- Adicione mais cartões conforme necessário -->
            </div>
            <button class="next">&gt;</button>
        </div>

    </section>





    <!--Newsletter-->
    <section class="newsletter">
        <div class="news-text">
            <h2>Inscreva-se para receber nossas ofertas</h2>
            <p>
                Você receberá e-mails promocionais da BestWay. Para mais informações, consulte as <a href="#">Politica
                    de privacidade.</a>.</p>
        </div>


        <div class="send">
            <form action="<?= BASE_URL ?>public/backend/newsletter.php" method="post">
                <input type="email" name="email" placeholder="Insira seu e-mail aqui" required>
                <input type="submit" value="Quero recebê-las!">
            </form>
        </div>
    </section>

    <?php include __DIR__ . '/../../views/partials/footer.php'; ?>


    <script type="text/javascript" src="<?= BASE_URL ?>assets/js/scriptpacotes.js"></script>
</body>

</html>