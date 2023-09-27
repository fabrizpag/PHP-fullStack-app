<?php


if (isset($_SESSION['carrello'])) {
    // travasamento
    $prod_gia_presente = false;

    $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$_SESSION['utente']['id']}")->fetch_all(MYSQLI_ASSOC);

    foreach ($_SESSION['carrello'] as &$cart_elem) {
        $userid = $_SESSION['utente']['id'];
        $prod_gia_presente = false;

        foreach ($res as $r) {
            if ($cart_elem['id_prodotto'] == $r['id_prodotto'] && $cart_elem['taglia'] == $r['taglia_prodotto']) {
                // sommo
                $quantita_attuale = intval($r['quantita_prodotto']);
                $nuova_quantita = $quantita_attuale + intval($cart_elem['id_prodotto']);

                $upd = $connessione->prepare("UPDATE Carrello SET quantita_prodotto = ? WHERE id_prodotto = ? AND id_utente = ? AND taglia_prodotto = ?");
                $upd->bind_param("iiis", $nuova_quantita, $r['id_prodotto'], $userid, $r['taglia_prodotto']);
                //

                // CONTROLLA BENE la rimozione dal carrello cancella l'ID, non il prodotto con la taglia X

                //
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
            $add->bind_param("iiis", $userid, $cart_elem['id_prodotto'], $cart_elem['quantita'], $cart_elem['taglia']);
            if ($add->execute()) {
                echo "Aggiunta tabella Carrello.";
            } else {
                echo "Errore durante aggiunta tabella Carrello: " . $add->error;
            }
        }
    }
    // distruggo il carrello in sessione
    unset($_SESSION['carrello']);
}
