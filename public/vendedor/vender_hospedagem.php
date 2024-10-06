<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

$uploadOk = 1;
$target_dir = "../../public/hotels/imagens/";

function processar_foto($file, $id_hospedagem, $numero_foto) {
    global $uploadOk, $ligacao, $target_dir;
    
    if ($uploadOk == 0) {
        return;
    }

    $target_file = $target_dir . basename($file["name"]);
    $file_name = basename($file["name"]);
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        echo "O arquivo não é uma imagem.";
        $uploadOk = 0;
        return;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        $sql = "UPDATE t_hospedagem SET foto{$numero_foto}='{$file_name}' WHERE id={$id_hospedagem};";
        mysqli_query($ligacao, $sql);
    } else {
        echo "O seu ficheiro não foi enviado.";
        $uploadOk = 0;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário com verificações
    $id_user = $_POST['id_user'];
    $nome = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $n_quartos = $_POST['n_quartos'];
    $preco_diaria = $_POST['preco'];
    $classificacao = $_POST['estado'];
    $localizacao = isset($_POST['localizacao']) ? $_POST['localizacao'] : '';
    $horario_checkin = $_POST['horario_checkin'];
    $horario_checkout = $_POST['horario_checkout'];
    $tipo_hospedagem = $_POST['tipo_hospedagem'];

    $sql = "INSERT INTO t_hospedagem (id_user, nome, descricao, n_quartos, preco_diaria, classificacao, localizacao, horario_checkin, horario_checkout, tipo_hospedagem, foto1) 
            VALUES ('$id_user', '$nome', '$descricao', '$n_quartos', '$preco_diaria', '$classificacao', '$localizacao', '$horario_checkin', '$horario_checkout', '$tipo_hospedagem', '');";

    if (mysqli_query($ligacao, $sql)) {
        $id_hospedagem = mysqli_insert_id($ligacao);
        echo "<h2>Registro efetuado com sucesso! </h2>";

        $_FILES["ficheiro"] = $_FILES["ficheiro1"];
        processar_foto($_FILES["ficheiro"], $id_hospedagem, 1);

        if (!empty($_FILES['ficheiro2']['name'])) {
            $_FILES["ficheiro"] = $_FILES["ficheiro2"];
            processar_foto($_FILES["ficheiro"], $id_hospedagem, 2);
        }

        if (!empty($_FILES['ficheiro3']['name'])) {
            $_FILES["ficheiro"] = $_FILES["ficheiro3"];
            processar_foto($_FILES["ficheiro"], $id_hospedagem, 3);
        }
    } else {
        echo "Erro: " . mysqli_error($ligacao);
    }

    mysqli_close($ligacao);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Vender Hospedagem</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <br><br>
    <style>
        body {
            margin-top: 100px;
        }

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
            color: black;
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

        input[type="text"], 
        input[type="date"], 
        input[type="file"], 
        input[type="time"],
        select, 
        textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-sizing: border-box;
            background-color: #f9f9f9;
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        input[type="text"]:focus, 
        input[type="date"]:focus, 
        input[type="file"]:focus, 
        input[type="time"]:focus,
        select:focus, 
        textarea:focus {
            border-color: #007AFF;
            box-shadow: 0 0 8px rgba(0, 122, 255, 0.3);
            outline: none;
        }

        textarea {
            resize: none;
            height: 100px;
        }

        input[type="submit"] {
            width: auto; 
            background-color: #007AFF;
            color: white;
            padding: 10px 20px; 
            margin: 8px 0;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px; 
            font-weight: 600;
            transition: background-color 0.3s ease-in-out;
        }

        input[type="submit"]:hover {
            background-color: #005bb5;
        }

        select option {
            padding: 10px;
        }

        form {
            margin-top: 20px;
        }

        label {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
            display: block;
            color: #333;
        }
    </style>
</head>

<body>
    <header>
        <a href="../../index.html" style="font-size: 35px; font-weight: 600; color: black;">BestWay</a>
        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="#home">Hospedagem</a></li>
            <li><a href="#package">Passagens</a></li>
            <li><a href="#destination">Tours</a></li>
            <li><a href="#contact">Pacotes</a></li>
        </ul>
    </header>

    <div class="container light-style flex-grow-1 container-p-y">
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left menu-container">
                    <nav class="menu">
                        <a class="menu-item" style="color: #3A506B"><strong>Painel de Vendedor</strong></a>
                        <a class="menu-item" href="../vendedor/admin.php"><i class="bi bi-person-circle"></i> Editar perfil</a>
                        <a class="menu-item" href="vender_opcoes.php"><i class="bi bi-bag"></i> Vender</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i>Gestão de Reservas</a>
                        <a class="menu-item" href="gestao_hospedagem.php"><i class="bi bi-train-freight-front"></i> Gestão de Hospedagem</a>
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9 p-4">
                    <h1 class="h2">Vender Hospedagem</h1>

                    <form action="vender_hospedagem.php" id="f2" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_user" value="<?php echo $_SESSION['id']; ?>" required>

                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao"></textarea>

                        <label for="n_quartos">Número de Quartos:</label>
                        <select id="n_quartos" name="n_quartos" required>
                            <?php for ($i = 1; $i <= 100; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>

                        <label for="preco">Valor diária:</label>
                        <input type="text" id="preco" name="preco" required>

                        <label for="estado">Classificação:</label>
                        <select id="estado" name="estado">
                            <option value="1">1 estrela</option>
                            <option value="2">2 estrelas</option>
                            <option value="3">3 estrelas</option>
                            <option value="4">4 estrelas</option>
                            <option value="5">5 estrelas</option>
                        </select>

                        <label for="localizacao">Localização:</label>
                        <input type="text" id="localizacao" name="localizacao" required>

                        <label for="horario_checkin">Horário de Check-in:</label>
                        <input type="time" id="horario_checkin" name="horario_checkin" required>

                        <label for="horario_checkout">Horário de Check-out:</label>
                        <input type="time" id="horario_checkout" name="horario_checkout" required>

                        <label for="tipo_hospedagem">Tipo de Hospedagem:</label>
                        <select id="tipo_hospedagem" name="tipo_hospedagem" required>
                            <option value="hotel">Hotel</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="hostel">Hostel</option>
                            <option value="castelo">Castelo</option>
                            <option value="cabana">Cabana</option>
                            <option value="resort">Resort</option>
                        </select>

                        <label for="ficheiro1">Foto 1:</label>
                        <input type="file" id="ficheiro1" name="ficheiro1">

                        <label for="ficheiro2">Foto 2:</label>
                        <input type="file" id="ficheiro2" name="ficheiro2">

                        <label for="ficheiro3">Foto 3:</label>
                        <input type="file" id="ficheiro3" name="ficheiro3">

                        <input type="submit" value="Vender">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../views/partials/footer.php' ?>
</body>

</html>
