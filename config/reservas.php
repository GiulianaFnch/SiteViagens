<?php
// Regras de reserva: validação de datas, cálculo do preço e disponibilidade.
// Usado no carrinho (adicionar, mostrar, finalizar) e nos painéis de reservas,
// para que o preço e a disponibilidade sejam sempre calculados no servidor.
//
// Significado das colunas em t_carrinho / t_reservas:
//   hospedagem: data_inicio = check-in, data_fim = check-out, quantidade = nº de quartos
//   atividade:  data_inicio = dia do passeio (data_fim igual), quantidade = nº de pessoas
//   voo:        sem datas, quantidade = 1

const RESERVA_MAX_NOITES = 60;
const RESERVA_MAX_PESSOAS = 20;

// Converte "AAAA-MM-DD" numa data; devolve null se for inválida
function reserva_data($texto)
{
    if (!is_string($texto) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $texto)) {
        return null;
    }
    $data = DateTimeImmutable::createFromFormat('!Y-m-d', $texto);
    return ($data && $data->format('Y-m-d') === $texto) ? $data : null;
}

function reserva_noites($inicio, $fim)
{
    return (int) reserva_data($inicio)->diff(reserva_data($fim))->days;
}

// Formata uma data da BD para dd/mm/aaaa (ou "—" se não existir)
function reserva_formatar_data($texto)
{
    $data = reserva_data($texto);
    return $data ? $data->format('d/m/Y') : '—';
}

function reserva_formatar_preco($valor)
{
    return $valor === null ? '—' : number_format((float) $valor, 2, ',', '.') . ' €';
}

/**
 * Maior número de quartos ocupados numa mesma noite entre $inicio (check-in) e $fim (check-out).
 * Só conta reservas confirmadas com datas. $ignorar_reserva permite excluir uma reserva
 * (usado quando se edita essa reserva).
 */
