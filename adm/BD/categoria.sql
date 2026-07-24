-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.147
-- Generation Time: 30-Jan-2026 às 12:54
-- Versão do servidor: 5.7.32-35-log
-- PHP Version: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jedieduca`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`) VALUES
(1, 'Política'),
(2, 'Educação'),
(3, 'Tecnologia e Inovação'),
(4, 'Segurança e Justiça'),
(5, 'Economia e Negócios'),
(6, 'Saúde e Bem-estar'),
(7, 'Internacional'),
(8, 'Ciências'),
(9, 'Meio Ambiente e Clima'),
(10, 'Cultura e Entretenimento'),
(11, 'Sociedade e Comportamento'),
(12, 'Esporte'),
(13, 'Cidades e Cotidiano'),
(14, 'Direitos Humanos'),
(15, 'Mídia, Informação e Redes Sociais'),
(16, 'Energia e Transição Energética'),
(17, 'Turismo');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
