<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";
require_once "include/php-utils/global.php";

session_start();

global $connessione;

$main = new Template("skins/template/dtml/index_v2.html");
$body = new Template("skins/template/dtml/home.html");

// tiene aggiornato il numero di oggetti presenti nel carrello
require "include/php-utils/preferiti_carrello.php";

// search id promozione
$res = $connessione->query("SELECT id_promozione, id_categoria FROM Prodotto WHERE id_promozione IS NOT NULL LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
if (!empty($res)) {

    // categoria associata
    $cat = $connessione->query("SELECT nome_categoria FROM Categoria WHERE id = {$res[0]['id_categoria']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("CATEGORIA_PROMOZIONE", $cat[0]['nome_categoria']);

    // promozione
    $promo = $connessione->query("SELECT * FROM Promozione WHERE id = {$res[0]['id_promozione']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $body->setContent("NOME_PROMOZIONE", $promo[0]['nome_promozione']);
    $body->setContent("DESCRIZIONE_PROMOZIONE", $promo[0]['descrizione']);
    $body->setContent("SCONTO_PERCENTUALE", intval($promo[0]['sconto_percentuale']));

    $data = strtotime($promo[0]['data_fine']);
    $body->setContent("GG", date("d", $data));
    $body->setContent("MM", date("m", $data));
    $body->setContent("AAAA", date("Y", $data));

    //setto l'immagine
    $idTmp = $res[0]['id_promozione'];
    $res3 = $connessione->query("SELECT * FROM Prodotto WHERE id_promozione = '$idTmp'")->fetch_all(MYSQLI_ASSOC);
    $idTmp = $res3[0]['id'];
    $res3 = $connessione->query("SELECT * FROM Immagine_Prodotto WHERE id_prodotto = '$idTmp'")->fetch_all(MYSQLI_ASSOC);
    $pathImg = $res3[0]['url_immagine'];
    $body->setContent("SET_IMG", $pathImg);
}

$res = $connessione->query("SELECT * FROM Recensione ORDER BY data_recensione DESC LIMIT 3;")->fetch_all(MYSQLI_ASSOC);
foreach ($res as $r) {
    $feedback = new Template("skins/template/dtml/dtml_items/ultime_3_recensioniItem.html");

    // elementi recensione
    $feedback->setContent("DATA_RECENSIONE", $r['data_recensione']);
    $feedback->setContent("STELLE", $r['valutazione']);
    $feedback->setContent("TESTO_RECENSIONE", $r['testo_recensione']);
    $feedback->setContent("ID_PRODOTTO", $r['id_prodotto']);

    // dati utente
    $utente = $connessione->query("SELECT nome, cognome FROM Utente WHERE id = {$r['id_utente']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $feedback->setContent("NOME_COGNOME_UTENTE", $utente[0]['nome'] . " " . $utente[0]['cognome']);

    // url_immagine
    $img = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$r['id_prodotto']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
    $feedback->setContent("URL_IMMAGINE", _IMG_PATH . $img[0]['url_immagine']);

    $body->setContent("FEEDBACK", $feedback->get());
}











$main->setContent('body', $body->get());
$main->close();
