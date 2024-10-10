<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

$id_user = $_SESSION['id'];

// Verifique se a ligação ao banco de dados está correta
if ($ligacao->connect_error) {
    die("Falha na ligação: " . $ligacao->connect_error);
}

// Consulta para obter itens do carrinho
$stmt = $ligacao->prepare("
    SELECT c.id, 
           CASE 
               WHEN c.tipo_item = 'atividade' THEN a.titulo 
               WHEN c.tipo_item = 'hospedagem' THEN h.nome 
               WHEN c.tipo_item = 'voo' THEN v.arrival 
           END AS titulo, 
           CASE 
               WHEN c.tipo_item = 'atividade' THEN a.preco 
               WHEN c.tipo_item = 'hospedagem' THEN h.preco_diaria 
               WHEN c.tipo_item = 'voo' THEN v.price 
           END AS preco, 
           c.quantidade, 
           c.tipo_item
    FROM t_carrinho c
    LEFT JOIN t_artigo a ON c.id_artigo = a.id AND c.tipo_item = 'atividade'
    LEFT JOIN t_hospedagem h ON c.id_artigo = h.id AND c.tipo_item = 'hospedagem'
    LEFT JOIN t_voos v ON c.id_artigo = v.id AND c.tipo_item = 'voo'
    WHERE c.id_user = ?
");
if (!$stmt) {
    die("Erro na preparação da consulta: " . $ligacao->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado_artigos = $stmt->get_result();

if (!$resultado_artigos) {
    die("Erro na execução da consulta: " . $stmt->error);
}

if (!isset($_SESSION['total_carrinho'])) {
    $_SESSION['total_carrinho'] = 0; // Inicialize com 0 se não estiver definida
}

// Calcular o total do carrinho
$total_carrinho = $_SESSION['total_carrinho'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho</title>

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
                        <th>Preço</th>
                        <th>Quantidade</th>
                        <th>Total</th>
                        <th>Tipo de Item</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($linha = $resultado_artigos->fetch_assoc()) {
                        $total_item = $linha['preco'] * $linha['quantidade'];
                        $total_carrinho += $total_item;
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($linha['titulo']) . "</td>";
                        echo "<td>" . htmlspecialchars($linha['preco']) . " €</td>";
                        echo "<td>" . htmlspecialchars($linha['quantidade']) . "</td>";
                        echo "<td>" . htmlspecialchars($total_item) . " €</td>";
                        echo "<td>" . htmlspecialchars($linha['tipo_item']) . "</td>";
                        echo "<td>";
                        ?>
                        <form action="remover_do_carrinho.php" method="post" style="display:inline;">
                            <input type="hidden" name="id_carrinho" value="<?php echo htmlspecialchars($linha['id']); ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                        </form>
                        <?php
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <h3>Total do Carrinho:
                <?php echo htmlspecialchars($total_carrinho) . " €"; ?>
            </h3>
            <form action="efetuar_reserva.php" method="post">
            <a href="#" role="link" class="slide-button" onclick="this.closest('form').submit();">
                    <span class="text">Finalizar Reserva</span>    
                </a>
            </form>
        </div>
    </main>
</body>

<?php include '../../views/partials/footer.php'; ?>
</html>

<?php
$stmt->close();
$ligacao->close();
?>
