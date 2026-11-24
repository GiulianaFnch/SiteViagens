<?php
include 'valida_vendedor.php';
include '../../config/liga_bd.php';
$painel = 'vendedor';
$painel_titulo = 'Vender passeios';
$painel_menu = 'vender';
$painel_css = ['vender.css'];
include __DIR__ . '/../../views/partials/painel_inicio.php';
?>
                    <h1 class="h2">Vender Tours</h1>
                    <?php
                    $categoria = isset($_POST['categoria']) ? (int) $_POST['categoria'] : 1;
                    ?>

                    <form action="vender_tours.php" id="f1" method="post">
                        Categoria:
                        <select name="categoria" id="categoria" onchange="this.form.submit();">
                            <?php
                            $sql = "SELECT * FROM t_categoria";
                            $resultado = mysqli_query($ligacao, $sql);
                            while ($linha = mysqli_fetch_assoc($resultado)) {
                                $selected = ($categoria == $linha['id']) ? 'selected' : '';
                                echo "<option value='" . (int) $linha['id'] . "' $selected>" . htmlspecialchars($linha['categoria']) . "</option>";
                            }
                            ?>
                        </select>
                        <br>Subcategoria:
                        <select name="subcategoria" id="subcategoria" onchange="atualiza();">
                            <?php
                            $stmt2 = $ligacao->prepare("SELECT * FROM t_subcat WHERE categoria = ?");
                            $stmt2->bind_param("i", $categoria);
                            $stmt2->execute();
                            $resultado2 = $stmt2->get_result();
                            while ($linha2 = mysqli_fetch_assoc($resultado2)) {
                                echo "<option value='" . (int) $linha2['id'] . "'>" . htmlspecialchars($linha2['subcat']) . "</option>";
                            }
                            ?>
                        </select>
                    </form>


                    <form action="guardar_tour.php" id="f2" method="post" enctype="multipart/form-data">
                        <?= csrf_campo() ?>
                        <input type="hidden" name="id_user" value="<?php echo $_SESSION['id']; ?>" required>
                        <input type="hidden" name="valor_cat" value="<?php echo $categoria; ?>" id="valor_cat" required>
                        <input type="hidden" name="valor_subcat" value="" id="valor_subcat" required>

                        <label for="titulo">Título:</label>
                        <input type="text" id="titulo" name="titulo" required>

                        <label for="descricao">Descrição:</label>
                        <textarea id="descricao" name="descricao"></textarea>

                        <label for="preco">Preço:</label>
                        <input type="text" id="preco" name="preco" required>

                        <label for="estado">Estado:</label>
                        <select id="estado" name="estado">
                            <option value="1">1 estrela</option>
                            <option value="2">2 estrelas</option>
                            <option value="3">3 estrelas</option>
                            <option value="4">4 estrelas</option>
                            <option value="5">5 estrelas</option>
                        </select>

                        <label for="localizacao">Localização:</label>
                        <input type="text" id="localizacao" name="localizacao" required>

                        <label for="data_inicio">Data de Início:</label>
                        <input type="date" id="data_inicio" name="data_inicio" required>

                        <label for="data_fim">Data de Fim:</label>
                        <input type="date" id="data_fim" name="data_fim" required>

                        <label for="vagas">Vagas por dia (deixe vazio para não limitar):</label>
                        <input type="number" id="vagas" name="vagas" min="1">

                        <label for="ficheiro1">Foto 1:</label>
                        <input type="file" id="ficheiro1" name="ficheiro1">

                        <label for="ficheiro2">Foto 2:</label>
                        <input type="file" id="ficheiro2" name="ficheiro2">

                        <label for="ficheiro3">Foto 3:</label>
                        <input type="file" id="ficheiro3" name="ficheiro3">

                        <input type="submit" value="Vender">
                    </form>
                
            
        
    

    
<script>
        function atualiza() {
            var categoria = document.getElementById("categoria").value;
            var subcategoria = document.getElementById("subcategoria").value;

            document.getElementById("valor_cat").value = categoria;
            document.getElementById("valor_subcat").value = subcategoria;
        }
        window.onload = atualiza; 
    </script>
<?php include __DIR__ . '/../../views/partials/painel_fim.php'; ?>
