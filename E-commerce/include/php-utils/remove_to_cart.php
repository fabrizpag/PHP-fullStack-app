<?php

require "../dbms.inc.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prodotto = $_POST['id_prodotto'];
    $taglia_prod = $_POST['taglia'];

    if (isset($_SESSION['auth']) && $_SESSION['auth']) {
        // rimuovo dal DB
        global $connessione;

        $userid = $_SESSION['utente']['id'];

        $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid} AND id_prodotto = {$id_prodotto} AND taglia_prodotto = '{$taglia_prod}'")->fetch_all(MYSQLI_ASSOC);
        if (!empty($res)) {
            $rmv = $connessione->prepare("DELETE FROM Carrello WHERE id_utente = ? AND id_prodotto = ? AND taglia_prodotto = ?;");
            $rmv->bind_param("iis", $userid, $id_prodotto, $taglia_prod);
            if ($rmv->execute()) {
                // Elemento eliminato dal Carrello
            } else {
                echo "Errore durante eliminazione in Carrello: " . $rmv->error;
            }
        }
    } else {
        // rimuovo in sessione
        echo "IMPOSSIBILE ACCEDERE AL CARRELLO SENZA ESSERE AUTENTICATI";
    }
}
