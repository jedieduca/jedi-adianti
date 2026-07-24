-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.147
-- Generation Time: 13-Maio-2026 às 22:15
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
-- Estrutura da tabela `usuarioescola`
--

CREATE TABLE `usuarioescola` (
  `id` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `idescola` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `usuarioescola`
--

INSERT INTO `usuarioescola` (`id`, `idusuario`, `idescola`) VALUES
(1, 1, 100),
(2, 130, 103),
(3, 132, 103),
(4, 132, 103),
(5, 132, 103),
(6, 3, 100),
(7, 3, 100),
(8, 3, 100),
(9, 4, 100),
(10, 4, 100);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `usuarioescola`
--
ALTER TABLE `usuarioescola`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `usuarioescola`
--
ALTER TABLE `usuarioescola`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
