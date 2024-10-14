-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2024 at 11:23 PM
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
(1, 14, 1, 1, 'Tour Histórico pela Cidade Velha', 'Um passeio pelos pontos históricos da cidade velha com um guia especializado.', 50.00, 'Lisboa, Portugal', '2024-09-01', '2024-09-30', 5, 'tour_hist_1.jpg', 'tour_hist_2.jpg', 'tour_hist_3.jpg', 0),
(2, 6, 2, 4, 'Passeio de Balão ao Nascer do Sol', 'Desfrute de uma incrível vista do nascer do sol em um passeio de balão pela região vinícola.', 150.00, 'Toscana, Itália', '2024-09-01', '2024-09-30', 4, 'balao_1.jpg', 'balao_2.jpg', 'balao_3.jpg', 0),
(3, 7, 3, 3, 'Visita Guiada ao Museu do Vinho', 'Conheça a história e produção dos vinhos locais com degustação incluída.', 80.00, 'Bordeaux, França', '2024-10-01', '2024-10-31', 5, 'museu_vinho_1.jpg', 'museu_vinho_2.jpg', 'museu_vinho_3.jpg', 0),
(4, 8, 1, 2, 'Show de Fado Tradicional', 'Aprecie uma noite de Fado com artistas locais em uma das casas de show mais renomadas.', 35.00, 'Porto, Portugal', '2024-10-01', '2024-10-31', 5, 'fado_1.jpg', 'fado_2.jpg', 'fado_3.jpg', 0),
(5, 14, 4, 6, 'Rapel em Montanha', 'Rapel em montanhas para aventureiros com instrutores experientes.', 200.00, 'Patagônia, Argentina', '2024-09-01', '2024-09-30', 4, 'rapel_1.jpg', 'rapel_2.jpg', 'rapel_3.jpg', 0),
(6, 6, 3, 4, 'Passeio Gastronômico pelos Mercados Locais', 'Uma jornada gastronômica pelos mercados locais com degustação de pratos tradicionais.', 45.00, 'São Paulo, Brasil', '2024-10-01', '2024-10-31', 5, 'mercado_gastr_1.jpg', 'mercado_gastr_2.jpg', 'mercado_gastr_3.jpg', 0),
(7, 7, 2, 5, 'Visita ao Parque Nacional', 'Explore as trilhas do parque nacional com guias locais e observe a fauna nativa.', 120.00, 'Kruger, África do Sul', '2024-09-01', '2024-09-30', 3, 'parque_kruger_1.jpg', 'parque_kruger_2.jpg', 'parque_kruger_3.jpg', 0),
(8, 8, 1, 3, 'Museu de Arte Moderna', 'Visite uma das maiores coleções de arte moderna com um guia especializado.', 60.00, 'Nova York, EUA', '2024-10-01', '2024-10-31', 5, 'museu_arte_moderna_1.jpg', 'museu_arte_moderna_2.jpg', 'museu_arte_moderna_3.jpg', 0),
(9, 14, 4, 6, 'Rafting em Rápidos', 'Uma emocionante aventura em rios com águas rápidas e guias profissionais.', 180.00, 'Colorado, EUA', '2024-10-05', '2024-10-05', 4, 'rafting_1.jpg', 'rafting_2.jpg', 'rafting_3.jpg', 0),
(10, 6, 2, 4, 'Passeio de Bicicleta na Floresta', 'Descubra paisagens naturais enquanto pedala por trilhas seguras na floresta.', 95.00, 'Amazônia, Brasil', '2024-10-25', '2024-10-25', 5, 'bicicleta_1.jpg', 'bicicleta_2.jpg', 'bicicleta_3.jpg', 0),
(11, 14, 1, 1, 'Passeio Cultural pelo Centro Histórico', 'Um passeio guiado pelas ruas históricas e monumentos icônicos da cidade.', 40.00, 'Lisboa, Portugal', '2024-09-21', '2024-10-15', 5, 'centro_hist_1.jpg', 'centro_hist_2.jpg', 'centro_hist_3.jpg', 0),
(12, 6, 3, 4, 'Tour Gastronômico pelos Restaurantes Típicos', 'Descubra os melhores restaurantes locais com pratos tradicionais.', 70.00, 'Lisboa, Portugal', '2024-09-22', '2024-10-20', 5, 'tour_gastro_1.jpg', 'tour_gastro_2.jpg', 'tour_gastro_3.jpg', 0),
(13, 7, 2, 2, 'Show de Música Tradicional', 'Aprecie um show de música tradicional em um dos melhores teatros da cidade.', 55.00, 'Lisboa, Portugal', '2024-09-25', '2024-10-25', 4, 'musica_trad_1.jpg', 'musica_trad_2.jpg', 'musica_trad_3.jpg', 0),
(14, 8, 4, 6, 'Aventura de Escalada em Penhascos', 'Desfrute de uma aventura de escalada com vistas deslumbrantes e guias experientes.', 180.00, 'Lisboa, Portugal', '2024-10-01', '2024-10-31', 4, 'escalada_1.jpg', 'escalada_2.jpg', 'escalada_3.jpg', 0),
(15, 14, 2, 4, 'Passeio de Caiaque no Rio Tejo', 'Explore as águas do Rio Tejo em uma emocionante aventura de caiaque.', 90.00, 'Lisboa, Portugal', '2024-09-30', '2024-10-20', 5, 'caiaque_1.jpg', 'caiaque_2.jpg', 'caiaque_3.jpg', 0),
(16, 6, 4, 6, 'Pular de paraquedas', 'bem radical', 1000.00, 'brasil', '2024-09-23', '2028-01-01', 1, 'transferir (2).jpeg', NULL, NULL, 0),
(17, 6, 4, 6, 'Skydiving', 'bem radical', 1500.00, 'portugal', '2024-09-03', '2024-11-07', 2, 'uma imagem.jpg', NULL, NULL, 0),
(18, 9, 1, 2, 'Show do Gustavo Lima', 'Show', 10.00, 'Porto', '2024-09-03', '2024-09-30', 1, '', '../../public/tours/imagens/gustavo.jpeg', '../../public/tours/imagens/gustavo.jpeg', 0),
(21, 9, 4, 6, 'Surfar onda gigante', 'Surfar ondas gigantes em Nazaré!!!', 10.00, 'Nazaré, Portugal', '2000-01-01', '2030-01-01', 4, 'surf.jpg', 'surf.jpg', NULL, 0),
(22, 14, 2, 4, 'Camping', 'Acampamentos de nudismo ', 24.00, 'Amazonas', '2024-12-31', '2025-01-31', 5, 'camping.jpg', 'camping2.jpg', 'camping3.jpg', 0);

