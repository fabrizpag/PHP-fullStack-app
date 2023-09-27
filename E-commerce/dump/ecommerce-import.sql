-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Giu 26, 2023 alle 18:37
-- Versione del server: 10.4.28-MariaDB
-- Versione PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `carrello`
--

CREATE TABLE `carrello` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `quantita_prodotto` int(11) NOT NULL,
  `taglia_prodotto` enum('S','M','L','XL') DEFAULT 'M'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `carrello`
--

INSERT INTO `carrello` (`id`, `id_utente`, `id_prodotto`, `quantita_prodotto`, `taglia_prodotto`) VALUES
(1, 1, 1, 1, 'S'),
(2, 2, 2, 2, 'M'),
(3, 3, 3, 1, 'M'),
(4, 4, 4, 1, 'L'),
(5, 5, 5, 2, 'XL');

-- --------------------------------------------------------

--
-- Struttura della tabella `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome_categoria` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `categoria`
--

INSERT INTO `categoria` (`id`, `nome_categoria`) VALUES
(6, 'camice'),
(2, 'felpe'),
(5, 'gonne'),
(4, 'maglioni'),
(3, 'pantaloni'),
(1, 'T-shirt');

-- --------------------------------------------------------

--
-- Struttura della tabella `corriere`
--

CREATE TABLE `corriere` (
  `id` int(11) NOT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `azienda` varchar(50) NOT NULL,
  `giorni_consegna` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `corriere`
--

INSERT INTO `corriere` (`id`, `prezzo`, `azienda`, `giorni_consegna`) VALUES
(1, 5.99, 'CorriereExpress', 7),
(2, 9.99, 'SpeedyShipping', 3),
(3, 3.99, 'EcoDelivery', 4),
(4, 7.99, 'QuickShip', 8),
(5, 4.99, 'LocalCourier', 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `coupon`
--

CREATE TABLE `coupon` (
  `id` int(11) NOT NULL,
  `codice_coupon` varchar(50) NOT NULL,
  `sconto_percentuale` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `coupon`
--

INSERT INTO `coupon` (`id`, `codice_coupon`, `sconto_percentuale`) VALUES
(1, 'SUMMER2023', 10.00),
(2, 'SALE25', 25.00),
(3, 'FREESHIP', 100.00),
(4, 'WELCOME10', 10.00),
(5, 'FLASH50', 50.00);

-- --------------------------------------------------------

--
-- Struttura della tabella `immagine_prodotto`
--

CREATE TABLE `immagine_prodotto` (
  `id` int(11) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `url_immagine` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `immagine_prodotto`
--

INSERT INTO `immagine_prodotto` (`id`, `id_prodotto`, `url_immagine`) VALUES
(1, 1, 'products/M-element-Tshirt-front-black.jpg'),
(2, 1, 'products/M-element-Tshirt-back-black.jpg'),
(3, 2, 'products/M-element-Tshirt2-front-grey.jpg'),
(4, 2, 'products/M-element-Tshirt2-back-grey.jpg'),
(5, 3, 'products/M-element-Tshirt3-front-yellow.jpg'),
(6, 3, 'products/M-element-Tshirt3-back-yellow.jpg'),
(7, 4, 'products/M-adidas-sweatshirt-front-black.jpg'),
(8, 5, 'products/M-adidas-sweatshirt-front-white.jpg'),
(9, 6, 'products/M-H&H-sweatshirt-front-grey.jpg'),
(10, 7, 'products/M-H&H-sweatshirt-front-white.jpg'),
(11, 8, 'products/M-levis-jeans-front-blu.jpg'),
(12, 9, 'products/M-levis-jeans-front-darkblu.jpg'),
(13, 10, 'products/M-levis-pants-front-beige.jpg'),
(14, 11, 'products/M-levis-pants-front-red.jpg'),
(15, 13, 'products/D-Stradivarius-camicia-front-green.jpg'),
(16, 13, 'products/D-Stradivarius-camicia-back-green.jpg'),
(17, 14, 'products/D-Stradivarius-camicia2-front-lightBlu.jpg'),
(18, 14, 'products/D-Stradivarius-camicia2-back-lightBlu.jpg'),
(19, 15, 'products/D-Stradivarius-gonna-front-grey.jpg'),
(20, 16, 'products/B-Stradivarius-Tshirt-front-lightBlu.jpg'),
(21, 17, 'products/B-Stradivarius-Tshirt2-front-black.jpg');

-- --------------------------------------------------------

--
-- Struttura della tabella `indirizzo_spedizione`
--

CREATE TABLE `indirizzo_spedizione` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `indirizzo` varchar(200) NOT NULL,
  `citta` varchar(100) NOT NULL,
  `regione` varchar(100) NOT NULL,
  `provincia` varchar(100) NOT NULL,
  `CAP` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `indirizzo_spedizione`
--

INSERT INTO `indirizzo_spedizione` (`id`, `id_utente`, `indirizzo`, `citta`, `regione`, `provincia`, `CAP`) VALUES
(1, 1, 'Via Roma 1', 'Milano', 'Lombardia', 'MI', '20100'),
(2, 2, 'Via Verdi 10', 'Roma', 'Lazio', 'RM', '00100'),
(3, 3, 'Piazza Garibaldi 5', 'Napoli', 'Campania', 'NA', '80100'),
(4, 4, 'Rue de la Paix 3', 'Parigi', 'Ile-de-France', '75', '75001'),
(5, 5, 'Broadway 100', 'New York', 'New York', 'NY', '10001'),
(6, 6, 'Via Sirio 3', 'Cassina de pecchi', 'Lombardia', 'Milano', '20051');

-- --------------------------------------------------------

--
-- Struttura della tabella `magazzino`
--

CREATE TABLE `magazzino` (
  `id` int(11) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `quantita` int(11) NOT NULL DEFAULT 0,
  `taglia` enum('S','M','L','XL') DEFAULT 'M'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `magazzino`
--

INSERT INTO `magazzino` (`id`, `id_prodotto`, `quantita`, `taglia`) VALUES
(1, 1, 0, 'S'),
(2, 1, 0, 'M'),
(3, 1, 0, 'L'),
(4, 1, 0, 'XL'),
(5, 2, 5, 'S'),
(6, 2, 5, 'M'),
(7, 2, 0, 'L'),
(8, 2, 5, 'XL'),
(9, 3, 0, 'S'),
(10, 3, 2, 'M'),
(11, 3, 0, 'L'),
(12, 3, 3, 'XL'),
(13, 4, 8, 'S'),
(14, 4, 8, 'M'),
(15, 4, 8, 'L'),
(16, 4, 8, 'XL'),
(17, 5, 20, 'S'),
(18, 5, 20, 'M'),
(19, 5, 16, 'L'),
(20, 5, 17, 'XL'),
(21, 6, 20, 'S'),
(22, 6, 20, 'M'),
(23, 6, 0, 'L'),
(24, 6, 10, 'XL'),
(25, 7, 70, 'S'),
(26, 7, 20, 'M'),
(27, 7, 20, 'L'),
(28, 7, 20, 'XL'),
(29, 8, 0, 'S'),
(30, 8, 20, 'M'),
(31, 8, 10, 'L'),
(32, 8, 20, 'XL'),
(33, 9, 20, 'S'),
(34, 9, 20, 'M'),
(35, 9, 20, 'L'),
(36, 9, 20, 'XL'),
(37, 10, 20, 'S'),
(38, 10, 40, 'M'),
(39, 10, 20, 'L'),
(40, 10, 0, 'XL'),
(41, 11, 30, 'S'),
(42, 11, 0, 'M'),
(43, 11, 30, 'L'),
(44, 11, 30, 'XL'),
(45, 13, 30, 'S'),
(46, 13, 30, 'M'),
(47, 13, 30, 'L'),
(48, 13, 30, 'XL'),
(49, 14, 30, 'S'),
(50, 14, 30, 'M'),
(51, 14, 30, 'L'),
(52, 14, 30, 'XL'),
(53, 15, 30, 'S'),
(54, 15, 30, 'M'),
(55, 15, 30, 'L'),
(56, 15, 30, 'XL'),
(57, 16, 30, 'S'),
(58, 16, 30, 'M'),
(59, 16, 30, 'L'),
(60, 16, 30, 'XL'),
(61, 17, 30, 'S'),
(62, 17, 30, 'M'),
(63, 17, 30, 'L'),
(64, 17, 30, 'XL');

-- --------------------------------------------------------

--
-- Struttura della tabella `marca`
--

CREATE TABLE `marca` (
  `id` int(11) NOT NULL,
  `nome_marca` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `marca`
--

INSERT INTO `marca` (`id`, `nome_marca`) VALUES
(2, 'Adidas'),
(4, 'Element'),
(3, 'Gucci'),
(6, 'H&H'),
(5, 'Levis'),
(1, 'Nike'),
(7, 'Puma'),
(8, 'Stradivarius');

-- --------------------------------------------------------

--
-- Struttura della tabella `messaggio_assistenza`
--

CREATE TABLE `messaggio_assistenza` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `richiesta` text DEFAULT NULL,
  `risposta` text DEFAULT NULL,
  `data_richiesta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `messaggio_assistenza`
--

INSERT INTO `messaggio_assistenza` (`id`, `id_utente`, `richiesta`, `risposta`, `data_richiesta`) VALUES
(1, 1, 'quando sarà disponibile la prossima promozione?', 'la prossima promozione sarà una promozione sui prodotti estivi e sarà disponibile fra 3 mesi', '2023-06-07'),
(2, 1, 'saranno mai venduti occhiali da sole?', NULL, '2023-06-07'),
(3, 1, 'saranno mai venduti bracciali?', NULL, '2023-06-07'),
(4, 2, 'ho eseguito un ordine 3 mesi fa, è possibile eseuguire un reso?', NULL, '2023-06-07'),
(5, 2, 'ho appena eseguito un ordine, è possibile annullarlo?', NULL, '2023-06-07');

-- --------------------------------------------------------

--
-- Struttura della tabella `metodo_pagamento`
--

CREATE TABLE `metodo_pagamento` (
  `id` int(11) NOT NULL,
  `tipo_pagamento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `metodo_pagamento`
--

INSERT INTO `metodo_pagamento` (`id`, `tipo_pagamento`) VALUES
(3, 'Apple Pay'),
(5, 'Bonifico Bancario'),
(1, 'Carta di Credito'),
(4, 'Google Pay'),
(2, 'PayPal');

-- --------------------------------------------------------

--
-- Struttura della tabella `oggetto_ordine`
--

CREATE TABLE `oggetto_ordine` (
  `id` int(11) NOT NULL,
  `id_ordine` int(11) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `quantita_prodotto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `oggetto_ordine`
--

INSERT INTO `oggetto_ordine` (`id`, `id_ordine`, `id_prodotto`, `quantita_prodotto`) VALUES
(1, 1, 3, 2),
(2, 2, 2, 1),
(3, 3, 4, 1),
(4, 4, 1, 1),
(5, 5, 5, 3),
(6, 6, 3, 1),
(7, 6, 5, 3),
(8, 6, 5, 4);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `id_corriere` int(11) NOT NULL,
  `id_coupon` int(11) DEFAULT NULL,
  `id_metodo_pagamento` int(11) NOT NULL,
  `id_indirizzo_spedizione` int(11) NOT NULL,
  `data_ordine` date NOT NULL,
  `data_spedizione` date DEFAULT NULL,
  `prezzo_ordine` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ordine`
--

INSERT INTO `ordine` (`id`, `id_utente`, `id_corriere`, `id_coupon`, `id_metodo_pagamento`, `id_indirizzo_spedizione`, `data_ordine`, `data_spedizione`, `prezzo_ordine`) VALUES
(1, 1, 1, NULL, 1, 1, '2023-06-05', '2023-06-07', 49.99),
(2, 2, 3, 2, 3, 2, '2023-06-06', '2023-06-08', 69.99),
(3, 3, 2, NULL, 4, 1, '2023-06-06', NULL, 119.99),
(4, 4, 4, 3, 2, 1, '2023-06-07', NULL, 999.99),
(5, 5, 1, NULL, 5, 1, '2023-06-07', '2023-06-09', 29.99),
(6, 6, 4, 2, 1, 6, '2023-06-26', '2023-07-04', 329.25);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotto`
--

CREATE TABLE `prodotto` (
  `id` int(11) NOT NULL,
  `nome_prodotto` varchar(100) NOT NULL,
  `descrizione` varchar(500) DEFAULT NULL,
  `prezzo` decimal(10,2) NOT NULL,
  `genere` enum('uomo','donna','bambino') DEFAULT 'uomo',
  `id_categoria` int(11) NOT NULL,
  `id_marca` int(11) DEFAULT NULL,
  `id_promozione` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `prodotto`
--

INSERT INTO `prodotto` (`id`, `nome_prodotto`, `descrizione`, `prezzo`, `genere`, `id_categoria`, `id_marca`, `id_promozione`) VALUES
(1, 'T-shirt StarWars', 'Maglietta nera con logo di star wars', 29.99, 'uomo', 1, 4, NULL),
(2, 'T-shirt', 'Maglietta blu con logo Element', 79.99, 'uomo', 1, 4, NULL),
(3, 'T-shirt', 'Maglietta a maniche corte gialla ', 19.99, 'uomo', 1, 4, NULL),
(4, 'felpa ', 'Felpa nera dell\'Adidas', 149.99, 'uomo', 2, 2, NULL),
(5, 'felpa', 'Felpa bianca dell\'Adidas', 59.99, 'uomo', 2, 2, NULL),
(6, 'felpa', 'Felpa grigio scuro della H&M', 299.99, 'uomo', 2, 6, NULL),
(7, 'felpa', 'Felpa grigio chiaro della H&M', 949.99, 'uomo', 2, 6, NULL),
(8, 'jeans', 'Jeans classici in cotone leggero per l\'estate', 199.99, 'uomo', 3, 5, NULL),
(9, 'jeans', 'Jeans nero con materiale più pesante, adatto per l\'inverno', 99.99, 'uomo', 3, 5, NULL),
(10, 'pantalone', 'Pantalone classico beige in lino', 199.99, 'uomo', 3, 5, NULL),
(11, 'pantalone', 'Pantalone rosso con tessuto che fa rumore quando cammini', 199.99, 'uomo', 3, 5, NULL),
(12, 'camicia', 'Camicia di lino colore verde acqua, esitva', 44.99, 'uomo', 6, 5, NULL),
(13, 'camicia semplice', 'Camicia di lino colore verde acqua chiaro, esitva', 44.99, 'donna', 6, 8, NULL),
(14, 'camicia semplice', 'descrizione bla bla', 44.99, 'donna', 6, 8, NULL),
(15, 'gonna grigia', 'Gonna corta grigio scuro, con stile semplice ed elegante', 50.77, 'donna', 5, 8, NULL),
(16, 'maglietta bambino', 'Maglietta semplice per bambini colore ', 19.99, 'bambino', 1, 8, NULL),
(17, 'maglietta bambino', 'Maglietta semplice per bambini colore nero', 19.99, 'bambino', 1, 8, NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `promozione`
--

CREATE TABLE `promozione` (
  `id` int(11) NOT NULL,
  `nome_promozione` varchar(100) NOT NULL,
  `descrizione` varchar(500) DEFAULT NULL,
  `sconto_percentuale` decimal(5,2) NOT NULL,
  `data_inizio` date NOT NULL,
  `data_fine` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `promozione`
--

INSERT INTO `promozione` (`id`, `nome_promozione`, `descrizione`, `sconto_percentuale`, `data_inizio`, `data_fine`) VALUES
(1, 'Sconto Estivo', 'Sconto del 20% su tutti gli articoli estivi', 20.00, '2023-06-01', '2023-06-30'),
(2, 'Promo Invernale', 'Sconto del 30% su abbigliamento invernale', 30.00, '2023-01-01', '2023-01-31'),
(3, 'Offerta Limitata', 'Sconto del 15% su una selezione di prodotti', 15.00, '2023-06-10', '2023-06-15'),
(4, 'Promo Giugno', 'Sconto del 25% su tutti gli articoli', 25.00, '2023-06-01', '2023-06-30'),
(5, 'Promo Nuovi Arrivi', 'Sconto del 10% su nuovi arrivi', 10.00, '2023-06-01', '2023-06-30');

-- --------------------------------------------------------

--
-- Struttura della tabella `recensione`
--

CREATE TABLE `recensione` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `id_prodotto` int(11) NOT NULL,
  `testo_recensione` text DEFAULT NULL,
  `valutazione` int(11) NOT NULL,
  `data_recensione` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `recensione`
--

INSERT INTO `recensione` (`id`, `id_utente`, `id_prodotto`, `testo_recensione`, `valutazione`, `data_recensione`) VALUES
(1, 1, 1, 'Ottima maglia, tessuto di alta qualità', 5, '2023-06-07'),
(2, 2, 3, 'La maglietta gialla si indossa molto bene, tiene freschi', 4, '2023-06-01'),
(3, 3, 2, ' di ottima fattura', 4, '2023-05-11'),
(4, 4, 4, ' eccezionale', 5, '2023-06-04'),
(5, 5, 5, 'è un buon prodotto', 4, '2023-04-12'),
(6, 6, 5, 'La taglia è giusta, veste perfettamente, l\'unico problema è che costa un po\' troppo', 3, '2023-06-26');

-- --------------------------------------------------------

--
-- Struttura della tabella `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `script` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `service`
--

INSERT INTO `service` (`id`, `script`, `description`) VALUES
(1, 'admin.php', NULL),
(2, 'profile.php', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ugroup`
--

CREATE TABLE `ugroup` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ugroup`
--

INSERT INTO `ugroup` (`id`, `name`, `description`) VALUES
(1, 'Administrator', NULL),
(2, 'User', NULL);

-- --------------------------------------------------------

--
-- Struttura della tabella `ugroup_has_service`
--

CREATE TABLE `ugroup_has_service` (
  `ugroup_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `ugroup_has_service`
--

INSERT INTO `ugroup_has_service` (`ugroup_id`, `service_id`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `user_has_ugroup`
--

CREATE TABLE `user_has_ugroup` (
  `id` int(11) NOT NULL,
  `id_utente` int(11) NOT NULL,
  `id_ugroup` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `user_has_ugroup`
--

INSERT INTO `user_has_ugroup` (`id`, `id_utente`, `id_ugroup`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2),
(4, 4, 2),
(5, 5, 2),
(6, 6, 2),
(7, 7, 1),
(8, 8, 2);

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `cognome` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `nome`, `cognome`, `email`, `password`) VALUES
(1, 'Mario', 'Rossi', 'mario.rossi@example.com', 'password123'),
(2, 'Giulia', 'Bianchi', 'giulia.bianchi@example.com', 'pass1234'),
(3, 'Luca', 'Verdi', 'luca.verdi@example.com', 'securepass'),
(4, 'Martina', 'Gialli', 'martina.gialli@example.com', '123456'),
(5, 'Simone', 'Neri', 'simone.neri@example.com', 'password456'),
(6, 'Carmine', 'Pittella', 'carmine@gmail.com', '71ba0b9064690af299c7a7737eb6818c'),
(7, 'admin', 'admin', 'admin@email.com', '67f43efc5701784db1504e4993d7e393'),
(8, 'Fabrizio', 'Paglia', 'fabrizio@gmail.com', '8011f855edbd1ecf441426af8036b880');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_categoria` (`nome_categoria`);

--
-- Indici per le tabelle `corriere`
--
ALTER TABLE `corriere`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `immagine_prodotto`
--
ALTER TABLE `immagine_prodotto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `indirizzo_spedizione`
--
ALTER TABLE `indirizzo_spedizione`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_utente` (`id_utente`,`indirizzo`,`citta`,`regione`,`provincia`);

--
-- Indici per le tabelle `magazzino`
--
ALTER TABLE `magazzino`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_prodotto` (`id_prodotto`,`taglia`);

--
-- Indici per le tabelle `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome_marca` (`nome_marca`);

--
-- Indici per le tabelle `messaggio_assistenza`
--
ALTER TABLE `messaggio_assistenza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`);

--
-- Indici per le tabelle `metodo_pagamento`
--
ALTER TABLE `metodo_pagamento`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tipo_pagamento` (`tipo_pagamento`);

--
-- Indici per le tabelle `oggetto_ordine`
--
ALTER TABLE `oggetto_ordine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ordine` (`id_ordine`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`),
  ADD KEY `id_corriere` (`id_corriere`),
  ADD KEY `id_coupon` (`id_coupon`),
  ADD KEY `id_metodo_pagamento` (`id_metodo_pagamento`),
  ADD KEY `id_indirizzo_spedizione` (`id_indirizzo_spedizione`);

--
-- Indici per le tabelle `prodotto`
--
ALTER TABLE `prodotto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `id_promozione` (`id_promozione`);

--
-- Indici per le tabelle `promozione`
--
ALTER TABLE `promozione`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `recensione`
--
ALTER TABLE `recensione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`),
  ADD KEY `id_prodotto` (`id_prodotto`);

--
-- Indici per le tabelle `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ugroup`
--
ALTER TABLE `ugroup`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `ugroup_has_service`
--
ALTER TABLE `ugroup_has_service`
  ADD KEY `ugroup_id` (`ugroup_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indici per le tabelle `user_has_ugroup`
--
ALTER TABLE `user_has_ugroup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_utente` (`id_utente`),
  ADD KEY `id_ugroup` (`id_ugroup`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `carrello`
--
ALTER TABLE `carrello`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT per la tabella `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `corriere`
--
ALTER TABLE `corriere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `immagine_prodotto`
--
ALTER TABLE `immagine_prodotto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT per la tabella `indirizzo_spedizione`
--
ALTER TABLE `indirizzo_spedizione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `magazzino`
--
ALTER TABLE `magazzino`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT per la tabella `marca`
--
ALTER TABLE `marca`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `messaggio_assistenza`
--
ALTER TABLE `messaggio_assistenza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `metodo_pagamento`
--
ALTER TABLE `metodo_pagamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `oggetto_ordine`
--
ALTER TABLE `oggetto_ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT per la tabella `promozione`
--
ALTER TABLE `promozione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `recensione`
--
ALTER TABLE `recensione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT per la tabella `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `ugroup`
--
ALTER TABLE `ugroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT per la tabella `user_has_ugroup`
--
ALTER TABLE `user_has_ugroup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `carrello`
--
ALTER TABLE `carrello`
  ADD CONSTRAINT `carrello_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `carrello_ibfk_2` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id`);

--
-- Limiti per la tabella `immagine_prodotto`
--
ALTER TABLE `immagine_prodotto`
  ADD CONSTRAINT `immagine_prodotto_ibfk_1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id`);

--
-- Limiti per la tabella `indirizzo_spedizione`
--
ALTER TABLE `indirizzo_spedizione`
  ADD CONSTRAINT `indirizzo_spedizione_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`);

--
-- Limiti per la tabella `magazzino`
--
ALTER TABLE `magazzino`
  ADD CONSTRAINT `magazzino_ibfk_1` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id`);

--
-- Limiti per la tabella `messaggio_assistenza`
--
ALTER TABLE `messaggio_assistenza`
  ADD CONSTRAINT `messaggio_assistenza_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`);

--
-- Limiti per la tabella `oggetto_ordine`
--
ALTER TABLE `oggetto_ordine`
  ADD CONSTRAINT `oggetto_ordine_ibfk_1` FOREIGN KEY (`id_ordine`) REFERENCES `ordine` (`id`),
  ADD CONSTRAINT `oggetto_ordine_ibfk_2` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id`);

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `ordine_ibfk_2` FOREIGN KEY (`id_corriere`) REFERENCES `corriere` (`id`),
  ADD CONSTRAINT `ordine_ibfk_3` FOREIGN KEY (`id_coupon`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `ordine_ibfk_4` FOREIGN KEY (`id_metodo_pagamento`) REFERENCES `metodo_pagamento` (`id`),
  ADD CONSTRAINT `ordine_ibfk_5` FOREIGN KEY (`id_indirizzo_spedizione`) REFERENCES `indirizzo_spedizione` (`id`);

--
-- Limiti per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  ADD CONSTRAINT `prodotto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id`),
  ADD CONSTRAINT `prodotto_ibfk_2` FOREIGN KEY (`id_marca`) REFERENCES `marca` (`id`),
  ADD CONSTRAINT `prodotto_ibfk_3` FOREIGN KEY (`id_promozione`) REFERENCES `promozione` (`id`);

--
-- Limiti per la tabella `recensione`
--
ALTER TABLE `recensione`
  ADD CONSTRAINT `recensione_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `recensione_ibfk_2` FOREIGN KEY (`id_prodotto`) REFERENCES `prodotto` (`id`);

--
-- Limiti per la tabella `ugroup_has_service`
--
ALTER TABLE `ugroup_has_service`
  ADD CONSTRAINT `ugroup_has_service_ibfk_1` FOREIGN KEY (`ugroup_id`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ugroup_has_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `user_has_ugroup`
--
ALTER TABLE `user_has_ugroup`
  ADD CONSTRAINT `user_has_ugroup_ibfk_1` FOREIGN KEY (`id_utente`) REFERENCES `utente` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_has_ugroup_ibfk_2` FOREIGN KEY (`id_ugroup`) REFERENCES `ugroup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
