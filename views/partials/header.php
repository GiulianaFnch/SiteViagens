<?php
// Cabeçalho comum a todas as páginas PHP do site (as páginas estáticas .html têm uma cópia).
// Antes de incluir, a página pode escolher a aparência:
//   $cabecalho_sobre_imagem = true;  // logo e menu brancos, para páginas com uma imagem grande no topo
//   $cabecalho_escuro = true;        // logo e menu pretos, para páginas de fundo claro
// Sem nenhuma das duas: logo preto e menu branco (páginas com imagem só do lado direito, como o login).
require_once __DIR__ . '/../../config/config.php';

if (!empty($cabecalho_sobre_imagem)) {
    $classe_cabecalho = 'cabecalho-sobre-imagem';
} elseif (!empty($cabecalho_escuro)) {
    $classe_cabecalho = 'cabecalho-escuro';
} else {
    $classe_cabecalho = 'cabecalho-padrao';
}
?>
<header class="<?= $classe_cabecalho ?>">
    <a href="<?= BASE_URL ?>" class="logo">BestWay</a>
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
<!-- menu do telemóvel e cabeçalho fixo ao fazer scroll (defer: corre depois de a página carregar) -->
<script defer src="<?= BASE_URL ?>assets/js/script.js"></script>
