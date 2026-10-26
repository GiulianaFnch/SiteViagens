-- Lotação dos passeios: nº máximo de pessoas por dia (NULL = sem limite).
ALTER TABLE `t_artigo` ADD COLUMN IF NOT EXISTS `vagas` int(11) DEFAULT NULL;
