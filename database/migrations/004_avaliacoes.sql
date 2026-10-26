-- Avaliações de passeios e hospedagens (nota de 1 a 5 e comentário opcional).
-- Cada utilizador tem no máximo uma avaliação por item (pode editá-la).
CREATE TABLE IF NOT EXISTS `t_avaliacoes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tipo_item` enum('atividade','hospedagem') NOT NULL,
  `item_id` int(11) NOT NULL,
  `nota` tinyint(1) NOT NULL,
  `comentario` text DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uma_por_utilizador` (`id_user`, `tipo_item`, `item_id`),
  KEY `item` (`tipo_item`, `item_id`),
  CONSTRAINT `t_avaliacoes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
