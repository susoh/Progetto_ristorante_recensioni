-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 14, 2025 alle 20:43
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ristorante_recensioni`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `id_recensione` int(11) NOT NULL,
  `voto` int(11) NOT NULL CHECK (`voto` >= 1 and `voto` <= 5),
  `data` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_utente` int(11) NOT NULL,
  `codiceristorante` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `recensione`
--

INSERT INTO `recensione` (`id_recensione`, `voto`, `data`, `id_utente`, `codiceristorante`) VALUES
(1, 4, '2025-04-11 21:00:00', 5, 'ris1');

-- --------------------------------------------------------

--
-- Struttura della tabella `ristorante`
--

CREATE TABLE `ristorante` (
  `id_ristorante` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `indirizzo` varchar(255) NOT NULL,
  `citta` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ristorante`
--

INSERT INTO `ristorante` (`id_ristorante`, `nome`, `indirizzo`, `citta`) VALUES
('ris1', 'Il bubolo antico', 'Via del pongo 23', 'Firenze'),
('ris2', 'Il cantinone', 'Via di Leon 63', 'Firenze');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id_utente` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dataRegistrazione` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id_utente`, `username`, `password`, `nome`, `cognome`, `email`, `dataRegistrazione`, `admin`) VALUES
(4, 'Gab3645', 'b97873a40f73abedd8d685a7cd5e5f85e4a9cfb83eac26886640a0813850122', ' Gabriel', ' Siano ', ' Gab.siano@gmail.it ', '2025-04-01 07:33:28', 0),
(5, 'malenia', '8c2101cf54bcafc68385dd494735f93826ccc148cff1ffa9f4184db3b7e30737', ' malenia', ' miquella ', ' malenia.bladeof@miquella.er ', '2025-05-14 18:39:29', 1),
(7, 'papa quinto', 'ada8e6490c4294083ffd70cd256b92b9b4e51bbcc7b1301a111dd2152bf4e41a', 'Lorenzo', 'Vinciguerra', 'papv@slime.100k', '2025-04-01 07:36:49', 0),
(8, 'FabXBenti', '6df983c20df2ddf0c981c8978d526cf0a5b4fd8369759fee4419fdf31d6b22cb', 'Fabio', 'Zavataro', 'fabio.zava@protomail.ow', '2025-04-03 08:15:24', 0),
(9, 'Blackest Snake', '67116a8b5426fd51e13ec9c796f1a250de534b74ea186c83154b5bfd72dce3f8', 'Nerissima', 'Serpe', 'neri.serpe@slime.100k', '2025-04-03 08:24:38', 0),
(10, 'rigo72_', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'Mattia', 'Gori', 'rigo.storto63@liberomail.uk', '2025-04-08 06:33:51', 0),
(11, 'mattia', '03ac674216f3e15c761ee1a5e255f067953623c8b388b4459e13f978d7c846f4', 'mattia', 'negri', 'mattia.negri@gmail.com', '2025-04-08 06:54:50', 0),
(21, 'a', 'ca978112ca1bbdcafac231b39a23dc4da786eff8147c4e72b9807785afee48bb', 'a', 'a', 'brussi.giovanni@itismeucci.com', '2025-04-29 06:33:55', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `recensione`
--
ALTER TABLE `recensione`
  ADD PRIMARY KEY (`id_recensione`),
  ADD KEY `id_utente` (`id_utente`),
  ADD KEY `codiceristorante` (`codiceristorante`);

--
-- Indici per le tabelle `ristorante`
--
ALTER TABLE `ristorante`
  ADD PRIMARY KEY (`id_ristorante`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id_utente`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `recensione`
--
ALTER TABLE `recensione`
  MODIFY `id_recensione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id_utente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `recensione`
--
ALTER TABLE `recensione`
  ADD CONSTRAINT `recensione_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id_utente`),
  ADD CONSTRAINT `recensione_ibfk_2` FOREIGN KEY (`codiceristorante`) REFERENCES `ristorante` (`id_ristorante`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
