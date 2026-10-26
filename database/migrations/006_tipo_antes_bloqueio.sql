-- Guarda o tipo do utilizador (cliente, vendedor, ...) enquanto está bloqueado,
-- para que ao reativá-lo volte ao que era (antes, um vendedor reativado ficava cliente).
ALTER TABLE `t_user` ADD COLUMN IF NOT EXISTS `tipo_antes_bloqueio` int(11) DEFAULT NULL;
