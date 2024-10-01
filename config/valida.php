<?php
session_start();

// Defina a URL base do seu site
define('BASE_URL', 'http://localhost/siteviagens/');

// Validação das variáveis de sessão
if (!isset($_SESSION['id']) || !isset($_SESSION['nick'])) {
	?>
	<!DOCTYPE html>
	<html lang="pt-BR">

	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Validação</title>

		<link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
			integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

		<link rel="stylesheet" href="../assets/css/style.css">

		<link
			href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
			rel="stylesheet">
	</head>

	<body>
		<?php include '../views/partials/header.php'; ?>

		<main>
			<div class="py-5 text-center"><br><br>
			<h2>Erro no acesso!</h2>
			<p>Você precisa estar logado para acessar esta página.</p>
			<meta http-equiv='refresh' content='3;url=" . BASE_URL . "index.html'>
			</div>
		</main>

		<?php
		include '../views/partials/footer.php';
		?>

	</body>

	</html>
	<?php
	header("refresh:2;url=" . BASE_URL . "public/login.php");
	exit(); // Certifique-se de sair após o redirecionamento
}

// Conexão com o banco de dados
include 'liga_bd.php';

// Verificação do tipo de usuário
$id_user = $_SESSION['id'];
$sql = "SELECT tipo_user FROM t_user WHERE id = ?";
$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['tipo_user'] == 1) {
	echo "<h2>Erro no acesso!</h2>";
	echo "<p>Você não tem permissão para acessar esta página, você está bloqueado!</p>";
	echo "<meta http-equiv='refresh' content='3;url=" . BASE_URL . "index.html'>";
	header("refresh:2;url=" . BASE_URL . "public/login.php");
	exit(); // Certifique-se de sair após o redirecionamento
}

$stmt->close();
//$ligacao->close();
?>