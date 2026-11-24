<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!-- Inscrição na newsletter (public/backend/newsletter.php) -->
<section class="newsletter">
    <div class="news-text">
        <h2>Inscreva-se para receber nossas ofertas</h2>
        <p>Você receberá e-mails promocionais da BestWay. Para mais informações, consulte a
            <a href="<?= BASE_URL ?>public/contatos/privacidade.php">Política de privacidade</a>.</p>
    </div>
    <div class="send">
        <form action="<?= BASE_URL ?>public/backend/newsletter.php" method="post">
            <input type="email" name="email" placeholder="Insira seu e-mail aqui" required>
            <input type="submit" value="Quero recebê-las!">
        </form>
    </div>
</section>
