 <?php

  // Inicia a sessão se ainda não tiver sido iniciada
  if (session_status() === PHP_SESSION_NONE) {
	session_start();
	}

	 // Verifica se a constante BASE_URL já está definida
	 if (!defined('BASE_URL')) {
		define('BASE_URL', 'http://seu-site.com/');
	  }

	//validação das variaveis de sessão
	if((!isset ($_SESSION['id']) == true) and (!isset ($_SESSION['nick']) == true))
	{
		echo "<h2>Erro no acesso!</h2>";
		echo "<meta http-equiv='refresh' content='2;url=../index.html'>";

	}
 ?>

<!-- 
	Tem que arrumar esse arquivo pq n ta redirecionando :((
-->
