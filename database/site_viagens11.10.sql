-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2024 at 01:33 AM
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
(11, 14, 1, 1, 'Passeio Cultural pelo Centro Histórico', 'Um passeio guiado pelas ruas históricas e monumentos icônicos da cidade.', 40.00, 'Lisboa, Portugal', '2024-09-21', '2024-10-15', 5, 'centro_hist_1.jpeg', 'centro_hist_2.jpg', 'centro_hist_3.jpg', 0),
(12, 6, 3, 4, 'Tour Gastronômico pelos Restaurantes Típicos', 'Descubra os melhores restaurantes locais com pratos tradicionais.', 70.00, 'Lisboa, Portugal', '2024-09-22', '2024-10-20', 5, 'tour_gastro_1.jpg', 'tour_gastro_2.jpg', 'tour_gastro_3.jpg', 0),
(13, 7, 2, 2, 'Show de Música Tradicional', 'Aprecie um show de música tradicional em um dos melhores teatros da cidade.', 55.00, 'Lisboa, Portugal', '2024-09-25', '2024-10-25', 4, 'musica_trad_1.jpg', 'musica_trad_2.jpg', 'musica_trad_3.jpg', 0),
(14, 8, 4, 6, 'Aventura de Escalada em Penhascos', 'Desfrute de uma aventura de escalada com vistas deslumbrantes e guias experientes.', 180.00, 'Lisboa, Portugal', '2024-10-01', '2024-10-31', 4, 'escalada_1.jpg', 'escalada_2.jpg', 'escalada_3.jpg', 0),
(15, 14, 2, 4, 'Passeio de Caiaque no Rio Tejo', 'Explore as águas do Rio Tejo em uma emocionante aventura de caiaque.', 90.00, 'Lisboa, Portugal', '2024-09-30', '2024-10-20', 5, 'caiaque_1.jpg', 'caiaque_2.jpg', 'caiaque_3.jpg', 0),
(16, 6, 4, 6, 'Pular de paraquedas', 'bem radical', 1000.00, 'brasil', '2024-09-23', '2028-01-01', 1, 'transferir (2).jpeg', NULL, NULL, 0),
(17, 6, 4, 6, 'Skydiving', 'bem radical', 1500.00, 'portugal', '2024-09-03', '2024-11-07', 2, 'uma imagem.jpg', NULL, NULL, 0),
(18, 9, 1, 2, 'Show do Gustavo Lima', 'Show', 10.00, 'Porto', '2024-09-03', '2024-09-30', 1, '', '../../public/tours/imagens/gustavo.jpeg', '../../public/tours/imagens/gustavo.jpeg', 0),
(21, 9, 4, 6, 'Surfar onda gigante', 'Surfar ondas gigantes em Nazaré!!!', 10.00, 'Nazaré, Portugal', '2000-01-01', '2030-01-01', 4, 'surf.jpg', 'surf.jpg', NULL, 0),
(22, 14, 2, 4, 'Camping', 'Acampamentos de nudismo ', 24.00, 'Amazonas', '2024-12-31', '2025-01-31', 5, 'camping.jpg', 'camping2.jpg', 'camping3.jpg', 0),
(23, 9, 1, 1, 'Tour Coliseu de Roma', 'Visite o icônico Coliseu de Roma, um marco da história.', 50.00, 'Roma, Itália', '2024-06-01', '2024-06-30', 1, 'coliseu.jpg', NULL, NULL, 0),
(24, 12, 1, 1, 'Passeio de barco pelo Rio Sena', 'Desfrute de um passeio romântico pelo Rio Sena.', 60.00, 'Paris, França', '2024-07-01', '2024-07-15', 1, 'riosena.jpg', NULL, NULL, 0),
(25, 14, 1, 1, 'Cristo Redentor', 'Admire o Cristo Redentor, uma das maravilhas do mundo moderno.', 30.00, 'Rio de Janeiro, Brasil', '2024-08-01', '2024-08-10', 1, 'cristoredentor.jpg', NULL, NULL, 0),
(26, 9, 1, 1, 'Pirâmides de Gizé', 'Descubra as enigmáticas Pirâmides de Gizé.', 100.00, 'Cairo, Egito', '2024-09-01', '2024-09-15', 1, 'piramides.jpg', NULL, NULL, 0),
(27, 12, 1, 1, 'Tour pelo Central Park', 'Caminhe pelo famoso Central Park em Nova York.', 80.00, 'Nova York, EUA', '2024-10-01', '2024-10-20', 1, 'centralpark.jpg', NULL, NULL, 0),
(28, 14, 2, 4, 'Cruzeiro pelas ilhas vulcânicas de Santorini', 'Cruzeiro pelas ilhas vulcânicas de Santorini.', 120.00, 'Santorini, Grécia', '2024-07-10', '2024-07-20', 1, 'santorini.jpg', NULL, NULL, 0),
(29, 9, 4, 6, 'Gold Coast: Aula de Surf', 'Experimente a emoção de pegar uma onda com uma aula de surf na Gold Coast.', 41.98, 'Gold Coast, Austrália', '2024-08-05', '2024-08-10', 1, 'australia.jpg', NULL, NULL, 0),
(30, 12, 4, 6, 'Chamonix: Dia de Ski', 'Experimente esquiar nos Alpes franceses, no famoso resort de Chamonix.', 41.98, 'Chamonix, França', '2024-12-01', '2024-12-10', 1, 'frança.jpg', NULL, NULL, 0),
(31, 14, 4, 6, 'Antalya: Safári de Buggy', 'Experimente a emoção de percorrer paisagens desérticas.', 46.43, 'Antalya, Turquia', '2024-09-15', '2024-09-20', 1, 'antalya.jpg', NULL, NULL, 0),
(32, 9, 4, 6, 'Dubai: Paraquedismo no Deserto', 'Contemple as dunas de Dubai enquanto despenca do céu a uma velocidade de 192 km/h.', 41.98, 'Dubai, Emirados Árabes', '2024-11-01', '2024-11-05', 1, 'dubai.jpg', NULL, NULL, 0),
(33, 16, 1, 2, 'sherek rave', 'inimigos do fim', 123.00, 'Alentejo', '2024-12-11', '2025-01-11', 3, 'pine.jpg', 'dogs.jpg', NULL, 0),
(34, 9, 4, 6, 'Saara: Passeio de quadriciclo pelo deserto', 'Explore as paisagens deslumbrantes de Erg Chebbi em uma emocionante aventura de quadriciclo.', 266.00, 'Saara, Marrocos', '2024-05-01', '2024-05-15', 1, 'saara.jpg', NULL, NULL, 0),
(35, 12, 4, 6, 'Geysir: snowmobile na geleira Langjökull', 'Embarque em um passeio de snowmobiling em Geysir.', 208.00, 'Langjökull, Islândia', '2024-07-01', '2024-07-10', 1, 'geysir.jpg', NULL, NULL, 0),
(36, 14, 4, 6, 'Tenerife: Voo duplo de parapente', 'Experimente o incrível e emocionante mundo do parapente com uma equipe experiente e profissional.', 110.00, 'Tenerife, Espanha', '2024-08-01', '2024-08-10', 1, 'tenerife.jpg', NULL, NULL, 0),
(37, 9, 4, 6, 'Punta Cana: Jet Ski a melhor experiência de adrena', 'Leve os participantes a lugares deslumbrantes como Cayo Bocaina, Cayo Esteban, a praia El Mono e a piscina natural de Miches.', 266.00, 'Punta Cana, República Dominicana', '2024-09-01', '2024-09-10', 1, 'puntacana.jpg', NULL, NULL, 0);

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
(5, 'oii', 12, 9, '2024-10-07 17:31:41'),
(6, 'disse a  putinha', 16, 6, '2024-10-08 17:14:22'),
(7, '🏳️‍🌈', 16, 6, '2024-10-08 17:14:32'),
(8, '🏳️‍🌈🏳️‍🌈🏳️‍🌈', 16, 6, '2024-10-08 17:14:38'),
(9, '😊✈️🏳️‍🌈', 10, 15, '2024-10-10 16:40:42'),
(10, 'oii Lipe', 10, 15, '2024-10-10 16:40:50');

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
(52, 6, 5, 2, '2024-10-08 15:20:52', 'hospedagem'),
(83, 10, 1, 6, '2024-10-10 14:56:19', 'hospedagem'),
(84, 10, 3, 4, '2024-10-10 14:57:02', 'hospedagem'),
(85, 10, 1, 1, '2024-10-10 14:57:26', 'atividade'),
(86, 10, 4, 1, '2024-10-10 14:57:41', 'atividade'),
(87, 10, 14, 1, '2024-10-10 14:58:08', 'atividade');

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
-- Table structure for table `t_favoritos`
--

