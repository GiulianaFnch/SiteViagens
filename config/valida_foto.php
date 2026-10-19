<?php
// código upload foto de perfil (usado no registo)
// Define $foto (nome do ficheiro guardado) e $uploadOk (1 = sucesso, 0 = erro)
require_once __DIR__ . '/upload.php';

$foto = isset($_FILES["ficheiro"]) ? salvar_imagem($_FILES["ficheiro"], __DIR__ . "/../assets/images/pics/") : null;
$uploadOk = $foto === null ? 0 : 1;
if ($uploadOk == 0) {
    echo "Só são permitidas imagens JPG, PNG, GIF, WEBP ou BMP com no máximo 5MB.";
}
