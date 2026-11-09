<?php
$conta_titulo = 'Criar conta';
$conta_subtitulo = 'Depois do registo enviamos-lhe um e-mail para confirmar o endereço';
include __DIR__ . '/../views/partials/conta_inicio.php';
require_once __DIR__ . '/../config/contas.php';

// Valores do envio anterior, quando o registo foi recusado (para não ter de escrever tudo de novo)
$antes = $_SESSION['registo_valores'] ?? [];
unset($_SESSION['registo_valores']);
$valor = fn($campo) => htmlspecialchars($antes[$campo] ?? '');
?>
                <form action="backend/criar_conta.php" method="post" enctype="multipart/form-data">
                    <?= csrf_campo() ?>
                    <div class="input-group">
                        <label for="nick">Nick:</label>
                        <input type="text" id="nick" name="nick" class="form-control" required maxlength="<?= NICK_MAX ?>"
                            autocomplete="username" value="<?= $valor('nick') ?>">
                    </div>
                    <div class="input-group">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" class="form-control" required value="<?= $valor('nome') ?>">
                    </div>
                    <div class="input-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" class="form-control" required maxlength="<?= EMAIL_MAX ?>"
                            value="<?= $valor('email') ?>">
                    </div>
                    <div class="input-group">
                        <label for="data_nasc">Data de nascimento:</label>
                        <input type="date" id="data_nasc" name="data_nasc" class="form-control" required value="<?= $valor('data_nasc') ?>">
                    </div>
                    <div class="input-group">
                        <label for="pass">Palavra-passe (mínimo <?= SENHA_MIN ?> caracteres):</label>
                        <input type="password" id="pass" name="pass" class="form-control" required minlength="<?= SENHA_MIN ?>"
                            autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <label for="pass2">Repetir a palavra-passe:</label>
                        <input type="password" id="pass2" name="pass2" class="form-control" required minlength="<?= SENHA_MIN ?>"
                            autocomplete="new-password">
                    </div>
                    <div class="input-group">
                        <label for="ficheiro">Foto:</label>
                        <input type="file" id="ficheiro" name="ficheiro" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="home-btn">Registrar</button>
                </form>
<?php include __DIR__ . '/../views/partials/conta_fim.php'; ?>
