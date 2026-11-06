<?php
include 'valida_vendedor.php'; // só vendedores
$vendedor_titulo = 'Gestão';
$vendedor_menu = 'gestao';
include __DIR__ . '/../../views/partials/vendedor_inicio.php';
?>
                    <a class="option-card" href="gestao_tours.php">
                        <i class="bi bi-map"></i>
                        <h3>Gestão de Tours</h3>
                        <p>Gerencie seus passeios.</p>
                    </a>
                    <a class="option-card" href="gerir_hospedagem.php">
                        <i class="bi bi-house"></i>
                        <h3>Gestão de Hospedagem</h3>
                        <p>Aqui você pode gerenciar suas hospedagens.</p>
                    </a>
                    <a class="option-card" href="pacotes.php">
                        <i class="bi bi-suitcase2"></i>
                        <h3>Gestão de Pacotes</h3>
                        <p>Edite, desative ou apague os seus pacotes de viagem.</p>
                    </a>
<?php include __DIR__ . '/../../views/partials/vendedor_fim.php'; ?>
