<?php
include '../config/valida.php';
include '../config/liga_bd.php';

$user_id = $_SESSION['id'];

// Ajuste a consulta SQL para buscar informações de acordo com o tipo_reserva
$sql = "SELECT f.tipo_reserva, 
               CASE 
                   WHEN f.tipo_reserva = 'atividade' THEN a.titulo 
                   WHEN f.tipo_reserva = 'hospedagem' THEN h.nome 
               END AS titulo, 
               CASE 
                   WHEN f.tipo_reserva = 'atividade' THEN a.foto1 
                   WHEN f.tipo_reserva = 'hospedagem' THEN h.foto1 
               END AS foto1, 
               CASE 
                   WHEN f.tipo_reserva = 'atividade' THEN a.preco 
                   WHEN f.tipo_reserva = 'hospedagem' THEN h.preco_diaria 
               END AS preco, 
               f.id_artigo 
        FROM t_favoritos f
        LEFT JOIN t_artigo a ON f.id_artigo = a.id AND f.tipo_reserva = 'atividade'
        LEFT JOIN t_hospedagem h ON f.id_artigo = h.id AND f.tipo_reserva = 'hospedagem'
        WHERE f.id_user = '$user_id'";

$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

mysqli_close($ligacao);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favoritos - BestWay</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
            display: fixed;
        }

        .navbar a {
            color: var(--bg-color);
            font-size: var(--p-font);
            font-weight: 500;
            padding: 10px 22px;
            border-radius: 4px;
           
        }

        .navbar a:hover {
            background: var(--bg-color);
            color: var(--text-color);
            box-shadow: 5px 10px 30px rgb(85 85 85 / 20%);
            border-radius: 4px;
        }
        
        img {
            width: 30%; /* A imagem ocupa toda a largura do seu container */
            height: auto; /* A altura é ajustada automaticamente */
            border-radius: 8px; /* Bordas arredondadas para as imagens */
        }


        .image-container {
    display: flex;
    flex-wrap: wrap; 
    gap: 10px; 
    width: 100%;
    justify-content: space-between; 
}

.image-container img {
    width: 35%; /* As imagens ocuparão 48% da largura do contêiner, deixando espaço entre elas */
    height: auto;
    border-radius: 8px; /* Bordas arredondadas para as imagens */
}
         /* Layout do container principal */
         .container {
            display: flex;
            justify-content: space-between;
            padding-top: 100px; /* Para não cobrir o conteúdo com o header fixo */
        }

        .menu-container {
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
            width: 250px; /* Define uma largura fixa para a sidebar */
            padding: 20px;
        }

        .menu {
            display: flex;
            flex-direction: column;
        }

        .menu-item {
            color: #007AFF;
            padding: 10px;
            margin: 5px 0;
            text-decoration: none;
            font-size: 18px;
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out;
        }

        .menu-item:hover {
            background-color: #f0f0f0;
            transform: translateX(8px);
        }

        .menu-item i {
            margin-right: 8px;
            font-size: 20px;
        }

        .menu-item.active {
            background-color: #dfe4ea;
            font-weight: bold;
        }

        .menu-item:active {
            transform: scale(0.98);
       
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card .tab-content {
            border-top: 1px solid #e0e0e0;
        }

        .main-content {
            flex: 1; /* O conteúdo principal ocupa o espaço restante */
            padding: 20px;
        }

        .titulo-pequeno {
    font-size: 120%; 
    line-height: 1.2; 
}
    </style>
</head>
<body>

<header>
        <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="/SiteViagens/public/hotels/hotels.php" style="color: black;">Hospedagem</a></li>
            <li><a href="#package" style="color: black;">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php" style="color: black;">Tours</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black !important;"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <br><br><br>

    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                <a class="menu-item" href="perfil.php#account-general"><i class="bi bi-person-circle"></i> Editar perfil</a>
                <a class="menu-item" href="reservas.php"><i class="bi bi-clipboard2"></i> Reservas</a>
                <a class="menu-item" href="favoritos.php"><i class="bi bi-heart"></i> Favoritos</a>
                <a class="menu-item" href="chat2.php"><i class="bi bi-chat-dots"></i> Chat</a>
                <a class="menu-item" href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a>
            </nav>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content">
            <div class="card shadow-sm rounded-lg">
                <div class="row no-gutters">
                    <div class="tab-content p-4">
                        <div class="tab-pane fade active show" id="account-general">
                            
                       
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <ul>
                <?php while ($linha = mysqli_fetch_array($resultado)): ?>
                    <li>
                        <h2 class="titulo-pequeno"><?php echo htmlspecialchars($linha['titulo']); ?></h2>

                        <div class="image-container">
                        <?php if ($linha['tipo_reserva'] == 'atividade'): ?>
                            <img src="tours/imagens/<?php echo htmlspecialchars($linha['foto1']); ?>" alt="Imagem da atividade">
                        <?php elseif ($linha['tipo_reserva'] == 'hospedagem'): ?>
                            <img src="hotels/imagens/<?php echo htmlspecialchars($linha['foto1']); ?>" alt="Imagem da hospedagem">
                            
                        <?php endif; ?>
                        <p>Preço: € <?php echo number_format($linha['preco'], 2, ',', '.'); ?></p>
                        

                        <form action="backend/remover_favorito.php" method="post">
                            <input type="hidden" name="id_artigo" value="<?php echo htmlspecialchars($linha['id_artigo']); ?>">
                            <input type="submit" value="Remover">
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não tem artigos favoritos.</p>
        <?php endif; ?>
        
    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>


     <!--footer-->
     <?php include '../views/partials/footer.php' ?>

</body>
</html>
