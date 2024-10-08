<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Vendedor</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    </script>
    <style>

        /* Header */
        header {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 18%;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }

        header a {
            font-size: 35px;
            font-weight: 600;
            color: black;
        }

        header ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        header ul li {
            margin-right: 20px;
        }

        header ul li a {
            color: black;
            font-weight: 500;
            text-decoration: none;
        }

        /* Sidebar Menu */
        .menu-container {
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
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
        
        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .tab-pane {
            padding: 20px;
        }


        /* Main Content */

        .option-card {
            flex: 1;
            margin: 0 10px;
            padding: 30px 20px;
            text-align: center;
            border-radius: 12px;
            background-color: #f8f9fa;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .option-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .option-card h3 {
            font-size: 22px;
            font-weight: 600;
            color: #004AAD;
            margin-top: 15px;
        }

        .option-card i {
            font-size: 48px;
            color: #004AAD;
        }

        .option-card p {
            font-size: 16px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .option-card {
                margin: 10px 0;
            }
        }
    </style>
</head>


<body>
<!-- Header -->
<header>

<a href="../../index.html"
    style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
<div class="bx bx-menu" id="menu-icon"></div>

<ul class="navbar">
    <li><a href="#home" style="color: black;">Hospedagem</a></li>
    <li><a href="#package" style="color: black;">Passagens</a></li>
    <li><a href="#destination" style="color: black; ">Tours</a></li>
    <li><a href="#contact" style="color: black;">Pacotes</a></li>
</ul>
</header>

<br><br><br><br><br><br>
    <!-- Sidebar -->
    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <a class="menu-item" style="color: #3A506B"><strong>Painel de Vendedor</strong></a>
                        <a class="menu-item" href="admin.php" onclick="showContent('profile');"><i class="bi bi-person-circle"></i> Editar Perfil</a>
                        <a class="menu-item" href="vender_opcoes.php"><i class="bi bi-bag"></i> Vender</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i> Reservas</a>
                        <a class="menu-item" href="gestao_opcoes.php"><i class="bi bi-train-freight-front"></i> Gestão</a>
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

    <!-- Main Content -->
    <div class="col-md-9">
   <br><br>
        <div class="option-card" onclick="window.location.href='gestao_tours.php'">
            <i class="bi bi-map"></i>
            <h3>Gestão de Tours</h3>
            <p>vou escrever algo aqui.</p>
        </div>
        <br><br>
        <div class="option-card" onclick="window.location.href='gerir_hospedagem.php'">
            <i class="bi bi-house"></i>
            <h3>Gestão de Hospedagem</h3>
            <p>vou escrever algo aqui.</p>
        </div>
        <br><br>
    </div>
    </div>
            </div>
        </div>
    </div>

    <br><br><br>

    <?php include '../../views/partials/footer.php' ?>

</body>

</html>
