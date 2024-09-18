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

    // Lidar com upload de foto
    $fotoNova = $fotoAntiga;  // Manter a foto antiga por padrão
    if (isset($_FILES['ficheiro']) && $_FILES['ficheiro']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 800 * 1024;  // 800 KB

        // Verificar tipo e tamanho do arquivo
        if (in_array($_FILES['ficheiro']['type'], $allowed_types) && $_FILES['ficheiro']['size'] <= $max_size) {
            $fotoNova = $_FILES['ficheiro']['name'];
            $destino = 'images/pics/' . $fotoNova;

            // Mover o arquivo para o diretório desejado
            if (move_uploaded_file($_FILES['ficheiro']['tmp_name'], $destino)) {
                // Remover a foto antiga, se for o caso
                if ($fotoAntiga && file_exists('../images/pics/' . $fotoAntiga)) {
                    unlink('../images/pics/' . $fotoAntiga);
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
    mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));

    // Redirecionar após o sucesso
    header('Location: perfil.php');
    exit;
} else {
    // Se não for uma requisição POST, redirecione para a página de perfil
    header('Location: perfil.php');
    exit;
}

mysqli_close($ligacao);
?>
