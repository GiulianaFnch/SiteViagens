-- Para bases de dados criadas antes desta correção.
-- (Quem importar database/site_viagens.sql de novo não precisa correr isto.)

-- O registo de clientes não envia nome_marca; sem DEFAULT o INSERT falha em modo estrito.
ALTER TABLE `t_user` MODIFY `nome_marca` varchar(150) NOT NULL DEFAULT '';

-- Usada pela opção "receber ofertas" em configuracoes.php.
ALTER TABLE `t_user` ADD COLUMN IF NOT EXISTS `notificacoes_ofertas` tinyint(1) NOT NULL DEFAULT 1;
