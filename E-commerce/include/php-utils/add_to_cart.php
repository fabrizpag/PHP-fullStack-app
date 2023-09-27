<?php

require "../dbms.inc.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prodotto = $_POST['id_prodotto'];
    $quantita = $_POST['quantita'];
    $taglia = $_POST['taglia'];

    if (isset($_SESSION['auth']) && $_SESSION['auth']) {
        // aggiungere tutto sul DB
        global $connessione;

        $userid = $_SESSION['utente']['id'];
        $prod_gia_presente = false;

        $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$userid}")->fetch_all(MYSQLI_ASSOC);
        foreach ($res as $r) {
            if ($r['id_prodotto'] == $id_prodotto && $r['taglia_prodotto'] == $taglia) {
                // sommo
                $quantita_attuale = intval($r['quantita_prodotto']);
                $nuova_quantita = $quantita_attuale + $quantita;

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
        if (!$prod_gia_presente) {
            // aggiungo
            $add = $connessione->prepare("INSERT INTO Carrello (id_utente, id_prodotto, quantita_prodotto, taglia_prodotto) VALUES (?, ?, ?, ?)");
            $add->bind_param("iiis", $userid, $id_prodotto, $quantita, $taglia);
            if ($add->execute()) {
                echo "Aggiunta tabella Carrello.";
            } else {
                echo "Errore durante aggiunta tabella Carrello: " . $add->error;
            }
        }
    } else {
        // aggiungere tutto in sessione
        $prod_gia_presente = false;
        if (isset($_SESSION['carrello'])) {
            foreach ($_SESSION['carrello'] as &$cart_elem) {
                if ($cart_elem['id_prodotto'] == $id_prodotto && $cart_elem['taglia'] == $taglia) {
                    // aggiorno la quantita
                    $prod_gia_presente = true;
                    $nuova_quantita = intval($cart_elem['quantita']) + intval($quantita);
                    $cart_elem['quantita'] = $nuova_quantita;
                    break;
                }
            }

            if (!$prod_gia_presente) {
                // lo aggiungo
                $_SESSION['carrello'][] = array(
                    'id_prodotto' => $id_prodotto,
                    'quantita' => $quantita,
                    'taglia' => $taglia
                );
            }
        } else {
            // lo aggiungo
            $_SESSION['carrello'][] = array(
                'id_prodotto' => $id_prodotto,
                'quantita' => $quantita,
                'taglia' => $taglia
            );
        }
    }
}
