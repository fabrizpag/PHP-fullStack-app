<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/php-utils/alert.php";

session_start();

global $connessione;

if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    // utente autenticato
    $userid = $_SESSION['utente']['id'];
    $subtotal = 0.0;
    // $coupon = "";

    // controllo di sicurezza per le quantità nel magazzino
    require "include/php-utils/check-quantita-magazzino.php";


    $main = new Template("skins/template/dtml/index_v2.html");
    $body = new Template("skins/template/check-out.html");

    // tiene aggiornato il numero di oggetti presenti nel carrello
    require "include/php-utils/preferiti_carrello.php";


    // coupon POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $regione = $_POST['coupon'];
        echo $regione;
    }

    // indirizzi
    $res = $connessione->query("SELECT * FROM Indirizzo_Spedizione WHERE id_utente = {$userid}")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $address = new Template("skins/template/dtml/dtml_items/indirizzoItem.html");
        $address->setContent("ID_INDIRIZZO", $r['id']);
        $address->setContent("VIA", $r['indirizzo']);
        $address->setContent("PROVINCIA", $r['provincia']);

        $body->setContent("I_MIEI_INDIRIZZI", $address->get());
    }

    // checkout
    $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid}")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $checkItem = new Template("skins/template/dtml/dtml_items/checkoutItem.html");

        // prodotto
        $checkItem->setContent("QUANTITA_PRODOTTO", $r['quantita_prodotto']);
        $checkItem->setContent("TAGLIA_PRODOTTO", $r['taglia_prodotto']);
        $prod = $connessione->query("SELECT nome_prodotto, prezzo, id_promozione FROM Prodotto WHERE id = {$r['id_prodotto']} LIMIT 1")->fetch_all(MYSQLI_ASSOC);
        $checkItem->setContent("NOME_PRODOTTO", $prod[0]['nome_prodotto']);

        // totale
        $tot = intval($prod[0]['prezzo']) * intval($r['quantita_prodotto']);
        if ($prod[0]['id_promozione']) {
            $promo = $connessione->query("SELECT sconto_percentuale FROM Promozione WHERE id = {$prod[0]['id_promozione']} LIMIT 1")->fetch_all(MYSQLI_ASSOC);
            $sconto = intval($promo[0]['sconto_percentuale']);
            $prezzoVecchio = $tot;
            $checkItem->setContent("PREZZO_VECCHIO", $prezzoVecchio);
            $tot = ($tot - ($sconto * $tot / 100));
        }
        $checkItem->setContent("TOTALE_PRODOTTO", $tot);
        $subtotal += $tot;

        $body->setContent("PRODOTTO_CART", $checkItem->get());
    }
    $body->setContent("SUBTOTAL", $subtotal);



    // corriere
    $res = $connessione->query("SELECT * FROM Corriere")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $corriere = new Template("skins/template/dtml/dtml_items/corriereItem.html");

        $corriere->setContent("ID_CORRIERE", $r['id']);
        $corriere->setContent("COSTO_CORRIERE", $r['prezzo']);
        $corriere->setContent("AZIENDA_CORRIERE", $r['azienda']);
        $corriere->setContent("GIORNI_CONSEGNA_CORRIERE", $r['giorni_consegna']);

        $body->setContent("ELEMENTO_CORRIERE", $corriere->get());
    }


    // metodo pagamento
    $res = $connessione->query("SELECT * FROM Metodo_Pagamento")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $pagamento = new Template("skins/template/dtml/dtml_items/metodo-pagamentoItem.html");
        $pagamento->setContent("ID_PAGAMENTO", $r['id']);
        $pagamento->setContent("TIPO_PAGAMENTO", $r['tipo_pagamento']);
        $body->setContent("ELEMENTO_METODO_PAGAMENTO", $pagamento->get());
    }





















    //
} else {
    Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
}







$main->setContent('body', $body->get());
$main->close();
