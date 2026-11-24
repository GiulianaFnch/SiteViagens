-- Voos no carrinho/reservas: aeroporto de partida e datas de ida e volta (volta NULL = só ida).
-- Com as datas, a reserva do voo mostra quando é e só pode ser cancelada antes da partida.
ALTER TABLE `t_voos`
  ADD COLUMN IF NOT EXISTS `origem` varchar(255) DEFAULT NULL AFTER `price`,
  ADD COLUMN IF NOT EXISTS `data_ida` date DEFAULT NULL AFTER `arrival`,
  ADD COLUMN IF NOT EXISTS `data_volta` date DEFAULT NULL AFTER `data_ida`;
