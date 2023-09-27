<?php

require "include/dbms.inc.php";
require "include/template2.inc.php";
require_once "include/php-utils/alert.php";

session_start();

global $connessione;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/richieste-assistenza-utente.html");

if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    // utente autenticato
    $userid = $_SESSION['utente']['id'];

    $res = $connessione->query("SELECT * FROM Messaggio_Assistenza WHERE id_utente = {$userid} ORDER BY data_richiesta DESC")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $request = new Template("skins/template/dtml/dtml_items/richiesta-assistenza-utenteItem.html");

        // codice, data, prezzo
        $request->setContent("DATA", $r['data_richiesta']);
        $request->setContent("DOMANDA", $r['richiesta']);

        // se c'è la risposta lo aggiorno
        if ($r['risposta']) {
            $request->setContent("RISPOSTA", $r['risposta']);
        } else {
            $request->setContent("RISPOSTA", "(nessuna risposta)");
        }

        $body->setContent("ELEMENTO_RICHIESTE_ASSISTENZA", $request->get());
    }





    //
} else {
    Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
}




$main->setContent('body', $body->get());
$main->close();
