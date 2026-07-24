-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 186.202.152.147
-- Generation Time: 24-Abr-2026 às 09:03
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
-- Estrutura da tabela `alunoturma`
--

CREATE TABLE `alunoturma` (
  `id` int(11) NOT NULL,
  `cpf` char(11) COLLATE latin1_general_ci NOT NULL,
  `idTurma` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Extraindo dados da tabela `alunoturma`
--

INSERT INTO `alunoturma` (`id`, `cpf`, `idTurma`) VALUES
(1, '98527193787', 0),
(2, '77777777777', 38);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alunoturma`
--
ALTER TABLE `alunoturma`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alunoturma`
--
ALTER TABLE `alunoturma`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
