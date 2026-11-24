<?php
require_once __DIR__ . '/../../config/config.php';
include '../../config/liga_bd.php';
include '../../config/avaliacoes.php';
include '../../config/paginacao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/listar_hospedagem.css">
</head>

<body>
    <!--header-->
    <?php $cabecalho_sobre_imagem = true; include __DIR__ . '/../../views/partials/header.php'; ?>
    <!--container-->
    <section class="listar-hospedagens" id="home">
        <div class="home-text2">
            <h1>Hospedagens <br> Mais <br> Procuradas.</h1>
            <p style="color: aliceblue;">"Encontre hospedagens que combinam com você!"</p>
        </div>
    </section>

    <!-- Container de Título -->
    <section class="container">
        <div class="text">
            <center>
                <h2>Hospedagens mais procuradas.</h2>
            </center>
        </div>
    </section>

    <!-- Lista de hospedagens -->
    <div class="hospedagens-grid">
        <?php
        // Filtro opcional por tipo (?tipo_hospedagem=hotel) e paginação (?pagina=N)
        $tipo_hospedagem = $_GET['tipo_hospedagem'] ?? '';
        $por_pagina = 12;
        $pagina = pagina_atual();
        $offset = ($pagina - 1) * $por_pagina;

        $stmt = $ligacao->prepare("SELECT COUNT(*) FROM t_hospedagem h JOIN t_user u ON u.id = h.id_user
                                   WHERE (? = '' OR h.tipo_hospedagem = ?)");
        $stmt->bind_param("ss", $tipo_hospedagem, $tipo_hospedagem);
        $stmt->execute();
        $total = (int) $stmt->get_result()->fetch_row()[0];

        // Hospedagens com o nick e email do vendedor e a média das avaliações, numa só consulta
        $stmt = $ligacao->prepare("SELECT h.*, u.nick, u.email, av.media_avaliacoes, COALESCE(av.total_avaliacoes, 0) AS total_avaliacoes
                                   FROM t_hospedagem h
                                   JOIN t_user u ON u.id = h.id_user
                                   LEFT JOIN (SELECT item_id, AVG(nota) AS media_avaliacoes, COUNT(*) AS total_avaliacoes
                                              FROM t_avaliacoes WHERE tipo_item = 'hospedagem' GROUP BY item_id) av ON av.item_id = h.id
                                   WHERE (? = '' OR h.tipo_hospedagem = ?)
                                   ORDER BY h.id
                                   LIMIT ? OFFSET ?");
        $stmt->bind_param("ssii", $tipo_hospedagem, $tipo_hospedagem, $por_pagina, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verifica se existem hospedagens disponíveis
        if (mysqli_num_rows($resultado) > 0) {
            // Itera sobre os resultados e exibe cada hospedagem
            while ($linha = mysqli_fetch_array($resultado)) {
                $linha_user = $linha; // nick e email do vendedor já vêm na consulta

                if ($linha_user) {
                    echo '<div class="box">';
                    echo '    <div class="thum">';
                    echo "        <img src='../../public/hotels/imagens/" . htmlspecialchars($linha['foto1']) . "' alt='Foto da hospedagem'>";
                    echo '    </div>';
                    echo '    <div class="dest-content">';
                    echo '        <div class="location">';
                    echo "            <h3>" . htmlspecialchars($linha['nome']) . "</h3>";
                    echo "            <h5>" . htmlspecialchars($linha['descricao']) . "</h5>";
                    echo '        </div>';
                    echo '        <div class="stars">';
                    echo '            <span style="color: #f5a623;">' . avaliacao_texto($linha['media_avaliacoes'] === null ? null : (float) $linha['media_avaliacoes'], (int) $linha['total_avaliacoes']) . '</span>';
                    echo "            <h4>A partir de " . htmlspecialchars($linha['preco_diaria']) . " € por noite</h4>";
                    echo '        </div>';
                    echo '        <div class="btn-container">';

                    // Botão "Ver mais" que redireciona para a página detalhes_hospedagem.php
                    echo '            <form action="detalhes_hospedagem.php" method="post">';
                    echo '                <input type="hidden" name="id_hospedagem" value="' . htmlspecialchars($linha['id']) . '">';
                    echo '                <input type="submit" value="Reservar" class="btn-ver-comentarios">';
                    echo '            </form>';

                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                } else {
                    echo "<div class='box'><p>Erro ao buscar dados do utilizador com ID: " . htmlspecialchars($linha['id_user']) . "</p></div>";
                }
            }
        } else {
            echo "<p>Nenhuma hospedagem encontrada.</p>";
        }
        ?>
    </div>
    <?= paginacao_html($total, $por_pagina, $pagina) ?>

    <?php include __DIR__ . '/../../views/partials/newsletter.php'; ?>

    <?php include __DIR__ . '/../../views/partials/footer.php'; ?>


    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

</body>

</html>