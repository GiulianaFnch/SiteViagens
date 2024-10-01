<?php
include 'valida_vendedor.php'; // Validação do usuário logado
include '../../config/liga_bd.php'; // Conexão com o banco de dados

$id_user = $_SESSION['id']; // ID do usuário logado

// Buscar todos os usuários
$stmt = $ligacao->prepare("SELECT id, foto, nick FROM t_user WHERE id != ?");
$stmt->bind_param("i", $id_user); // "i" para um inteiro
$stmt->execute();
$resultado = $stmt->get_result();

$usuarios = []; // Array para armazenar os usuários
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
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Estilos ajustados para o chat */

        body {
    font-family: 'Poppins', sans-serif;
    background-color: #f0f0f5; /* Fundo mais claro */
    color: #333; /* Cor do texto mais suave */
}

#chat-container {
    background-color: #ffffff; /* Fundo branco para o chat */
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
            width: 70%;
            padding: 10px;
            display: flex;
            flex-direction: column;
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
        }

        #message-input {
            display: flex;
            align-items: center;
        }

        #message-input input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
        }


        #message-input button {
            width: 20%;
            padding: 10px;
            background-color: #007AFF;
            color: white;
            border: none;
            border-radius: 20px;
            margin-left: 10px;
            cursor: pointer;
        }


      


        .message {
    padding: 5px;
    margin-bottom: 10px;
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
}

.user-icon-message {
    width: 30px; /* Ajuste o tamanho conforme necessário */
    height: 30px; /* Ajuste o tamanho conforme necessário */
    border-radius: 50%;
    margin-right: 10px;
}


#emoji-panel {
    display: flex;
    margin-top: 10px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 10px;
    position: absolute; /* Para posicionar o painel */
    z-index: 100; /* Para garantir que o painel fique acima */
}

#emoji-panel span {
    font-size: 20px; /* Ajuste o tamanho dos emojis */
    cursor: pointer;
    margin-right: 10px;
}
    </style>
</head>

<body>
    <div id="chat-container">
        <div id="user-list">
            <h2>Conversas</h2>
            <?php foreach ($usuarios as $usuario): ?>
                <div class="user-item" onclick="loadChat(<?php echo $usuario['id']; ?>)">
                    <img src="../../assets/images/pics/<?php echo $usuario['foto']; ?>" alt="Foto de <?php echo $usuario['nick']; ?>" class="user-icon">
                    <span><?php echo $usuario['nick']; ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="chat-box" style="display:none;">
            <i class="bi bi-arrow-left" id="back-arrow" onclick="showUserList()"></i>
            <div id="messages"></div>
            <div id="message-input">
                <input type="text" id="message" placeholder="Escreva uma mensagem...">
                <i class="bi bi-app-indicator" onclick="toggleEmojiPanel()"></i>
                <button onclick="sendMessage()">Enviar</button>
            </div>
        </div>
    </div>

    <div id="emoji-panel" style="display: none;">
    <span onclick="insertEmoji('😊')">😊</span>
    <span onclick="insertEmoji('🏳️‍🌈')">🏳️‍🌈</span>
    <span onclick="insertEmoji('👍')">👍</span>
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
            fetch('get_chat.php?user_id=' + userId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('messages').innerHTML = data;
                });

            // Atualizar as mensagens a cada 3 segundos
            refreshInterval = setInterval(function () {
                fetch('get_chat.php?user_id=' + userId)
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('messages').innerHTML = data;
                    });
            }, 3000); // Atualiza a cada 3 segundos
        }

        // Função para enviar mensagem
        function sendMessage() {
            const message = document.getElementById('message').value;

            if (message.trim() !== '') {
                fetch('send_message.php', {
                    method: 'POST',
                    body: new URLSearchParams({
                        'user_id': selectedUserId,
                        'message': message
                    })
                }).then(response => response.text())
                  .then(data => {
                      document.getElementById('messages').innerHTML = data;
                      document.getElementById('message').value = ''; // Limpa o campo de texto
                  });
            }
        }

        function toggleEmojiPanel() {
    const panel = document.getElementById('emoji-panel');
    panel.style.display = panel.style.display === 'none' ? 'flex' : 'none';
}

function insertEmoji(emoji) {
    const messageInput = document.getElementById('message');
    messageInput.value += emoji; // Adiciona o emoji ao campo de entrada
    toggleEmojiPanel(); // Fecha o painel de emojis
}
    </script>

    
</body>
</html>
