-- OPCIONAL — só para os dados de exemplo.
-- Os passeios e hospedagens de exemplo tinham disponibilidade até 2024/2025, por isso
-- já não podiam ser reservados. Isto prolonga o período de disponibilidade até ao fim de 2027.
-- Não corra isto numa base de dados com dados reais.
UPDATE `t_artigo`     SET `data_fim` = '2027-12-31' WHERE `data_fim` < '2027-12-31';
UPDATE `t_hospedagem` SET `data_fim` = '2027-12-31' WHERE `data_fim` IS NULL OR `data_fim` < '2027-12-31';