CREATE TABLE `t_favoritos` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_artigo` int(11) NOT NULL,
  `data_adicao` timestamp NOT NULL DEFAULT current_timestamp(),
  `tipo_reserva` enum('atividade','voo','hospedagem','pacote') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_favoritos`
--

INSERT INTO `t_favoritos` (`id`, `id_user`, `id_artigo`, `data_adicao`, `tipo_reserva`) VALUES
(3, 9, 1, '2024-10-09 23:48:28', NULL),
(0, 6, 21, '2024-10-10 22:45:33', 'atividade'),
(0, 6, 3, '2024-10-10 22:46:07', 'hospedagem'),
(0, 6, 4, '2024-10-10 23:01:31', 'hospedagem'),
(0, 6, 1, '2024-10-10 23:01:44', 'atividade');

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
(1, 9, 'Hotel Matos', 'Descubra um mundo de conforto, luxo e aventura enquanto explora nossa seleção selecionada de hotéis, tornando cada momento da sua fuga verdadeiramente extraordinário.', 20, 150.00, 'Portugal', 4, '15:00:00', '10:00:00', 'hotel', '2024-10-04 15:21:39', '2024-10-07 23:50:10', 'hotel matos.png', 'suite_romantica.jpg', NULL, '2024-10-01', '2024-10-31'),
(2, 9, 'Pine Cliff Algarve Resort', 'O Pine Cliffs, a Luxury Collection Resort é um dos resorts de luxo mais prestigiados e premiados da Europa, oferecendo uma localização privilegiada e mais', 100, 3000.00, 'Algarve, Portugal', 5, '16:00:00', '12:00:00', 'resort', '2024-10-04 15:40:41', '2024-10-07 23:50:10', 'pinecliff.jpg', 'algarve.jpg', 'pine.jpg', '2024-10-01', '2024-11-30'),
(3, 9, 'Transylvania', 'Hotel temática do filme de animação, assustar tomem cuidado com as crianças!!', 13, 15.00, 'Mistério', 2, '00:00:00', '23:59:00', 'castelo', '2024-10-04 15:42:07', '2024-10-07 23:50:10', 'transilvania.jpg', 'transyl.jpg', NULL, '2024-10-01', '2024-10-31'),
(4, 9, 'Hotel Bom pra Cachorro', 'Hotel para dogs', 5, 30.00, 'Portugal', 3, '10:00:00', '09:00:00', 'hostel', '2024-10-04 15:42:47', '2024-10-07 23:50:10', 'hotel dog.jpg', 'dogs.jpg', NULL, '2024-10-01', '2024-10-15'),
(5, 14, 'Crowne Plaza Paris République', 'Hotel clássico no centro de Paris', 200, 210.00, 'Paris, França', 4, '14:00:00', '11:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'paris.jpg', NULL, NULL, '2024-04-10', '2024-04-20'),
(6, 12, 'The Ritz-Carlton, Central Park', 'Hotel de luxo com vista para o Central Park', 150, 500.00, 'New York, EUA', 5, '15:00:00', '11:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'novayork.jpg', NULL, NULL, '2024-05-01', '2024-05-15'),
(7, 12, 'Torel Avantgarde', 'Hotel de luxo com vista para o rio Douro', 45, 250.00, 'Porto, Portugal', 5, '14:00:00', '12:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'porto.jpg', NULL, NULL, '2024-06-01', '2024-06-15'),
(8, 14, 'Signiel Seoul', 'Luxuoso hotel em Seul', 300, 320.00, 'Seul, Coreia do Sul', 5, '14:00:00', '11:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'seul.jpg', NULL, NULL, '2024-06-01', '2024-06-15'),
(9, 12, '7 Four Seasons', 'Hotel exclusivo com vista de Londres', 50, 600.00, 'Londres, Reino Unido', 5, '15:00:00', '12:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'londres.jpg', NULL, NULL, '2024-07-01', '2024-07-15'),
(10, 14, 'Pestana CR7', 'Hotel temático Cristiano Ronaldo', 50, 180.00, 'Marrakech, Marrocos', 4, '15:00:00', '12:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'Marrakech.jpg', NULL, NULL, '2024-07-10', '2024-07-25'),
(11, 12, 'Majestic Elegance Costa Mujeres', 'Resort all-inclusive em Cancun', 300, 320.00, 'Cancun, México', 5, '14:00:00', '11:00:00', 'resort', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'mexico.jpg', NULL, NULL, '2024-05-15', '2024-06-01'),
(12, 14, 'Hotel Walhalla', 'Elegante hotel em Gallen', 200, 400.00, 'Gallen, Suíça', 5, '14:00:00', '12:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'suica.jpg', NULL, NULL, '2024-06-01', '2024-06-15'),
(13, 12, 'Kandima Maldives', 'Resort exclusivo nas Maldivas', 100, 1000.00, 'Kudahuvadhoo, Maldivas', 5, '15:00:00', '12:00:00', 'resort', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'maldivas.jpg', NULL, NULL, '2024-07-01', '2024-07-10'),
(14, 14, 'Pera Palace Hotel', 'Hotel histórico em Istambul', 150, 220.00, 'Istambul, Turquia', 4, '14:00:00', '11:00:00', 'hotel', '2024-10-08 00:00:14', '2024-10-08 00:00:14', 'turquia.jpg', NULL, NULL, '2024-08-01', '2024-08-15');

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
(25, 1, 12, 'atividade', '2024-10-07', 1),
(26, 2, 6, 'voo', '2024-10-08', 1),
(27, 11, 6, 'atividade', '2024-10-08', 1),
(28, 4, 6, 'atividade', '2024-10-08', 1),
(29, 12, 6, 'atividade', '2024-10-08', 1),
(30, 2, 6, 'hospedagem', '2024-10-08', 1),
(31, 11, 9, 'atividade', '2024-10-08', 1),
(32, 4, 9, 'atividade', '2024-10-08', 1),
(33, 2, 9, 'hospedagem', '2024-10-08', 1),
(34, 27, 9, 'atividade', '2024-10-08', 1),
(35, 5, 9, 'atividade', '2024-10-08', 1),
(37, 17, 15, 'atividade', '2024-10-08', 2);

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
(8, 'teste', 'teste', 'teste@gmail.com', '', NULL, '2024-09-19', '$2y$10$UHhzzR4.fT4zRVFxaDQlr.D4MlSuxUCXiUr3IMupWrNzD0jGNAnn.', 'barco.jpg', 1),
(9, 'vendedor', 'vendedor1', 'vendedor1@gmail.com', 'Venda de Atividades', 'João Silva é um entusiasta do turismo com mais de 10 anos de experiência no setor. Apaixonado por explorar novos destinos e culturas, João dedica-se a oferecer aos seus clientes as melhores opções de viagens personalizadas. Com um olhar atento para o conforto e a autenticidade, ele garante que cada passeio e estadia selecionados sejam experiências únicas, independentemente do destino. Seja para uma escapada de fim de semana, férias em família ou uma aventura em terras exóticas, João trabalha com dedicação para tornar cada viagem inesquecível, com ofertas que combinam qualidade e preços competitivos.', '2024-09-01', '$2y$10$JVh.EL4CCCUWvpFT8CRvBes9uU2bMBz57vjXagvkYNys4E9AV8PH.', 'ia.jpg', 2),
(10, 'admin', 'Administrador', 'adm.bestway@bestway.com', '', NULL, '2000-01-01', '$2y$10$VY8ZO913kX3KCd75fmib/uoxrpLxbso2/Ihhv0akcG3o3isTDaHbO', 'adm.jpg', 4),
(11, 'vendedor2', 'vendedor de casas', 'vendedo2@gmail.com', 'Karols Hotels', NULL, NULL, '$2y$10$cLr90495Bjs/agRRPdT9RezwQsbSxkT838yjmDndPsIxzHjPDJJBG', NULL, 1),
(12, 'jimin vendedor', 'Jimin do BTS', 'inesbrasil@outlook.com', 'Inês Brasil do BTS', 'Bem-vindo ao mundo das viagens com Jimin, sua estrela e guia! Jimin, conhecido mundialmente como um dos membros do BTS, agora traz sua paixão por explorar novos destinos diretamente para você. Com sua energia contagiante e atenção aos detalhes, Jimin não só encanta no palco, mas também na curadoria de experiências de viagens inesquecíveis.', '2024-10-01', '$2y$10$cdDN3hxfxQaL5CrSo8GoWe6Ffj7KaIB2NTC.ZCmtNTOdSu2TKSwc2', 'jimin.jpeg', 2),
(13, 'David', 'Lucas Davi', 'lucasdavi@gmail.com', '', NULL, '2024-09-30', '$2y$10$Tshq8YnZHjSu3NHjuII79eSCeXk.MHtFTbFfagPgDGxpsW8ZBP6S.', 'lucas.jpg', 1),
(14, 'guilherme vendedor', 'Guilherme Silva', 'asdsadasdas@gmail.com', 'asdasdsad', 'Guilherme Santos, aos 20 anos, traz toda a sua paixão por cultura pop para o mundo das viagens. Apaixonado por divas pop, filmes, séries e tendências, Guilherme sabe como transformar uma viagem em uma experiência repleta de referências e momentos icônicos. Com um olhar jovem e antenado, ele seleciona roteiros que vão desde visitas a cenários de filmes até shows e festivais imperdíveis. Se você quer uma viagem com muito estilo, música e lugares que vibram com a energia da cultura pop, Guilherme é o vendedor perfeito para criar a sua próxima aventura!', '2004-01-27', '$2y$10$g949wYlF/dznY2ccZRDkYeIl7sn5gokGcATSiB2TSwwHfe0oeU9ci', 'Guilherme-Silva-13-min-684x1024.jpg', 2),
(15, 'felippinho', 'felippe', 'felippeutz1@gmail.com', '', NULL, '2003-04-14', '$2y$10$.jNcqAoId8fJMV30hrd7gOYUlVsV/VFNPgrF7gEZvp8/oWmNas2CO', 'felipetutz.jpeg', 0),
(16, 'seuze', 'ze', 'ze@gmail.com', 'zevendas', NULL, '2003-04-14', '$2y$10$ruJbk7L3XQqKdKnaA51h.uFJGcOWu0qLV4ZTH43TzS13yYOoU1uda', 'mamaco.jpg', 2),
(17, 'crowne ', 'Crowne Plaza', 'Crowne@Plaza.com', 'Crowne Plaza LDA.', NULL, '1589-06-09', '$2y$10$DAUSdmFv4YyIh.frKhypveUY8txw55j2y1gul.pn838Oh4CfBQ4aW', 'download (1).png', 3),
(18, 'theritz', 'The Ritz', 'The@Ritz.com', 'The Ritz LDA.', NULL, '1986-06-20', '$2y$10$bCO9a7K5o0eTdDCpBhCgcO9184CIsMQc0bH3tD53zEtNwMJ8M2HBm', 'download.png', 3),
(19, 'fourseasons ', 'Four Seasons ', 'Four@Seasons.com', 'Four Seasons  LDA', NULL, '1584-09-05', '$2y$10$GG3qLetR1OlCqomWpcjtDOJJTeMEw.phPFHTqHznJ7vEudGUCSYEu', 'download (2).png', 3);

-- --------------------------------------------------------

--
-- Table structure for table `t_voos`
--

CREATE TABLE `t_voos` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `flight_number` varchar(255) DEFAULT NULL,
  `airline` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `arrival` varchar(255) DEFAULT NULL,
  `data_adicao` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `t_voos`
--

INSERT INTO `t_voos` (`id`, `id_user`, `flight_number`, `airline`, `price`, `arrival`, `data_adicao`) VALUES
(1, 6, 'HV 6002', 'Transavia', 129.00, 'Aeroporto de Amesterdão Schiphol', '2024-10-07 22:24:56'),
(2, 6, 'HV 6002', 'Transavia', 129.00, 'Aeroporto de Amesterdão Schiphol', '2024-10-07 22:29:18'),
(3, 6, 'LH 992', 'Lufthansa', 1641.00, 'Aeroporto de Amesterdão Schiphol', '2024-10-07 23:11:00');

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
-- Indexes for table `t_voos`
--
ALTER TABLE `t_voos`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `t_artigo`
--
ALTER TABLE `t_artigo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `t_art_comen`
--
ALTER TABLE `t_art_comen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `t_carrinho`
--
ALTER TABLE `t_carrinho`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `t_categoria`
--
ALTER TABLE `t_categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `t_hospedagem`
--
ALTER TABLE `t_hospedagem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `t_reservas`
--
ALTER TABLE `t_reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `t_subcat`
--
ALTER TABLE `t_subcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `t_voos`
--
ALTER TABLE `t_voos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
