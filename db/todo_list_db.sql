-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Creato il: Gen 03, 2024 alle 16:56
-- Versione del server: 5.7.39
-- Versione PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todo_list`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `todo_list`
--

CREATE TABLE `todo_list` (
  `id` int(11) NOT NULL,
  `nome_todo` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `todo_list`
--

INSERT INTO `todo_list` (`id`, `nome_todo`, `status`, `user_id`) VALUES
(2, 'pipi', 0, 2),
(9, 'casa', 0, 13),
(11, 'vbiascotti', 0, 13),
(12, 'bistecca', 0, 2),
(13, 'bistecca', 0, 2),
(14, 'bistecca', 0, 2),
(15, 'pippo', 0, 2),
(16, 'pluto', 0, 2),
(17, 'pippo', 0, 2),
(19, 'pizza', 0, 2),
(20, 'pizza', 0, 13),
(21, 'pippo', 0, 1),
(24, 'spesa', 1, 34),
(25, 'cane fuori', 1, 34),
(28, 'portare fuori il cane', 0, 35),
(44, 'pescare', 0, 34),
(58, 'cane fuori', 1, 31),
(59, 'imparare js', 1, 31),
(60, 'portare fuori il cane', 0, 31);

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`ID`, `username`, `password`) VALUES
(1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99'),
(2, 'federico', '5f4dcc3b5aa765d61d8327deb882cf99'),
(3, 'carlo', '5f4dcc3b5aa765d61d8327deb882cf99'),
(4, 'franco', '5f4dcc3b5aa765d61d8327deb882cf99'),
(13, 'daniel', '5f4dcc3b5aa765d61d8327deb882cf99'),
(29, 'luca', '5f4dcc3b5aa765d61d8327deb882cf99'),
(31, 'fede', '81dc9bdb52d04dc20036dbd8313ed055'),
(32, 'fede', '81dc9bdb52d04dc20036dbd8313ed055'),
(33, 'fili', '81dc9bdb52d04dc20036dbd8313ed055'),
(34, 'fra', '81dc9bdb52d04dc20036dbd8313ed055'),
(35, 'ale', '81dc9bdb52d04dc20036dbd8313ed055'),
(36, 'alessandra', '81dc9bdb52d04dc20036dbd8313ed055');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `todo_list`
--
ALTER TABLE `todo_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `todo_list`
--
ALTER TABLE `todo_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `todo_list`
--
ALTER TABLE `todo_list`
  ADD CONSTRAINT `todo_list_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
