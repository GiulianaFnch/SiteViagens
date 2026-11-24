-- A foto da hospedagem "Pestana CR7" está guardada como marrakech.jpg (minúsculas); com "Marrakech.jpg"
-- a imagem não aparece em servidores Linux, que distinguem maiúsculas de minúsculas.
UPDATE `t_hospedagem` SET `foto1` = 'marrakech.jpg' WHERE `foto1` = 'Marrakech.jpg';
