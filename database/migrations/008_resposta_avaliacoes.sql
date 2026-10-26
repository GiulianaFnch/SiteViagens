-- Resposta do vendedor a cada avaliação (uma por avaliação, pode ser editada ou apagada).
ALTER TABLE `t_avaliacoes`
  ADD COLUMN IF NOT EXISTS `resposta` text DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `data_resposta` timestamp NULL DEFAULT NULL;
