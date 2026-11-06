<?php
// Pacotes de viagem: pesquisa (public/pacotes/pacotes.php), validação do formulário do vendedor
// (public/vendedor/pacotes.php) e fotos. As reservas de pacotes estão em config/reservas.php.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/reservas.php';

const PACOTE_PASTA_FOTOS = __DIR__ . '/../public/pacotes/images';
const PACOTE_MAX_DIAS = 30;

function pacote_foto_url($foto)
{
    return BASE_URL . 'public/pacotes/images/' . rawurlencode($foto ?: 'pacotes.jpg');
}

// Linhas do campo "o que inclui" (uma por linha, sem linhas vazias)
function pacote_inclui($texto)
{
    return array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $texto))));
}

/**
 * Pacotes ativos com partidas por fazer, opcionalmente filtrados por destino (texto no título ou
 * destino), por uma data de partida possível e por nº de pessoas (só pacotes sem limite ou com
 * vagas suficientes por partida). Devolve [lista da página, total].
 */
function pacotes_pesquisar($ligacao, $destino, $data, $pessoas, $limite, $offset)
{
    $condicoes = ["p.ativo = 1", "p.data_fim > CURDATE()"];
    $tipos = '';
    $valores = [];
    if ($destino !== '') {
        $condicoes[] = "(p.destino LIKE ? OR p.titulo LIKE ?)";
        $padrao = '%' . addcslashes($destino, '%_\\') . '%';
        $tipos .= 'ss';
        array_push($valores, $padrao, $padrao);
    }
    if ($data !== null) {
        $condicoes[] = "? BETWEEN p.data_inicio AND p.data_fim";
        $tipos .= 's';
        $valores[] = $data;
    }
    if ($pessoas > 0) {
        $condicoes[] = "(p.vagas IS NULL OR p.vagas >= ?)";
        $tipos .= 'i';
        $valores[] = $pessoas;
    }
    $where = implode(' AND ', $condicoes);

    $stmt = $ligacao->prepare("SELECT COUNT(*) FROM t_pacotes p WHERE $where");
    if ($tipos !== '') {
        $stmt->bind_param($tipos, ...$valores);
    }
    $stmt->execute();
    $total = (int) $stmt->get_result()->fetch_row()[0];

    $stmt = $ligacao->prepare("SELECT p.*, u.nome_marca, u.nick FROM t_pacotes p JOIN t_user u ON u.id = p.id_user
        WHERE $where ORDER BY p.data_criacao DESC, p.id DESC LIMIT ? OFFSET ?");
    $tipos .= 'ii';
    array_push($valores, $limite, $offset);
    $stmt->bind_param($tipos, ...$valores);
    $stmt->execute();
    return [$stmt->get_result()->fetch_all(MYSQLI_ASSOC), $total];
}

/**
 * Lê e valida os campos do formulário do vendedor. Devolve [dados, erro]; erro é null se estiver tudo bem.
 */
function pacote_ler_formulario($post)
{
    $dados = [
        'titulo' => trim($post['titulo'] ?? ''),
        'destino' => trim($post['destino'] ?? ''),
        'descricao' => trim($post['descricao'] ?? ''),
        'inclui' => trim($post['inclui'] ?? ''),
        'preco' => str_replace(',', '.', trim($post['preco'] ?? '')),
        'duracao_dias' => (int) ($post['duracao_dias'] ?? 0),
        'data_inicio' => $post['data_inicio'] ?? '',
        'data_fim' => $post['data_fim'] ?? '',
        'vagas' => trim($post['vagas'] ?? ''),
    ];
    if ($dados['titulo'] === '' || mb_strlen($dados['titulo']) > 100) {
        return [$dados, 'O título é obrigatório (até 100 caracteres).'];
    }
    if ($dados['destino'] === '' || mb_strlen($dados['destino']) > 100) {
        return [$dados, 'O destino é obrigatório (até 100 caracteres).'];
    }
    if ($dados['descricao'] === '' || mb_strlen($dados['descricao']) > 5000) {
        return [$dados, 'A descrição é obrigatória (até 5000 caracteres).'];
    }
    if (mb_strlen($dados['inclui']) > 2000) {
        return [$dados, 'O campo "o que inclui" pode ter no máximo 2000 caracteres.'];
    }
    if (!is_numeric($dados['preco']) || $dados['preco'] <= 0 || $dados['preco'] > 100000) {
        return [$dados, 'Indique um preço por pessoa válido.'];
    }
    if ($dados['duracao_dias'] < 1 || $dados['duracao_dias'] > PACOTE_MAX_DIAS) {
        return [$dados, 'A duração tem de ser entre 1 e ' . PACOTE_MAX_DIAS . ' dias.'];
    }
    $inicio = reserva_data($dados['data_inicio']);
    $fim = reserva_data($dados['data_fim']);
    if (!$inicio || !$fim || $fim < $inicio) {
        return [$dados, 'Indique o período das partidas (a última partida não pode ser antes da primeira).'];
    }
    if ($dados['vagas'] !== '' && (!ctype_digit($dados['vagas']) || (int) $dados['vagas'] < 1)) {
        return [$dados, 'As vagas por partida têm de ser um número positivo (ou fique em branco para sem limite).'];
    }
    $dados['preco'] = round((float) $dados['preco'], 2);
    $dados['vagas'] = $dados['vagas'] === '' ? null : (int) $dados['vagas'];
    $dados['inclui'] = $dados['inclui'] === '' ? null : $dados['inclui'];
    return [$dados, null];
}
