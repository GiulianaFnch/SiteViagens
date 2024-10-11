<?
require_once '../config/valida.php';
require_once '../config/liga_bd.php';

if (isset($_POST['aceitar'])) {
    $id_solicitacao = $_POST['id_solicitacao'];

    // Atualizar o status da amizade para "aceito"
    $stmt = $ligacao->prepare("UPDATE amizades SET status = 'aceito', data_aceite = NOW() WHERE id = ?");
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();

    echo "Amizade aceita!";
}

if (isset($_POST['rejeitar'])) {
    $id_solicitacao = $_POST['id_solicitacao'];

    // Atualizar o status da amizade para "rejeitado"
    $stmt = $ligacao->prepare("UPDATE amizades SET status = 'rejeitado' WHERE id = ?");
    $stmt->bind_param("i", $id_solicitacao);
    $stmt->execute();

    echo "Solicitação de amizade rejeitada.";
}
?>
