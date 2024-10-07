<?php
include_once "../../config/valida.php";
include_once "../../config/liga_bd.php";

$id_hospedagem = $_POST['id_hospedagem'];
$sql = "SELECT * FROM t_hospedagem WHERE id=" . $id_hospedagem;

// A variável resultado vai guardar todos os dados da hospedagem
$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
$linha = mysqli_fetch_array($resultado);
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

    <!-- Estilos do Slick Carousel -->
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />

    <style>
        /* Header com fundo branco ao rolar */
        header.scrolled {
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            margin: 0;
        }

        .navbar {
            list-style: none;
            display: flex;
            gap: 20px;
        }

        .navbar a {
            text-decoration: none;
            color: #555;
            font-weight: 500;
        }

        main {
            display: flex;
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .carousel-container {
            width: 50%;
        }

        .carousel-container img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .details-container {
            padding: 20px;
            width: 50%;
        }

        h2 {
            margin-bottom: 10px;
            font-size: 22px;
        }

        h4 {
            margin-bottom: 10px;
            font-size: 10px;
        }

        .price-container {
            margin: 20px 0;
            font-size: 20px;
            color: #333;
        }

        .description {
            font-size: 16px;
            color: #555;
        }

        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .buy-button {
            background-color: #6495ed;
            color: white;
            width: 100%;
            border: none;
            padding: 10px 0;
            cursor: pointer;
            text-align: center;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .buy-button:hover {
            background-color: #4169e1;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <header>
        <a href="/SiteViagens/index.html"
            style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <ul class="navbar">
            <li><a href="/SiteViagens/index.html#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Passeios</a></li>
            <li><a href="#contact">Pacotes</a></li>
        </ul>
    </header>
    <br><br><br><br><br>
    <main>
        <div class="carousel-container">
            <div class="artigo-carousel">
                <div><img src="../../public/hotels/imagens/<?php echo htmlspecialchars($linha['foto1']); ?>"
                        alt="Foto 1"></div>
                <?php if (!empty($linha['foto2'])): ?>
                    <div><img src="../../public/hotels/imagens/<?php echo htmlspecialchars($linha['foto2']); ?>"
                            alt="Foto 2"></div>
                <?php endif; ?>
                <?php if (!empty($linha['foto3'])): ?>
                    <div><img src="../../public/hotels/imagens/<?php echo htmlspecialchars($linha['foto3']); ?>"
                            alt="Foto 3"></div>
                <?php endif; ?>
            </div>
        </div>
        <div class="details-container">
            <h2><?php echo htmlspecialchars($linha['nome']); ?></h2>
            <div class="price-container">A partir de <?php echo htmlspecialchars($linha['preco_diaria']); ?> € por noite
            </div>
            <div class="description"><?php echo htmlspecialchars($linha['descricao']); ?></div>
            <div class="description">Classificação: <?php echo htmlspecialchars($linha['classificacao']); ?> estrelas
            </div>
            <div class="description">Quartos disponíveis: <?php echo htmlspecialchars($linha['n_quartos']); ?></div>
            <div class="button-container">
                <form action="../carrinho/adicionar_ao_carrinho.php" method="post" id="reservaForm">
                    <input type="hidden" name="id_artigo" value="<?php echo htmlspecialchars($linha['id']); ?>">
                    <input type="hidden" name="tipo_item" value="hospedagem">
                    <input type="hidden" name="return_url"
                        value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="total" id="total" value="">
                    <div class="form-group">
                        <label for="data-checkin">Data de início:</label>
                        <input type="date" id="data-checkin" name="data_checkin" required>
                    </div>

                    <div class="form-group">
                        <label for="data-checkout">Data de término:</label>
                        <input type="date" id="data-checkout" name="data_checkout" required>
                    </div>

                    <div class="form-group">
                        <label for="n_quartos">Número de quartos:</label>
                        <select id="n_quartos" name="n_quartos" required>
                            <?php
                            for ($i = 1; $i <= $linha['n_quartos']; $i++) {
                                echo "<option value='$i'>$i</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <input type="submit" value="Adicionar ao carrinho" class="buy-button">
                </form>
            </div>
        </div>
    </main>

    <!-- Scripts do Slick Carousel -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.artigo-carousel').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 1,
                adaptiveHeight: true
            });
        });

        document.getElementById('reservaForm').addEventListener('submit', function (e) {
            const checkin = new Date(document.getElementById('data-checkin').value);
            const checkout = new Date(document.getElementById('data-checkout').value);
            const dailyRate = <?php echo $linha['preco_diaria']; ?>;
            const numQuartos = document.getElementById('n_quartos').value;

            // Calcular a diferença de dias
            const timeDifference = checkout.getTime() - checkin.getTime();
            const days = timeDifference / (1000 * 3600 * 24); // Converter milissegundos em dias

            if (days <= 0) {
                alert("A data de término deve ser posterior à data de início.");
                e.preventDefault();
                return;
            }

            const total = dailyRate * days * numQuartos;

            // Definir o valor do campo oculto 'total'
            document.getElementById('total').value = total;

            // Confirmação para o usuário
            const confirmMessage = `Você está prestes a adicionar ao carrinho:\n${days} dias de hospedagem, com ${numQuartos} quarto(s).\nTotal: €${total.toFixed(2)}.`;
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>