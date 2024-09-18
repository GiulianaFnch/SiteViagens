<?php
session_start();
include '../config/liga_bd.php';

$activeMenu = 'settings';

// Verifica se a sessão do usuário está ativa
if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];

    // Buscar o nome do usuário para exibir na página
    $sql = "SELECT nome, pass FROM t_user WHERE id='$id'";
    $resultado = mysqli_query($ligacao, $sql);

    // Verificar se a consulta retornou um resultado
    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $linha = mysqli_fetch_array($resultado);
    } else {
        $linha['nome'] = 'Usuário'; // Valor padrão caso a consulta falhe
    }

    // Processa a atualização do serviço de localização
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['toggle_location_service'])) {
        $location_service = intval($_POST['location_service']);
        $sql = "UPDATE t_user SET location_service='$location_service' WHERE id='$id'";
        if (mysqli_query($ligacao, $sql)) {
            echo 'Serviço de localização atualizado.';
        } else {
            echo 'Erro ao atualizar o serviço de localização: ' . mysqli_error($ligacao);
        }
        exit;
    }
}

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['atualizar_senha'])) {
    $id = $_SESSION['id'];
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];



    // Verificar se a senha atual está correta
    $sql = "SELECT pass FROM t_user WHERE id='$id'";
    $resultado = mysqli_query($ligacao, $sql);
    $linha = mysqli_fetch_array($resultado);

    if (password_verify($senha_atual, $linha['pass'])) {
        if ($nova_senha == $confirmar_senha) {
            // Atualizar a senha no banco de dados
            $hash_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql = "UPDATE t_user SET pass='$hash_senha' WHERE id='$id'";
            if (mysqli_query($ligacao, $sql)) {
                $mensagem_sucesso = "Senha alterada com sucesso!";
            } else {
                $mensagem_erro = "Erro ao atualizar a senha.";
            }
        } else {
            $mensagem_erro = "As novas senhas não coincidem.";
        }
    } else {
        $mensagem_erro = "Senha atual incorreta.";

}
}
?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurações</title>
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script>
        function togglePasswordForm() {
            var form = document.getElementById('form-atualizar-senha');
            var settingsItems = document.querySelectorAll('.settings-item');
            var voltarIcon = document.getElementById('voltar-icon');

            // Esconde os itens de configuração e mostra o formulário
            if (form.style.display === 'none') {
                form.style.display = 'block';
                voltarIcon.style.display = 'inline-block';
                settingsItems.forEach(function(item) {
                    item.style.display = 'none';
                });
            } else {
                // Mostra os itens de configuração e esconde o formulário
                form.style.display = 'none';
                voltarIcon.style.display = 'none';
                settingsItems.forEach(function(item) {
                    item.style.display = 'flex';
                });
            }
        }
    </script>

    <style>
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
            transform: scale(0.98); /* Efeito de clique estilo iOS */
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
    <div class="container light-style flex-grow-1 container-p-y">
    <h2>Bem-vindo(a), <?php echo htmlspecialchars($linha['nome']); ?></h2>
        <h4 class="font-weight-bold py-3 mb-4 text-center">Configurações da Conta</h4>
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <a class="menu-item" href="perfil.php"><i class="bi bi-person-circle"></i> Editar perfil</a>
                        <a class="menu-item" href="/SiteViagens-main/index.html"><i class="bi bi-house-door"></i> Página Inicial</a>
                        <a class="menu-item" href="#reservations"><i class="bi bi-clipboard2"></i> Reservas</a>
                        <a class="menu-item" href="#favorites"><i class="bi bi-heart"></i> Favoritos</a>
                        <a class="menu-item" href="#chat"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item <?php echo $activeMenu === 'settings' ? 'active' : ''; ?>" href="configuracoes.php#account-general"><i class="bi bi-gear"></i> Configurações</a>
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
    xhr.onreadystatechange = function() {
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
                            <div class="settings-item">
                                <div class="item-left">
                                    <i class="bi bi-bell"></i>
                                    <span class="item-label">Notificações de Ofertas</span>
                                </div>
                                <div class="item-right">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="settings-section">
                            <div class="settings-item">
                                <div class="item-left">
                                    <i class="bi bi-clock-history"></i>
                                    <span class="item-label">Histórico de Viagens</span>
                                </div>
                                <div class="item-right">
                                    <i class="bi bi-chevron-right"></i>
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

                            <!-- Botão para Atualizar Senha -->
                            <div class="settings-item" onclick="togglePasswordForm()">
                                <div class="item-left">
                                    <i class="bi bi-shield-lock"></i>
                                    <span class="item-label">Atualizar Senha</span>
                                </div>
                                <div class="item-right">
                                    <i class="bi bi-chevron-right"></i>
                                </div>
                            </div>
                        </div>

                        <div id="form-atualizar-senha">
                            <h5>Alterar Senha</h5>
                            <form method="POST" action="">
                                <div class="form-group">
                                    <label for="senha_atual">Senha Atual</label>
                                    <input type="password" name="senha_atual" id="senha_atual" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="nova_senha">Nova Senha</label>
                                    <input type="password" name="nova_senha" id="nova_senha" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirmar_senha">Confirmar Nova Senha</label>
                                    <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required>
                                </div>
                                <button type="submit" name="atualizar_senha" class="btn btn-primary">Atualizar Senha</button>
                            </form>

                            <!-- Exibir Mensagens -->
                            <?php if (isset($mensagem_sucesso)): ?>
                                <div class="alert alert-success"><?php echo $mensagem_sucesso; ?></div>
                            <?php elseif (isset($mensagem_erro)): ?>
                                <div class="alert alert-danger"><?php echo $mensagem_erro; ?></div>
                            <?php endif; ?>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>