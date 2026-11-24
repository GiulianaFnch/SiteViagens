<?php
$pagina_titulo = 'Contatos | BestWay';
$pagina_css = ['contatos.css'];
$cabecalho_escuro = true;
include __DIR__ . '/../../views/partials/pagina_inicio.php';
?>
    <main>
        <section class="container">
        <section class="team-section">
            <h2>Entre em contato conosco!</h2>
            <br><br><br>
            <div class="team-cards">
                <div class="team-member">
                    <img src="GABRIELLE.jfif" alt="Gabrielle Reis">
                    <h3>Gabrielle Reis</h3>
                    <p>Front-End Developer</p>
                    <div class="contacts">
                        <a href="https://linkedin.com/in/nome1" target="_blank">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <a href="https://github.com/reiissgabi" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="mailto:nome1@bestway.com">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>

                <div class="team-member">
                    <img src="GUILIANA.jfif" alt="Guiliana Finochio">
                    <h3>Guiliana Finochio</h3>
                    <p>Back-End Developer</p>
                    <div class="contacts">
                        <a href="https://linkedin.com/in/nome2" target="_blank">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <a href="https://github.com/GiulianaFnch" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="mailto:nome2@bestway.com">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>

                <div class="team-member">
                    <img src="GUILHERME.jfif" alt="Guilherme Silva">
                    <h3>Guilherme Silva</h3>
                    <p>UI/IX Designer</p>
                    <div class="contacts">
                        <a href="https://linkedin.com/in/nome3" target="_blank">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <a href="https://github.com/Thats-GuilhermeDourado" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="mailto:nome3@bestway.com">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>

                <div class="team-member">
                    <img src="KAROLINE.jfif" alt="Karoline Moura">
                    <h3>Karoline Moura</h3>
                    <p>Web Developer and MSC in Project Management</p>
                    <div class="contacts">
                        <a href="https://linkedin.com/in/nome4" target="_blank">
                            <i class="fab fa-linkedin"></i> LinkedIn
                        </a>
                        <a href="https://github.com/karoolinemoura" target="_blank">
                            <i class="fab fa-github"></i> GitHub
                        </a>
                        <a href="mailto:nome4@bestway.com">
                            <i class="fas fa-envelope"></i> Email
                        </a>
                    </div>
                </div>
            </div>
        </section>
        </section>
    </main>

<?php include __DIR__ . '/../../views/partials/pagina_fim.php'; ?>
