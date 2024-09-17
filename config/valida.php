 <?php
	//validação das variaveis de sessão
	if((!isset ($_SESSION['id']) == true) and (!isset ($_SESSION['nick']) == true))
	{
		echo "<h2>Erro no acesso!</h2>";
		echo "<meta http-equiv='refresh' content='5;url='../index.html'>";

	}
 ?>