function reserva_quartos_ocupados($ligacao, $id_hospedagem, $inicio, $fim, $ignorar_reserva = 0)
{
    $stmt = $ligacao->prepare("SELECT data_inicio, data_fim, quantidade FROM t_reservas
        WHERE tipo_reserva = 'hospedagem' AND item_id = ? AND estado = 'confirmada' AND id <> ?
          AND data_inicio < ? AND data_fim > ?");
    $stmt->bind_param("iiss", $id_hospedagem, $ignorar_reserva, $fim, $inicio);
    $stmt->execute();
    $reservas = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $maximo = 0;
    for ($noite = reserva_data($inicio); $noite < reserva_data($fim); $noite = $noite->modify('+1 day')) {
        $dia = $noite->format('Y-m-d');
        $ocupados = 0;
        foreach ($reservas as $r) {
            if ($r['data_inicio'] <= $dia && $r['data_fim'] > $dia) {
                $ocupados += (int) $r['quantidade'];
            }
        }
        $maximo = max($maximo, $ocupados);
    }
    return $maximo;
}

/**
 * Valida um item e calcula o seu preço com os valores atuais da BD.
 *
 * Devolve um array com: erro (null se estiver tudo bem), titulo, quantidade,
 * data_inicio, data_fim, noites, preco_unitario, preco_total.
 */
function reserva_preparar_item($ligacao, $tipo, $id_item, $quantidade, $data_inicio, $data_fim, $id_user, $ignorar_reserva = 0)
{
    $item = [
        'erro' => null, 'titulo' => '', 'quantidade' => (int) $quantidade,
        'data_inicio' => null, 'data_fim' => null, 'noites' => null,
        'preco_unitario' => null, 'preco_total' => null,
    ];
    $hoje = new DateTimeImmutable('today');
    $id_item = (int) $id_item;

    if ($tipo === 'hospedagem') {
        $stmt = $ligacao->prepare("SELECT nome, preco_diaria, n_quartos, data_inicio, data_fim FROM t_hospedagem WHERE id = ?");
        $stmt->bind_param("i", $id_item);
        $stmt->execute();
        $h = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$h) {
            return ['erro' => 'Esta hospedagem já não existe.'] + $item;
        }
        $item['titulo'] = $h['nome'];

        $checkin = reserva_data($data_inicio);
        $checkout = reserva_data($data_fim);
        if (!$checkin || !$checkout) {
            return ['erro' => 'Escolha as datas de check-in e check-out.'] + $item;
        }
        $item['data_inicio'] = $checkin->format('Y-m-d');
        $item['data_fim'] = $checkout->format('Y-m-d');
        if ($checkin < $hoje) {
            return ['erro' => 'A data de check-in já passou.'] + $item;
        }
        if ($checkout <= $checkin) {
            return ['erro' => 'O check-out tem de ser depois do check-in.'] + $item;
        }
        $noites = reserva_noites($item['data_inicio'], $item['data_fim']);
        $item['noites'] = $noites;
        if ($noites > RESERVA_MAX_NOITES) {
            return ['erro' => 'O máximo por reserva é de ' . RESERVA_MAX_NOITES . ' noites.'] + $item;
        }
        if (($h['data_inicio'] && $item['data_inicio'] < $h['data_inicio']) || ($h['data_fim'] && $item['data_fim'] > $h['data_fim'])) {
            return ['erro' => 'A hospedagem só está disponível de ' . reserva_formatar_data($h['data_inicio'])
                . ' a ' . reserva_formatar_data($h['data_fim']) . '.'] + $item;
        }
        if ($item['quantidade'] < 1 || $item['quantidade'] > (int) $h['n_quartos']) {
            return ['erro' => 'Escolha entre 1 e ' . (int) $h['n_quartos'] . ' quartos.'] + $item;
        }
        $livres = (int) $h['n_quartos'] - reserva_quartos_ocupados($ligacao, $id_item, $item['data_inicio'], $item['data_fim'], $ignorar_reserva);
        if ($item['quantidade'] > $livres) {
            return ['erro' => $livres > 0
                ? "Só há $livres quarto(s) livre(s) nessas datas."
                : 'Não há quartos livres nessas datas.'] + $item;
        }
        $item['preco_unitario'] = (float) $h['preco_diaria'];
        $item['preco_total'] = round($item['preco_unitario'] * $noites * $item['quantidade'], 2);
        return $item;
    }

    if ($tipo === 'atividade') {
        $stmt = $ligacao->prepare("SELECT titulo, preco, data_inicio, data_fim, vendido FROM t_artigo WHERE id = ?");
        $stmt->bind_param("i", $id_item);
        $stmt->execute();
        $a = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$a || $a['vendido'] != 0) {
            return ['erro' => 'Este passeio já não está disponível.'] + $item;
        }
        $item['titulo'] = $a['titulo'];

        $dia = reserva_data($data_inicio);
        if (!$dia) {
            return ['erro' => 'Escolha a data do passeio.'] + $item;
        }
        $item['data_inicio'] = $item['data_fim'] = $dia->format('Y-m-d');
        if ($dia < $hoje) {
            return ['erro' => 'A data do passeio já passou.'] + $item;
        }
        if ($item['data_inicio'] < $a['data_inicio'] || $item['data_inicio'] > $a['data_fim']) {
            return ['erro' => 'O passeio só está disponível de ' . reserva_formatar_data($a['data_inicio'])
                . ' a ' . reserva_formatar_data($a['data_fim']) . '.'] + $item;
        }
        if ($item['quantidade'] < 1 || $item['quantidade'] > RESERVA_MAX_PESSOAS) {
            return ['erro' => 'Escolha entre 1 e ' . RESERVA_MAX_PESSOAS . ' pessoas.'] + $item;
        }
        $item['preco_unitario'] = (float) $a['preco'];
        $item['preco_total'] = round($item['preco_unitario'] * $item['quantidade'], 2);
        return $item;
    }

    if ($tipo === 'voo') {
        // Cada voo em t_voos é criado para um utilizador quando o adiciona ao carrinho
        $stmt = $ligacao->prepare("SELECT flight_number, airline, arrival, price FROM t_voos WHERE id = ? AND id_user = ?");
        $stmt->bind_param("ii", $id_item, $id_user);
        $stmt->execute();
        $v = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$v) {
            return ['erro' => 'Este voo já não existe.'] + $item;
        }
        $item['titulo'] = trim($v['airline'] . ' ' . $v['flight_number'] . ' → ' . $v['arrival']);
        $item['quantidade'] = 1;
        $item['preco_unitario'] = (float) $v['price'];
        $item['preco_total'] = $item['preco_unitario'];
        return $item;
    }

    return ['erro' => 'Tipo de item desconhecido.'] + $item;
}
