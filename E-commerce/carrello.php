<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/php-utils/global.php";
require_once "include/php-utils/alert.php";

session_start();

global $connessione;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/shopping-cart.html");


// tiene aggiornato il numero di oggetti presenti nel carrello
require "include/php-utils/preferiti_carrello.php";


if (isset($_SESSION['auth']) && $_SESSION['auth']) {

    $userid = $_SESSION['utente']['id'];
    // controllo di sicurezza per le quantità nel magazzino
    require "include/php-utils/check-quantita-magazzino.php";


    $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid}");
    $prezzoQT = 0;
    foreach ($res as $r) {
        $cart_elem = new Template("skins/template/dtml/dtml_items/shopping-cartItem.html");


        // id, quantita e taglia
        $cart_elem->setContent("ID_PRODOTTO", $r['id_prodotto']);
        $cart_elem->setContent("QUANTITA", $r['quantita_prodotto']);
        $cart_elem->setContent("TAGLIA_PRODOTTO", $r['taglia_prodotto']);

        // quantita massima impostabile
        $tmp = $connessione->query("SELECT quantita FROM Magazzino WHERE id_prodotto = {$r['id_prodotto']} AND taglia = '{$r['taglia_prodotto']}'")->fetch_all(MYSQLI_ASSOC);
        $cart_elem->setContent("Q_MAX", $tmp[0]['quantita']);

        // immagine
        $tmp = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$r['id_prodotto']}")->fetch_all(MYSQLI_ASSOC);
        $cart_elem->setContent("URL_IMMAGINE", _IMG_PATH . $tmp[0]['url_immagine']);

        $lallero = $tmp[0]['url_immagine'];
        // nome e prezzo
        $tmp = $connessione->query("SELECT * FROM Prodotto WHERE id = {$r['id_prodotto']};")->fetch_all(MYSQLI_ASSOC);
        $cart_elem->setContent("NOME_PRODOTTO", $tmp[0]['nome_prodotto']);

        // prezzo in promozione
        if ($tmp[0]['id_promozione']) {
            // devo impostare il vecchio prezzo e calcolare il nuovo
            $cart_elem->setContent("PREZZO_ORIGINALE", $tmp[0]['prezzo']);
            $promo = $connessione->query("SELECT sconto_percentuale FROM Promozione WHERE id = {$tmp[0]['id_promozione']}")->fetch_all(MYSQLI_ASSOC);
            $sconto = intval($promo[0]['sconto_percentuale']);
            $nuovoPrezzo = intval($tmp[0]['prezzo']) - ($sconto * intval($tmp[0]['prezzo']) / 100);
            $cart_elem->setContent("PREZZO_SINGOLO", $nuovoPrezzo);
            $var = floatval($nuovoPrezzo) * floatval($r['quantita_prodotto']);
            $cart_elem->setContent("PREZZO_SINGOLO_TOT", $var);
        } else {
            $cart_elem->setContent("PREZZO_SINGOLO", $tmp[0]['prezzo']);
            $var = floatval($tmp[0]['prezzo']) * floatval($r['quantita_prodotto']);
            $cart_elem->setContent("PREZZO_SINGOLO_TOT", $var);
        }

        // totale prodotto
        $prezzoQT = $prezzoQT + $var;

        $body->setContent("elemento_carrello", $cart_elem->get());
    }

    $body->setContent("TOTALE_CARRELLO", strval($prezzoQT));

    //
} else {
    Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
}


$main->setContent('body', $body->get());
$main->close();
