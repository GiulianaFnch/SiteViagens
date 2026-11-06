<?php
include 'valida_vendedor.php'; // só vendedores
$vendedor_titulo = 'Vender';
$vendedor_menu = 'vender';
include __DIR__ . '/../../views/partials/vendedor_inicio.php';
?>
                    <a class="option-card" href="vender_tours.php">
                        <i class="bi bi-map"></i>
                        <h3>Vender Tours</h3>
                        <p>Crie seus passeios e experiências turísticas.</p>
                    </a>
                    <a class="option-card" href="vender_hospedagem.php">
                        <i class="bi bi-house"></i>
                        <h3>Vender Hospedagem</h3>
                        <p>Anuncie acomodações como hotéis, resorts, hostels e mais!</p>
                    </a>
                    <a class="option-card" href="pacotes.php?novo=1#formulario">
                        <i class="bi bi-suitcase2"></i>
                        <h3>Vender Pacotes</h3>
                        <p>Junte viagem, estadia e passeios num pacote com preço por pessoa.</p>
                    </a>
<?php include __DIR__ . '/../../views/partials/vendedor_fim.php'; ?>
