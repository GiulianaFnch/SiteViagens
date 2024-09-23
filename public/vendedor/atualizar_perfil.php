<?php
include '../../config/valida.php';
include '../../config/liga_bd.php';

function getUserData() {
    global $ligacao;

    if (!isset($_SESSION['id'])) {
        return null;
    }

    $userId = $_SESSION['id'];
    $query = "SELECT nome, email, foto, nome_marca FROM t_user WHERE id = ?";
    $stmt = mysqli_prepare($ligacao, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

$userData = getUserData();

if (!$userData) {
    die("Usuário não encontrado ou não autenticado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    global $ligacao;

    $userId = $_SESSION['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $brandName = $_POST['brand_name']; // Nome da marca
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirm'];
    $hashedPassword = null;

    // Verifica se a senha foi alterada
    if (!empty($password) || !empty($passwordConfirm)) {
        if ($password !== $passwordConfirm) {
            echo json_encode(["status" => "error", "message" => "As senhas não coincidem."]);
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    // Lógica para upload da imagem
    $photoPath = $userData['foto']; // Mantém a foto atual por padrão
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../assets/images/pics/'; // Ajuste para o diretório correto
        $photoName = uniqid() . '-' . basename($_FILES['photo']['name']);
        $photoPath = $uploadDir . $photoName;

        // Move a imagem para o diretório especificado
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            echo json_encode(["status" => "error", "message" => "Erro ao fazer upload da imagem."]);
            exit;
        }
    }

    // Atualizando o banco de dados
    $query = "UPDATE t_user SET nome = ?, email = ?, nome_marca = ?" . (!empty($hashedPassword) ? ", senha = ?" : "") . ", foto = ? WHERE id = ?";
    $stmt = mysqli_prepare($ligacao, $query);

    if (!empty($hashedPassword)) {
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $brandName, $hashedPassword, $photoPath, $userId);
    } else {
        mysqli_stmt_bind_param($stmt, "ssssi", $username, $email, $brandName, $photoPath, $userId);
    }

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["status" => "success", "message" => "Informações atualizadas com sucesso!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Erro ao atualizar as informações: " . mysqli_error($ligacao)]);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($ligacao);
    exit;
}
?>
