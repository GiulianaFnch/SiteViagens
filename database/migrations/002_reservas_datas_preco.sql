-- Reservas com datas, preço e estado.
-- Para bases de dados criadas antes desta alteração.
-- (Quem importar database/site_viagens.sql de novo não precisa correr isto.)

-- Carrinho: datas escolhidas pelo cliente.
--   hospedagem: data_inicio = check-in, data_fim = check-out, quantidade = nº de quartos
--   atividade:  data_inicio = dia do passeio,             quantidade = nº de pessoas
ALTER TABLE `t_carrinho`
  ADD COLUMN IF NOT EXISTS `data_inicio` date DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `data_fim` date DEFAULT NULL;

-- Reservas: datas, preço pago (fica fixo mesmo que o vendedor mude o preço) e estado.
ALTER TABLE `t_reservas`
  ADD COLUMN IF NOT EXISTS `data_inicio` date DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `data_fim` date DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `preco_unitario` decimal(10,2) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `preco_total` decimal(10,2) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `estado` enum('confirmada','cancelada') NOT NULL DEFAULT 'confirmada',
  ADD KEY IF NOT EXISTS `item` (`tipo_reserva`, `item_id`);
