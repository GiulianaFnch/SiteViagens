<?php
    require_once __DIR__ . '/config.php';

    // cria a variável $ligacao usando as credenciais de config/config.php
    $ligacao = mysqli_connect($config['db_host'], $config['db_user'], $config['db_pass'], $config['db_name']);
    if (!$ligacao)
        die("Erro na ligação à base de dados: " . mysqli_connect_error());
    mysqli_set_charset($ligacao, 'utf8mb4');
 ?>
