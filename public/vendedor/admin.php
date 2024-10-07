<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

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

        if (in_array($_FILES['ficheiro']['type'], $allowed_types) && $_FILES['ficheiro']['size'] <= $max_size) {
            $fotoNova = $_FILES['ficheiro']['name'];
            $destino = '../../assets/images/pics/' . $fotoNova;

            if (!is_dir('../../assets/images/pics')) {
                mkdir('../../assets/images/pics', 0777, true);
            }

            if (move_uploaded_file($_FILES['ficheiro']['tmp_name'], $destino)) {
                if ($fotoAntiga && file_exists('../assets/images/pics/' . $fotoAntiga)) {
                    unlink('../../assets/images/pics/' . $fotoAntiga);
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
        header('Location: admin.php');
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
    <title>Painel do Vendedor</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">



    <script>
        function showContent(contentId) {
            // Oculta todos os conteúdos
            document.getElementById("profile").style.display = "none";
            document.getElementById("vender-tours").style.display = "none";

            // Mostra o conteúdo selecionado
            document.getElementById(contentId).style.display = "block";
        }
    </script>
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

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .tab-pane {
            padding: 20px;
        }

        .popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0);
    width: 80%;
    max-width: 1600px;
    height: 90vh;
    max-height: 800px;
    border-radius: 20px;
    background: rgba(0, 0, 0, 0.75);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    overflow: hidden;
    transition: 1s;
    opacity: 0;
}

.popup.active {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
}

.top-bar {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 50px;
    background: #000;
    color: #fff;
    text-align: center;
    line-height: 50px;
    font-weight: 300;
}

.close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #f00;
    cursor: pointer;
}

.large-image {
    margin-top: 5%;
    width: 80%;
    height: 80%;
    object-fit: contain;
}

.index {
    position: absolute;
    bottom: 10px;
    right: 10px;
    font-size: 80px;
    font-weight: 100;
    color: rgba(255, 255, 255, 0.4);
}
    
    </style>
</head>

<body>
    <!--header-->

    <header>

        <a href="../../index.html"
            style="font-size: 35px; font-weight: 600; letter-spacing: 1px; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>

        <ul class="navbar">
            <li><a href="#home" style="color: black;">Hospedagem</a></li>
            <li><a href="#package" style="color: black;">Passagens</a></li>
            <li><a href="#destination" style="color: black; ">Tours</a></li>
            <li><a href="#contact" style="color: black;">Pacotes</a></li>
        </ul>
    </header>

    <br><br><br><br><br><br><br>


    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <a class="menu-item" style="color: #3A506B"><strong>Painel de Vendedor</strong></a>
                        <a class="menu-item" href="#" onclick="showContent('profile');"><i class="bi bi-person-circle"></i> Editar Perfil</a>
                        <a class="menu-item" href="vender_opcoes.php"><i class="bi bi-bag"></i> Vender</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i> Reservas</a>
                        <a class="menu-item" href="gestao_opcoes.php"><i class="bi bi-train-freight-front"></i> Gestão</a>
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9">
                    <div class="tab-content p-4">
                        <form action="admin.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($linha['id']); ?>">

                            <div class="media align-items-center mb-3">
                            <img src="<?php echo $linha['foto'] ? '../../assets/images/pics/' . htmlspecialchars($linha['foto']) : 'https://bootdey.com/img/Content/avatar/avatar1.png'; ?>"
                            alt="avatar" class="rounded-circle mr-3 image" style="width: 80px;">
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

                            <!-- Popup container -->
                            <div class="popup">
                                <div class="top-bar">
                                    <span class="image-name"></span>
                                    <div class="close-btn"></div>
                                </div>
                                <img class="large-image" src="" alt="Imagem Grande">
                                
                                <div class="index"></div>
                            </div>

                            <input type="hidden" name="nome_foto"
                                value="<?php echo htmlspecialchars($linha['foto']); ?>">

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

                            <div class="text-right mt-3">
                                <input type="submit" class="btn btn-primary rounded-pill" value="Alterar">
                            </div>
                        </form>
                    </div>
                    

                </div>
            </div>
        </div>
    </div>
    </div>
    <br><br><br>

    <?php include '../../views/partials/footer.php' ?>


        <script>
    const images = [...document.querySelectorAll('.image')];
    const popup = document.querySelector('.popup');
    const closeBtn = document.querySelector('.close-btn');
    const largeImage = document.querySelector('.large-image');
    const imageName = document.querySelector('.image-name');
    const imageIndex = document.querySelector('.index');
    let index = 0;

    images.forEach((item, i) => {
        item.addEventListener('click', () => {
            updateImage(i);
            popup.classList.add('active');
        });
    });

    const updateImage = (i) => {
        let path = images[i].src;
        largeImage.src = path;
        imageName.innerHTML = path.split('/').pop();
        imageIndex.innerHTML = `0${i+1}`;
        index = i;
    };

    closeBtn.addEventListener('click', () => {
        popup.classList.remove('active');
    });

    
        
</script>


</body>

</html>
