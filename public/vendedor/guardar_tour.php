<?php
include 'valida_vendedor.php';
csrf_verificar(); // só aceita POST com o token CSRF da sessão
include '../../config/liga_bd.php';
require_once '../../config/upload.php';

$uploadOk = 1;
$target_dir = "../../public/tours/imagens/";

function processar_foto($file, $id_artigo, $numero_foto)
{
    global $uploadOk, $ligacao, $target_dir;

    if ($uploadOk == 0) {
        return;
    }

    $file_name = salvar_imagem($file, $target_dir);
    if ($file_name === null) {
        echo "O arquivo não é uma imagem válida.";
        $uploadOk = 0;
        return;
    }

    $numero_foto = (int) $numero_foto; // 1, 2 ou 3
    $stmt = $ligacao->prepare("UPDATE t_artigo SET foto{$numero_foto} = ? WHERE id = ?");
    $stmt->bind_param("si", $file_name, $id_artigo);
    $stmt->execute();
}

// Captura os dados do formulário com verificações
$id_user = $_SESSION['id'];
$valor_cat = $_POST['valor_cat'];
$valor_subcat = $_POST['valor_subcat'];
$titulo = $_POST['titulo'];
$descricao = $_POST['descricao'];
$preco = $_POST['preco'];
$estado = $_POST['estado'];
$localizacao = isset($_POST['localizacao']) ? $_POST['localizacao'] : '';
$data_inicio = isset($_POST['data_inicio']) ? $_POST['data_inicio'] : '';
$data_fim = isset($_POST['data_fim']) ? $_POST['data_fim'] : '';
// Lotação por dia: vazio = sem limite
$vagas = isset($_POST['vagas']) && $_POST['vagas'] !== '' ? max(1, (int) $_POST['vagas']) : null;

$stmt = $ligacao->prepare("INSERT INTO t_artigo (id_user, cat, subcat, titulo, descricao, preco, estado, localizacao, data_inicio, data_fim, vagas, foto1)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '')");
$stmt->bind_param("iiissdisssi", $id_user, $valor_cat, $valor_subcat, $titulo, $descricao, $preco, $estado, $localizacao, $data_inicio, $data_fim, $vagas);

if ($stmt->execute()) {
    $id_artigo = $stmt->insert_id;
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
    error_log("guardar_tour: " . $stmt->error);
    echo "Erro ao registar o passeio.";
}

mysqli_close($ligacao);
?>
<br />
<a href="admin.php" target="_self">Volta ao Menu</a>