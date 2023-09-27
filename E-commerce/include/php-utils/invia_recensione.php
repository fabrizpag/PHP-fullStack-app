<?php

require "../template2.inc.php";
require "../dbms.inc.php";
require_once "../php-utils/alert.php";


session_start();

global $connessione;

$userid = $_SESSION['utente']['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProd = $_POST['id_prodotto'];
    $feedback = $_POST['testo'];
    $stelle = $_POST['valutazione'];

    $oggi = new DateTime();
    $oggi_str = $oggi->format('Y-m-d');

    $oid = $connessione->prepare("INSERT INTO Recensione (`id_utente`, `id_prodotto`, `testo_recensione`, `valutazione`, `data_recensione`)
                                    VALUES (?, ?, ?, ?, ?)");
    $oid->bind_param("iisis", $userid, $idProd, $feedback, $stelle, $oggi_str);
    if ($oid->execute()) {
        Alert::OpenAlert("Recensione inviata con successo", "carrello.php");
    } else {
        Alert::OpenAlert("Ops! si è verificato un errore durante l'invio di una recensione", "carrello.php");
        // se metto carrello.php non cambia schermata (l'ho impostato io)
    }
}
