<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/php-utils/alert.php";

session_start();

global $connessione;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/add-indirizzo.html");

// tiene aggiornato il numero di oggetti presenti nel carrello
require "include/php-utils/preferiti_carrello.php";


if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    // utente autenticato
    $userid = $_SESSION['utente']['id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $regione = $_POST['regione'];
        $provincia = $_POST['provincia'];
        $citta = $_POST['citta'];
        $indirizzo = $_POST['address'];
        $cap = $_POST['postal_code'];

        // aggiungo un nuovo indirizzo all'utente con id = (...)
        $oid = $connessione->prepare("INSERT INTO Indirizzo_Spedizione (`id_utente`, `indirizzo`, `citta`, `regione`, `provincia`, `CAP`)
                                    VALUES (?, ?, ?, ?, ?, ?)");
        $oid->bind_param("isssss", $userid, $indirizzo, $citta, $regione, $provincia, $cap);

        if ($oid->execute()) {
            Alert::OpenAlert("Indirizzo aggiunto con successo", "profile.php");
        } else {
            Alert::OpenAlert("Indirizzo già esistente", "add-indirizzo.php");
        }
    }
} else {
    Alert::OpenAlert("Devi effettuare l'accesso", "login.php");
}


$main->setContent('body', $body->get());
$main->close();
