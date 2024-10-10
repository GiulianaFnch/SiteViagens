-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10/10/2024 às 18:39
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `site_viagens`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `t_user`
--

CREATE TABLE `t_user` (
  `id` int(11) NOT NULL,
  `nick` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `data_nasc` varchar(10) DEFAULT NULL,
  `nome_marca` varchar(150) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `tipo_user` int(11) NOT NULL DEFAULT 0,
  `notificacoes_ofertas` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Despejando dados para a tabela `t_user`
--

INSERT INTO `t_user` (`id`, `nick`, `nome`, `email`, `data_nasc`, `nome_marca`, `pass`, `foto`, `tipo_user`, `notificacoes_ofertas`) VALUES
(15, 'crowne ', 'Crowne Plaza', 'Crowne@Plaza.com', '1589-06-09', 'Crowne Plaza LDA.', '$2y$10$DAUSdmFv4YyIh.frKhypveUY8txw55j2y1gul.pn838Oh4CfBQ4aW', 'download (1).png', 3, 1),
(16, 'theritz', 'The Ritz', 'The@Ritz.com', '1986-06-20', 'The Ritz LDA.', '$2y$10$bCO9a7K5o0eTdDCpBhCgcO9184CIsMQc0bH3tD53zEtNwMJ8M2HBm', 'download.png', 3, 1),
(17, 'fourseasons ', 'Four Seasons ', 'Four@Seasons.com', '1584-09-05', 'Four Seasons  LDA', '$2y$10$GG3qLetR1OlCqomWpcjtDOJJTeMEw.phPFHTqHznJ7vEudGUCSYEu', 'download (2).png', 3, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nick` (`nick`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
