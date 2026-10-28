-- E-mails inscritos na newsletter (formulário "Inscreva-se para receber nossas ofertas").
CREATE TABLE IF NOT EXISTS `t_newsletter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(254) NOT NULL,
  `data_inscricao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
