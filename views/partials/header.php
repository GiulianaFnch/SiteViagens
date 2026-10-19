<?php require_once __DIR__ . '/../../config/config.php'; ?>
<header>
    <a href="<?= BASE_URL ?>" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
    <div class="bx bx-menu" id="menu-icon"></div>

    <ul class="navbar">
        <li><a href="<?= BASE_URL ?>public/hotels/hotels.php">Hospedagem</a></li>
        <li><a href="<?= BASE_URL ?>public/voos/voos.php">Passagens</a></li>
        <li><a href="<?= BASE_URL ?>public/tours/tours.php">Passeios</a></li>
        <li><a href="<?= BASE_URL ?>public/pacotes/pacotes.php">Pacotes</a></li>
        <li><a href="<?= BASE_URL ?>public/perfil.php"><i class='bx bx-user'></i></a></li>
        <li><a href="<?= BASE_URL ?>public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
    </ul>
</header>