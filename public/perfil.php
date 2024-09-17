<!-- 
    Essa página será inspirada no arquivo do projeto do Rui chamado perfil1.php que está na pasta exemplos/projeto_rui/perfil1.php

    Pra conseguir editar que ainda colocar o perfil2.php
-->

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en"> <!-- Ainda é preciso ajustar idioma, head, title, etc. -->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CodingDung | Profile Template</title>
    <link rel="stylesheet" href="../assets/css/styleperfil.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <?php 
    include '../config/valida.php';
    ?>

</head>

<body>

    <?php
    // ligação a base de dados
    include '../config/liga_bd.php';
    // crio a instrucao sql para selecionar todos os registros
    $sql = "SELECT * FROM t_user WHERE id=$_SESSION[id]";
    // a variavel resultado guarda todos os dados vindos dos clientes
    $resultado = mysqli_query($ligacao, $sql) or die(mysqli_error($ligacao));
    $linha = mysqli_fetch_array($resultado); ?>

    <div class="container light-style flex-grow-1 container-p-y">
        <h2>Bem vinda(o), <?php echo $linha['nome'];?></h2>
        <h4 class="font-weight-bold py-3 mb-4 text-center">
            Account Settings
        </h4>
        <div class="card shadow-sm rounded-lg">
            <div class="row no-gutters">
                <div class="col-md-3 p-3 bg-light rounded-left">
                    <div class="list-group list-group-flush account-settings-links">
                        
                        <a class="list-group-item list-group-item-action active" data-toggle="list" href="#account-general"><i class="bi bi-house-door">  Editar perfil</i></a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-social-links"><i class="bi bi-layout-wtf"></i></a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-connections"><i class="bi bi-chat-dots-fill"></i></a>
                        <a class="list-group-item list-group-item-action" data-toggle="list" href="#account-notifications"><i class="bi bi-gear"></i></a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content p-4">
                        <div class="tab-pane fade active show" id="account-general">
                            <div class="media align-items-center mb-3">
                                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="avatar" class="rounded-circle mr-3" style="width: 80px;">
                                <div class="media-body">
                                    <label class="btn btn-outline-primary rounded-pill">
                                        Upload New Photo
                                        <input type="file" class="account-settings-fileinput">
                                    </label>
                                    <button type="button" class="btn btn-light ml-2 rounded-pill">Reset</button>
                                    <div class="small text-muted mt-1">Allowed JPG, GIF or PNG. Max size of 800K</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" class="form-control rounded-pill" value="nmaxwell">
                            </div>
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" class="form-control rounded-pill" value="Nelle Maxwell">
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="text" id="email" class="form-control rounded-pill" value="nmaxwell@mail.com">
                                <div class="alert alert-warning mt-2 rounded-pill">
                                    Your email is not confirmed. Please check your inbox.<br>
                                    <a href="javascript:void(0)" class="text-primary">Resend confirmation</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" id="company" class="form-control rounded-pill" value="Company Ltd.">
                            </div>
                        </div>

                        <!-- Add other tab content similar to above, for password change, info, etc. -->
                    </div>
                </div>
            </div>
        </div>
        <div class="text-right mt-3">
            <button type="button" class="btn btn-primary rounded-pill">Save Changes</button>
            <button type="button" class="btn btn-light rounded-pill">Cancel</button>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
