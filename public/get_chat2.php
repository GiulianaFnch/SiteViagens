<?php
include '../config/valida.php'; // Validação do usuário logado
include '../config/liga_bd.php'; // Conexão com o banco de dados

$id_user = $_SESSION['id']; // ID do usuário logado
$id_destinatario = $_GET['user_id']; // ID do usuário com quem estamos conversando

// Buscando as mensagens entre o usuário logado e o destinatário
$sql = "SELECT t_art_comen.comentario, t_user.nick, t_user.foto
        FROM t_art_comen
        JOIN t_user ON t_art_comen.id_remetente = t_user.id
        WHERE (t_art_comen.id_remetente = $id_user AND t_art_comen.id_destinatario = $id_destinatario)
           OR (t_art_comen.id_remetente = $id_destinatario AND t_art_comen.id_destinatario = $id_user)
        ORDER BY t_art_comen.id ASC";

$resultado = mysqli_query($ligacao, $sql);

// Verificando se há mensagens
if (mysqli_num_rows($resultado) > 0) {
    while ($linha = mysqli_fetch_assoc($resultado)) {
        echo "<div class='message'><img src='../../assets/images/pics/" . $linha['foto'] . "' class='user-icon-message' alt='" . htmlspecialchars($linha['nick']) . "'>
              <b>" . htmlspecialchars($linha['nick']) . ":</b> " . htmlspecialchars($linha['comentario']) . "</div>";
    }
} else {
    echo "<div class='message'>Nenhuma mensagem encontrada.</div>";
}
?>
