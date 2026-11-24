<?php
include 'valida_vendedor.php';
// Rejeita pedidos POST sem o token CSRF da sessão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verificar();
}
include '../../config/liga_bd.php';
require_once '../../config/upload.php';

$uploadOk = 1;
$target_dir = "../../public/hotels/imagens/";

function processar_foto($file, $id_hospedagem, $numero_foto)
{
    global $uploadOk, $ligacao, $target_dir;

    if ($uploadOk == 0) {
        return;
    }

    $file_name = salvar_imagem($file, $target_dir);
    if ($file_name === null) {
        echo "O arquivo não é uma imagem válida.";
        $uploadOk = 0;
        return;
    }

    $numero_foto = (int) $numero_foto; // 1, 2 ou 3
    $stmt = $ligacao->prepare("UPDATE t_hospedagem SET foto{$numero_foto} = ? WHERE id = ?");
    $stmt->bind_param("si", $file_name, $id_hospedagem);
    $stmt->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura os dados do formulário com verificações
    $id_user = $_SESSION['id'];
    $nome = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $n_quartos = $_POST['n_quartos'];
    $preco_diaria = $_POST['preco'];
    $classificacao = $_POST['estado'];
    $localizacao = isset($_POST['localizacao']) ? $_POST['localizacao'] : '';
    $horario_checkin = $_POST['horario_checkin'];
    $horario_checkout = $_POST['horario_checkout'];
    $tipo_hospedagem = $_POST['tipo_hospedagem'];
    $data_inicio = $_POST['data_inicio'];
    $data_fim = $_POST['data_fim'];

    $stmt = $ligacao->prepare("INSERT INTO t_hospedagem (id_user, nome, descricao, n_quartos, preco_diaria, classificacao, localizacao, horario_checkin, horario_checkout, tipo_hospedagem, data_inicio, data_fim, foto1)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '')");
    $stmt->bind_param("issidissssss", $id_user, $nome, $descricao, $n_quartos, $preco_diaria, $classificacao, $localizacao, $horario_checkin, $horario_checkout, $tipo_hospedagem, $data_inicio, $data_fim);

    if ($stmt->execute()) {
        $id_hospedagem = $stmt->insert_id;
        echo "<h2>Registro efetuado com sucesso! </h2>";

        $_FILES["ficheiro"] = $_FILES["ficheiro1"];
        processar_foto($_FILES["ficheiro"], $id_hospedagem, 1);

        if (!empty($_FILES['ficheiro2']['name'])) {
            $_FILES["ficheiro"] = $_FILES["ficheiro2"];
            processar_foto($_FILES["ficheiro"], $id_hospedagem, 2);
        }

        if (!empty($_FILES['ficheiro3']['name'])) {
            $_FILES["ficheiro"] = $_FILES["ficheiro3"];
            processar_foto($_FILES["ficheiro"], $id_hospedagem, 3);
        }
    } else {
        error_log("vender_hospedagem: " . $stmt->error);
        echo "Erro ao registar a hospedagem.";
    }

    mysqli_close($ligacao);
}
$painel = 'vendedor';
$painel_titulo = 'Vender hospedagem';
$painel_menu = 'vender';
$painel_css = ['vender.css'];
include __DIR__ . '/../../views/partials/painel_inicio.php';
?>
                    <h1 class="h2">Vender Hospedagem</h1>

                    <form action="vender_hospedagem.php" id="f2" method="post" enctype="multipart/form-data">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id_user" value="<?php echo $_SESSION['id']; ?>" required>

                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao"></textarea>

                        <label for="n_quartos">Número de Quartos:</label>
                        <select id="n_quartos" name="n_quartos" required>
                            <?php for ($i = 1; $i <= 100; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>

                        <label for="preco">Valor diária:</label>
                        <input type="text" id="preco" name="preco" required>

                        <label for="estado">Classificação:</label>
                        <select id="estado" name="estado">
                            <option value="1">1 estrela</option>
                            <option value="2">2 estrelas</option>
                            <option value="3">3 estrelas</option>
                            <option value="4">4 estrelas</option>
                            <option value="5">5 estrelas</option>
                        </select>

                        <label for="localizacao">Localização:</label>
                        <input type="text" id="localizacao" name="localizacao" required>

                        <label for="horario_checkin">Horário de Check-in:</label>
                        <input type="time" id="horario_checkin" name="horario_checkin" required>

                        <label for="horario_checkout">Horário de Check-out:</label>
                        <input type="time" id="horario_checkout" name="horario_checkout" required>

                        <label for="tipo_hospedagem">Tipo de Hospedagem:</label>
                        <select id="tipo_hospedagem" name="tipo_hospedagem" required>
                            <option value="hotel">Hotel</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="hostel">Hostel</option>
                            <option value="castelo">Castelo</option>
                            <option value="cabana">Cabana</option>
                            <option value="resort">Resort</option>
                        </select>

                        <label for="data_inicio">Data de Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" required>

                        <label for="data_fim">Data de Fim:</label>
                        <input type="date" id="data_fim" name="data_fim" required>

                        <label for="ficheiro1">Foto 1:</label>
                        <input type="file" id="ficheiro1" name="ficheiro1">

                        <label for="ficheiro2">Foto 2:</label>
                        <input type="file" id="ficheiro2" name="ficheiro2">

                        <label for="ficheiro3">Foto 3:</label>
                        <input type="file" id="ficheiro3" name="ficheiro3">

                        <input type="submit" value="Vender">
                    </form>
<?php include __DIR__ . '/../../views/partials/painel_fim.php'; ?>