-- --------------------------------------------------------

--
-- Table structure for table `t_art_comen`
--

CREATE TABLE `t_art_comen` (
  `id` int(11) NOT NULL,
  `comentario` text NOT NULL,
  `id_remetente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_art_comen`
--

INSERT INTO `t_art_comen` (`id`, `comentario`, `id_remetente`, `id_destinatario`, `created_at`) VALUES
(1, 'bom dia', 12, 13, '2024-10-07 15:22:30'),
(2, '🏳️‍🌈?', 12, 13, '2024-10-07 15:22:39'),
(3, 'bom dia adminino', 12, 10, '2024-10-07 15:22:56'),
(4, 'bom dia tudo bem?', 10, 12, '2024-10-07 15:23:24'),
(5, 'oii', 12, 9, '2024-10-07 17:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `t_carrinho`
--

CREATE TABLE `t_carrinho` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_artigo` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT 1,
  `data_adicao` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_item` enum('atividade','hospedagem','voo','pacote') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_carrinho`
--

INSERT INTO `t_carrinho` (`id`, `id_user`, `id_artigo`, `quantidade`, `data_adicao`, `tipo_item`) VALUES
(19, 9, 11, 1, '2024-09-24 18:21:19', 'atividade'),
(22, 9, 4, 1, '2024-09-28 19:09:37', 'atividade'),
(30, 9, 2, 1, '2024-10-04 16:26:08', 'hospedagem'),
(33, 10, 3, 1, '2024-10-07 15:09:51', 'atividade'),
(34, 10, 2, 1, '2024-10-07 15:11:45', 'hospedagem');

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
-- Table structure for table `t_hospedagem`
--

CREATE TABLE `t_hospedagem` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `n_quartos` int(11) DEFAULT NULL,
  `preco_diaria` decimal(10,2) NOT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `classificacao` int(11) DEFAULT NULL,
  `horario_checkin` time DEFAULT NULL,
  `horario_checkout` time DEFAULT NULL,
  `tipo_hospedagem` enum('hotel','apartamento','hostel','castelo','cabana','resort') DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_atualizacao` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `foto1` varchar(255) DEFAULT NULL,
  `foto2` varchar(255) DEFAULT NULL,
  `foto3` varchar(255) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `data_fim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_hospedagem`
--

INSERT INTO `t_hospedagem` (`id`, `id_user`, `nome`, `descricao`, `n_quartos`, `preco_diaria`, `localizacao`, `classificacao`, `horario_checkin`, `horario_checkout`, `tipo_hospedagem`, `data_criacao`, `data_atualizacao`, `foto1`, `foto2`, `foto3`, `data_inicio`, `data_fim`) VALUES
(1, 9, 'Hotel Matos', 'Descubra um mundo de conforto, luxo e aventura enquanto explora nossa seleção selecionada de hotéis, tornando cada momento da sua fuga verdadeiramente extraordinário.', 20, 150.00, 'Portugal', 4, '15:00:00', '10:00:00', 'hotel', '2024-10-04 15:21:39', '2024-10-04 15:21:39', 'hotel matos.png', 'suite_romantica.jpg', NULL, NULL, NULL),
(2, 9, 'Pine Cliff Algarve Resort', 'O Pine Cliffs, a Luxury Collection Resort é um dos resorts de luxo mais prestigiados e premiados da Europa, oferecendo uma localização privilegiada e mais', 100, 3000.00, 'Algarve, Portugal', 5, '16:00:00', '12:00:00', 'resort', '2024-10-04 15:40:41', '2024-10-04 15:40:41', 'pinecliff.jpg', 'algarve.jpg', 'pine.jpg', NULL, NULL),
(3, 9, 'Transylvania', 'Hotel temática do filme de animação, assustar tomem cuidado com as crianças!!', 13, 15.00, 'Mistério', 2, '00:00:00', '23:59:00', 'castelo', '2024-10-04 15:42:07', '2024-10-04 15:42:07', 'transilvania.jpg', 'transyl.jpg', NULL, NULL, NULL),
(4, 9, 'Hotel Bom pra Cachorro', 'Hotel para dogs', 5, 30.00, 'Portugal', 3, '10:00:00', '09:00:00', 'hostel', '2024-10-04 15:42:47', '2024-10-04 15:42:48', 'hotel dog.jpg', 'dogs.jpg', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `t_reservas`
--

CREATE TABLE `t_reservas` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tipo_reserva` enum('atividade','voo','hospedagem','pacote') NOT NULL,
  `data_reserva` date NOT NULL,
  `quantidade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_reservas`
--

INSERT INTO `t_reservas` (`id`, `item_id`, `user_id`, `tipo_reserva`, `data_reserva`, `quantidade`) VALUES
(1, 4, 6, 'atividade', '2024-09-24', 4),
(2, 12, 6, 'atividade', '2024-09-24', 1),
(3, 13, 6, 'atividade', '2024-09-24', 1),
(4, 15, 6, 'atividade', '2024-09-24', 1),
(5, 4, 6, 'atividade', '2024-09-24', 1),
(7, 11, 9, 'atividade', '2024-09-24', 2),
(8, 4, 9, 'atividade', '2024-09-24', 1),
(9, 12, 9, 'atividade', '2024-09-24', 2),
(11, 4, 9, 'atividade', '2024-09-24', 3),
(12, 15, 9, 'atividade', '2024-09-24', 1),
(13, 17, 9, 'atividade', '2024-09-24', 2),
(14, 11, 9, 'atividade', '2024-09-24', 2),
(15, 13, 9, 'atividade', '2024-09-24', 2),
(16, 20, 9, 'atividade', '2024-09-24', 1),
(20, 12, 8, 'atividade', '2024-09-28', 2),
(21, 11, 10, 'atividade', '2024-09-30', 1),
(23, 22, 10, 'atividade', '2024-10-01', 1),
(24, 6, 12, 'atividade', '2024-10-07', 4),
(25, 1, 12, 'atividade', '2024-10-07', 1);

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
(6, 'jujuba', 'jujubas', 'finochio@gmail.com', '0002-03-20', '', '$2y$10$FO8sLuzGb48wK.RdmhUNnuef1309Ajc2aFiPlvDsvdPHiYLPL9Cw.', 'teste.jpg', 0, 1),
(7, 'Karol', 'Karoline', 'karol@gmail.com', '2024-09-19', '', '$2y$10$AeZm1fuqUb8cmWag2DboEejr8eDak/qD5PQuxRh.HcFhgqUlIAP/2', 'barco.jpg', 1, 1),
(8, 'teste', 'teste', 'teste@gmail.com', '2024-09-19', '', '$2y$10$UHhzzR4.fT4zRVFxaDQlr.D4MlSuxUCXiUr3IMupWrNzD0jGNAnn.', 'barco.jpg', 0, 1),
(9, 'vendedor', 'vendedor1', 'vendedor1@gmail.com', '2024-09-01', 'Venda de Atividades', '$2y$10$JVh.EL4CCCUWvpFT8CRvBes9uU2bMBz57vjXagvkYNys4E9AV8PH.', 'ia.jpg', 2, 1),
(10, 'admin', 'Administrador', 'adm.bestway@bestway.com', '2000-01-01', '', '$2y$10$VY8ZO913kX3KCd75fmib/uoxrpLxbso2/Ihhv0akcG3o3isTDaHbO', 'adm.jpg', 4, 1),
(11, 'vendedor2', 'vendedor de casas', 'vendedo2@gmail.com', NULL, 'Karols Hotels', '$2y$10$cLr90495Bjs/agRRPdT9RezwQsbSxkT838yjmDndPsIxzHjPDJJBG', NULL, 1, 1),
(12, 'jimin vendedor', 'Jimin do BTS', 'inesbrasil@outlook.com', '2024-10-01', 'Inês Brasil do BTS', '$2y$10$cdDN3hxfxQaL5CrSo8GoWe6Ffj7KaIB2NTC.ZCmtNTOdSu2TKSwc2', 'jimin.jpeg', 2, 1),
(13, 'David', 'Lucas Davi', 'lucasdavi@gmail.com', '2024-09-30', '', '$2y$10$Tshq8YnZHjSu3NHjuII79eSCeXk.MHtFTbFfagPgDGxpsW8ZBP6S.', 'lucas.jpg', 1, 1),
(14, 'guilherme vendedor', 'Guilherme Silva', 'asdsadasdas@gmail.com', NULL, 'asdasdsad', '$2y$10$g949wYlF/dznY2ccZRDkYeIl7sn5gokGcATSiB2TSwwHfe0oeU9ci', NULL, 2, 1);

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_remetente` (`id_remetente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Indexes for table `t_carrinho`
--
ALTER TABLE `t_carrinho`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_artigo` (`id_artigo`);

--
-- Indexes for table `t_categoria`
--
ALTER TABLE `t_categoria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_hospedagem`
--
ALTER TABLE `t_hospedagem`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_reservas`
--
ALTER TABLE `t_reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `t_subcat`
--
ALTER TABLE `t_subcat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nick` (`nick`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_artigo`
--
ALTER TABLE `t_artigo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `t_art_comen`
--
ALTER TABLE `t_art_comen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `t_carrinho`
--
ALTER TABLE `t_carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `t_categoria`
--
ALTER TABLE `t_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_hospedagem`
--
ALTER TABLE `t_hospedagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_reservas`
--
ALTER TABLE `t_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `t_subcat`
--
ALTER TABLE `t_subcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `t_art_comen`
--
ALTER TABLE `t_art_comen`
  ADD CONSTRAINT `t_art_comen_ibfk_1` FOREIGN KEY (`id_remetente`) REFERENCES `t_user` (`id`),
  ADD CONSTRAINT `t_art_comen_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `t_user` (`id`);

--
-- Constraints for table `t_carrinho`
--
ALTER TABLE `t_carrinho`
  ADD CONSTRAINT `t_carrinho_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `t_user` (`id`),
  ADD CONSTRAINT `t_carrinho_ibfk_2` FOREIGN KEY (`id_artigo`) REFERENCES `t_artigo` (`id`);

--
-- Constraints for table `t_reservas`
--
ALTER TABLE `t_reservas`
  ADD CONSTRAINT `t_reservas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `t_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
