-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 17, 2024 at 06:24 PM
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
  `estado` int(11) NOT NULL,
  `foto1` varchar(50) NOT NULL,
  `foto2` varchar(50) DEFAULT NULL,
  `foto3` varchar(50) DEFAULT NULL,
  `vendido` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_artigo`
--

INSERT INTO `t_artigo` (`id`, `id_user`, `cat`, `subcat`, `titulo`, `descricao`, `preco`, `estado`, `foto1`, `foto2`, `foto3`, `vendido`) VALUES
(1, 1, 1, 3, 'Teste', 'testestestste', 123, 3, 'atv2.png', NULL, NULL, 0),
(2, 1, 2, 1, 'Teste', 'testestestestes', 1233, 4, 'at1v.png', 'Captura de tela 2024-04-23 184314.png', 'Captura de tela 2024-03-28 220413.png', 0),
(3, 2, 3, 7, 'Reboque', 'Reboque novo para dois cavalos', 1000, 4, '111.jpeg', '112.jpeg', NULL, 0),
(4, 2, 1, 3, 'Roupa cavaleiro', 'Roupa para cavalieros', 50, 3, 'cavaleiro.jpeg', 'cavaleiro2.jpeg', NULL, 0),
(5, 2, 1, 4, 'Toque', 'Toque equitação novo', 500, 5, 'toque1.jpeg', NULL, NULL, 2),
(6, 2, 1, 4, 'Toque usado', 'Toques usado de equitação', 250, 3, 'toque2.jpeg', 'toque3.jpeg', NULL, 0),
(7, 2, 1, 5, 'Bota nova', 'Bota nova ariat', 225, 4, 'bota.jpeg', NULL, NULL, 2),
(8, 2, 2, 1, 'Sela equitação clássica', 'Sela nova', 1000, 5, 'selaeq.jpeg', NULL, NULL, 0),
(9, 2, 2, 1, 'Arreio à portuguesa', 'Arreio à portuguesa como novo', 1500, 4, 'arreio.jpeg', NULL, NULL, 0),
(10, 2, 2, 2, 'Suadouro', '', 20, 2, 'manta.jpeg', NULL, NULL, 0),
(11, 2, 2, 6, 'Caneleiras e cloches', '', 100, 4, 'caneleira.jpeg', 'caneleiro.jpeg', NULL, 0),
(12, 2, 3, 8, 'Carrinho de mão', 'Carrinho de mão marca sdkjda como novo', 42.99, 2, 'carrinho.jpeg', NULL, NULL, 0),
(13, 4, 1, 3, 'Roupa equitação', 'Roupa equitação feminina', 100, 1, 'roupa.jpeg', NULL, NULL, 0);

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
(1, 'Cavaleiros'),
(2, 'Cavalos'),
(3, 'Estabulo');

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
(1, 2, 'Arreios e selas'),
(2, 2, 'Suadouros'),
(3, 1, 'Roupas'),
(4, 1, 'Toques e proteção'),
(5, 1, 'Botas'),
(6, 2, 'Caneleiras e proteções'),
(7, 3, 'Trailers e veículos'),
(8, 3, 'Ferramentas'),
(9, 3, 'Teste'),
(10, 3, 'Teste');

-- --------------------------------------------------------

--
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `id` int(11) NOT NULL,
  `nick` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `data_nasc` varchar(10) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `tipo_user` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id`, `nick`, `nome`, `email`, `data_nasc`, `pass`, `foto`, `tipo_user`) VALUES
(1, 'jujuba', 'Giuliana Finochio1', 'finochio44@gmail.com', '2005-04-13', '$2y$10$Xsz2q.eNP.BzC2FdfP8poe1D727CZaHEL03G.Xuly.WpmGrzNmgXa', '222.png', 0),
(2, 'jujuba2', 'Giuliana Avelar Finochio 2', 'finochio4@gmail.com', '1223-03-12', '$2y$10$DJ8SZfFDflQVL8n5QknheeLUxkAQge1ef81jeUllzmUB2yoEUgWRa', 'atv2.png', 0),
(3, 'jujubaTeste', 'GiulianaTeste', 'finochio4@gmail.comteste', '2005-03-02', '$2y$10$sFfP30.RtfrkyRInc1zUGOWX074pC.8laiy8mF7VihipH5W45Phye', 'WIN_20240626_17_24_13_Pro.jpg', 0),
(4, 'Guilherme', 'Guilherme Silva', 'guidouradosilva2004@gmail.com', '2004-01-20', '$2y$10$HK1nHehtYhAEVkAU3Fx.Yul4g1mb3BO.IzEc0tQ.r4F1H1KIF.aLG', '16530692916287d5ebe00ca_1653069291_3x4_md.jpg', 0),
(5, 'gui', 'guilherme silva', 'guiguigui@gmail.com', '2024-07-01', '$2y$10$UsCzhT1lc3LF8jQCQbH7Cud52itS5MvrPMvyuVydaWoSJYdI33OCe', 'WIN_20240507_18_36_19_Pro.jpg', 0),
(6, 'guii', 'Guilherme Silva', 'guidouradosilva2004@gmail.com', '2024-07-09', '$2y$10$lVr6pwxiRjbm.D2uajuI4.sKwo9mRQDeFVgIzGEbE3bHL328bdHh6', 'teste.jpg', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `t_subcat`
--
ALTER TABLE `t_subcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
