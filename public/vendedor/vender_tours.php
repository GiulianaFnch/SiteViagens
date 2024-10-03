<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Vender Tours</title>
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
            /* Define a cor padrão dos links */
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

/* Adicionando espaço e estilo ao formulário */
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

    <script>
        function atualiza() {
            var categoria = document.getElementById("categoria").value;
            var subcategoria = document.getElementById("subcategoria").value;

            document.getElementById("valor_cat").value = categoria;
            document.getElementById("valor_subcat").value = subcategoria;
        }
        window.onload = atualiza; 
    </script>
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
                        <a class="menu-item" href="vender_tours.php"><i class="bi bi-bag"></i> Vender Tours</a>
                        <a class="menu-item" href="gerenciar_reservas.php"><i class="bi bi-magic"></i>Gestão de Reservas</a>
                        <a class="menu-item" href="gestao_tours.php"><i class="bi bi-train-freight-front"></i> Gestão de Tours</a>
                        <a class="menu-item" href="chat.php"><i class="bi bi-chat-dots"></i> Chat</a>
                        <a class="menu-item" href="configuracoes2.php"><i class="bi bi-gear"></i> Configurações</a>
                    </nav>
                </div>

                <div class="col-md-9 p-4">
                    <h1 class="h2">Vender Passeios</h1>
                    <?php
                    $categoria = isset($_POST['categoria']) ? $_POST['categoria'] : 1;
                    ?>

                    <form action="vender_tours.php" id="f1" method="post">
                        Categoria:
                        <select name="categoria" id="categoria" onchange="this.form.submit();">
                            <?php
                            $sql = "SELECT * FROM t_categoria";
                            $resultado = mysqli_query($ligacao, $sql);
                            while ($linha = mysqli_fetch_assoc($resultado)) {
                                $selected = ($categoria == $linha['id']) ? 'selected' : '';
                                echo "<option value='" . $linha['id'] . "' $selected>" . $linha['categoria'] . "</option>";
                            }
                            ?>
                        </select>
                        <br>Subcategoria:
                        <select name="subcategoria" id="subcategoria" onchange="atualiza();">
                            <?php
                            $sql2 = "SELECT * FROM t_subcat WHERE categoria = " . $categoria;
                            $resultado2 = mysqli_query($ligacao, $sql2);
                            while ($linha2 = mysqli_fetch_assoc($resultado2)) {
                                echo "<option value='" . $linha2['id'] . "'>" . $linha2['subcat'] . "</option>";
                            }
                            ?>
                        </select>
                    </form>


                    <form action="vender_tours2.php" id="f2" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id_user" value="<?php echo $_SESSION['id']; ?>" required>
                        <input type="hidden" name="valor_cat" value="<?php echo $categoria; ?>" id="valor_cat" required>
                        <input type="hidden" name="valor_subcat" value="" id="valor_subcat" required>

                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao"></textarea>

                        <label for="preco">Preço:</label>
                        <input type="text" id="preco" name="preco" required>

                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado">
                            <option value="1">1 estrela</option>
                            <option value="2">2 estrelas</option>
                            <option value="3">3 estrelas</option>
                            <option value="4">4 estrelas</option>
                            <option value="5">5 estrelas</option>
                        </select>

                        <label for="localizacao">Localização:</label>
                        <input type="text" id="localizacao" name="localizacao" required>

                        <label for="data_inicio">Data de Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" required>

                        <label for="data_fim">Data de Fim:</label>
                        <input type="date" id="data_fim" name="data_fim" required>

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
