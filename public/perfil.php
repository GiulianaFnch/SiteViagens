<!-- 
    Essa página será inspirada no arquivo do projeto do Rui chamado perfil1.php que está na pasta exemplos/projeto_rui/perfil1.php

    Pra conseguir editar que ainda colocar o perfil2.php
-->

<?php
include '../config/valida.php';
include '../config/liga_bd.php';

$activeMenu = 'profile';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nick = $_POST['nick'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $data_nasc = $_POST['data_nasc'];
    $fotoAntiga = $_POST['nome_foto'];
    $fotoNova = $fotoAntiga;  // Manter a foto antiga por padrão

    // Lidar com upload de foto
    if (isset($_FILES['ficheiro']) && $_FILES['ficheiro']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 800 * 1024;  // 800 KB

        // Verificar tipo e tamanho do arquivo
        if (in_array($_FILES['ficheiro']['type'], $allowed_types) && $_FILES['ficheiro']['size'] <= $max_size) {
            $fotoNova = $_FILES['ficheiro']['name'];
            $destino = '../assets/images/pics/' . $fotoNova;

            // Verificar se o diretório 'pics/' existe, caso contrário, cria-o
            if (!is_dir('../assets/images/pics')) {
                mkdir('../assets/images/pics', 0777, true);
            }

            // Mover o arquivo para o diretório desejado
            if (move_uploaded_file($_FILES['ficheiro']['tmp_name'], $destino)) {
                // Remover a foto antiga, se for o caso
                if ($fotoAntiga && file_exists('../assets/images/pics/' . $fotoAntiga)) {
                    unlink('../assets/images/pics/' . $fotoAntiga);
                }
            } else {
                echo "Erro ao fazer upload da imagem!";
                exit;
            }
        } else {
            echo "Arquivo inválido ou excedeu o tamanho permitido!";
            exit;
        }
    }

    // Atualizar dados no banco de dados
    $sql = "UPDATE t_user SET nick='$nick', nome='$nome', email='$email', data_nasc='$data_nasc', foto='$fotoNova' WHERE id='$id'";
    if (mysqli_query($ligacao, $sql)) {
        // Redirecionar após o sucesso
        header('Location: perfil.php');
        exit;
    } else {
        echo "Erro ao atualizar os dados: " . mysqli_error($ligacao);
        exit;
    }
}

$id = $_SESSION['id'];
$sql = "SELECT * FROM t_user WHERE id=$id";
$resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
$linha = mysqli_fetch_array($resultado);
mysqli_close($ligacao);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    
    
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

        /* Header */
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

        /*--------*/
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
                        <a class="menu-item <?php echo $activeMenu === 'profile' ? 'active' : ''; ?>"
                            href="perfil.php#account-general"><i class="bi bi-person-circle"></i> Editar perfil</a>
                        <a class="menu-item" href="../index.html"><i class="bi bi-house-door"></i> Página
                            Inicial</a>
                        <a class="menu-item" href="reservas.php"><i class="bi bi-clipboard2"></i> Reservas</a>
                        <a class="menu-item" href="favoritos.php"><i class="bi bi-heart"></i> Favoritos</a>
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9">
                    <div class="tab-content p-4">
                        <div class="tab-pane fade active show" id="account-general">
                            <form action="perfil.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($linha['id']); ?>">

                                <!-- Avatar e upload de nova foto -->
                                <div class="media align-items-center mb-3">
                                    <img src="<?php echo $linha['foto'] ? '../assets/images/pics/' . htmlspecialchars($linha['foto']) : 'https://bootdey.com/img/Content/avatar/avatar1.png'; ?>"
                                        alt="avatar" class="rounded-circle mr-3" style="width: 80px;">
                                    <div class="media-body">
                                        <label class="btn btn-outline-primary rounded-pill">
                                            Upload Nova Foto
                                            <input type="file" name="ficheiro" class="account-settings-fileinput"
                                                accept=".jpg, .jpeg, .png, .gif">
                                        </label>
                                        <div class="small text-muted mt-1">Permitido JPG, GIF ou PNG. Tamanho máximo de
                                            800K.</div>
                                    </div>
                                </div>

                                <input type="hidden" name="nome_foto"
                                    value="<?php echo htmlspecialchars($linha['foto']); ?>">

                                <!-- Campos de informações pessoais -->
                                <div class="form-group">
                                    <label for="username" class="form-label">Nick</label>
                                    <input type="text" id="username" class="form-control rounded-pill" name="nick"
                                        value="<?php echo htmlspecialchars($linha['nick']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" id="name" class="form-control rounded-pill" name="nome"
                                        value="<?php echo htmlspecialchars($linha['nome']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" id="email" class="form-control rounded-pill" name="email"
                                        value="<?php echo htmlspecialchars($linha['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_nasc" class="form-label">Data de Nascimento</label>
                                    <input type="date" id="data_nasc" class="form-control rounded-pill" name="data_nasc"
                                        value="<?php echo htmlspecialchars($linha['data_nasc']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pass" class="form-label">Senha atual</label>
                                    <input type="password" id="pass" class="form-control rounded-pill" name="pass"
                                        required>
                                </div>

                                <!-- Botões de ação -->
                                <div class="text-right mt-3">
                                    <input type="submit" class="btn btn-primary rounded-pill" value="Alterar">
                                    <a href="index.html" class="btn btn-light rounded-pill">Voltar ao menu</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <!--footer-->
    <?php include '../views/partials/footer.php' ?>
</body>

</html>