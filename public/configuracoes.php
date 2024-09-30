<?php
include '../config/valida.php';
include '../config/liga_bd.php';

$activeMenu = 'settings';
$mensagem_sucesso = '';
$mensagem_erro = '';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // Usando prepared statements para segurança
    $stmt = $ligacao->prepare("SELECT nome, pass FROM t_user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $linha = $resultado->fetch_assoc();
    } else {
        $linha['nome'] = 'Usuário';
        $linha['notificacoes_ofertas'] = 1; // Valor padrão
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['atualizar_senha'])) {
        $senha_atual = $_POST['senha_atual'];
        $nova_senha = $_POST['nova_senha'];
        $confirmar_senha = $_POST['confirmar_senha'];

        // Verifica a senha atual
        $stmt = $ligacao->prepare("SELECT pass FROM t_user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $linha = $resultado->fetch_assoc();

        if (password_verify($senha_atual, $linha['pass'])) {
            if ($nova_senha == $confirmar_senha) {
                $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt = $ligacao->prepare("UPDATE t_user SET pass = ? WHERE id = ?");
                $stmt->bind_param("si", $hash_senha, $id);
                if ($stmt->execute()) {
                    $mensagem_sucesso = "Senha alterada com sucesso!";
                } else {
                    $mensagem_erro = "Erro ao atualizar a senha.";
                }
                $stmt->close();
            } else {
                $mensagem_erro = "As novas senhas não coincidem.";
            }
        } else {
            $mensagem_erro = "Senha atual incorreta.";
        }
    } elseif (isset($_POST['toggle_notificacoes_ofertas'])) {
        $notificacoes_ofertas = $_POST['notificacoes_ofertas'] ? 1 : 0;

        // Atualiza a configuração de notificações
        $stmt = $ligacao->prepare("UPDATE t_user SET notificacoes_ofertas = ? WHERE id = ?");
        $stmt->bind_param("ii", $notificacoes_ofertas, $id);
        if ($stmt->execute()) {
            echo "Configuração de notificações atualizada.";
        } else {
            echo "Erro ao atualizar configurações.";
        }
        $stmt->close();
        exit(); // Saia após processar o AJAX
    }
}

if (isset($_POST['logout'])) {
    // Destroi a sessão atual
    session_destroy();

    // Redireciona para a página de login (index.html)
    header('Location: ../index.html');
    exit();
}

?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <script>
        function togglePasswordForm() {
            var form = document.getElementById('form-atualizar-senha');
            var settingsItems = document.querySelectorAll('.settings-item');
            var voltarIcon = document.getElementById('voltar-icon');

            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
                voltarIcon.style.display = 'inline-block';
                settingsItems.forEach(function (item) {
                    item.style.display = 'none';
                });
            } else {
                form.style.display = 'none';
                voltarIcon.style.display = 'none';
                settingsItems.forEach(function (item) {
                    item.style.display = 'flex';
                });
            }
        }

        function toggleNotification() {
            var icon = document.getElementById('notificacoes-ofertas-icon');
            var currentStatus = icon.classList.contains('bi-file-earmark-check-fill');
            var newStatus = currentStatus ? 0 : 1;

            icon.classList.toggle('bi-file-earmark');
            icon.classList.toggle('bi-file-earmark-check-fill');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'configuracoes.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log('Configuração de notificações atualizada.');
                }
            };
            xhr.send('toggle_notificacoes_ofertas=1&notificacoes_ofertas=' + newStatus);
        }
    </script>

<style>

header{
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        z-index:100;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 30px 18%;
        background-color: white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar {
    display: flex;
}

.navbar a {
    color: black; /* Define a cor padrão dos links */
}


        body {
            font-family: 'San Francisco', 'Helvetica Neue', Arial, sans-serif;
            background-image: url('../assets/images/fundo.png');
            background-size: cover;
            background-position: center;
            margin-top: 30px;
            color: #333;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .menu-container {
            background-color: #f8f9fa;
            border-right: 1px solid #e0e0e0;
        }

        .menu {
            display: flex;
            flex-direction: column;
        }

        .menu-item {
            color: #007AFF;
            padding: 10px;
            margin: 5px 0;
            text-decoration: none;
            font-size: 18px;
            border-radius: 10px;
            transition: background-color 0.3s ease, transform 0.2s ease-in-out;
        }

        .menu-item:hover {
            background-color: #f0f0f0;
            transform: translateX(8px);
        }

        .menu-item i {
            margin-right: 8px;
            font-size: 20px;
        }

        .menu-item.active {
            background-color: #dfe4ea;
            font-weight: bold;
        }

        .menu-item:active {
            transform: scale(0.98);
            /* Efeito de clique estilo iOS */
        }

        .settings-header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .settings-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #d1d1d6;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .settings-item:hover {
            background-color: #f0f0f5;
            cursor: pointer;
        }

        .settings-item i {
            font-size: 20px;
            color: #007AFF;
            margin-right: 15px;
        }

        .settings-item .item-label {
            font-size: 17px;
        }

        .settings-item .item-right {
            display: flex;
            align-items: center;
        }

        .settings-item .item-right i {
            color: #c7c7cc;
        }

        #form-atualizar-senha {
            display: none;
            transition: opacity 0.3s ease;
        }

        #voltar-icon {
            display: none;
            font-size: 24px;
            color: #007AFF;
            cursor: pointer;
            margin-bottom: 15px;
            transition: transform 0.3s ease;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .menu-container {
                border-right: none;
                border-bottom: 1px solid #e0e0e0;
            }

            .col-md-3 {
                width: 100%;
                padding: 0;
            }

            .settings-container {
                margin: 10px;
            }
        }

    </style>
</head>

<body>

<!--header-->
    
