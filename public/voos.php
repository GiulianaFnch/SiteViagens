<?php
include '../config/valida.php';

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewpoert" content="width=device-width, inicial-scale=1">
    <title>BestWay</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">

    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <!--header-->
    <?php include '../views/partials/header.php'; ?>

    <!--Home section-->
    <section class="home" id="home">
        <div class="home-text">
            <h1>"Explore. Sonhe. " <br>  Viaje!</h1>
            <p>Atendimento exclusivo para garantir que você aproveite o melhor de cada destino.</p>
            <a href="public/login.php" class="home-btn">Vamos</a>
        </div>
    </section>

    <!--footer-->
    <?php include '../views/partials/footer.php'; ?>

</body>

</html>
