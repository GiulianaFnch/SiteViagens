<?php 
include 'valida_vendedor.php'; // Validação do usuário logado
include '../../config/liga_bd.php'; // Conexão com o banco de dados

$id_user = intval($_SESSION['id']); // ID do usuário logado


$id_destinatario = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;  // ID do usuário com quem estamos conversando

// Preparando a consulta para buscar as mensagens
$stmt = $ligacao->prepare("SELECT t_art_comen.comentario, t_user.nick, t_user.foto 
                            FROM t_art_comen
                            JOIN t_user ON t_art_comen.id_remetente = t_user.id
                            WHERE (t_art_comen.id_remetente = ? AND t_art_comen.id_destinatario = ?)
                               OR (t_art_comen.id_remetente = ? AND t_art_comen.id_destinatario = ?)
                            ORDER BY t_art_comen.id ASC");

// Bindando os parâmetros
$stmt->bind_param("iiii", $id_user, $id_destinatario, $id_destinatario, $id_user);

// Executando a consulta
$stmt->execute();

// Obtendo o resultado
$resultado = $stmt->get_result();

// Verificando se há mensagens
if ($resultado && mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        echo "<div class='message'><img src='../../assets/images/pics/" . htmlspecialchars($linha['foto']) . "' class='user-icon-message' alt='" . htmlspecialchars($linha['nick']) . "'>
              <b>" . htmlspecialchars($linha['nick']) . ":</b> " . htmlspecialchars($linha['comentario']) . "</div>";
    }
} else {
    echo "<div class='message'>Nenhuma mensagem encontrada.</div>";
}

// Fechando a declaração
$stmt->close();
?>
