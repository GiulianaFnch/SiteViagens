<?php
$conta_titulo = 'Login';
$conta_subtitulo = 'Entre com o seu nick e a sua palavra-passe';
include __DIR__ . '/../views/partials/conta_inicio.php';
?>
                <form action="backend/autenticar.php" method="post">
                    <?= csrf_campo() ?>
                    <div class="input-group">
                        <label for="nick">Nick:</label>
                        <input type="text" id="nick" name="nick" class="form-control" required autocomplete="username">
                    </div>
                    <div class="input-group">
                        <label for="pass">Palavra-passe:</label>
                        <input type="password" id="pass" name="pass" class="form-control" required autocomplete="current-password">
                    </div>
                    <p><a href="esqueci_senha.php">Esqueceu a palavra-passe?</a></p>
                    <button type="submit" class="home-btn">Login</button>
                    <input type="button" value="Registrar" class="btn" onclick="window.open('registro.php', '_self')">
                </form>
<?php include __DIR__ . '/../views/partials/conta_fim.php'; ?>
