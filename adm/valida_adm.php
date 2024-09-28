<?php
session_start();

// Defina a URL base do seu site
define('BASE_URL', 'http://localhost/siteviagens/');

// Validação das variáveis de sessão
if (!isset($_SESSION['id']) || !isset($_SESSION['nick'])) {
    echo "<h2>Erro no acesso!</h2>";
    echo "<p>Você precisa estar logado para acessar esta página.</p>";
    echo "<meta http-equiv='refresh' content='3;url=" . BASE_URL . "index.html'>";
    header("refresh:2;url=" . BASE_URL . "public/login.php");
    exit(); // Certifique-se de sair após o redirecionamento
}

// Conexão com o banco de dados
include '../config/liga_bd.php';

// Verificação do tipo de usuário
$id_user = $_SESSION['id'];
$sql = "SELECT tipo_user FROM t_user WHERE id = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['tipo_user'] != 4) {
    echo "<h2>Erro no acesso!</h2>";
    echo "<p>Você não tem permissão para acessar esta página.</p>";
    echo "<meta http-equiv='refresh' content='3;url=" . BASE_URL . "index.html'>";
    header("refresh:2;url=" . BASE_URL . "public/login.php");
    exit(); // Certifique-se de sair após o redirecionamento
}

$stmt->close();
$ligacao->close();
?>