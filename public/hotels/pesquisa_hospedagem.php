<?php
require_once __DIR__ . '/../../config/config.php';
/*include "../../config/valida.php";*/
include "../../config/liga_bd.php";

// Evitar a injeção de SQL
$stmt = $ligacao->prepare("SELECT * FROM t_hospedagem WHERE localizacao LIKE ? AND data_inicio <= ? AND data_fim >= ? AND n_quartos > 0");
$localizacao = "%" . ($_GET['localizacao'] ?? '') . "%";
$data_inicio = $_GET['data_inicio'] ?? '';
$data_fim = $_GET['data_fim'] ?? '';
$stmt->bind_param("sss", $localizacao, $data_inicio, $data_fim);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesquisa de Hospedagem</title>
    <link rel="stylesheet" type="text/css" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600;900&family=Roboto:wght@400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/pesquisa_hospedagem.css">
</head>

<body>
    <?php $cabecalho_escuro = true; include __DIR__ . '/../../views/partials/header.php'; ?>
    <br><br><br><br><br><br>
    <!--container-->
    <section class="search-bar">
        <form action="pesquisa_hospedagem.php" method="get">
            <!-- Preserva os valores de localização e data nos campos de pesquisa -->
            <input type="text" placeholder="Para onde?" name="localizacao" value="<?php echo htmlspecialchars($_GET['localizacao'] ?? ''); ?>" required>
            <input type="date" placeholder="Data de Início" name="data_inicio" value="<?php echo htmlspecialchars($_GET['data_inicio'] ?? ''); ?>" required>
            <input type="date" placeholder="Data de Fim" name="data_fim" value="<?php echo htmlspecialchars($_GET['data_fim'] ?? ''); ?>" required>
            <button style="color: grey;" type="submit">Pesquisar</button>
        </form>
    </section>
    <section class="destinos" id="destinos">
        <div class="title">
            <h2>
                Hospedagens em 
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

    <div class="hospedagem-grid">
        <?php
        while ($linha = $resultado->fetch_array()) {
            echo '<div class="box">';
            echo '    <div class="thum">';
            echo "        <img src='imagens/" . htmlspecialchars($linha['foto1']) . "' alt='Foto da hospedagem'>";
            echo '    </div>';
            echo '    <div class="dest-content">';
            echo '        <h3>' . htmlspecialchars($linha['nome']) . '</h3>';
            echo '        <p>' . htmlspecialchars($linha['descricao']) . '</p>';
            echo '        <p>Localização: ' . htmlspecialchars($linha['localizacao']) . '</p>';
            echo '        <p>Preço por diária: ' . htmlspecialchars($linha['preco_diaria']) . ' €</p>';
            echo '        <p>Quartos disponíveis: ' . htmlspecialchars($linha['n_quartos']) . '</p>';

            echo '        <div class="btn-container">';
            echo '            <form action="detalhes_hospedagem.php" method="post">';
            echo '                <input type="hidden" name="id_hospedagem" value="' . htmlspecialchars($linha['id']) . '">';
            echo '                <input type="submit" value="Ver Detalhes" class="btn-ver-mais">';
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