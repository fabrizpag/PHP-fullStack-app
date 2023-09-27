<?php

require "../template2.inc.php";
require "../dbms.inc.php";
require_once "../php-utils/alert.php";


session_start();

global $connessione;

$userid = $_SESSION['utente']['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idProd = $_POST['id_prodotto'];

    $res = $connessione->query("SELECT * FROM Recensione WHERE id_utente = {$userid} AND id_prodotto = {$idProd} LIMIT 1")->fetch_all(MYSQLI_ASSOC);
    if (empty($res)) {
        // non l'ha ancora lasciata
        $response = array(
            "lasciata" => false
        );
    } else {
        // l'ha già lasciata
        $response = array(
            "lasciata" => true,
            "messaggio" => $res[0]['testo_recensione'],
            "valutazione" => $res[0]['valutazione']
        );
    }

    header("Content-Type: application/json");
    echo json_encode($response);
}
