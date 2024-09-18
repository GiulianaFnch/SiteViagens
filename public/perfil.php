<!-- 
    Essa página será inspirada no arquivo do projeto do Rui chamado perfil1.php que está na pasta exemplos/projeto_rui/perfil1.php

    Pra conseguir editar que ainda colocar o perfil2.php
-->

<?php
session_start();
include '../config/liga_bd.php';

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
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<style>
.menu-container {
    background-color: #f8f9fa;
    border-right: 1px solid #e0e0e0;
}

.menu {
    display: flex;
    flex-direction: column;
}

.menu-item {
    display: flex;
    align-items: center;
    padding: 15px;
    font-size: 16px;
    color: #333;
    text-decoration: none;
    border-radius: 8px;
    transition: background-color 0.3s, color 0.3s;
}

.menu-item i {
    margin-right: 8px;
    font-size: 20px;
}

.menu-item.active, .menu-item:hover {
    background-color: #007bff;
    color: #fff;
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

    </style>

<body>
    <div class="container light-style flex-grow-1 container-p-y">
        <h2>Bem vinda(o), <?php echo htmlspecialchars($linha['nome']); ?></h2>
        <h4 class="font-weight-bold py-3 mb-4 text-center">Configurações da Conta</h4>
    <div class="card shadow-sm rounded-lg">
        <div class="row no-gutters">
            <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                <nav class="menu">
                    <a class="menu-item active" href="#account-general"><i class="bi bi-person-circle"></i> Editar perfil</a>
                    <a class="menu-item" href="#home"><i class="bi bi-house-door"></i> Página Inicial</a>
                    <a class="menu-item" href="#reservations"><i class="bi bi-clipboard2"></i> Reservas</a>
                    <a class="menu-item" href="#favorites"><i class="bi bi-heart"></i> Favoritos</a>
                    <a class="menu-item" href="#chat"><i class="bi bi-chat-dots"></i> Chat</a>
                    <a class="menu-item" href="#settings"><i class="bi bi-gear"></i> Configurações</a>
                </nav>
            </div>
            

                <div class="col-md-9">
                    <div class="tab-content p-4">
                        <div class="tab-pane fade active show" id="account-general">
                            <form action="perfil.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($linha['id']); ?>">

                                <!-- Avatar e upload de nova foto -->
                                <div class="media align-items-center mb-3">
                                    <img src="<?php echo $linha['foto'] ? '../assets/images/pics/' . htmlspecialchars($linha['foto']) : 'https://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="avatar" class="rounded-circle mr-3" style="width: 80px;">
                                    <div class="media-body">
                                        <label class="btn btn-outline-primary rounded-pill">
                                            Upload Nova Foto
                                            <input type="file" name="ficheiro" class="account-settings-fileinput" accept=".jpg, .jpeg, .png, .gif">
                                        </label>
                                        <div class="small text-muted mt-1">Permitido JPG, GIF ou PNG. Tamanho máximo de 800K.</div>
                                    </div>
                                </div>

                                <input type="hidden" name="nome_foto" value="<?php echo htmlspecialchars($linha['foto']); ?>">

                                <!-- Campos de informações pessoais -->
                                <div class="form-group">
                                    <label for="username" class="form-label">Nick</label>
                                    <input type="text" id="username" class="form-control rounded-pill" name="nick" value="<?php echo htmlspecialchars($linha['nick']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="form-label">Nome</label>
                                    <input type="text" id="name" class="form-control rounded-pill" name="nome" value="<?php echo htmlspecialchars($linha['nome']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" id="email" class="form-control rounded-pill" name="email" value="<?php echo htmlspecialchars($linha['email']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="data_nasc" class="form-label">Data de Nascimento</label>
                                    <input type="date" id="data_nasc" class="form-control rounded-pill" name="data_nasc" value="<?php echo htmlspecialchars($linha['data_nasc']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pass" class="form-label">Senha atual</label>
                                    <input type="password" id="pass" class="form-control rounded-pill" name="pass" required>
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

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
