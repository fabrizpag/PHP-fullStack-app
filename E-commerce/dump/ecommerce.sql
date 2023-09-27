--
-- Database: ecommerce
--
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


-- Drop Tabelle
DROP TABLE IF EXISTS Utente;
DROP TABLE IF EXISTS Categoria;
DROP TABLE IF EXISTS Marca;
DROP TABLE IF EXISTS Promozione;
DROP TABLE IF EXISTS Prodotto;
DROP TABLE IF EXISTS Corriere;
DROP TABLE IF EXISTS Coupon;
DROP TABLE IF EXISTS Metodo_Pagamento;
DROP TABLE IF EXISTS Indirizzo_Spedizione;
DROP TABLE IF EXISTS Ordine;
DROP TABLE IF EXISTS Oggetto_Ordine;
DROP TABLE IF EXISTS Carrello;
DROP TABLE IF EXISTS Recensione;
DROP TABLE IF EXISTS Messaggio_Assistenza;
DROP TABLE IF EXISTS Immagine_Prodotto;
DROP TABLE IF EXISTS Magazzino;
DROP TABLE IF EXISTS Service;
DROP TABLE IF EXISTS Ugroup;
DROP TABLE IF EXISTS Ugroup_has_service;
DROP TABLE IF EXISTS User_has_ugroup;

-- --------------------------------  CREAZIONE TABELLE ----------------------------------

CREATE TABLE IF NOT EXISTS Utente (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(50) NOT NULL,
    cognome VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS Categoria (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_categoria VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS Marca (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_marca VARCHAR(100) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS Promozione (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_promozione VARCHAR(100) NOT NULL,
    descrizione VARCHAR(500),
    sconto_percentuale DECIMAL(5, 2) NOT NULL,
    data_inizio DATE NOT NULL,
    data_fine DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS Prodotto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome_prodotto VARCHAR(100) NOT NULL,
    descrizione VARCHAR(500),
    prezzo DECIMAL(10, 2) NOT NULL,
    genere ENUM('uomo', 'donna', 'bambino') DEFAULT 'uomo',
    id_categoria INT NOT NULL,
    id_marca INT,
    id_promozione INT,
    FOREIGN KEY (id_categoria) REFERENCES Categoria(id),
    FOREIGN KEY (id_marca) REFERENCES Marca(id),
    FOREIGN KEY (id_promozione) REFERENCES Promozione(id)
);

CREATE TABLE IF NOT EXISTS Corriere (
    id INT PRIMARY KEY AUTO_INCREMENT,
    prezzo DECIMAL(10, 2) NOT NULL,
    azienda VARCHAR(50) NOT NULL,
    giorni_consegna INT NOT NULL
);

CREATE TABLE IF NOT EXISTS Coupon (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codice_coupon VARCHAR(50) NOT NULL,
    sconto_percentuale DECIMAL(5, 2) NOT NULL
);

CREATE TABLE IF NOT EXISTS Metodo_Pagamento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo_pagamento VARCHAR(100) UNIQUE
);

CREATE TABLE IF NOT EXISTS Indirizzo_Spedizione (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utente INT NOT NULL,
    indirizzo VARCHAR(200) NOT NULL,
    citta VARCHAR(100) NOT NULL,
    regione VARCHAR(100) NOT NULL,
    provincia VARCHAR(100) NOT NULL,
    CAP VARCHAR(10) NOT NULL,

    UNIQUE (id_utente, indirizzo, citta, regione, provincia),
    FOREIGN KEY (id_utente) REFERENCES Utente(id)
);

CREATE TABLE IF NOT EXISTS Ordine (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utente INT NOT NULL,
    id_corriere INT NOT NULL,
    id_coupon INT,
    id_metodo_pagamento INT NOT NULL,
    id_indirizzo_spedizione INT NOT NULL,
    data_ordine DATE NOT NULL,
    data_spedizione DATE,
    prezzo_ordine DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_utente) REFERENCES Utente(id),
    FOREIGN KEY (id_corriere) REFERENCES Corriere(id),
    FOREIGN KEY (id_coupon) REFERENCES Coupon(id),
    FOREIGN KEY (id_metodo_pagamento) REFERENCES Metodo_Pagamento(id),
    FOREIGN KEY (id_indirizzo_spedizione) REFERENCES Indirizzo_Spedizione(id)
);

CREATE TABLE IF NOT EXISTS Oggetto_Ordine (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_ordine INT NOT NULL,
    id_prodotto INT NOT NULL,
    quantita_prodotto INT NOT NULL,
    FOREIGN KEY (id_ordine) REFERENCES Ordine(id),
    FOREIGN KEY (id_prodotto) REFERENCES Prodotto(id)
);

CREATE TABLE IF NOT EXISTS Carrello (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utente INT NOT NULL,
    id_prodotto INT NOT NULL,
    quantita_prodotto INT NOT NULL,
    taglia_prodotto ENUM('S', 'M', 'L', 'XL') DEFAULT 'M',
    FOREIGN KEY (id_utente) REFERENCES Utente(id),
    FOREIGN KEY (id_prodotto) REFERENCES Prodotto(id)
);

CREATE TABLE IF NOT EXISTS Recensione (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utente INT NOT NULL,
    id_prodotto INT NOT NULL,
    testo_recensione TEXT(500),
    valutazione INT NOT NULL,
    data_recensione DATE NOT NULL,
    FOREIGN KEY (id_utente) REFERENCES Utente(id),
    FOREIGN KEY (id_prodotto) REFERENCES Prodotto(id)
);

CREATE TABLE IF NOT EXISTS Messaggio_Assistenza (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utente INT NOT NULL,
    richiesta TEXT(500),
    risposta TEXT(500) DEFAULT NULL,
    data_richiesta DATE NOT NULL,
    FOREIGN KEY (id_utente) REFERENCES Utente(id)
);

CREATE TABLE IF NOT EXISTS Immagine_Prodotto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_prodotto INT NOT NULL, 
    url_immagine VARCHAR(300) NOT NULL,
    FOREIGN KEY (id_prodotto) REFERENCES Prodotto(id)
);

