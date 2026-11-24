<?php
require_once __DIR__ . '/../config/config.php';
include '../config/valida.php';
include '../config/liga_bd.php';

$user_id = $_SESSION['id'];

// Ajuste a consulta SQL para buscar informações de acordo com o tipo_reserva
$sql = "SELECT f.tipo_reserva, 
               CASE 
                   WHEN f.tipo_reserva = 'atividade' THEN a.titulo 
                   WHEN f.tipo_reserva = 'hospedagem' THEN h.nome 
               END AS titulo, 
               CASE 
                   WHEN f.tipo_reserva = 'atividade' THEN a.foto1 
                   WHEN f.tipo_reserva = 'hospedagem' THEN h.foto1 
               END AS foto1, 
               CASE 
                   WHEN f.tipo_reserva = 'atividade' THEN a.preco 
                   WHEN f.tipo_reserva = 'hospedagem' THEN h.preco_diaria 
               END AS preco, 
               f.id_artigo 
        FROM t_favoritos f
        LEFT JOIN t_artigo a ON f.id_artigo = a.id AND f.tipo_reserva = 'atividade'
        LEFT JOIN t_hospedagem h ON f.id_artigo = h.id AND f.tipo_reserva = 'hospedagem'
        WHERE f.id_user = ?
          AND (a.id IS NOT NULL OR h.id IS NOT NULL)"; // ignora favoritos de itens que já foram apagados

$stmt = $ligacao->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultado = $stmt->get_result();

mysqli_close($ligacao);
$painel = 'cliente';
$painel_titulo = 'Favoritos';
$painel_menu = 'favoritos';
$painel_css = ['favoritos.css'];
include __DIR__ . '/../views/partials/painel_inicio.php';
?>
                            
                       
        <?php if (mysqli_num_rows($resultado) > 0): ?>
            <ul>
                <?php while ($linha = mysqli_fetch_array($resultado)): ?>
                    <li>
                        <h2 class="titulo-pequeno"><?php echo htmlspecialchars($linha['titulo']); ?></h2>

                        <div class="image-container">
                        <?php if ($linha['tipo_reserva'] == 'atividade'): ?>
                            <img src="tours/imagens/<?php echo htmlspecialchars($linha['foto1']); ?>" alt="Imagem da atividade">
                        <?php elseif ($linha['tipo_reserva'] == 'hospedagem'): ?>
                            <img src="hotels/imagens/<?php echo htmlspecialchars($linha['foto1']); ?>" alt="Imagem da hospedagem">
                            
                        <?php endif; ?>
                        <p>Preço: € <?php echo number_format($linha['preco'], 2, ',', '.'); ?></p>
                        

                        <form action="backend/remover_favorito.php" method="post">
                            <?= csrf_campo() ?>
                            <input type="hidden" name="id_artigo" value="<?php echo htmlspecialchars($linha['id_artigo']); ?>">
                            <input type="submit" value="Remover">
                        </form>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Você ainda não tem artigos favoritos.</p>
        <?php endif; ?>
        
    
                        </div>
                    
                
            
        
    
    
    
    


     <!--footer-->
<?php include __DIR__ . '/../views/partials/painel_fim.php'; ?>
