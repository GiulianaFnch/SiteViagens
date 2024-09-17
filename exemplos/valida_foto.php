<?php
        //código upload foto
        $foto=basename($_FILES["ficheiro"]["name"]);
        $target_dir = "pics/";
        $target_file = $target_dir . basename($_FILES["ficheiro"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // verifica se é uma imagem
        if(isset($_POST["submit"])) {
            $check = getimagesize($_FILES["ficheiro"]["tmp_name"]);
            if($check !== false) {
                echo "Ficheiro é uma imagem - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "O ficheiro não é uma imagem.";
                $uploadOk = 0;
                }
        }
        // verifica se já existe
        if (file_exists($target_file)) {
            echo "O ficheiro já existe.";
            $uploadOk = 0;
        }

        // verifica o tamanho da imagem
        if ($_FILES["ficheiro"]["size"] > 5000000) { //5MB
            echo "O ficheiro é demasiado grande. Máximo 5MB";
            $uploadOk = 0;
        }
        // tipo de ficheiro permitido
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" && $imageFileType != "bmp" ) {
                echo "Só são permitidos ficheiros JPG, JPEG, PNG, GIF e BMP.";
                $uploadOk = 0;
        }
        ?>