CREATE TABLE IF NOT EXISTS Magazzino (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_prodotto INT NOT NULL, 
    quantita INT NOT NULL DEFAULT 0,
    taglia ENUM('S', 'M', 'L', 'XL') DEFAULT 'M',
    FOREIGN KEY (id_prodotto) REFERENCES Prodotto(id),
    UNIQUE (id_prodotto, taglia)
);

CREATE TABLE IF NOT EXISTS Service (
    id INT PRIMARY KEY AUTO_INCREMENT,
    script VARCHAR(100) DEFAULT NULL,
    description TEXT DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS Ugroup (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) DEFAULT NULL,
    description TEXT DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS Ugroup_has_service (
    ugroup_id INT DEFAULT NULL,
    service_id INT DEFAULT NULL,
    FOREIGN KEY (ugroup_id) REFERENCES ugroup(id) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (service_id) REFERENCES service(id) 
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS User_has_ugroup (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_utente INT NOT NULL,
    id_ugroup INT NOT NULL,
    FOREIGN KEY (id_utente) REFERENCES utente(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (id_ugroup) REFERENCES ugroup(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);



-- --------------------------------  INSERIMENTO DATI ----------------------------------

-- Inserimento di valori nella tabella Utente
INSERT INTO Utente (nome, cognome, email, password)
VALUES
('Mario', 'Rossi', 'mario.rossi@example.com', 'password123'),
('Giulia', 'Bianchi', 'giulia.bianchi@example.com', 'pass1234'),
('Luca', 'Verdi', 'luca.verdi@example.com', 'securepass'),
('Martina', 'Gialli', 'martina.gialli@example.com', '123456'),
('Simone', 'Neri', 'simone.neri@example.com', 'password456');

-- Inserimento di valori nella tabella Categoria
INSERT INTO Categoria (nome_categoria)
VALUES
('T-shirt'),
('felpe'),
('pantaloni'),
('maglioni'),
('gonne'),
('camice');

-- Inserimento di valori nella tabella Marca
INSERT INTO Marca (nome_marca)
VALUES
('Nike'),
('Adidas'),
('Gucci'),
('Element'),
('Levis'),
('H&H'),
('Puma'),
('Stradivarius');


-- Inserimento di valori nella tabella Promozione
INSERT INTO Promozione (nome_promozione, descrizione, sconto_percentuale, data_inizio, data_fine)
VALUES
('Sconto Estivo', 'Sconto del 20% su tutti gli articoli estivi', 20.00, '2023-06-01', '2023-06-30'),
('Promo Invernale', 'Sconto del 30% su abbigliamento invernale', 30.00, '2023-01-01', '2023-01-31'),
('Offerta Limitata', 'Sconto del 15% su una selezione di prodotti', 15.00, '2023-06-10', '2023-06-15'),
('Promo Giugno', 'Sconto del 25% su tutti gli articoli', 25.00, '2023-06-01', '2023-06-30'),
('Promo Nuovi Arrivi', 'Sconto del 10% su nuovi arrivi', 10.00, '2023-06-01', '2023-06-30');

-- Inserimento di valori nella tabella Prodotto
INSERT INTO Prodotto (nome_prodotto, descrizione, prezzo, genere, id_categoria, id_marca, id_promozione)
VALUES
('T-shirt', 'descrizione bla bla', 29.99, 'uomo', 1, 4, NULL),
('T-shirt', 'descrizione bla bla', 79.99, 'uomo', 1, 4, NULL),
('T-shirt', 'descrizione bla blaa', 19.99, 'uomo', 1, 4, NULL),
('felpa', 'descrizione bla bla', 149.99, 'uomo', 2, 2, NULL),
('felpa', 'descrizione bla bla', 59.99, 'uomo', 2, 2, NULL),
('felpa', 'descrizione bla bla', 299.99, 'uomo', 2, 6, NULL),
('felpa', 'descrizione bla bla', 949.99, 'uomo', 2, 6, NULL),
('jeans', 'descrizione bla bla', 199.99, 'uomo', 3, 5, NULL),
('jeans', 'descrizione bla bla', 99.99, 'uomo', 3, 5, NULL),
('pantalone', 'descrizione bla bla', 199.99, 'uomo', 3, 5, NULL),
('pantalone', 'descrizione bla bla', 199.99, 'uomo', 3, 5, NULL),
('camicia', 'descrizione bla bla', 44.99, 'uomo', 6, 5, NULL),
('camicia semplice', 'descrizione bla bla', 44.99, 'donna', 6, 8, NULL),
('camicia semplice', 'descrizione bla bla', 44.99, 'donna', 6, 8, NULL),
('gonna grigia','descrizione bla bla', 50.77,'donna',5,8, NULL),
('maglietta bambino','descrizione bla bla',19.99,'bambino',1,8,NULL),
('maglietta bambino','descrizione bla bla',19.99,'bambino',1,8,NULL);

-- Inserimento di valori nella tabella Corriere
INSERT INTO Corriere (prezzo, azienda, giorni_consegna)
VALUES
(5.99, 'CorriereExpress', 7),
(9.99, 'SpeedyShipping', 3),
(3.99, 'EcoDelivery', 4),
(7.99, 'QuickShip', 8),
(4.99, 'LocalCourier', 2);

-- Inserimento di valori nella tabella Coupon
INSERT INTO Coupon (codice_coupon, sconto_percentuale)
VALUES
('SUMMER2023', 10.00),
('SALE25', 25.00),
('FREESHIP', 100.00),
('WELCOME10', 10.00),
('FLASH50', 50.00);

-- Inserimento di valori nella tabella Metodo_Pagamento
INSERT INTO Metodo_Pagamento (tipo_pagamento)
VALUES
('Carta di Credito'),
('PayPal'),
('Apple Pay'),
('Google Pay'),
('Bonifico Bancario');

-- Inserimento di valori nella tabella Indirizzo_Spedizione
INSERT INTO Indirizzo_Spedizione (id_utente, indirizzo, citta, regione, provincia, CAP)
VALUES
(1, 'Via Roma 1', 'Milano', 'Lombardia', 'MI', '20100'),
(2, 'Via Verdi 10', 'Roma', 'Lazio', 'RM', '00100'),
(3, 'Piazza Garibaldi 5', 'Napoli', 'Campania', 'NA', '80100'),
(4, 'Rue de la Paix 3', 'Parigi', 'Ile-de-France', '75', '75001'),
(5, 'Broadway 100', 'New York', 'New York', 'NY', '10001');

-- Inserimento di valori nella tabella Ordine
INSERT INTO Ordine (id_utente, id_corriere, id_coupon, id_metodo_pagamento, id_indirizzo_spedizione, data_ordine, data_spedizione, prezzo_ordine)
VALUES
(1, 1, NULL, 1, 1, '2023-06-05', '2023-06-07', 49.99),
(2, 3, 2, 3, 2, '2023-06-06', '2023-06-08', 69.99),
(3, 2, NULL, 4, 1, '2023-06-06', NULL, 119.99),
(4, 4, 3, 2, 1, '2023-06-07', NULL, 999.99),
(5, 1, NULL, 5, 1, '2023-06-07', '2023-06-09', 29.99);

-- Inserimento di valori nella tabella Oggetto_Ordine
INSERT INTO Oggetto_Ordine (id_ordine, id_prodotto, quantita_prodotto)
VALUES
(1, 3, 2),
(2, 2, 1),
(3, 4, 1),
(4, 1, 1),
(5, 5, 3);

-- Inserimento di valori nella tabella Carrello
INSERT INTO Carrello (id_utente, id_prodotto, quantita_prodotto, taglia_prodotto)
VALUES
(1, 1, 1, 'S'),
(2, 2, 2, 'M'),
(3, 3, 1, 'M'),
(4, 4, 1, 'L'),
(5, 5, 2, 'XL');

-- Inserimento di valori nella tabella Recensione
INSERT INTO Recensione (id_utente, id_prodotto, testo_recensione, valutazione, data_recensione)
VALUES
(1, 1, 'Ottima maglia, tessuto di alta qualità', 5, '2023-06-07'),
(2, 3, 'ottimo', 4, '2023-06-01'),
(3, 2, ' di ottima fattura', 4, '2023-05-11'),
(4, 4, ' eccezionale', 5, '2023-06-04'),
(5, 5, 'è un buon prodotto', 4, '2023-04-12');

-- Inserimento di valori nella tabella Immagine_Prodotto
INSERT INTO Immagine_Prodotto (id_prodotto, url_immagine)
VALUES
(1, 'products/M-element-Tshirt-front-black.jpg'),
(1, 'products/M-element-Tshirt-back-black.jpg'),
(2, 'products/M-element-Tshirt2-front-grey.jpg'),
(2, 'products/M-element-Tshirt2-back-grey.jpg'),
(3, 'products/M-element-Tshirt3-front-yellow.jpg'),
(3, 'products/M-element-Tshirt3-back-yellow.jpg'),
(4, 'products/M-adidas-sweatshirt-front-black.jpg'),
(5, 'products/M-adidas-sweatshirt-front-white.jpg'),
(6, 'products/M-H&H-sweatshirt-front-grey.jpg'),
(7, 'products/M-H&H-sweatshirt-front-white.jpg'),
(8, 'products/M-levis-jeans-front-blu.jpg'),
(9, 'products/M-levis-jeans-front-darkblu.jpg'),
(10, 'products/M-levis-pants-front-beige.jpg'),
(11, 'products/M-levis-pants-front-red.jpg'), 
(13, 'products/D-Stradivarius-camicia-front-green.jpg'),
(13, 'products/D-Stradivarius-camicia-back-green.jpg'),
(14, 'products/D-Stradivarius-camicia2-front-lightBlu.jpg'),
(14, 'products/D-Stradivarius-camicia2-back-lightBlu.jpg'), 
(15, 'products/D-Stradivarius-gonna-front-grey.jpg'),
(16, 'products/B-Stradivarius-Tshirt-front-lightBlu.jpg'),
(17, 'products/B-Stradivarius-Tshirt2-front-black.jpg');

-- Inserimento di valori nella tabella Magazzino
INSERT INTO Magazzino (id_prodotto, quantita, taglia)
VALUES
(1, 10, 'S'),
(1, 10, 'M'),
(1, 0 , 'L'),
(1, 0 , 'XL'),
(2, 5, 'S'),
(2, 5, 'M'),
(2, 0, 'L'),
(2, 5, 'XL'),
(3, 0, 'S'),
(3, 3, 'M'),
(3, 0, 'L'),
(3, 3, 'XL'),
(4, 8, 'S'),
(4, 8, 'M'),
(4, 8, 'L'),
(4, 8, 'XL'),
(5, 20, 'S'),
(5, 20, 'M'),
(5, 20, 'L'),
(5, 20, 'XL'),
(6, 20, 'S'),
(6, 20, 'M'),
(6, 0, 'L'),
(6, 10, 'XL'),
(7, 70, 'S'),
(7, 20, 'M'),
(7, 20, 'L'),
(7, 20, 'XL'),
(8, 0, 'S'),
(8, 20, 'M'),
(8, 10, 'L'),
(8, 20, 'XL'),
(9, 20, 'S'),
(9, 20, 'M'),
(9, 20, 'L'),
(9, 20, 'XL'),
(10, 20, 'S'),
(10, 40, 'M'),
(10, 20, 'L'),
(10, 0, 'XL'),
(11, 30, 'S'),
(11, 0, 'M'),
(11, 30, 'L'),
(11, 30, 'XL'),
(13, 30, 'S'),
(13, 30, 'M'),
(13, 30, 'L'),
(13, 30, 'XL'),
(14, 30, 'S'),
(14, 30, 'M'),
(14, 30, 'L'),
(14, 30, 'XL'),
(15, 30, 'S'),
(15, 30, 'M'),
(15, 30, 'L'),
(15, 30, 'XL'),
(16, 30, 'S'),
(16, 30, 'M'),
(16, 30, 'L'),
(16, 30, 'XL'),
(17, 30, 'S'),
(17, 30, 'M'),
(17, 30, 'L'),
(17, 30, 'XL');


-- Inserimento di valori nella tabella Messaggio_Assistenza
INSERT INTO Messaggio_Assistenza (id,id_utente,richiesta,risposta, data_richiesta) VALUES
(1,1,'quando sarà disponibile la prossima promozione?','la prossima promozione sarà una promozione sui prodotti estivi e sarà disponibile fra 3 mesi', '2023-06-07'),
(2,1,'saranno mai venduti occhiali da sole?',NULL, '2023-06-07'),
(3,1,'saranno mai venduti bracciali?',NULL, '2023-06-07'),
(4,2,'ho eseguito un ordine 3 mesi fa, è possibile eseuguire un reso?',NULL, '2023-06-07'),
(5,2,'ho appena eseguito un ordine, è possibile annullarlo?',NULL, '2023-06-07');

-- Inserimento di valori nella tabella Service
INSERT INTO Service (id, script, description) VALUES
(1, 'admin.php', NULL),
(2, 'profile.php', NULL);

-- Inserimento di valori nella tabella Ugroup
INSERT INTO Ugroup (id, name, description) VALUES
(1, 'Administrator', NULL),
(2, 'User', NULL);

-- Inserimento di valori nella tabella Ugroup_has_service
INSERT INTO Ugroup_has_service (ugroup_id, service_id) VALUES
(1, 1),
(2, 2);

-- Inserimento di valori nella tabella User_has_ugroup
INSERT INTO User_has_ugroup (id, id_utente, id_ugroup) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 2),
(4, 4, 2),
(5, 5, 2);



