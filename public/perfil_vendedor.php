<?php
include_once "../config/valida.php";
include_once "../config/liga_bd.php";

// Verifica se o user_id do vendedor foi passado na URL
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id']; // Obtém o ID do vendedor da URL

    // Consulta para buscar informações do vendedor na tabela t_user
    $sql = "SELECT * FROM t_user WHERE id = ?"; 
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se o vendedor foi encontrado
    if ($linha = $resultado->fetch_assoc()) {
        // Agora $linha contém os dados do vendedor
    } else {
        // Se não encontrar, define $linha como null
        $linha = null;
    }
} else {
    // Se user_id não foi passado, define $linha como null
    $linha = null;
}


// Função para enviar solicitação de amizade
if (isset($_POST['enviar_solicitacao'])) {
    $id_usuario1 = $_SESSION['id']; // Usuário logado
    $id_usuario2 = $_POST['id_usuario2']; // Usuário que vai receber a solicitação

    // Verifica se a solicitação já foi feita
    $checkStmt = $ligacao->prepare("SELECT * FROM amizades WHERE id_usuario1 = ? AND id_usuario2 = ?");
    $checkStmt->bind_param("ii", $id_usuario1, $id_usuario2);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows == 0) {
        // Inserir nova solicitação de amizade
        $stmt = $ligacao->prepare("INSERT INTO amizades (id_usuario1, id_usuario2) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_usuario1, $id_usuario2);
        $stmt->execute();
        echo "Solicitação de amizade enviada!";
    } else {
        echo "Você já enviou uma solicitação para esse usuário.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Vendedor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        header {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            z-index: 100;
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
            padding: 10px 22px;
        }

        .vendedor-info {
            max-width: 400px;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(1, 1, 1, 1);
            text-align: center;
        }
        .vendedor-info img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .info-container {
            text-align: left;
        }
        .info-row {
            margin-bottom: 10px;
        }
        .label {
            font-weight: 600;
            font-weight: bold;
            display: block;
            font-size: 15px;
            
        }
        
        .value {
            font-size: 14px;
            color: #555;
        }
        .obsession {
            font-size: 14px;
            color: #f14668;
            margin-top: 10px;
        }
        .btn-primary {
    background-color: #4169e1; /* Azul royal */
    border: none;
    padding: 12px 20px;
    color: white;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    cursor: pointer;
    border-radius: 25px; /* Botão com bordas arredondadas */
    font-size: 14px; /* Ajustar o tamanho da fonte */
    transition: background-color 0.3s, transform 0.2s; /* Efeitos de transição */
}
        .btn-primary:hover {
            background-color: #4169e1;
        }
    </style>
</head>

<body>
    <header>
        <a href="../index.html" style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="/SiteViagens/public/hotels/hotels.php">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="/SiteViagens/public/tours/tours.php">Tours</a></li>
            <li><a href="#contact">Pacotes</a></li>
            <li><a href="/SiteViagens/public/carrinho/carrinho.php"><i class='bx bx-cart'></i></a></li>
        </ul>
    </header>

    <br><br><br><br><br><br>

    <div class="vendedor-info">
        <?php if ($linha): ?>
            <img src="<?php echo $linha['foto'] ? '../assets/images/pics/' . htmlspecialchars($linha['foto']) : 'https://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="Foto do Vendedor">
            <div class="info-container">
                <div class="info-row">
                    <span class="label">Nome da Marca:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['nome_marca']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Nick:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['nick']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['email']); ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Biografia:</span>
                    <span class="value"><?php echo htmlspecialchars($linha['biografia']); ?></span>
                </div>
                
                        <form method="POST">
            <input type="hidden" name="id_usuario2" value="<?php echo $linha['id']; ?>">
            <button type="submit" name="enviar_solicitacao">
                <i class="bi bi-person-fill-add"></i> Enviar solicitação de amizade
            </button>
        </form>
                
            </div>
        <?php else: ?>
            <p>Vendedor não encontrado ou não é um vendedor.</p>
        <?php endif; ?>
        <br><br>
        <a href="reservas.php" class="btn btn-primary">Voltar</a> <!-- Botão para voltar à página anterior -->
    </div>
     <!--footer-->
     <section id="contact">
        <div class="footer">
            <div class="main">
                <div class="list">

                    <h4> Minha Conta</h4>
                    <ul>
                        <li><a href="#">Minhas Viagens</a></li>
                        <li><a href="public/perfil.php">Meu Perfil</a></li>
                        <li><a href="#">Deletar minha conta</a></li>

                    </ul>
                </div>

                <div class="list">
                    <h4>Suporte</h4>
                    <ul>
                        <li><a href="#">Contatos</a></li>
                        <li><a href="#">Termos & Condições</a></li>
                        <li><a href="#">Politica de privacidade</a></li>

                    </ul>
                </div>

                <div class="list">
                    <h4>Trabalhe conosco</h4>
                    <ul>
                        <li><a href="public/vendedor/registro_vendedor.php">Como Parceiro Fornecedor</a></li>
                        <li><a href="public/vendedor/admin.php">Acessar ao painel de vendedor</a></li>
                    </ul>
                </div>

                <div class="list">
                    <h4>Connect</h4>
                    <div class="social">
                        <a href="#"><i class='bx bxl-facebook'></i></a>
                        <a href="#"><i class='bx bxl-instagram'></i></a>

                        <a href="#"></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="end-text">
            <p>© 2024 BestWay. Todos os direitos reservados.</p>
        </div>
    </section>

    <!--link to js-->
    <script type="text/javascript" src="../../assets/js/script.js"></script>

    <!-- Script para mudar a cor do header ao rolar a página -->
    <script>
        window.addEventListener('scroll', function () {
            const header = document.querySelector('header');
            header.classList.toggle('scrolled', window.scrollY > 0);
        });
    </script>

</body>

</html>
