-- Confirmação de e-mail e recuperação de palavra-passe.
-- As contas que já existem ficam confirmadas (a coluna é criada com DEFAULT 1 e depois
-- passa a DEFAULT 0, para que só as contas novas tenham de confirmar o e-mail).
ALTER TABLE `t_user` ADD COLUMN IF NOT EXISTS `email_confirmado` tinyint(1) NOT NULL DEFAULT 1;
ALTER TABLE `t_user` MODIFY `email_confirmado` tinyint(1) NOT NULL DEFAULT 0;

-- Links enviados por e-mail. Guarda-se só o hash SHA-256 do token: quem ler a base de dados
-- não consegue usar os links.
CREATE TABLE IF NOT EXISTS `t_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `tipo` enum('confirmar_email','recuperar_senha') NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expira` datetime NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token_hash` (`token_hash`),
  KEY `utilizador` (`id_user`, `tipo`),
  CONSTRAINT `t_tokens_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
