-- Pacotes de viagem criados pelos vendedores (public/vendedor/pacotes.php) e reservados pelos clientes.
-- Na reserva: data_inicio = dia de partida, data_fim = último dia, quantidade = nº de pessoas.
CREATE TABLE IF NOT EXISTS `t_pacotes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `destino` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `inclui` text DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `duracao_dias` int(11) NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `vagas` int(11) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `t_pacotes_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
