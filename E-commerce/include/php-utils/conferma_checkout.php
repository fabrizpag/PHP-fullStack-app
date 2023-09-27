<?php

require "../template2.inc.php";
require "../dbms.inc.php";


session_start();

global $connessione;

$userid = $_SESSION['utente']['id'];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_indirizzo = $_POST['address'];
    $id_corriere = $_POST['corriere'];
    $id_pagamento = $_POST['pagamento'];
    $totale = $_POST['totale'];
    $id_coupon = $_POST['coupon'];
    $id_ordine; // serve dopo

    // giorni di consegna
    $corriere = $connessione->query("SELECT giorni_consegna FROM Corriere WHERE id = {$id_corriere} LIMIT 1")->fetch_all(MYSQLI_ASSOC);
    $data_ordine = new DateTime();
    $data_spedizione = clone $data_ordine;
    $data_spedizione->add(new DateInterval("P{$corriere[0]['giorni_consegna']}D"));
    $data_ordine = $data_ordine->format("Y-m-d");
    $data_spedizione = $data_spedizione->format("Y-m-d");

    // creazione dell'ordine
    if ($id_coupon == 0) {
        $oid = $connessione->prepare("INSERT INTO Ordine (`id_utente`, `id_corriere`, `id_coupon`, `id_metodo_pagamento`, `id_indirizzo_spedizione`, `data_ordine`, `data_spedizione`, `prezzo_ordine`)
                                    VALUES (?, ?, NULL, ?, ?, ?, ?, ?)");
        $oid->bind_param("iiiisss", $userid, $id_corriere, $id_pagamento, $id_indirizzo, $data_ordine, $data_spedizione, $totale);
    } else {
        $oid = $connessione->prepare("INSERT INTO Ordine (`id_utente`, `id_corriere`, `id_coupon`, `id_metodo_pagamento`, `id_indirizzo_spedizione`, `data_ordine`, `data_spedizione`, `prezzo_ordine`)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $oid->bind_param("iiiiisss", $userid, $id_corriere, $id_coupon, $id_pagamento, $id_indirizzo, $data_ordine, $data_spedizione, $totale);
    }
    if ($oid->execute()) {
        $id_ordine = $oid->insert_id;
    } else {
        echo "Errore inserimento in Ordine: " . $oid->error;
    }

    // creazione Oggetti Ordine
    $oggOrd = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid}")->fetch_all(MYSQLI_ASSOC);
    foreach ($oggOrd as $o) {
        $oid = $connessione->prepare("INSERT INTO Oggetto_Ordine (`id_ordine`, `id_prodotto`, `quantita_prodotto`)
                                    VALUES (?, ?, ?)");
        $oid->bind_param("iii", $id_ordine, $o['id_prodotto'], $o['quantita_prodotto']);
        if ($oid->execute()) {
            // eseguito con successo
        } else {
            echo "Errore inserimento in Oggetto_Ordine: " . $oid->error;
        }

        // modifica quantita in magazzino
        $qmag = $connessione->query("SELECT quantita FROM Magazzino WHERE id_prodotto = {$o['id_prodotto']} AND taglia = '{$o['taglia_prodotto']}'")->fetch_all(MYSQLI_ASSOC);
        $qmag[0]['quantita'];
        $nuova_quantita = intval($qmag[0]['quantita']) - intval($o['quantita_prodotto']);

        $upd = $connessione->prepare("UPDATE Magazzino SET quantita = ? WHERE id_prodotto = ? AND taglia = ?");
        $upd->bind_param("iis", $nuova_quantita, $o['id_prodotto'], $o['taglia_prodotto']);
        if ($upd->execute()) {
            // eseguito con successo
        } else {
            echo "Errore durante aggiornamento in Magazzino: " . $upd->error;
        }
    }

    // svuotare il carrello dell'utente
    $rmv = $connessione->prepare("DELETE FROM Carrello WHERE id_utente = ?");
    $rmv->bind_param("i", $userid);
    if ($rmv->execute()) {
        // eseguito con successo
    } else {
        echo "Errore durante eliminazione in Carrello: " . $rmv->error;
    }
}
