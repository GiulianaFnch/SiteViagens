-- Histórico das ofertas enviadas por e-mail pelo administrador (adm/ofertas.php).
CREATE TABLE IF NOT EXISTS `t_ofertas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `assunto` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `enviados` int(11) NOT NULL DEFAULT 0,
  `falhados` int(11) NOT NULL DEFAULT 0,
  `id_admin` int(11) DEFAULT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  CONSTRAINT `t_ofertas_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `t_user` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
