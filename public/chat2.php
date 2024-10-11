<?php
require_once '../config/valida.php'; // Validação do usuário logado
require_once '../config/liga_bd.php'; // Conexão com o banco de dados

$id_user = $_SESSION['id']; // ID do usuário logado

// Buscar apenas os usuários com amizade aceita
$stmt = $ligacao->prepare("SELECT u.id, u.foto, u.nick FROM amizades a 
                           JOIN t_user u ON (a.id_usuario1 = u.id OR a.id_usuario2 = u.id)
                           WHERE (a.id_usuario1 = ? OR a.id_usuario2 = ?) AND a.status = 'aceito' AND u.id != ?");
$stmt->bind_param("iii", $id_user, $id_user, $id_user);
$stmt->execute();
$resultado = $stmt->get_result();

$usuarios = [];
while ($linha = mysqli_fetch_assoc($resultado)) {
    $usuarios[] = $linha; // Adiciona cada usuário ao array
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chat</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    
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
    color: black; 

} .menu-container {
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
       
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .card .tab-content {
            border-top: 1px solid #e0e0e0;
        }

        .tab-pane {
            padding: 20px;
        }

        .navbar {
            display: flex;
        }

        .navbar a {
            color: var(--bg-color);
            font-size: var(--p-font);
            font-weight: 500;
            padding: 10px 22px;
            border-radius: 4px;
           
        }

        .navbar a:hover {
            background: var(--bg-color);
            color: var(--text-color);
            box-shadow: 5px 10px 30px rgb(85 85 85 / 20%);
            border-radius: 4px;
        }

        body {
    font-family: 'Poppins', sans-serif;

   
}

#chat-container {
   
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}


        #user-list {
            width: 30%;
            border-right: 1px solid #ccc;
            padding: 10px;
            overflow-y: auto;
        }

        .user-item {
            display: flex;
            align-items: center;
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            border-radius: 10px;
        }

        .user-item:hover {
            background-color: #f1f1f1;
        }

        .user-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        #chat-box {
    width: 100%;
    background-color: #f0f0f0;
    padding: 15px;
    border-radius: 10px;
}

      

        #back-arrow {
            font-size: 24px;
            color: #007AFF;
            cursor: pointer;
        }

        #messages {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 10px;
            max-width: 100%;
            scroll-behavior: smooth;
        }

        #message-input {
            display: flex;
            align-items: center;
         
        }

        #message-input input {
    width: 75%;
    padding: 12px 10px; /* Adiciona um padding para a entrada */
    border: 1px solid #ddd;
    border-radius: 20px;
    font-size: 16px;
}


#message-input button {
    background: none; /* Remove o fundo padrão do botão */
    border: none; /* Remove a borda */
    cursor: pointer; /* Muda o cursor para um ponteiro */
    margin-right: 10px; /* Espaço entre o ícone e o campo de entrada */
}

#message-input button:last-child {
    padding: 7px; /* Ajuste no botão de enviar */
    background-color: #007AFF; /* Cor do botão de enviar */
    color: white;
    border-radius: 50%;
    margin-left: 10px; 
    font-size: 9px; 
}


#message-input button i {
    font-size: 18px;
}

        .message {
    padding: 10px;
    margin-bottom: 10px;
    max-width: 60%;
    border-radius: 10px;
    display: flex;
    align-items: center;
}

#messages {
    display: flex;
    flex-direction: column;
}


.user-icon-message {
    width: 30px; 
    height: 30px; 
    border-radius: 50%;
    margin-right: 10px;
}

#emoji-panel {
    display: flex;
    flex-wrap: wrap;
    position: absolute;
    bottom: 50px;
    right: 10px;
    background: white;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

#emoji-panel span {
    font-size: 20px; 
    cursor: pointer;
    margin-right: 10px;
}

.sent-message {
    background-color: #DCF8C6;
    align-self: flex-end; /* Alinha à direita */
    border-top-right-radius: 0px;
}

.received-message {
    background-color: #ffffff;
    align-self: flex-start; /* Alinha à esquerda */
    border-top-left-radius: 0px;
}

.notification-dot {
    height: 10px;
    width: 10px;
    background-color: red;
    border-radius: 50%;
    display: inline-block;
    margin-left: 5px;
}

@media (max-width: 768px) {
    #user-list, #chat-box {
        width: 100%;
        padding: 10px;
    }

    #message-input input {
        width: 70%;
    }

    #message-input button {
        width: 30%;
    }
}
    </style>
</head>

<body>

<header>
    <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
    <ul class="navbar">
    <li><a href="/SiteViagens/public/hotels/hotels.php" style="color: black;" >Hospedagem</a></li>
            <li><a href="#package" style="color: black;" >Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php" style="color: black; ">Tours</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php" style="color: black !important;"><i class='bx bx-cart'></i></a></li>
    </ul>
</header>

<br><br><br><br>


