<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';

$uploadOk = 1;
$target_dir = "../../public/tours/imagens/";

function processar_foto($file, $id_artigo, $numero_foto) {
    global $uploadOk, $ligacao, $target_dir;
    
    if ($uploadOk == 0) {
        return;
    }

    $target_file = $target_dir . basename($file["name"]);
    $check = getimagesize($file["tmp_name"]);
    if($check === false) {
        echo "O arquivo não é uma imagem.";
        $uploadOk = 0;
        return;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        if ($numero_foto == 1) {
            return $target_file; 
        } else {
            $sql = "UPDATE t_artigo SET foto{$numero_foto}='{$target_file}' WHERE id={$id_artigo};";
            mysqli_query($ligacao, $sql);
        }
    } else {
        echo "O seu ficheiro não foi enviado.";
        $uploadOk = 0;
    }
}

// Captura os dados do formulário com verificações
$id_user = $_POST['id_user'];
$valor_cat = $_POST['valor_cat'];
$valor_subcat = $_POST['valor_subcat'];
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];
$estado = $_POST['estado'];
$localizacao = isset($_POST['localizacao']) ? $_POST['localizacao'] : '';
$data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : '';
$data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : '';

$sql = "INSERT INTO t_artigo (id_user, cat, subcat, titulo, descricao, preco, estado, localizacao, data_inicio, data_fim, foto1) 
        VALUES ('$id_user', '$valor_cat', '$valor_subcat', '$titulo', '$descricao', '$preco', '$estado', '$localizacao', 
        '$data_inicio', '$data_fim', '');";

if (mysqli_query($ligacao, $sql)) {
    $id_artigo = mysqli_insert_id($ligacao);
    echo "<h2>Registro efetuado com sucesso! </h2>";

    $_FILES["ficheiro"] = $_FILES["ficheiro1"];
    processar_foto($_FILES["ficheiro"], $id_artigo, 1);

    if (!empty($_FILES['ficheiro2']['name'])) {
        $_FILES["ficheiro"] = $_FILES["ficheiro2"];
        processar_foto($_FILES["ficheiro"], $id_artigo, 2);
    }

    if (!empty($_FILES['ficheiro3']['name'])) {
        $_FILES["ficheiro"] = $_FILES["ficheiro3"];
        processar_foto($_FILES["ficheiro"], $id_artigo, 3);
    }
} else {
    echo "Erro: " . mysqli_error($ligacao);
}

mysqli_close($ligacao);
?>
<br />
<a href="admin.php" target="_self">Volta ao Menu</a>
