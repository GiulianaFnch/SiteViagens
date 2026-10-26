<?php
// Guarda ou apaga uma avaliação e volta à página de detalhes do item.
include '../../config/valida.php';
csrf_verificar(); // só aceita POST com o token CSRF da sessão
include '../../config/liga_bd.php';
include '../../config/avaliacoes.php';

$id_user = $_SESSION['id'];
$tipo = $_POST['tipo_item'] ?? '';
$item_id = (int) ($_POST['item_id'] ?? 0);
if (!in_array($tipo, AVALIACAO_TIPOS, true) || $item_id <= 0) {
    http_response_code(400);
    exit('Pedido inválido.');
}

$mensagem = null;
if (isset($_POST['apagar'])) {
    // Cada utilizador apaga a sua avaliação; o administrador pode apagar qualquer uma (moderação)
    $id_avaliacao = (int) ($_POST['id_avaliacao'] ?? 0);
    if (avaliacao_e_admin($ligacao, $id_user)) {
        $stmt = $ligacao->prepare("DELETE FROM t_avaliacoes WHERE id = ?");
        $stmt->bind_param("i", $id_avaliacao);
    } else {
        $stmt = $ligacao->prepare("DELETE FROM t_avaliacoes WHERE id = ? AND id_user = ?");
        $stmt->bind_param("ii", $id_avaliacao, $id_user);
    }
    $stmt->execute();
    $mensagem = $stmt->affected_rows > 0 ? 'Avaliação apagada.' : 'Não foi possível apagar a avaliação.';
} else {
    $nota = (int) ($_POST['nota'] ?? 0);
    $comentario = trim($_POST['comentario'] ?? '');

    if (!avaliacao_pode_avaliar($ligacao, $id_user, $tipo, $item_id)) {
        $mensagem = 'Só pode avaliar depois de usar uma reserva sua.';
    } elseif ($nota < 1 || $nota > 5) {
        $mensagem = 'Escolha uma nota de 1 a 5 estrelas.';
    } elseif (mb_strlen($comentario) > AVALIACAO_MAX_COMENTARIO) {
        $mensagem = 'O comentário pode ter no máximo ' . AVALIACAO_MAX_COMENTARIO . ' caracteres.';
    } else {
        // Uma avaliação por utilizador e item: avaliar de novo substitui a anterior
        $comentario = $comentario === '' ? null : $comentario;
        $stmt = $ligacao->prepare("INSERT INTO t_avaliacoes (id_user, tipo_item, item_id, nota, comentario) VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE nota = VALUES(nota), comentario = VALUES(comentario)");
        $stmt->bind_param("isiis", $id_user, $tipo, $item_id, $nota, $comentario);
        $stmt->execute();
        $mensagem = 'Obrigado pela sua avaliação!';
    }
}

$_SESSION['avaliacao_mensagem'] = $mensagem;
$pagina = $tipo === 'hospedagem' ? '../hotels/detalhes_hospedagem.php' : '../tours/detalhes_tours.php';
header('Location: ' . $pagina . '?id=' . $item_id . '#avaliacoes');
exit;
