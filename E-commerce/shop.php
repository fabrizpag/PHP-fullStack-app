<?php

require "include/template2.inc.php";
require "include/dbms.inc.php";

require_once "include/php-utils/global.php";


$main = new Template("skins/template/dtml/index_v2.html");
$shop = new Template("skins/template/shop.html");
$filtri = new Template("skins/template/dtml/filtri_laterali.html");



/******* caso chiamata POST per filtri  *******/
if (isset($_POST['valore'])) {
    $v = $_POST['valore'];

    $escape = "'";
    $arrCategoria = $v['arrCategoria'];
    $arrGenere = $v['arrGenere'];
    $arrMarca = $v['arrMarca'];
    $arrPrezzo = $v['arrPrezzo'];
    $min = strval($arrPrezzo[0]);
    $min = substr($min, 1);
    $max = strval($arrPrezzo[1]);
    $max = substr($max, 1);
    $size = $v['size'];

    $strquery = " SELECT DISTINCT p.* FROM Prodotto p JOIN Magazzino m ON p.id = m.id_prodotto JOIN Categoria c ON p.id_categoria = c.id JOIN Marca ma ON p.id_marca = ma.id ";
    $strquery = $strquery . "WHERE p.prezzo >= " . $min . " AND p.prezzo <= " . $max;



    if ($arrCategoria[0] !== '-1') {

        $strquerytmp = " SELECT id FROM Categoria WHERE nome_categoria IN (";
        $arrIDCategoriatmp = array();
        foreach ($arrCategoria as $cat) {
            $strquerytmp = $strquerytmp . $escape . $cat . $escape . ",";
        }
        $strquerytmp = substr($strquerytmp, 0, -1);
        $strquerytmp = $strquerytmp . ")";

        $strquery = $strquery . " AND c.nome_categoria IN (";
        foreach ($arrCategoria as $cat) {

            $strquery = $strquery . $escape . $cat . $escape . ",";
        }
        $strquery = substr($strquery, 0, -1);
        $strquery = $strquery . ")";

        $qProva = $connessione->query($strquerytmp)->fetch_all(MYSQLI_ASSOC);
        $strquery = $strquery . " AND p.id_categoria IN (";
        foreach ($qProva as $q) {
            $wtmp = (int) $q['id'];
            $wtmp = strval($wtmp);
            $strquery = $strquery . $wtmp . ",";
        }
        $strquery = substr($strquery, 0, -1);
        $strquery = $strquery . ")";
    }
    if ($arrGenere[0] !== '-1') {
        $strquery = $strquery . " AND p.genere IN (";
        foreach ($arrGenere as $gen) {
            $strquery = $strquery . $escape . $gen . $escape . ",";
        }
        $strquery = substr($strquery, 0, -1);
        $strquery = $strquery . ")";
    }
    if ($arrMarca[0] !== '-1') {
        $strquery = $strquery . " AND ma.nome_marca IN (";
        foreach ($arrMarca as $mar) {
            $strquery = $strquery . $escape . $mar . $escape . ",";
        }
        $strquery = substr($strquery, 0, -1);
        $strquery = $strquery . ")";
    }
    if ($size !== "U") {
        $strquery = $strquery . " AND m.taglia =" . $escape . $size . $escape;
        $strquery = $strquery . " AND m.quantita > 0";
    }
    //echo $strquery;

    /****** CHIAMATA DALLA HOME PAGE CON FILTRO Già SETTATO SU UN GENERE ******/
    
    if (isset($v['changePage'])) {


        $main = new Template("skins/template/dtml/index_v2.html");
        $shop = new Template("skins/template/shop.html");
        $filtri = new Template("skins/template/dtml/filtri_laterali.html");
        require "include/php-utils/preferiti_carrello.php";

        BarraFiltri($shop, $filtri);
        $res = $connessione->query("$strquery")->fetch_all(MYSQLI_ASSOC);
        foreach ($res as $r) {
            $marcaTmp = $connessione->query("SELECT m.nome_marca FROM prodotto p LEFT JOIN marca m ON $r[id_marca] = m.id;")->fetch_all(MYSQLI_ASSOC);
            $url_img = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$r['id']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);

            $prodotto = new Template("skins/template/dtml/dtml_items/prodottoShopItem.html");

            $prodotto->setContent("ID_PRODOTTO", $r['id']);
            $prodotto->setContent("NOME_PRODOTTO", $r['nome_prodotto']);
            $prodotto->setContent("MARCA_PRODOTTO", $marcaTmp[0]['nome_marca']);

            // immagine prodotto
            if (empty($url_img)) {
                $prodotto->setContent("URL_IMMAGINE", _IMG_PATH . "product-single/noimage.png");
            } else {
                $prodotto->setContent("URL_IMMAGINE", _IMG_PATH . $url_img[0]['url_immagine']);
            }

            // prezzo in promozione
            if ($r['id_promozione']) {
                // devo impostare il vecchio prezzo e calcolare il nuovo
                $prodotto->setContent("PREZZO_PRODOTTO_PRECEDENTE", $r['prezzo']);
                $promo = $connessione->query("SELECT prom.sconto_percentuale FROM prodotto p LEFT JOIN promozione prom ON $r[id_promozione] = prom.id")->fetch_all(MYSQLI_ASSOC);
                $sconto = intval($promo[0]['sconto_percentuale']);
                $nuovoPrezzo = intval($r['prezzo']) - ($sconto * intval($r['prezzo']) / 100);
                $prodotto->setContent("PREZZO_PRODOTTO", $nuovoPrezzo);
            } else {
                $prodotto->setContent("PREZZO_PRODOTTO", $r['prezzo']);
            }

            $shop->setContent('prodotti', $prodotto->get());
        }
        echo $shop->get();
    }

    /****** CHIAMATA CON FILTRO DALLO SHOP ******/
    
    else {

        $res = $connessione->query("$strquery")->fetch_all(MYSQLI_ASSOC);
        foreach ($res as $r) {
            $marcaTmp = $connessione->query("SELECT m.nome_marca FROM prodotto p LEFT JOIN marca m ON $r[id_marca] = m.id;")->fetch_all(MYSQLI_ASSOC);
            $url_img = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$r['id']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);
            $prodotto = new Template("skins/template/dtml/dtml_items/prodottoShopItem.html");

            $prodotto->setContent("ID_PRODOTTO", $r['id']);
            $prodotto->setContent("NOME_PRODOTTO", $r['nome_prodotto']);
            $prodotto->setContent("MARCA_PRODOTTO", $marcaTmp[0]['nome_marca']);

            // immagine prodotto
            if (empty($url_img)) {
                $prodotto->setContent("URL_IMMAGINE", _IMG_PATH . "product-single/noimage.png");
            } else {
                $prodotto->setContent("URL_IMMAGINE", _IMG_PATH . $url_img[0]['url_immagine']);
            }

            if ($r['id_promozione']) {
                // devo impostare il vecchio prezzo e calcolare il nuovo
                $prodotto->setContent("PREZZO_PRODOTTO_PRECEDENTE", $r['prezzo']);
                $promo = $connessione->query("SELECT prom.sconto_percentuale FROM prodotto p LEFT JOIN promozione prom ON $r[id_promozione] = prom.id")->fetch_all(MYSQLI_ASSOC);
                $sconto = intval($promo[0]['sconto_percentuale']);
                $nuovoPrezzo = intval($r['prezzo']) - ($sconto * intval($r['prezzo']) / 100);

                $prodotto->setContent("PREZZO_PRODOTTO", $nuovoPrezzo);
            } else {
                $prodotto->setContent("PREZZO_PRODOTTO", $r['prezzo']);
            }
            echo $prodotto->get();
        }
    }
    

    /****** CHIAMATA NORMALE ******/
} else {
    global $connessione;

    // tiene aggiornato il numero di oggetti presenti nel carrello
    require "include/php-utils/preferiti_carrello.php";

    BarraFiltri($shop, $filtri);

    /********* popolamento dei prodotti *********/
    $res = $connessione->query("SELECT DISTINCT p.* FROM Prodotto p JOIN Magazzino m ON p.id = m.id_prodotto")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $marcaTmp = $connessione->query("SELECT m.nome_marca FROM prodotto p LEFT JOIN marca m ON $r[id_marca] = m.id;")->fetch_all(MYSQLI_ASSOC);
        $url_img = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$r['id']} LIMIT 1;")->fetch_all(MYSQLI_ASSOC);

        $prodotto = new Template("skins/template/dtml/dtml_items/prodottoShopItem.html");

        $prodotto->setContent("ID_PRODOTTO", $r['id']);
        $prodotto->setContent("NOME_PRODOTTO", $r['nome_prodotto']);
        $prodotto->setContent("MARCA_PRODOTTO", $marcaTmp[0]['nome_marca']);

        // immagine prodotto
        if (empty($url_img)) {
            $prodotto->setContent("URL_IMMAGINE", _IMG_PATH . "product-single/noimage.png");
        } else {
            $prodotto->setContent("URL_IMMAGINE", _IMG_PATH . $url_img[0]['url_immagine']);
        }

        // prezzo in promozione
        if ($r['id_promozione']) {
            // devo impostare il vecchio prezzo e calcolare il nuovo
            $prodotto->setContent("PREZZO_PRODOTTO_PRECEDENTE", $r['prezzo']);
            $promo = $connessione->query("SELECT prom.sconto_percentuale FROM prodotto p LEFT JOIN promozione prom ON $r[id_promozione] = prom.id")->fetch_all(MYSQLI_ASSOC);
            $sconto = intval($promo[0]['sconto_percentuale']);
            $nuovoPrezzo = intval($r['prezzo']) - ($sconto * intval($r['prezzo']) / 100);
            $prodotto->setContent("PREZZO_PRODOTTO", $nuovoPrezzo);
        } else {
            $prodotto->setContent("PREZZO_PRODOTTO", $r['prezzo']);
        }

        $shop->setContent('prodotti', $prodotto->get());
    }

    $main->setContent('body', $shop->get());
    $main->close();
}



function BarraFiltri($shop, $filtri)
{
    global $connessione;
    /********* popolamento della colonna laterale dei filtri *********/
    $res = $connessione->query("SELECT * FROM categoria c ORDER BY c.nome_categoria")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $categoria = new Template("skins/template/dtml/dtml_items/barra laterale filtri/categoriaItem.html");
        $categoria->setContent("NOME_CATEGORIA", $r['nome_categoria']);
        $filtri->setContent("categorie", $categoria->get());
    }

    $res = $connessione->query("SELECT * FROM marca m ORDER BY m.nome_marca")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $marca = new Template("skins/template/dtml/dtml_items/barra laterale filtri/marcaItem.html");
        $marca->setContent("NOME_MARCA", $r['nome_marca']);
        $filtri->setContent('marche', $marca->get());
    }
    $shop->setContent('sezione_filtri', $filtri->get());
}
