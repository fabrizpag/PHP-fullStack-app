<?php
require "../include/template2.inc.php";
require "../include/dbms.inc.php";
global $connessione;
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin']) {

//display form per l'aggiunta di una promozione
if(isset($_GET['add'])){
    $page = new Template("../skins/template/adminAddPromozione.html");
    $page->setContent("formId","-1");
    $page->close();
}
//aggiungi o modifica una promozione
if(isset($_POST['nomePromozione'])){
    $nomePromozione = $_POST['nomePromozione'];
    $descrizione = $_POST['descrizione'];
    $scontoPercentuale = $_POST['scontoPercentuale'];
    $dataInizio = $_POST['dataInizio'];
    $dataFine = $_POST['dataFine'];
    $idP = $_POST['formId'];

    //caso di aggiunta
    if($idP ==="-1"){
        $connessione->query("INSERT INTO Promozione (nome_promozione, descrizione, sconto_percentuale, data_inizio, data_fine)
        VALUES ('$nomePromozione', '$descrizione', $scontoPercentuale, '$dataInizio', '$dataFine')");
        header("location:http://localhost/E-commerce/admin.php");
        exit();
    }
    //caso di modifica
    else{
        $connessione->query(" UPDATE Promozione
        SET nome_promozione = '$nomePromozione', sconto_percentuale = $scontoPercentuale,
        descrizione ='$descrizione', data_inizio='$dataInizio', data_fine='$dataFine'
        WHERE id = $idP");
        header("location:http://localhost/E-commerce/admin.php");
        exit();
    }
     
    
}
//cancellazione
if(isset($_GET['cancella'])){
    $idP = $_GET['cancella'];
    $connessione->query("DELETE FROM Promozione WHERE id = $idP");
    header("location:http://localhost/E-commerce/admin.php");
    exit();
}

//display form di  modifica 
if(isset($_GET['modifica'])){
    $idP = $_GET['modifica'];
    $res = $connessione->query("SELECT * FROM Promozione WHERE id = $idP")->fetch_all(MYSQLI_ASSOC);
    //settings parametri
    $page = new Template("../skins/template/adminAddPromozione.html");
    $page->setContent("formId",$idP);
    $page->setContent("nomePromozione",$res[0]['nome_promozione']);
    $page->setContent("descrizione",$res[0]['descrizione']);
    $page->setContent("scontoPercentuale",$res[0]['sconto_percentuale']);
    $dateString = $res[0]['data_inizio'];
    $date = date("Y-m-d", strtotime($dateString));
    $dateString2= $res[0]['data_fine'];
    $date2 = date("Y-m-d", strtotime($dateString2));

    $page->setContent("dataInizio",$date);
    $page->setContent("dataFine",$date2);
    $page->close();
}

// display per l'applicazione di una promo
if(isset($_GET['prom_id'])){
    $page = new Template("../skins/template/adminPromozioni.html");
    $idPromo = $_GET['prom_id'];
    
   ////popola categorie
   $res = $connessione->query("SELECT * FROM Categoria")->fetch_all(MYSQLI_ASSOC);
   $tot = "";
   foreach($res as $r){
        $str = $r['nome_categoria'];
        $str = '<option value="'.$str.'">'.$str."</option>";
        $tot = $tot.$str;
    }
    $page->setContent("select_categoria",$tot);
    $page->setContent("form_id_prom",$idPromo);
    $page->close();
}
// rimuovere una promo
if(isset($_GET['prom_idDlt'])){
    $idPromo = $_GET['prom_idDlt'];
    $res = $connessione->query("SELECT * FROM Prodotto WHERE id_promozione = $idPromo")->fetch_all(MYSQLI_ASSOC);
    foreach($res as $r){
        $res2 = $connessione->query("UPDATE Prodotto SET id_promozione = NULL ");
    }
    // redirect 
    header("location:http://localhost/E-commerce/admin.php");
    exit();

}
//applicazione di una prova, selezionata una categoria
if(isset($_POST['formCategoria'])){
    $cat = $_POST['formCategoria'];
    $idPromo = $_POST['formIDProm'];
    
    $res = $connessione->query("SELECT id FROM Categoria WHERE nome_categoria = '$cat'")->fetch_all(MYSQLI_ASSOC);
    $cat = $res[0]['id'];
    $res = $connessione->query("SELECT * FROM Prodotto WHERE id_categoria =  $cat")->fetch_all(MYSQLI_ASSOC);
    foreach($res as $r){
        $idProduct = $r['id'];
        $res2 = $connessione->query("UPDATE Prodotto SET id_promozione = '$idPromo' WHERE id = '$idProduct'");
    }
    // redirect 
    header("location:http://localhost/E-commerce/admin.php");
    exit();
    

}
// display lista promozioni
if( (!isset($_GET['prom_id']))&&
(!isset($_GET['prom_idDlt']))&&
(!isset($_POST['formCategoria']))&&
(!isset($_GET['add']))&&
(!isset($_POST['nomePromozione']))&&
(!isset($_GET['cancella']))&&
(!isset($_GET['modifica']))
 ){
    $admin_container2 = new Template("../skins/template/adminContainer2.html");
    $admin_container2->setContent('elemento',"una nuova promozione");
    $admin_container2->setContent("HREF","adminPHP/adminPromozione.php?add=1");
    $res2 = $connessione->query("SELECT pr.* FROM Prodotto p JOIN Promozione pr ON p.id_promozione = pr.id")->fetch_all(MYSQLI_ASSOC); 

    $res = $connessione->query("SELECT * FROM Promozione")->fetch_all(MYSQLI_ASSOC); 
    foreach($res as $r){
        $item = new Template ("../skins/template/dtml/dtml_items/promozioneAdminItem.html");
        $idTmp = $r['id'];
        $item->setContent("NOME", $r['nome_promozione']);
        $item->setContent("SCONTO",$r['sconto_percentuale']);
        $item->setContent("INIZIO",$r['data_inizio']);
        $item->setContent("FINE",$r['data_fine']);
        //caso in cui nessuna promozione è stata applicata
        if(count($res2)===0){
            $item->setContent("rimuovi_anchor",'<a style="pointer-events: none; opacity: 0.5;" class="btn btn-danger"> rimuovi </a>');
            $item->setContent("applica_anchor",'<a  href="adminPHP/adminPromozione.php?prom_id='.$idTmp.'" class="btn btn-primary ml-2"> Applica </a>');
            $item->setContent("cancella_anchor",'<a href="adminPHP/adminPromozione.php?cancella='.$idTmp.'" style="position: relative;right: 7px;" class="btn btn-danger">Cancella</a>');
            $item->setContent("modifica_anchor",'<a href="adminPHP/adminPromozione.php?modifica='.$idTmp.'" style="position: relative;right: 7px;" class="btn btn-primary ml-2">modifica</a>');

        }
        //caso in cui c'è una promozione applicata
        else{
            // questa è la promozione applicata
            if($idTmp===$res2[0]['id']){
                $item->setContent("rimuovi_anchor",'<a  href="adminPHP/adminPromozione.php?prom_idDlt='.$idTmp.'" class="btn btn-danger"> rimuovi </a>');
                $item->setContent("applica_anchor",'<a style="pointer-events: none; opacity: 0.5;" class="btn btn-primary ml-2"> Applica </a>');
                $item->setContent("cancella_anchor",'<a  style="position: relative;right: 7px;pointer-events: none; opacity: 0.5;" class="btn btn-danger">Cancella</a>');
            $item->setContent("modifica_anchor",'<a  style="position: relative;right: 7px;pointer-events: none; opacity: 0.5;" class="btn btn-primary ml-2">modifica</a>');
            }
            //queste sono promozioni non applicate
            else{
                $item->setContent("rimuovi_anchor",'<a style="pointer-events: none; opacity: 0.5;" class="btn btn-danger"> rimuovi </a>');
                $item->setContent("applica_anchor",'<a style="pointer-events: none; opacity: 0.5;" class="btn btn-primary ml-2"> Applica </a>');
                $item->setContent("cancella_anchor",'<a href="adminPHP/adminPromozione.php?cancella='.$idTmp.'" style="position: relative;right: 7px;" class="btn btn-danger">Cancella</a>');
                $item->setContent("modifica_anchor",'<a href="adminPHP/adminPromozione.php?modifica='.$idTmp.'" style="position: relative;right: 7px;" class="btn btn-primary ml-2">modifica</a>');
            }

        }
        $admin_container2->setContent('item',$item->get());
    }
    echo ($admin_container2->get());
}
}
//display error 403
else{
    $temp = new Template("../skins/template/dtml/error403.html");
    $temp->close();
  
  }
    