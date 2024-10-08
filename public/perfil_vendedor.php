<?php
include_once "../config/valida.php";
include_once "../config/liga_bd.php";

// Verifica se o user_id do vendedor foi passado na URL
if (isset($_GET['user_id'])) {
    $user_id = (int)$_GET['user_id']; // Obtém o ID do vendedor da URL

    // Consulta para buscar informações do vendedor na tabela t_user
    $sql = "SELECT * FROM t_user WHERE id = ?"; 
    $stmt = $ligacao->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Verifica se o vendedor foi encontrado
    if ($linha = $resultado->fetch_assoc()) {
        // Agora $linha contém os dados do vendedor
    } else {
        // Se não encontrar, define $linha como null
        $linha = null;
    }
} else {
    // Se user_id não foi passado, define $linha como null
    $linha = null;
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Vendedor</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Perfil do Vendedor</h1>

        <?php if ($linha): ?>
            <img src="<?php echo $linha['foto'] ? '../assets/images/pics/' . htmlspecialchars($linha['foto']) : 'https://bootdey.com/img/Content/avatar/avatar1.png'; ?>" alt="Foto do Vendedor" style="width: 100px; height: 100px; border-radius: 50%;">
            <p>Nome da Marca: <?php echo htmlspecialchars($linha['nome_marca']); ?></p>
            <p>Nick: <?php echo htmlspecialchars($linha['nick']); ?></p>
            <p>Email: <?php echo htmlspecialchars($linha['email']); ?></p>

            <p>Biografia: <?php echo htmlspecialchars($linha['biografia']); ?></p>
            
            <p>Outras informações...</p>

            
        <?php else: ?>
            <p>Vendedor não encontrado ou não é um vendedor.</p>
        <?php endif; ?>
        
        <a href="reservas.php" class="btn btn-primary">Voltar</a> <!-- Botão para voltar à página anterior -->
    </div>
</body>
</html>