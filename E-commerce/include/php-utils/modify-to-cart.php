<?php

require "../dbms.inc.php";
session_start();

if(isset($_POST['valore'])) {
    
    $v = $_POST['valore'];
    $id_prodotto =$v['id_prodotto'];
    $quantita =$v['quantita'];
    $taglia = $v['taglia'];

    
    global $connessione;

    $userid = $_SESSION['utente']['id'];
    $prod_gia_presente = false;

    $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid}")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        if ($r['id_prodotto'] == $id_prodotto && $r['taglia_prodotto'] == $taglia) {
            // aggiorno
            $nuova_quantita = $quantita;

            $upd = $connessione->prepare("UPDATE Carrello SET quantita_prodotto = ? WHERE id_prodotto = ? AND id_utente = ? AND taglia_prodotto = ?");
            $upd->bind_param("iiis", $nuova_quantita, $r['id_prodotto'], $userid, $taglia);
            if ($upd->execute()) {
                echo "Aggiornamento tabella Carrello.";
            } else {
                echo "Errore durante aggiornamento in Carrello: " . $upd->error;
            }
            $prod_gia_presente = true;
            break;
        }
    }

    
}