<div class="container light-style flex-grow-1 container-p-y">
    <div class="card shadow-sm rounded-lg">
        <div class="row no-gutters">
            <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                <nav class="menu">
                    <a class="menu-item" href="perfil.php"><i class="bi bi-person-circle"></i> Editar perfil</a>
                    <a class="menu-item" href="reservas.php"><i class="bi bi-clipboard2"></i> Reservas</a>
                    <a class="menu-item" href="favoritos.php"><i class="bi bi-heart"></i> Favoritos</a>
                    <a class="menu-item" href="chat2.php"><i class="bi bi-chat-dots"></i> Chat</a>
                    <a class="menu-item" href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a>
                </nav>
            </div>

            <div class="col-md-9 p-4">
    <div id="chat-container">
    <div id="user-list">
    <h2>Conversas</h2>
    <?php foreach ($usuarios as $usuario): ?>
        <div class="user-item" onclick="loadChat(<?php echo $usuario['id']; ?>)">
            <img src="../assets/images/pics/<?php echo $usuario['foto']; ?>" alt="Foto de <?php echo $usuario['nick']; ?>" class="user-icon">
            <span><?php echo $usuario['nick']; ?></span>
        </div>
    <?php endforeach; ?>
</div>

        <div id="chat-box" style="display:none;">
            <i class="bi bi-arrow-left" id="back-arrow" onclick="showUserList()"></i>
            <div id="messages"></div>
            <div id="message-input">
    <button onclick="toggleEmojiPanel()" style="background: none; border: none; cursor: pointer;">
        <i class="bi bi-emoji-smile"></i>
    </button>
    <input type="text" id="message" placeholder="Escreva uma mensagem...">
    <button onclick="sendMessage()"><i class="bi bi-send"></i></button>
</div>
        </div>
    </div>

    <div id="emoji-panel" style="display: none;">
    <span onclick="insertEmoji('😊')">😊</span>
    <span onclick="insertEmoji('🏳️‍🌈')">🏳️‍🌈</span>
    <span onclick="insertEmoji('👍')">👍</span>
    <span onclick="insertEmoji('✈️')">✈️</span>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    let selectedUserId = null;
    let refreshInterval = null;

    // Função para exibir a lista de usuários
    function showUserList() {
        document.getElementById('user-list').style.display = 'block';
        document.getElementById('chat-box').style.display = 'none';
        clearInterval(refreshInterval);
    }

    // Função para carregar o chat de um usuário específico
    function loadChat(userId) {
        selectedUserId = userId;
        document.getElementById('user-list').style.display = 'none';
        document.getElementById('chat-box').style.display = 'block';

        // Carregar mensagens do banco de dados
        fetch('get_chat2.php?user_id=' + userId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('messages').innerHTML = data;
                scrollToBottom(); // Rola para o final após inserir as mensagens
            });

        // Atualizar as mensagens a cada 3 segundos
        refreshInterval = setInterval(function () {
            fetch('get_chat2.php?user_id=' + userId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('messages').innerHTML = data;
                    scrollToBottom(); // Rola para o final a cada atualização
                });
        }, 3000); // Atualiza a cada 3 segundos
    }

    // Função para enviar mensagem
    function sendMessage() {
    const message = document.getElementById('message').value;
    if (message.trim() !== '') {
        fetch('send_message2.php', {
            method: 'POST',
            body: new URLSearchParams({
                'user_id': selectedUserId,
                'message': message
            })
        }).then(response => response.text())
          .then(data => {
              // Apenas adiciona a nova mensagem ao final
              document.getElementById('messages').insertAdjacentHTML('beforeend', data);
              document.getElementById('message').value = ''; // Limpa o campo de texto
              scrollToBottom();
          });
    }
}

    // Função para exibir/esconder o painel de emojis
    function toggleEmojiPanel() {
        const panel = document.getElementById('emoji-panel');
        panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
    }

    // Função para inserir um emoji no campo de mensagem
    function insertEmoji(emoji) {
        const messageInput = document.getElementById('message');
        messageInput.value += emoji; // Adiciona o emoji ao campo de entrada
        toggleEmojiPanel(); // Fecha o painel de emojis
    }

    // Função para verificar novas mensagens
    function checkNewMessages() {
        fetch('check_new_messages.php?user_id=' + selectedUserId)
            .then(response => response.json())
            .then(data => {
                if (data.new_messages) {
                    // Exibe a notificação com ponto vermelho
                    document.querySelector('.notification-dot').style.display = 'inline-block';
                } else {
                    // Esconde o ponto vermelho caso não haja novas mensagens
                    document.querySelector('.notification-dot').style.display = 'none';
                }
            });
    }

    // Chama a função para verificar novas mensagens a cada 5 segundos
    setInterval(checkNewMessages, 5000);

    // Função para rolar a lista de mensagens para o final
    function scrollToBottom() {
        const messagesDiv = document.getElementById("messages");
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    // Atualiza o chat automaticamente a cada 3 segundos e rola para o final
    refreshInterval = setInterval(function () {
        fetch('get_chat2.php?user_id=' + selectedUserId)
            .then(response => response.text())
            .then(data => {
                document.getElementById('messages').innerHTML = data;
                scrollToBottom(); // Rola para o final a cada atualização
            });
    }, 3000); // Atualiza a cada 3 segundos


</script>

<br>

<!--footer-->
<?php include '../views/partials/footer.php' ?>
    
</body>
</html>
