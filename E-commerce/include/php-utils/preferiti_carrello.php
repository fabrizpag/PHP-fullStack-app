<?php

require_once "include/php-utils/global.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// tiene aggiornato il numero di oggetti presenti nel carrello
$cart = new Template("skins/template/dtml/dtml_items/main/icona_carrello.html");
$cart_items = 0;
$totale_cart = 0.0;


if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    // utente autenticato -- contare dalla query
    $res = $connessione->query("SELECT * FROM Carrello WHERE id_utente = {$_SESSION['utente']['id']}")->fetch_all(MYSQLI_ASSOC);
    $cart_items = count($res);

    foreach ($res as $r) {
        $lista_carrello = new Template("skins/template/dtml/dtml_items/main/prodotti_lista_cart.html");

        $lista_carrello->setContent('QUANTITA_PROD', $r['quantita_prodotto']);
        $lista_carrello->setContent('TAGLIA_PROD', $r['taglia_prodotto']);

        $tmp = $connessione->query("SELECT * FROM Prodotto WHERE id = {$r['id_prodotto']}")->fetch_all(MYSQLI_ASSOC);
        $lista_carrello->setContent('NOME_PROD', $tmp[0]['nome_prodotto']);
        $lista_carrello->setContent('PREZZO_PROD', $tmp[0]['prezzo']);

        $prezzo_cart_elem = floatval($tmp[0]['prezzo']) * intval($r['quantita_prodotto']);
        $totale_cart += $prezzo_cart_elem;

        $url_img = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$tmp[0]['id']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
        $lista_carrello->setContent('IMMAGINE_PROD', _IMG_PATH . $url_img[0]['url_immagine']);

        $cart->setContent('lista_prodotti', $lista_carrello->get());
    }
} else {
    // utente non autenticato -- contare dalla sessione
    if (isset($_SESSION['carrello'])) {
        $cart_items = count($_SESSION['carrello']);
        foreach ($_SESSION['carrello'] as &$cart_elem) {
            $lista_carrello = new Template("skins/template/dtml/dtml_items/main/prodotti_lista_cart.html");

            $lista_carrello->setContent('QUANTITA_PROD', $cart_elem['quantita']);
            $lista_carrello->setContent('TAGLIA_PROD', $cart_elem['taglia']);

            $res = $connessione->query("SELECT * FROM Prodotto WHERE id = {$cart_elem['id_prodotto']}")->fetch_all(MYSQLI_ASSOC);
            $lista_carrello->setContent('NOME_PROD', $res[0]['nome_prodotto']);
            $lista_carrello->setContent('PREZZO_PROD', $res[0]['prezzo']);

            $prezzo_cart_elem = floatval($res[0]['prezzo']) * intval($cart_elem['quantita']);
            $totale_cart += $prezzo_cart_elem;

            $url_img = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$res[0]['id']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
            $lista_carrello->setContent('IMMAGINE_PROD', _IMG_PATH . $url_img[0]['url_immagine']);

            $cart->setContent('lista_prodotti', $lista_carrello->get());
        }
    }
}

$cart->setContent('TOTALE_CART', $totale_cart);

$cart->setContent('oggetti_carrello', $cart_items);
$main->setContent('carrello_icon', $cart->get());