<header>
 
        <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="#home" style="color: black;" >Hospedagem</a></li>
            <li><a href="#package" style="color: black;" >Passagens</a></li>
            <li><a href="#destination" style="color: black; ">Tours</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
        </ul>
        </header>
        
    <br><br><br><br><br><br>

    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <?php echo htmlspecialchars($linha['nome']); ?>
                        <a class="menu-item" href="perfil.php"><i class="bi bi-person-circle"></i> Editar perfil</a>
                        <a class="menu-item" href="/SiteViagens-main/index.html"><i class="bi bi-house-door"></i> Página Inicial</a>
                        <a class="menu-item" href="reservas.php"><i class="bi bi-clipboard2"></i> Reservas</a>
                        <a class="menu-item" href="#favorites"><i class="bi bi-heart"></i> Favoritos</a>
                        <a class="menu-item" href="#chat"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item active" href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9 p-3">
                    <div class="settings-container">
                        <div id="voltar-icon" onclick="togglePasswordForm()">
                            <i class="bi bi-chevron-compact-left"></i> Voltar
                        </div>

                        
                        <div class="settings-section">
                            <div class="settings-item" id="location-toggle" onclick="toggleLocationService()">
                                <div class="item-left">
                                    <i class="bi bi-geo-alt"></i>
                                    <span class="item-label">Serviços de Localização</span>
                                </div>
                                <div class="item-right">
                                    <!-- Ícone de Toggle Inicial -->
                                    <i id="location-icon" class="bi bi-toggle-off"></i>
                                </div>
                            </div>
                        </div>

                       



                        <div class="settings-item">
                            <div class="item-left">
                                <i class="bi bi-credit-card"></i>
                                <span class="item-label">Informações de Pagamento</span>
                            </div>
                            <div class="item-right">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="item-left">
                                <i class="bi bi-calendar-event"></i>
                                <span class="item-label">Lembretes de Viagem</span>
                            </div>
                            <div class="item-right">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                        <div class="settings-item">
                            <div class="item-left">
                                <i class="bi bi-globe"></i>
                                <span class="item-label">Idioma e Moeda</span>
                            </div>
                            <div class="item-right">
                                <i class="bi bi-chevron-right"></i>
                            </div>
                        </div>

                    

                        <div class="settings-item" onclick="togglePasswordForm()">
                            <div class="item-left">
                                <i class="bi bi-shield-lock"></i>
                                <span class="item-label">Atualizar Senha</span>
                                </div>
                            <div class="item-right">
                            <i class="bi bi-chevron-right" onclick="togglePasswordForm()"></i>
                            </div>
                        </div>


                        <div class="settings-item" id="logout-item">
    <div class="item-left">
        <i class="bi bi-box-arrow-right"></i>
        <span class="item-label">Logout</span>
    </div>
    <div class="item-right">
        <form method="POST" style="display: inline;">
            <button type="submit" name="logout" class="btn btn-link" style="color: #007AFF; padding: 0; font-size: 18px;">
             <i class="bi bi-chevron-right"></i>
            </button>
        </form>
    </div>
</div>
                     

                    <div id="form-atualizar-senha">
                        <form method="post" action="configuracoes.php">
                            <div class="form-group">
                                <label for="senha_atual">Senha Atual</label>
                                <input type="password" id="senha_atual" name="senha_atual" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="nova_senha">Nova Senha</label>
                                <input type="password" id="nova_senha" name="nova_senha" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmar_senha">Confirmar Nova Senha</label>
                                <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control" required>
                            </div>
                            <button type="submit" name="atualizar_senha" class="btn btn-primary">Atualizar Senha</button>
                        </form>
                        <i id="voltar-icon" class="bi bi-arrow-left-circle" onclick="togglePasswordForm()"></i>
                    </div>

                    <?php if ($mensagem_sucesso): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo htmlspecialchars($mensagem_sucesso); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($mensagem_erro): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($mensagem_erro); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </div>
    
    <?php include '../views/partials/footer.php' ?>
    



                                <script>

                            function toggleLocationService() {
                                var icon = document.getElementById('location-icon');
                                var isEnabled = icon.classList.contains('bi-toggle-on');

                                // Alterna o ícone
                                if (isEnabled) {
                                    icon.classList.remove('bi-toggle-on');
                                    icon.classList.add('bi-toggle-off');
                                } else {
                                    icon.classList.remove('bi-toggle-off');
                                    icon.classList.add('bi-toggle-on');
                                }

                                // Envia uma solicitação AJAX para atualizar o estado no servidor
                                var xhr = new XMLHttpRequest();
                                xhr.open('POST', 'configuracoes.php', true);
                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                xhr.onreadystatechange = function () {
                                    if (xhr.readyState === 4) {
                                        if (xhr.status === 200) {
                                            console.log('Serviço de localização atualizado.');
                                            console.log(xhr.responseText); // Para depuração
                                        } else {
                                            console.error('Erro na solicitação AJAX:', xhr.statusText);
                                        }
                                    }
                                };
                                xhr.send('toggle_location_service=1&location_service=' + (isEnabled ? 0 : 1));
                            }
                        </script>

    <script>
function toggleNotification() {
    var icon = document.getElementById('notificacoes-ofertas-icon');
    var currentStatus = icon.classList.contains('bi-file-earmark-check-fill');
    var newStatus = currentStatus ? 0 : 1;

    icon.classList.toggle('bi-file-earmark');
    icon.classList.toggle('bi-file-earmark-check-fill');

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'configuracoes.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText); // Adiciona log para depuração
        }
    };
    xhr.send('toggle_notificacoes_ofertas=1&notificacoes_ofertas=' + newStatus);
}
</script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>


</body>

</html>
