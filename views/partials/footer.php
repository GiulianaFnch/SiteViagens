<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!--footer-->
<section id="contact">
        <div class="footer">
            <div class="main">
                <div class="list">

                    <h4> Minha Conta</h4>
                    <ul>
                    <li><a href="<?= BASE_URL ?>public/perfil.php">Meu Perfil</a></li>
                        <li><a href="<?= BASE_URL ?>public/reservas.php">Minhas Viagens</a></li>
                        <li><a href="<?= BASE_URL ?>public/backend/logout.php">Logout</a></li>
                    </ul>
                </div>

                <div class="list">
                    <h4>Suporte</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>public/contatos/Contatos.html">Contatos</a></li>
                        <li><a href="#">Termos & Condições</a></li>
                        <li><a href="#">Politica de privacidade</a></li>

                    </ul>
                </div>

                <div class="list">
                    <h4>Trabalhe conosco</h4>
                    <ul>
                        <li><a href="<?= BASE_URL ?>public/vendedor/registro_vendedor.php">Como Parceiro Fornecedor</a></li>
                        <li><a href="<?= BASE_URL ?>public/vendedor/admin.php">Acessar ao painel de vendedor</a></li>
                    </ul>
                </div>

                <div class="list">
                    <h4>Redes Sociais</h4>
                    <div class="social">
                        <ul>
                        <li><a href="#"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="#"><i class='bx bxl-instagram'></i></a></li>
                        <a href="#"></a>
                    </ul>
                    </div>
                </div>

            </div>
        </div>

        <div class="end-text">
            <p>© 2024 BestWay. Todos os direitos reservados.</p>
        </div>
    </section>
<!--link to js-->
<script type="text/javascript" src="<?= BASE_URL ?>assets/js/script.js"></script>