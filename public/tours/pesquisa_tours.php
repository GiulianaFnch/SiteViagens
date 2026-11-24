<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/csrf.php'; // inicia a sessão para o token do carrinho
/*include "../../config/valida.php";*/
include "../../config/liga_bd.php";

// Evitar a injeção de SQL
$stmt = $ligacao->prepare("SELECT * FROM t_artigo WHERE localizacao LIKE ? AND data_inicio <= ? AND data_fim >= ? AND vendido = 0");
$localizacao = "%" . ($_GET['localizacao'] ?? '') . "%";
$data = $_GET['data'] ?? '';
$stmt->bind_param("sss", $localizacao, $data, $data);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisa de Atividades</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&family=Roboto:wght@400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/pesquisa_tours.css">
</head>

<body>
    <?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>
<br><br><br><br><br><br>
    <!--container-->
        <section class="search-bar">
            <form action="pesquisa_tours.php" method="get">
                <!-- Preserva os valores de localização e data nos campos de pesquisa -->
                <input type="text" placeholder="Para onde?" name="localizacao" value="<?php echo htmlspecialchars($_GET['localizacao'] ?? ''); ?>" required>
                <input type="date" placeholder="Selecione as datas" name="data" value="<?php echo htmlspecialchars($_GET['data'] ?? ''); ?>" required>
                <button style="color: grey;" type="submit">Pesquisar</button>
            </form>
            </section>
            <section class="destinos" id="destinos">
        <div class="title">
            <h2>
                Coisas para fazer em 
                <span class="location">
                    <?php
                        // Verifica se a localização foi definida
                        if (isset($_GET['localizacao']) && !empty($_GET['localizacao'])) {
                            echo htmlspecialchars($_GET['localizacao']);
                        } else {
                            echo "a sua localidade"; // Mensagem padrão se a localização não for especificada
                        }
                    ?>
                </span>
            </h2>
        </div>

        
    </section>

    <div class="passeios-grid">
        <?php
        while ($linha = $resultado->fetch_array()) {
            echo '<div class="box">';
            echo '    <div class="thum">';
            echo "        <img src='imagens/" . htmlspecialchars($linha['foto1']) . "' alt='Foto da atividade'>";
            echo '    </div>';
            echo '    <div class="dest-content">';
            echo '        <h3>' . htmlspecialchars($linha['titulo']) . '</h3>';
            echo '        <p>' . htmlspecialchars($linha['descricao']) . '</p>';
            echo '        <p>Localização: ' . htmlspecialchars($linha['localizacao']) . '</p>';
            echo '        <p>Preço: ' . htmlspecialchars($linha['preco']) . ' €</p>';

            echo '        <div class="btn-container">';
            echo '            <form action="detalhes_tours.php" method="post">';
            echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
            echo '                <input type="submit" value="Ver Detalhes" class="btn-ver-mais">';
            echo '            </form>';

            // Abre os detalhes, onde se escolhe a data e o nº de pessoas
            echo '            <form action="detalhes_tours.php" method="post">';
            echo '                <input type="hidden" name="id_artigo" value="' . htmlspecialchars($linha['id']) . '">';
            echo '                <input type="submit" value="Reservar" class="btn-comprar">';
            echo '            </form>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
        $stmt->close();
        ?>
    </div>

    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</body>

</html>

