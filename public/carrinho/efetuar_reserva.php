<?php
include "../../config/valida.php";
include "../../config/liga_bd.php";

$id_user = $_SESSION['id']; // Assumindo que o ID do usuário está armazenado na sessão

// Verifique se a ligação ao banco de dados está correta
if ($ligacao->connect_error) {
    die("Falha na ligação: " . $ligacao->connect_error);
}

// Consulta para obter itens do carrinho
$stmt = $ligacao->prepare("SELECT c.id, c.id_artigo, c.tipo_item, c.quantidade
                           FROM t_carrinho c
                           WHERE c.id_user = ?");
if (!$stmt) {
    die("Erro na preparação da consulta: " . $ligacao->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$resultado_artigos = $stmt->get_result();

if (!$resultado_artigos) {
    die("Erro na execução da consulta: " . $stmt->error);
}

// Processa cada item do carrinho e insere na tabela de reservas
while ($linha = $resultado_artigos->fetch_assoc()) {
    $id_artigo = $linha['id_artigo'];
    $tipo_item = $linha['tipo_item'];
    $quantidade = $linha['quantidade'];
    $data_reserva = date('Y-m-d'); // Data atual

    $stmt_reserva = $ligacao->prepare("INSERT INTO t_reservas (item_id, user_id, tipo_reserva, data_reserva, quantidade) VALUES (?, ?, ?, ?, ?)");
    $stmt_reserva->bind_param("iissi", $id_artigo, $id_user, $tipo_item, $data_reserva, $quantidade);
    $stmt_reserva->execute();
}

// Limpa o carrinho após a reserva
$stmt_limpar = $ligacao->prepare("DELETE FROM t_carrinho WHERE id_user = ?");
$stmt_limpar->bind_param("i", $id_user);
$stmt_limpar->execute();

// Zera a variável de sessão do total do carrinho
$_SESSION['total_carrinho'] = 0;

echo "Reserva efetuada com sucesso!";
header("refresh:2;url=../reservas.php");

$stmt->close();
$stmt_reserva->close();
$stmt_limpar->close();
$ligacao->close();
?>