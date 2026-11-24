<?php
require_once __DIR__ . '/../../config/config.php';
/*include '../../config/valida.php';*/
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

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/listar_tours.css">
</head>

<body>
    <!--header-->
    <?php $cabecalho_sobre_imagem = true; include __DIR__ . '/../../views/partials/header.php'; ?>
    <!--container-->
    <section class="listar-tours" id="home">
        <div class="home-text2">
            <h1>Atividades <br> Mais <br> Procuradas.</h1>
            <p style="color: aliceblue;">"Encontre destinos e experiências que combinam com você!"</p>
        </div>
    </section>
<br><br>
    <!-- Formulário de seleção de categorias -->
    <?php $categoria = (int) ($_GET['categoria'] ?? $_POST['categoria'] ?? 0); ?>
    <center><form action="" method="get"><h1>
        Encontre seu destino por categorias:</h1>
       <center> <select name="categoria" id="categoria" onchange="this.form.submit();">
            <?php
            // Conecte-se ao banco de dados e busque as categorias
            $sql = "SELECT * FROM t_categoria";
            $resultado = mysqli_query($ligacao, $sql);

            // Adiciona a opção "Todos" com valor 0
            echo "<option value='0'>Todos</option>";

            // Preenche as opções do select com as categorias vindas do banco de dados
            while ($linha = mysqli_fetch_array($resultado)) {
                // Verifica se a categoria selecionada é a atual
                if ($categoria == $linha['id'])
                    echo "<option value='" . (int) $linha['id'] . "' selected>" . htmlspecialchars($linha['categoria']) . "</option>";
                else
                    echo "<option value='" . (int) $linha['id'] . "'>" . htmlspecialchars($linha['categoria']) . "</option>";
            }
            ?>
        </select></center>
    </form></center>
<br><br>
    <!-- Lista de passeios -->
    <div class="passeios-grid">
        <?php
        // Paginação (?pagina=N); a categoria 0 ("Todos") mostra todas
        $por_pagina = 12;
        $pagina = pagina_atual();
        $offset = ($pagina - 1) * $por_pagina;

        $stmt = $ligacao->prepare("SELECT COUNT(*) FROM t_artigo a JOIN t_user u ON u.id = a.id_user
                                   WHERE a.vendido = 0 AND (? = 0 OR a.cat = ?)");
        $stmt->bind_param("ii", $categoria, $categoria);
        $stmt->execute();
        $total = (int) $stmt->get_result()->fetch_row()[0];

        // Artigos não vendidos com o nick e email do vendedor e a média das avaliações, numa só consulta
        $stmt = $ligacao->prepare("SELECT a.*, u.nick, u.email, av.media_avaliacoes, COALESCE(av.total_avaliacoes, 0) AS total_avaliacoes
                                   FROM t_artigo a
                                   JOIN t_user u ON u.id = a.id_user
                                   LEFT JOIN (SELECT item_id, AVG(nota) AS media_avaliacoes, COUNT(*) AS total_avaliacoes
                                              FROM t_avaliacoes WHERE tipo_item = 'atividade' GROUP BY item_id) av ON av.item_id = a.id
                                   WHERE a.vendido = 0 AND (? = 0 OR a.cat = ?)
                                   ORDER BY a.id
                                   LIMIT ? OFFSET ?");
        $stmt->bind_param("iiii", $categoria, $categoria, $por_pagina, $offset);
        $stmt->execute();
        $resultado = $stmt->get_result();

        // Verifica se existem passeios disponíveis
        if (mysqli_num_rows($resultado) > 0) {
            // Itera sobre os resultados e exibe cada passeio
            while ($linha = mysqli_fetch_array($resultado)) {
                $linha_user = $linha; // nick e email do vendedor já vêm na consulta

                if ($linha_user) {
                    echo '<div class="box">';
                    echo '    <div class="thum">';
                    echo "        <img src='imagens/" . htmlspecialchars($linha['foto1']) . "' alt='Foto do passeio'>";
                    echo '    </div>';
                    echo '    <div class="dest-content">';
                    echo '        <div class="location">';
                    echo "            <h3>" . htmlspecialchars($linha['titulo']) . "</h3>";
                    echo "            <h5>" . htmlspecialchars($linha['descricao']) . "</h5>";
                    echo '        </div>';
                    echo '        <div class="stars">';
                    echo '            <span style="color: #f5a623;">' . avaliacao_texto($linha['media_avaliacoes'] === null ? null : (float) $linha['media_avaliacoes'], (int) $linha['total_avaliacoes']) . '</span>';
                    echo "            <h4>A partir de " . htmlspecialchars($linha['preco']) . " € por pessoa</h4>";
                    echo '        </div>';
                    echo '        <div class="btn-container">';

                    // Botão "Comprar": abre os detalhes, onde se escolhe a data e o nº de pessoas
                    echo '            <form action="detalhes_tours.php" method="post">';
                    echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
                    echo '                <input type="submit" value="Comprar" class="btn-comprar">';
                    echo '            </form>';

                    // Botão "Ver mais" que redireciona para a página detalhes_tour.php
                    echo '            <form action="detalhes_tours.php" method="post">';
                    echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
                    echo '                <input type="submit" value="Ver Mais" class="btn-ver-comentarios">';
                    echo '            </form>';

                    echo '        </div>';
                    echo '    </div>';
                    echo '</div>';
                } else {
                    echo "<div class='box'><p>Erro ao buscar dados do utilizador com ID: " . htmlspecialchars($linha['id_user']) . "</p></div>";
                }
            }
        } else {
            echo "<p>Nenhum passeio encontrado.</p>";
        }
        ?>
    </div>
    <?= paginacao_html($total, $por_pagina, $pagina) ?>

    </div>

    </div>
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


    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

</body>

</html>