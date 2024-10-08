-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2024 at 06:38 PM
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
-- Table structure for table `t_user`
--

CREATE TABLE `t_user` (
  `id` int(11) NOT NULL,
  `nick` varchar(20) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL,
  `nome_marca` varchar(150) NOT NULL,
  `biografia` text DEFAULT NULL,
  `data_nasc` varchar(10) DEFAULT NULL,
  `pass` varchar(255) NOT NULL,
  `foto` varchar(50) DEFAULT NULL,
  `tipo_user` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `t_user`
--

INSERT INTO `t_user` (`id`, `nick`, `nome`, `email`, `nome_marca`, `biografia`, `data_nasc`, `pass`, `foto`, `tipo_user`) VALUES
(6, 'jujuba', 'jujubas finicio', 'finochio@gmail.com', '', NULL, '0002-03-20', '$2y$10$FO8sLuzGb48wK.RdmhUNnuef1309Ajc2aFiPlvDsvdPHiYLPL9Cw.', 'teste.jpg', 0),
(7, 'Karol', 'Karoline', 'karol@gmail.com', '', NULL, '2024-09-19', '$2y$10$AeZm1fuqUb8cmWag2DboEejr8eDak/qD5PQuxRh.HcFhgqUlIAP/2', 'barco.jpg', 1),
(8, 'teste', 'teste', 'teste@gmail.com', '', NULL, '2024-09-19', '$2y$10$UHhzzR4.fT4zRVFxaDQlr.D4MlSuxUCXiUr3IMupWrNzD0jGNAnn.', 'barco.jpg', 0),
(9, 'vendedor', 'vendedor1', 'vendedor1@gmail.com', 'Venda de Atividades', NULL, '2024-09-01', '$2y$10$JVh.EL4CCCUWvpFT8CRvBes9uU2bMBz57vjXagvkYNys4E9AV8PH.', 'ia.jpg', 2),
(10, 'admin', 'Administrador', 'adm.bestway@bestway.com', '', NULL, '2000-01-01', '$2y$10$VY8ZO913kX3KCd75fmib/uoxrpLxbso2/Ihhv0akcG3o3isTDaHbO', 'adm.jpg', 4),
(11, 'vendedor2', 'vendedor de casas', 'vendedo2@gmail.com', 'Karols Hotels', NULL, NULL, '$2y$10$cLr90495Bjs/agRRPdT9RezwQsbSxkT838yjmDndPsIxzHjPDJJBG', NULL, 1),
(12, 'jimin vendedor', 'Jimin do BTS', 'inesbrasil@outlook.com', 'Inês Brasil do BTS', NULL, '2024-10-01', '$2y$10$cdDN3hxfxQaL5CrSo8GoWe6Ffj7KaIB2NTC.ZCmtNTOdSu2TKSwc2', 'jimin.jpeg', 2),
(13, 'David', 'Lucas Davi', 'lucasdavi@gmail.com', '', NULL, '2024-09-30', '$2y$10$Tshq8YnZHjSu3NHjuII79eSCeXk.MHtFTbFfagPgDGxpsW8ZBP6S.', 'lucas.jpg', 1),
(14, 'guilherme vendedor', 'Guilherme Silva', 'asdsadasdas@gmail.com', 'asdasdsad', NULL, '2004-01-27', '$2y$10$g949wYlF/dznY2ccZRDkYeIl7sn5gokGcATSiB2TSwwHfe0oeU9ci', 'Guilherme-Silva-13-min-684x1024.jpg', 2);

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
