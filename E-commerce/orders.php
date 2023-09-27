<?php

require "include/dbms.inc.php";
require "include/template2.inc.php";
require_once "include/php-utils/alert.php";

session_start();

global $connessione;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/orders.html");

if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    // utente autenticato
    $userid = $_SESSION['utente']['id'];

    $res = $connessione->query("SELECT * FROM Ordine WHERE id_utente = {$userid} ORDER BY data_ordine DESC")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $ordine = new Template("skins/template/dtml/dtml_items/ordine-Item.html");

        // codice, data, prezzo
        $ordine->setContent("CODICE_ORDINE", $r['id']);
        $ordine->setContent("DATA_ORDINE", $r['data_ordine']);
        $ordine->setContent("PREZZO_ORDINE", $r['prezzo_ordine']);

        // stato
        $stato_ordine = "In preparazione";
        if ($r['data_spedizione']) {
            $oggi = new DateTime();
            $oggi_str = $oggi->format('Y-m-d');
            $dataSped = strval($r['data_spedizione']);
            if ($dataSped < $oggi_str) {
                echo "minore";
                $stato_ordine = "Spedito";
            }
        }
        $ordine->setContent("STATO_ORDINE", $stato_ordine);

        // indirizzo
        $address = $connessione->query("SELECT indirizzo FROM Indirizzo_spedizione WHERE id = {$r['id_indirizzo_spedizione']}")->fetch_all(MYSQLI_ASSOC);
        $ordine->setContent("INDIRIZZO_SPEDIZIONE_ORDINE", $address[0]['indirizzo']);

        $body->setContent("ELEMENTO_ORDINE", $ordine->get());
    }





    //
} else {
    Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
}




$main->setContent('body', $body->get());
$main->close();
