-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2024 at 11:14 PM
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
  `preco` decimal(10,2) NOT NULL,
  `localizacao` varchar(150) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `estado` int(11) NOT NULL,
  `foto1` varchar(50) NOT NULL,
  `foto2` varchar(50) DEFAULT NULL,
  `foto3` varchar(50) DEFAULT NULL,
  `vendido` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_artigo`
--

INSERT INTO `t_artigo` (`id`, `id_user`, `cat`, `subcat`, `titulo`, `descricao`, `preco`, `localizacao`, `data_inicio`, `data_fim`, `estado`, `foto1`, `foto2`, `foto3`, `vendido`) VALUES
(1, 4, 1, 1, 'Tour Histórico pela Cidade Velha', 'Um passeio pelos pontos históricos da cidade velha com um guia especializado.', 50.00, 'Lisboa, Portugal', '2024-09-01', '2024-09-30', 5, 'tour_hist_1.jpg', 'tour_hist_2.jpg', 'tour_hist_3.jpg', 0),
(2, 6, 2, 4, 'Passeio de Balão ao Nascer do Sol', 'Desfrute de uma incrível vista do nascer do sol em um passeio de balão pela região vinícola.', 150.00, 'Toscana, Itália', '2024-09-01', '2024-09-30', 4, 'balao_1.jpg', 'balao_2.jpg', 'balao_3.jpg', 0),
(3, 7, 3, 3, 'Visita Guiada ao Museu do Vinho', 'Conheça a história e produção dos vinhos locais com degustação incluída.', 80.00, 'Bordeaux, França', '2024-10-01', '2024-10-31', 5, 'museu_vinho_1.jpg', 'museu_vinho_2.jpg', 'museu_vinho_3.jpg', 0),
(4, 8, 1, 2, 'Show de Fado Tradicional', 'Aprecie uma noite de Fado com artistas locais em uma das casas de show mais renomadas.', 35.00, 'Porto, Portugal', '2024-10-01', '2024-10-31', 5, 'fado_1.jpg', 'fado_2.jpg', 'fado_3.jpg', 0),
(5, 4, 4, 6, 'Rapel em Montanha', 'Rapel em montanhas para aventureiros com instrutores experientes.', 200.00, 'Patagônia, Argentina', '2024-09-01', '2024-09-30', 4, 'rapel_1.jpg', 'rapel_2.jpg', 'rapel_3.jpg', 0),
(6, 6, 3, 4, 'Passeio Gastronômico pelos Mercados Locais', 'Uma jornada gastronômica pelos mercados locais com degustação de pratos tradicionais.', 45.00, 'São Paulo, Brasil', '2024-10-01', '2024-10-31', 5, 'mercado_gastr_1.jpg', 'mercado_gastr_2.jpg', 'mercado_gastr_3.jpg', 0),
(7, 7, 2, 5, 'Visita ao Parque Nacional', 'Explore as trilhas do parque nacional com guias locais e observe a fauna nativa.', 120.00, 'Kruger, África do Sul', '2024-09-01', '2024-09-30', 3, 'parque_kruger_1.jpg', 'parque_kruger_2.jpg', 'parque_kruger_3.jpg', 0),
(8, 8, 1, 3, 'Museu de Arte Moderna', 'Visite uma das maiores coleções de arte moderna com um guia especializado.', 60.00, 'Nova York, EUA', '2024-10-01', '2024-10-31', 5, 'museu_arte_moderna_1.jpg', 'museu_arte_moderna_2.jpg', 'museu_arte_moderna_3.jpg', 0),
(9, 4, 4, 6, 'Rafting em Rápidos', 'Uma emocionante aventura em rios com águas rápidas e guias profissionais.', 180.00, 'Colorado, EUA', '2024-10-05', '2024-10-05', 4, 'rafting_1.jpg', 'rafting_2.jpg', 'rafting_3.jpg', 0),
(10, 6, 2, 4, 'Passeio de Bicicleta na Floresta', 'Descubra paisagens naturais enquanto pedala por trilhas seguras na floresta.', 95.00, 'Amazônia, Brasil', '2024-10-25', '2024-10-25', 5, 'bicicleta_1.jpg', 'bicicleta_2.jpg', 'bicicleta_3.jpg', 0),
(11, 4, 1, 1, 'Passeio Cultural pelo Centro Histórico', 'Um passeio guiado pelas ruas históricas e monumentos icônicos da cidade.', 40.00, 'Lisboa, Portugal', '2024-09-21', '2024-10-15', 5, 'centro_hist_1.jpg', 'centro_hist_2.jpg', 'centro_hist_3.jpg', 0),
(12, 6, 3, 4, 'Tour Gastronômico pelos Restaurantes Típicos', 'Descubra os melhores restaurantes locais com pratos tradicionais.', 70.00, 'Lisboa, Portugal', '2024-09-22', '2024-10-20', 5, 'tour_gastro_1.jpg', 'tour_gastro_2.jpg', 'tour_gastro_3.jpg', 0),
(13, 7, 2, 2, 'Show de Música Tradicional', 'Aprecie um show de música tradicional em um dos melhores teatros da cidade.', 55.00, 'Lisboa, Portugal', '2024-09-25', '2024-10-25', 4, 'musica_trad_1.jpg', 'musica_trad_2.jpg', 'musica_trad_3.jpg', 0),
(14, 8, 4, 6, 'Aventura de Escalada em Penhascos', 'Desfrute de uma aventura de escalada com vistas deslumbrantes e guias experientes.', 180.00, 'Lisboa, Portugal', '2024-10-01', '2024-10-31', 4, 'escalada_1.jpg', 'escalada_2.jpg', 'escalada_3.jpg', 0),
(15, 4, 2, 4, 'Passeio de Caiaque no Rio Tejo', 'Explore as águas do Rio Tejo em uma emocionante aventura de caiaque.', 90.00, 'Lisboa, Portugal', '2024-09-30', '2024-10-20', 5, 'caiaque_1.jpg', 'caiaque_2.jpg', 'caiaque_3.jpg', 0);

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
  `tipo_user` int(11) NOT NULL DEFAULT 0,
  `notificacoes_ofertas` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id`, `nick`, `nome`, `email`, `data_nasc`, `nome_marca`, `pass`, `foto`, `tipo_user`, `notificacoes_ofertas`) VALUES
