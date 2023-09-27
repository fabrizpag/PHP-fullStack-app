<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/php-utils/global.php";

session_start();

global $connessione;
$product_id;
$taglia_sel;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/product.html");

// tiene aggiornato il numero di oggetti presenti nel carrello
require "include/php-utils/preferiti_carrello.php";


// recupero l'id del prodotto dall'URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    header('location: shop.php');
}


/********* popolamento rullino foto *********/
$res = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$product_id};")->fetch_all(MYSQLI_ASSOC);
$save_first = false;
foreach ($res as $r) {
    if (!$save_first) {
        $body->setContent("FOTO_PRINCIPALE_PRODOTTO", _IMG_PATH . $r['url_immagine']);
        $save_first = true;
    }
    $rullino_foto = new Template("skins/template/dtml/dtml_items/SequenzaFotoItem.html");
    $rullino_foto->setContent("URL_IMMAGINE", _IMG_PATH . $r['url_immagine']);

    $body->setContent("RULLINO_FOTO", $rullino_foto->get());
}




/********* popolamento dati Prodotto *********/
$res = $connessione->query("SELECT * FROM Prodotto p WHERE p.id = {$product_id}")->fetch_all(MYSQLI_ASSOC);
foreach ($res as $r) {
    $body->setContent("ID_PRODOTTO", $r['id']);
    $body->setContent("NOME_PRODOTTO", $r['nome_prodotto']);
    $body->setContent("DESCRIZIONE_PRODOTTO", $r['descrizione']);

    // prezzo in promozione
    if ($r['id_promozione']) {
        $body->setContent("PREZZO_PRODOTTO_PRECEDENTE", $r['prezzo']);
        $promo = $connessione->query("SELECT prom.sconto_percentuale FROM prodotto p LEFT JOIN promozione prom ON $r[id_promozione] = prom.id")->fetch_all(MYSQLI_ASSOC);
        $sconto = intval($promo[0]['sconto_percentuale']);
        $nuovoPrezzo = intval($r['prezzo']) - ($sconto * intval($r['prezzo']) / 100);
        $body->setContent("PREZZO_PRODOTTO", $nuovoPrezzo);
    } else {
        $body->setContent("PREZZO_PRODOTTO", $r['prezzo']);
    }

    // categoria
    $tmp = $connessione->query("SELECT c.nome_categoria FROM Prodotto p JOIN Categoria c ON {$r['id_categoria']} = c.id")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("CATEGORIA_PRODOTTO", $tmp[0]['nome_categoria']);

    // marca
    $tmp = $connessione->query("SELECT m.nome_marca FROM Prodotto p JOIN Marca m ON {$r['id_marca']} = m.id")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("MARCA_PRODOTTO", $tmp[0]['nome_marca']);

    // recensioni
    $tmp = $connessione->query("SELECT * FROM Recensione WHERE id_prodotto = {$product_id};");
    $body->setContent("N_RECENSIONI_PRODOTTO", $tmp->num_rows);
    $tmp->fetch_all(MYSQLI_ASSOC);

    foreach ($tmp as $t) {
        $recensione = new Template("skins/template/dtml/dtml_items/RecensioniProdottoItem.html");
        $tmp2 = $connessione->query("SELECT u.nome, u.cognome FROM Utente u JOIN Recensione r ON u.id = {$t['id_utente']}")->fetch_all(MYSQLI_ASSOC);
        $recensione->setContent("NOME_COGNOME_UTENTE", $tmp2[0]['nome'] . " " . $tmp2[0]['cognome']);
        $recensione->setContent("DATA_RECENSIONE", $t['data_recensione']);
        $recensione->setContent("TESTO_RECENSIONE", $t['testo_recensione']);

        $recensione->setContent("VALUTAZIONE", $t['valutazione']);
        $body->setContent("RECENSIONI_UTENTI", $recensione->get());
    }
    // $body->setContent("RECENSIONI_UTENTI", $recensione->get());

    // rating
    $tmp = $connessione->query("SELECT ROUND(AVG(valutazione), 1) AS media_valutazione FROM Recensione WHERE id_prodotto = {$product_id}")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("VALUTAZIONE_PRODOTTO", $tmp[0]['media_valutazione']);

    // quantità magazzino
    $tmp = $connessione->query("SELECT SUM(quantita) AS tot_quantita FROM Magazzino WHERE id_prodotto = {$product_id}")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("TOT_DISPONIBILITA_PRODOTTO", $tmp[0]['tot_quantita']);

    // numero ordinazioni
    $tmp = $connessione->query("SELECT SUM(quantita_prodotto) AS tot_ordini FROM Oggetto_Ordine WHERE id_prodotto = {$product_id}")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("N_ORDINAZIONI", $tmp[0]['tot_ordini']);
}

$main->setContent('body', $body->get());
$main->close();
