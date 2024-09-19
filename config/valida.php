<?php
	session_start();

	// Defina a URL base do seu site
	define('BASE_URL', 'http://localhost/siteviagens/');

	// Validação das variáveis de sessão
	if((!isset($_SESSION['id']) == true) and (!isset($_SESSION['nick']) == true))
	{
		echo "<h2>Erro no acesso!</h2>";
		echo "<p>Você precisa estar logado para acessar esta página.</p>";
		echo "<meta http-equiv='refresh' content='3;url=" . BASE_URL . "index.html'>";
		header("refresh:2;url=" . BASE_URL . "public/login.php");
		exit(); // Certifique-se de sair após o redirecionamento
	}
?>