(1, 'adm', 'Adimino Admin', 'admin@gmail.com', NULL, '', '123', NULL, 4, 1),
(2, 'Gabis', 'Gabrielle', 'reissgabi@hotmail.com', '2024-09-26', 'Reis Rooms', '123', 'camelo.jpg', 0, 1),
(4, 'vendedor tours', 'vendedor de tours', 'tours@hotmail.com', NULL, 'Tours Faceis Aqui', '1', NULL, 2, 1),
(5, 'vendedor hoteis', 'vendedor de hoteis muito bom', 'hoteis@hotmail.com', NULL, 'hoteis BB', '123', NULL, 3, 1),
(6, 'jujuba', 'jujubas', 'finochio@gmail.com', '0002-03-20', '', '$2y$10$FO8sLuzGb48wK.RdmhUNnuef1309Ajc2aFiPlvDsvdPHiYLPL9Cw.', 'teste.jpg', 0, 1),
(7, 'Karol', 'Karoline', 'karol@gmail.com', '2024-09-19', '', '$2y$10$AeZm1fuqUb8cmWag2DboEejr8eDak/qD5PQuxRh.HcFhgqUlIAP/2', 'barco.jpg', 0, 1),
(8, 'teste', 'teste', 'teste@gmail.com', '2024-09-19', '', '$2y$10$UHhzzR4.fT4zRVFxaDQlr.D4MlSuxUCXiUr3IMupWrNzD0jGNAnn.', 'barco.jpg', 0, 1);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
