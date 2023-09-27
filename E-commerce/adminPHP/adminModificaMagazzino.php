<?php


require "../include/template2.inc.php";
require "../include/dbms.inc.php";
require "../include/php-utils/global.php";

global $connessione;
global $idItem;
session_start();


if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    // chiata per salvare la modifica di un prodotto nel magazzino
    if (isset($_POST['formNome'])) {

        $formCategoria = $_POST['formCategoria'];
        $formMarca  = $_POST['formMarca'];
        $formNome  = $_POST['formNome'];
        $formDescrizione  = $_POST['formDescrizione'];
        $formPrezzo = $_POST['formPrezzo'];
        $formGenere = $_POST['formGenere'];
        $idItem = $_POST['formId'];
        $formTagliaS = $_POST['formTagliaS'];
        $formTagliaM = $_POST['formTagliaM'];
        $formTagliaL = $_POST['formTagliaL'];
        $formTagliaXL = $_POST['formTagliaXL'];

        $res = $connessione->query("SELECT id FROM Categoria WHERE nome_categoria = '$formCategoria'")->fetch_all(MYSQLI_ASSOC);
        $nuovo_id_categoria = $res[0]["id"];
        $res = $connessione->query("SELECT id FROM Marca WHERE nome_marca = '$formMarca'")->fetch_all(MYSQLI_ASSOC);
        $nuovo_id_marca = $res[0]["id"];
        $connessione->query("UPDATE Prodotto
    SET nome_prodotto = '$formNome', descrizione = '$formDescrizione', prezzo = '$formPrezzo', genere = '$formGenere', id_categoria = '$nuovo_id_categoria', id_marca = '$nuovo_id_marca'
    WHERE id = '$idItem'");
        //ora l'update delle quantità
        $connessione->query(" UPDATE Magazzino SET quantita = '$formTagliaS' WHERE id_prodotto = '$idItem' AND taglia = 'S'");
        $connessione->query(" UPDATE Magazzino SET quantita = '$formTagliaM' WHERE id_prodotto = '$idItem' AND taglia = 'M'");
        $connessione->query(" UPDATE Magazzino SET quantita = '$formTagliaL' WHERE id_prodotto = '$idItem' AND taglia = 'L'");
        $connessione->query(" UPDATE Magazzino SET quantita = '$formTagliaXL' WHERE id_prodotto = '$idItem' AND taglia = 'XL'");
        //eseguo una redirect sulla home dell'admin
        header("location:http://localhost/E-commerce/admin.php");
        exit();
    } else {

        //chiamata su modifica
        if (isset($_GET['mod'])) {
            $modMagazzino = new Template("../skins/template/adminModificaMagazzino.html");
            $idItem = $_GET['mod'];

            $img_product = $connessione->query("SELECT url_immagine FROM Immagine_Prodotto WHERE id_prodotto = {$idItem} LIMIT 1")->fetch_all(MYSQLI_ASSOC);
            $modMagazzino->setContent("URL_IMMAGINE", "../skins/template/img/" . $img_product[0]['url_immagine']);

            $res = $connessione->query("SELECT * FROM Categoria")->fetch_all(MYSQLI_ASSOC);

            $res2 = $connessione->query(" SELECT Prodotto.id AS id_prodotto,Prodotto.nome_prodotto,Prodotto.descrizione,Prodotto.prezzo,Prodotto.genere,Categoria.id AS id_categoria,Categoria.nome_categoria,Marca.id AS id_marca,Marca.nome_marca,Magazzino.quantita,Magazzino.taglia
                    FROM Prodotto JOIN Categoria ON Prodotto.id_categoria = Categoria.id JOIN Marca ON Prodotto.id_marca = Marca.id JOIN Magazzino ON Prodotto.id = Magazzino.id_prodotto;")->fetch_all(MYSQLI_ASSOC);
            $resFiltered = array();
            foreach ($res2 as $row) {
                if ($row['id_prodotto'] == $idItem) {
                    $resFiltered[] = $row;
                }
            }

            //popola e seleziona la categoria
            $tot = "";
            foreach ($res as $r) {
                $str = $r['nome_categoria'];
                if ($r['nome_categoria'] === $resFiltered[0]['nome_categoria']) {
                    $str = '<option value="' . $str . '" selected>' . $str . "</option>";
                    $tot = $tot . $str;
                } else {
                    $str = '<option value="' . $str . '">' . $str . "</option>";
                    $tot = $tot . $str;
                }
            }
            $modMagazzino->setContent("select_categoria", $tot);
            $modMagazzino->setContent("id_P", $idItem);
            $res = $connessione->query("SELECT * FROM Marca")->fetch_all(MYSQLI_ASSOC);

            //popola e seleziona la marca
            $tot = "";
            foreach ($res as $r) {
                $str = $r['nome_marca'];
                if ($r['nome_marca'] === $resFiltered[0]['nome_marca']) {
                    $str = '<option value="' . $str . '" selected>' . $str . "</option>";
                    $tot = $tot . $str;
                } else {
                    $str = '<option value="' . $str . '">' . $str . "</option>";
                    $tot = $tot . $str;
                }
            }
            $modMagazzino->setContent("select_marca", $tot);
            //popola e seleziona il genere
            $modMagazzino->setContent("select_genere", selectGenere($resFiltered[0]['genere']));
            //popola le quantità per ogni taglia
            $modMagazzino->setContent("value1", $resFiltered[0]['quantita']);
            $modMagazzino->setContent("value2", $resFiltered[1]['quantita']);
            $modMagazzino->setContent("value3", $resFiltered[2]['quantita']);
            $modMagazzino->setContent("value4", $resFiltered[3]['quantita']);
            //popola nome_P,descrizione_P,prezzo_P
            $modMagazzino->setContent("nome_P", $resFiltered[0]["nome_prodotto"]);
            $modMagazzino->setContent("descrizione_P", $resFiltered[0]["descrizione"]);
            $modMagazzino->setContent("prezzo_P", $resFiltered[0]["prezzo"]);

            //per la query string img
            $res3 = $connessione->query("SELECT * FROM Immagine_Prodotto WHERE id_prodotto = '$idItem' ")->fetch_all(MYSQLI_ASSOC);
            if (count($res3) === 0) {
                $queryString = "img=" . strval($idItem) . "&no=1";
                $modMagazzino->setContent("querystringImg", $queryString);
            } else {
                $queryString = "img=" . strval($idItem);
                $modMagazzino->setContent("querystringImg", $queryString);
            }

            $modMagazzino->close();
        }
        //chiamata su delete 
        else {
            $idTodel = $_GET['del'];
            $connessione->query("DELETE FROM Magazzino WHERE id_prodotto = $idTodel");
            header("location:http://localhost/E-commerce/admin.php");
            exit();
        }
    }
}
//display error 403
else {
    $temp = new Template("../skins/template/dtml/error403.html");
    $temp->close();
}

function selectGenere($str1)
{
    $toret = '';

    switch ($str1) {
        case 'uomo':
            $toret = '
                <option value="uomo" selected>uomo</option>
                <option value="donna">donna</option>
                <option value="bambino">bambino</option>
            ';
            break;
        case 'donna':
            $toret = '
                <option value="uomo">uomo</option>
                <option value="donna" selected>donna</option>
                <option value="bambino">bambino</option>
            ';
            break;
        case 'bambino':
            $toret = '
                <option value="uomo">uomo</option>
                <option value="donna">donna</option>
                <option value="bambino" selected>bambino</option>
            ';
            break;
    }

    return $toret;
}
