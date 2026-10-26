<?php
// Avaliações de passeios ('atividade') e hospedagens: média, permissões e estrelas.
require_once __DIR__ . '/acesso.php';

const AVALIACAO_TIPOS = ['atividade', 'hospedagem'];
const AVALIACAO_MAX_COMENTARIO = 1000;

// Média e número de avaliações de um item
function avaliacao_resumo($ligacao, $tipo, $item_id)
{
    $stmt = $ligacao->prepare("SELECT AVG(nota) AS media, COUNT(*) AS total FROM t_avaliacoes WHERE tipo_item = ? AND item_id = ?");
    $stmt->bind_param("si", $tipo, $item_id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return ['media' => $r['media'] === null ? null : (float) $r['media'], 'total' => (int) $r['total']];
}

/**
 * Só avalia quem reservou o item e já o usou: hospedagem com check-out no passado,
 * passeio com data no passado. Reservas antigas, sem datas, também contam.
 */
function avaliacao_pode_avaliar($ligacao, $id_user, $tipo, $item_id)
{
    $stmt = $ligacao->prepare("SELECT 1 FROM t_reservas
        WHERE user_id = ? AND tipo_reserva = ? AND item_id = ? AND estado = 'confirmada'
          AND (data_inicio IS NULL OR (CASE WHEN tipo_reserva = 'hospedagem' THEN data_fim ELSE data_inicio END) <= CURDATE())
        LIMIT 1");
    $stmt->bind_param("isi", $id_user, $tipo, $item_id);
    $stmt->execute();
    $pode = $stmt->get_result()->num_rows > 0;
    $stmt->close();
    return $pode;
}

// id do vendedor dono do passeio/hospedagem (ou null se o item não existir)
function avaliacao_dono_item($ligacao, $tipo, $item_id)
{
    $tabela = $tipo === 'hospedagem' ? 't_hospedagem' : 't_artigo';
    $stmt = $ligacao->prepare("SELECT id_user FROM $tabela WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r ? (int) $r['id_user'] : null;
}

function avaliacao_e_admin($ligacao, $id_user)
{
    $stmt = $ligacao->prepare("SELECT tipo_user FROM t_user WHERE id = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $r && (int) $r['tipo_user'] === TIPO_ADMIN;
}

// "★★★★☆" arredondado à estrela mais próxima (ou estrelas vazias sem avaliações)
function avaliacao_estrelas($media)
{
    $cheias = $media === null ? 0 : (int) round($media);
    return str_repeat('★', $cheias) . str_repeat('☆', 5 - $cheias);
}

// Texto curto para cartões e cabeçalhos: "★★★★☆ 4,3 (12 avaliações)"
function avaliacao_texto($media, $total)
{
    if ($total == 0) {
        return '☆☆☆☆☆ Sem avaliações';
    }
    return avaliacao_estrelas($media) . ' ' . number_format($media, 1, ',', '') . ' (' . $total . ($total == 1 ? ' avaliação)' : ' avaliações)');
}
