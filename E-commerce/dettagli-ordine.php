<?php

require "include/dbms.inc.php";
require "include/template2.inc.php";
require_once "include/php-utils/global.php";
require_once "include/php-utils/alert.php";

session_start();
global $connessione;
$orderid;

// recupero l'id dell'Ordine dall'URL
if (isset($_GET['id_ordine'])) {
    $orderid = $_GET['id_ordine'];
} else {
    header('location: orders.php');
}


$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/order-details.html");
$recensione = new Template("skins/template/dtml/dtml_items/lasciaRecensione.html");

if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    // utente autenticato
    $userid = $_SESSION['utente']['id'];
    $furbacchione = true;

    // controllo di sicurezza
    $security = $connessione->query("SELECT id FROM Ordine WHERE id_utente = $userid;")->fetch_all(MYSQLI_ASSOC);
    foreach ($security as $s) {
        if ($orderid == $s['id']) {
            $furbacchione = false;
            break;
        }
    }
    if ($furbacchione) {
        Alert::OpenAlert("Hey vecchia volpe !! perché non pensi ai tuoi di ordini eh...", "orders.php");
    }

    // Ordine
    $ordine = $connessione->query("SELECT * FROM Ordine WHERE id = $orderid LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("DATA_CONSEGNA", $ordine[0]['data_spedizione']);
    $body->setContent("TOTALE_ORDINE", $ordine[0]['prezzo_ordine']);
    $body->setContent("ID_ORDINE", $orderid);

    // Utente
    $utente = $connessione->query("SELECT nome, cognome FROM Utente WHERE id = $userid LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("NOME_UTENTE", $utente[0]['nome']);
    $body->setContent("COGNOME_UTENTE", $utente[0]['cognome']);

    // Corriere
    $corriere = $connessione->query("SELECT azienda FROM Corriere WHERE id = {$ordine[0]['id_corriere']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("CORRIERE", $corriere[0]['azienda']);

    // Metodo pagamento
    $pagamento = $connessione->query("SELECT tipo_pagamento FROM Metodo_pagamento WHERE id = {$ordine[0]['id_metodo_pagamento']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("METODO_PAGAMENTO", $pagamento[0]['tipo_pagamento']);

    // Indirizzo
    $indirizzo = $connessione->query("SELECT * FROM Indirizzo_spedizione WHERE id = {$ordine[0]['id_indirizzo_spedizione']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("INDIRIZZO", $indirizzo[0]['indirizzo']);
    $body->setContent("CITTA", $indirizzo[0]['citta']);
    $body->setContent("PROVINCIA", $indirizzo[0]['provincia']);

    // Oggetto ordine
    $oggOrd = $connessione->query("SELECT * FROM Oggetto_ordine WHERE id_ordine = $orderid;")->fetch_all(MYSQLI_ASSOC);
    foreach ($oggOrd as $ogg) {
        $prodotto_ordine = new Template("skins/template/dtml/dtml_items/prodotti-ordineItem.html");

        // Prodotto
        $prodotto = $connessione->query("SELECT id, nome_prodotto, prezzo FROM Prodotto WHERE id = {$ogg['id_prodotto']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
        $prodotto_ordine->setContent("ID_PRODOTTO", $prodotto[0]['id']);
        $prodotto_ordine->setContent("NOME", $prodotto[0]['nome_prodotto']);
        $prodotto_ordine->setContent("PREZZO", $prodotto[0]['prezzo']);
        $prodotto_ordine->setContent("QUANTITA", $ogg['quantita_prodotto']);

        // Immagine
        $url = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$prodotto[0]['id']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
        $prodotto_ordine->setContent("URL_IMMAGINE", _IMG_PATH . $url[0]['url_immagine']);

        $body->setContent("ELEMENTO_ORDINE", $prodotto_ordine->get());
    }









    //
} else {
    Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
    // header("Location: login.php");
}

$body->setContent('LASCIA_RECENSIONE_AREA', $recensione->get());
$main->setContent('body', $body->get());
$main->close();
