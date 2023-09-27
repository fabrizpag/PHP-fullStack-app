<?php

$userid = $_SESSION['utente']['id'];

$res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid}");
foreach ($res as $r) {

    $magazzino = $connessione->query("SELECT * FROM Magazzino WHERE id_prodotto = {$r['id_prodotto']} AND taglia = '{$r['taglia_prodotto']}' LIMIT 1")->fetch_all(MYSQLI_ASSOC);;
    if ($magazzino[0]['quantita'] < $r['quantita_prodotto']) {

        if ($magazzino[0]['quantita'] == 0) {
            // rimuovere il prodotto dal carrello
            $rmv = $connessione->prepare("DELETE FROM Carrello WHERE id_utente = ? AND id_prodotto = ? AND taglia_prodotto = ?;");
            $rmv->bind_param("iis", $userid, $r['id_prodotto'], $r['taglia_prodotto']);
            if ($rmv->execute()) {
                Alert::OpenAlert("La disponibilità dei prodotti è cambiata", "carrello.php");
            } else {
                echo "Errore durante eliminazione in Carrello: " . $rmv->error;
            }
        } else {
            // settare la quantità di quel prodotto nel carrello con id_utente = (...) a 1
            $upd = $connessione->prepare("UPDATE Carrello SET quantita_prodotto = ? WHERE id_prodotto = ? AND id_utente = ? AND taglia_prodotto = ?");
            $qMin = 1;
            $upd->bind_param("iiis", $qMin, $r['id_prodotto'], $userid, $r['taglia_prodotto']);
            if ($upd->execute()) {
                Alert::OpenAlert("La disponibilità dei prodotti è cambiata", "carrello.php");
            } else {
                echo "Errore durante aggiornamento in Carrello: " . $upd->error;
            }
        }
    }
}
