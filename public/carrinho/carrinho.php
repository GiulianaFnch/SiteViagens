<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

$id_user = $_SESSION['id'];

// Consulta para obter itens do carrinho
$stmt = $ligacao->prepare("SELECT id, id_artigo, tipo_item, quantidade, data_inicio, data_fim
                           FROM t_carrinho WHERE id_user = ? ORDER BY id");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$linhas_carrinho = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Recalcula cada item com os preços e a disponibilidade atuais
include "../../config/reservas.php";
$itens = [];
$total_carrinho = 0;
$tem_erros = false;
foreach ($linhas_carrinho as $linha) {
    $item = reserva_preparar_item($ligacao, $linha['tipo_item'], $linha['id_artigo'], $linha['quantidade'],
        $linha['data_inicio'], $linha['data_fim'], $id_user);
    $item['id_carrinho'] = $linha['id'];
    $item['tipo_item'] = $linha['tipo_item'];
    if ($item['erro'] === null) {
        $total_carrinho += $item['preco_total'];
    } else {
        $tem_erros = true;
    }
    $itens[] = $item;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho - BestWay</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../assets/css/carrinho.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha384-XiUb2ZnoMBBx3zW3Kwq9K5Z8v+UGYmo/Y7P0UnQyy8vm3YQBGHhNZP+ak3B3o7W" crossorigin="anonymous" rel="stylesheet">

</head>

<style>
/* Estilo global dos botões */
        .btn {
            border-radius: 25px; /* Bordas arredondadas */
            padding: 8px 16px; /* Espaçamento interno reduzido */
          
        }
 /* Botão de deslizamento */
 .slide-button {
            position: relative;
            display: inline-flex; /* Muda para inline-flex para melhor alinhamento */
            justify-content: center;
            align-items: center;
            width: 180px; /* Largura do botão reduzida */
            height: 50px; /* Altura do botão reduzida */
            gap: 10px; /* Espaço entre os ícones e o texto */
            border-radius: 25px; /* Bordas arredondadas */
            background: #006a00; /* Cor de fundo mais suave */
            color: #ffff; /* Cor do texto */
            font-family: 'Poppins', sans-serif; /* Fonte */
            font-size: 16px; /* Tamanho da fonte ajustado */
            text-decoration: none; /* Remove sublinhado */
            overflow: hidden; /* Esconde o conteúdo que transborda */
            transition: background 0.3s; /* Transição suave */
            cursor: pointer; /* Muda o cursor para indicar que é clicável */
        }

 /* Pseudo-elemento em estado normal */
 .slide-button::after {
            content: "";
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: #00ab00;
            border-radius: 25px; /* Bordas arredondadas */
            z-index: 1;
            transition: transform 500ms ease-in-out; /* Transição suave */
        }

          /* Pseudo-elemento em estado de hover */
          .slide-button:hover::after {
            transform: translateX(100%); /* Efeito de deslizamento mais sutil */
        }

  /* Texto em estado normal */
  .slide-button span {
            margin-left: -10px; /* Ajuste da margem */
            z-index: 2;
        }

</style>


<body>
    <?php include '../../views/partials/header.php'; ?>

    <main class="container mt-5 main-content">
        <h2 class="mb-4">Meu Carrinho</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Título</th>
                        <th>Tipo de Item</th>
                        <th>Datas</th>
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($itens as $item): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars($item['titulo']) ?>
                                <?php if ($item['erro'] !== null): ?>
                                    <div class="text-danger small"><?= htmlspecialchars($item['erro']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['tipo_item']) ?></td>
                            <td>
                                <?php if ($item['tipo_item'] == 'hospedagem'): ?>
                                    <?= reserva_formatar_data($item['data_inicio']) ?> a <?= reserva_formatar_data($item['data_fim']) ?>
                                    <?php if ($item['noites']): ?><br><small><?= $item['noites'] ?> noite(s)</small><?php endif; ?>
                                <?php elseif ($item['tipo_item'] == 'atividade'): ?>
                                    <?= reserva_formatar_data($item['data_inicio']) ?>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= reserva_formatar_preco($item['preco_unitario']) ?>
                                <?php if ($item['tipo_item'] == 'hospedagem'): ?><small>/noite</small><?php endif; ?>
                                <?php if ($item['tipo_item'] == 'atividade'): ?><small>/pessoa</small><?php endif; ?>
                            </td>
                            <td>
                                <?= (int) $item['quantidade'] ?>
                                <?php if ($item['tipo_item'] == 'hospedagem'): ?><small>quarto(s)</small><?php endif; ?>
                                <?php if ($item['tipo_item'] == 'atividade'): ?><small>pessoa(s)</small><?php endif; ?>
                            </td>
                            <td><?= $item['erro'] === null ? reserva_formatar_preco($item['preco_total']) : '—' ?></td>
                            <td>
                            <form action="remover_do_carrinho.php" method="post" style="display:inline;">
                                <?= csrf_campo() ?>
                                <input type="hidden" name="id_carrinho" value="<?= (int) $item['id_carrinho'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                            </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$itens): ?>
                        <tr><td colspan="7">O seu carrinho está vazio.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Total do Carrinho:
                <?= reserva_formatar_preco($total_carrinho) ?>
            </h3>
            <?php if ($tem_erros): ?>
                <p class="text-danger">Remova os itens indisponíveis para finalizar a reserva.</p>
            <?php elseif ($itens): ?>
            <form action="efetuar_reserva.php" method="post">
                <?= csrf_campo() ?>
            <a href="#" role="link" class="slide-button" onclick="this.closest('form').submit();">
                    <span class="text">Finalizar Reserva</span>    
                </a>
            </form>
            <?php endif; ?>
        </div>
    </main>
</body>

<?php include '../../views/partials/footer.php'; ?>
</html>

<?php
$ligacao->close();
?>
