<?php
    require "include/template2.inc.php";
    require "include/dbms.inc.php";
    global $connessione;
    session_start();

    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    /***** CASO POST PER FILTRI *****/
    if (isset($_POST['valore'])){

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

        $strquery=setQuery($arrCategoria,$arrGenere,$arrMarca,$arrPrezzo,$min,$max,$size,$strquery,$connessione);
        $res = $connessione->query($strquery);
        foreach ($res as $r) {
            $prodotto = new Template("skins/template/dtml/dtml_items/prodottoAdminItem.html");
            $marcaTmp = $connessione->query("SELECT m.nome_marca FROM prodotto p LEFT JOIN marca m ON $r[id_marca] = m.id;")->fetch_all(MYSQLI_ASSOC);
            $prodotto->setContent("MARCA_PRODOTTO", $marcaTmp[0]['nome_marca']);
            $prodotto->setContent("NOME_PRODOTTO", $r['nome_prodotto']);
            $prodotto->setContent("ID_PRODOTTO", $r['id']);
            echo $prodotto->get();
        }

    }
    /***** CASO PRIMO ACCESSO *****/
    else{
        $admin_home = new Template("skins/template/admin-home.html");
        $admin_container = new Template("skins/template/adminContainer.html");
    
        $filtri = new Template ("skins/template/dtml/filtri_laterali.html");
        $admin_container = setFiltri($filtri,$connessione,$admin_container);
    
        $res = $connessione->query("SELECT DISTINCT p.* FROM Prodotto p JOIN Magazzino m ON p.id = m.id_prodotto")->fetch_all(MYSQLI_ASSOC);
        $admin_container=setItems($res,$connessione,$admin_container);
        
    
        $admin_home->setContent('body', $admin_container->get());
        $admin_home->close();

    }

    
}
//display error 403
else{
    $temp = new Template("skins/template/dtml/error403.html");
    $temp->close();
  
  }
  function setFiltri($filtri,$connessione,$admin_container){
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
    $admin_container->setContent('filter', $filtri->get());
    return $admin_container;
}

function setItems($res,$connessione,$admin_container){
    foreach ($res as $r) {
        $prodotto = new Template("skins/template/dtml/dtml_items/prodottoAdminItem.html");
        $marcaTmp = $connessione->query("SELECT m.nome_marca FROM prodotto p LEFT JOIN marca m ON $r[id_marca] = m.id;")->fetch_all(MYSQLI_ASSOC);
        $prodotto->setContent("MARCA_PRODOTTO", $marcaTmp[0]['nome_marca']);
        $prodotto->setContent("NOME_PRODOTTO", $r['nome_prodotto']);
        $prodotto->setContent("ID_PRODOTTO", $r['id']);
        $prodotto->setContent("PREZZO",floatval($r['prezzo']));
        $admin_container->setContent('item', $prodotto->get());
    }
    return $admin_container;
}

function setQuery($arrCategoria,$arrGenere,$arrMarca,$arrPrezzo,$min,$max,$size,$strquery,$connessione){
    $escape = "'";
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
    return $strquery;
}