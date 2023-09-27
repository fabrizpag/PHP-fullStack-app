<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/php-utils/alert.php";

session_start();

global $connessione;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/contact.html");

// tiene aggiornato il numero di oggetti presenti nel carrello
require "include/php-utils/preferiti_carrello.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_SESSION['auth']) && $_SESSION['auth']) {
        // utente autenticato
        $userid = $_SESSION['utente']['id'];
        $messaggio = $_POST['messaggioAss'];

        $oggi = new DateTime();
        $oggi_str = $oggi->format('Y-m-d');

        $oid = $connessione->prepare("INSERT INTO Messaggio_Assistenza (`id_utente`, `richiesta`, `risposta`, `data_richiesta`)
                                    VALUES (?, ?, NULL, ?)");
        $oid->bind_param("iss", $userid, $messaggio, $oggi_str);

        if ($oid->execute()) {
            Alert::OpenAlert("Messaggio inviato con successo", "contatti.php");
        }
    } else {
        Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
    }



    //
}
$main->setContent('body', $body->get());
$main->close();
