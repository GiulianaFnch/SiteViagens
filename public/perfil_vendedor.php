<?php
include_once "../config/valida.php";
include_once "../config/liga_bd.php";

// Verifica se o user_id do vendedor foi passado na URL
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id']; // Obtém o ID do vendedor da URL

    // Consulta para buscar informações do vendedor na tabela t_user
    $sql = "SELECT * FROM t_user WHERE id = ?"; 
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se o vendedor foi encontrado
    if ($linha = $resultado->fetch_assoc()) {
        // Agora $linha contém os dados do vendedor
    } else {
        // Se não encontrar, define $linha como null
        $linha = null;
    }
} else {
    // Se user_id não foi passado, define $linha como null
    $linha = null;
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Vendedor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        header {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 30px 18%;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar {
            display: flex;
        }

        .navbar a {
            color: black; /* Define a cor padrão dos links */
            padding: 10px 22px;
        }

        .vendedor-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para destacar o bloco */
            max-width: 500px; /* Largura máxima da div */
            margin: 0 auto; /* Centraliza horizontalmente */
            font-family: Arial, sans-serif;
        }

        .vendedor-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px; /* Espaçamento abaixo da foto */
            object-fit: cover; /* Garante que a imagem seja cortada proporcionalmente */
        }

        .vendedor-info .info-row {
            display: flex;
            justify-content: space-between; /* Espaçamento entre os rótulos e valores */
            margin: 10px 0; /* Espaçamento entre as linhas */
        }

        .vendedor-info .label {
            font-weight: bold; /* Destaca os rótulos */
            color: #333;
            flex-basis: 40%; /* Largura fixa para os rótulos */
            text-align: right; /* Alinha o texto à direita */
            margin-right: 15px; /* Espaçamento à direita */
        }

        .vendedor-info .value {
            flex-basis: 60%; /* Largura fixa para os valores */
            color: #555; /* Cor do texto dos valores */
        }

        .vendedor-info a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .vendedor-info a:hover {
            background-color: #0056b3;
        }

        /* Estilo responsivo para telas menores */
        @media (max-width: 768px) {
            .vendedor-info {
                max-width: 100%;
                padding: 15px;
            }
            
            .vendedor-info .info-row {
                flex-direction: column; /* Muda a direção em telas menores */
                align-items: flex-start; /* Alinha à esquerda */
            }

            .vendedor-info .label {
                text-align: left; /* Alinha os rótulos à esquerda */
                margin-right: 0; /* Remove margem à direita */
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="/SiteViagens/public/hotels/hotels.php">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Tours</a></li>
            <li><a href="#contact">Pacotes</a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <br><br><br><br><br><br>

    <div class="vendedor-info">
        <?php if ($linha): ?>
            <img src="<?php echo $linha['foto'] ? '../assets/images/pics/' . htmlspecialchars($linha['foto']) : 'https://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="Foto do Vendedor">
            <div class="info-container">
                <div class="info-row">
                    <span class="label">Nome da Marca:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['nome_marca']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Nick:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['nick']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Biografia:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['biografia']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Outras informações:</span>
                    <span class="value">Outros detalhes aqui...</span>
                </div>
            </div>
        <?php else: ?>
            <p>Vendedor não encontrado ou não é um vendedor.</p>
        <?php endif; ?>
        
        <a href="reservas.php" class="btn btn-primary">Voltar</a> <!-- Botão para voltar à página anterior -->
    </div>
</body>
</html>
