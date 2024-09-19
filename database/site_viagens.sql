-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 19, 2024 at 08:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `site_viagens`
--
CREATE DATABASE IF NOT EXISTS `site_viagens` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `site_viagens`;

-- --------------------------------------------------------

--
-- Table structure for table `t_artigo`
--

CREATE TABLE `t_artigo` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  `subcat` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `preco` float NOT NULL,
  `localizacao` varchar(150) DEFAULT NULL,
  `estado` int(11) NOT NULL,
  `foto1` varchar(50) NOT NULL,
  `foto2` varchar(50) DEFAULT NULL,
  `foto3` varchar(50) DEFAULT NULL,
  `vendido` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_artigo`
--

INSERT INTO `t_artigo` (`id`, `id_user`, `cat`, `subcat`, `titulo`, `descricao`, `preco`, `localizacao`, `estado`, `foto1`, `foto2`, `foto3`, `vendido`) VALUES
(1, 4, 2, 4, 'Passeio de camelo', 'Passeio de camelo muito bom e barato nos desertos do Saara! Venham conhecer', 100, 'Saara', 2, 'camelo.jpg', NULL, NULL, 0),
(2, 4, 1, 1, 'Tour de barco', 'Tour de barco no litoral alentejano', 5000, 'Alentejo', 5, 'barco.jpg', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `t_art_comen`
--

CREATE TABLE `t_art_comen` (
  `id` int(11) NOT NULL,
  `id_artigo` int(11) NOT NULL,
  `comentario` varchar(255) NOT NULL,
  `avaliacao` int(11) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `publico` int(11) NOT NULL DEFAULT 0,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_art_comen`
--

INSERT INTO `t_art_comen` (`id`, `id_artigo`, `comentario`, `avaliacao`, `data`, `publico`, `id_user`) VALUES
(1, 1, 'srwrewsfdye teste', 4, '2024-07-16 15:18:31', 0, 2),
(2, 1, 'Novo comentário', 3, '2024-07-16 15:35:16', 0, 2),
(3, 1, 'fsfdsf', 2, '2024-07-16 15:50:37', 0, 2),
(4, 4, 'Não gostei publico', 1, '2024-07-16 20:41:11', 0, 2),
(5, 5, 'Amei privado quero comprar', 5, '2024-07-16 20:41:30', 1, 2),
(6, 3, 'testestes', 1, '2024-07-16 20:44:16', 0, 2),
(7, 3, 'Não gostei da resposta', 3, '2024-07-16 20:48:49', 0, 2),
(8, 2, 'quero comrpar', 2, '2024-07-16 20:51:22', 0, 2),
(9, 7, 'quero comprar', 3, '2024-07-18 13:48:38', 0, 2),
(10, 8, 'Quero comprar', 2, '2024-07-18 15:13:34', 0, 4),
(11, 9, 'Quero comprar', 5, '2024-07-18 15:13:53', 1, 4),
(12, 11, 'gostei\r\n', 2, '2024-07-18 15:19:38', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `t_art_comen_v`
--

CREATE TABLE `t_art_comen_v` (
  `id` int(11) NOT NULL,
  `id_comen` int(11) NOT NULL,
  `resposta` varchar(255) NOT NULL,
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_art_comen_v`
--

INSERT INTO `t_art_comen_v` (`id`, `id_comen`, `resposta`, `data`) VALUES
(1, 4, '', '2024-07-16 20:41:58'),
(2, 6, '', '2024-07-16 20:46:46'),
(3, 7, 'Idai eu hein vai cuidar da tua vida', '2024-07-16 20:49:26'),
(4, 9, 'vou vender', '2024-07-18 13:48:49');

-- --------------------------------------------------------

--
-- Table structure for table `t_categoria`
--

CREATE TABLE `t_categoria` (
  `id` int(11) NOT NULL,
  `categoria` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_categoria`
--

INSERT INTO `t_categoria` (`id`, `categoria`) VALUES
(1, 'Cultura'),
(2, 'Natureza'),
(3, 'Gastronomia'),
(4, 'Esporte');

-- --------------------------------------------------------

--
-- Table structure for table `t_livro`
--

CREATE TABLE `t_livro` (
  `isbn` int(11) NOT NULL,
  `titulo` varchar(50) NOT NULL,
  `preco` decimal(10,0) NOT NULL,
  `autor` varchar(50) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `editora` varchar(50) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL,
  `dataInicio` varchar(100) NOT NULL,
  `dataTermino` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `t_subcat`
--

CREATE TABLE `t_subcat` (
  `id` int(11) NOT NULL,
  `categoria` int(11) NOT NULL,
  `subcat` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_subcat`
--

INSERT INTO `t_subcat` (`id`, `categoria`, `subcat`) VALUES
(1, 1, 'Tours'),
(2, 1, 'Shows'),
(3, 1, 'Museus'),
(4, 2, 'Passeios'),
(5, 2, 'Parques'),
(6, 4, 'Radicais');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
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
  `tipo_user` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id`, `nick`, `nome`, `email`, `data_nasc`, `nome_marca`, `pass`, `foto`, `tipo_user`) VALUES
(1, 'adm', 'Adimino Admin', 'admin@gmail.com', NULL, '', '123', NULL, 4),
(2, 'Gabis', 'Gabrielle', 'reissgabi@hotmail.com', NULL, 'Reis Rooms', '123', NULL, 0),
(3, 'jujuba', 'Giuliana', 'finochio44@gmail.com', NULL, 'finochios hotels', '123', NULL, 0),
(4, 'vendedor tours', 'vendedor de tours', 'tours@hotmail.com', NULL, 'Tours Faceis Aqui', '1', NULL, 2),
(5, 'vendedor hoteis', 'vendedor de hoteis muito bom', 'hoteis@hotmail.com', NULL, 'hoteis BB', '123', NULL, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `t_artigo`
--
ALTER TABLE `t_artigo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_art_comen`
--
ALTER TABLE `t_art_comen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_art_comen_v`
--
ALTER TABLE `t_art_comen_v`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_categoria`
--
ALTER TABLE `t_categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_subcat`
--
ALTER TABLE `t_subcat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_artigo`
--
ALTER TABLE `t_artigo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `t_art_comen`
--
ALTER TABLE `t_art_comen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `t_art_comen_v`
--
ALTER TABLE `t_art_comen_v`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_categoria`
--
ALTER TABLE `t_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_subcat`
--
ALTER TABLE `t_subcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
