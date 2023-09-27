<?php
require "../include/template2.inc.php";
require "../include/dbms.inc.php";

require_once "../include/php-utils/global.php";
// variabile _IMG_PATH  skins/template/img/        products/nomeimg.png

global $connessione;
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin']) {
//display della form per l'aggiunta di un prodotto
if(isset($_GET['add'])){
    $template = new Template("../skins/template/adminAddProdotto.html");

    //popola  la categoria
    $res = $connessione->query("SELECT * FROM Categoria")->fetch_all(MYSQLI_ASSOC);
    $tot = "";
    foreach($res as $r){
        $str = $r['nome_categoria'];
        $str = '<option value="'.$str.'">'.$str."</option>";
        $tot = $tot.$str;
    }
    $template->setContent("select_categoria",$tot);
    //popola le marche
    $res = $connessione->query("SELECT * FROM Marca")->fetch_all(MYSQLI_ASSOC);
    $tot = "";
    foreach($res as $r){
        $str = $r['nome_marca'];
        $str = '<option value="'.$str.'">'.$str."</option>";
        $tot = $tot.$str;
    }
    $template->setContent("select_marca",$tot);
    $template->setContent("error1","");
    $template->setContent("error2","");
    $template->setContent("error3","0");

    $template->close();

}
if(isset($_POST['formCategoria'])){
    //caso di form non completa
    if( ($_POST['formNome']==="") || ($_POST['formDescrizione']==="") || ($_POST['formPrezzo']==="0") ){
        $template = new Template("../skins/template/adminAddProdotto.html");

    //popola  la categoria
    $res = $connessione->query("SELECT * FROM Categoria")->fetch_all(MYSQLI_ASSOC);
    $tot = "";
    foreach($res as $r){
        $str = $r['nome_categoria'];
        $str = '<option value="'.$str.'">'.$str."</option>";
        $tot = $tot.$str;
    }
    $template->setContent("select_categoria",$tot);
    //popola le marche
    $res = $connessione->query("SELECT * FROM Marca")->fetch_all(MYSQLI_ASSOC);
    $tot = "";
    foreach($res as $r){
        $str = $r['nome_marca'];
        $str = '<option value="'.$str.'">'.$str."</option>";
        $tot = $tot.$str;
    }
    $template->setContent("select_marca",$tot);
    $template->setContent("error1","campo obbligatorio");
    $template->setContent("error2","campo obbligatorio");
    $template->setContent("error3","1");

    $template->close();

    }

    //caso di form completa
    else{
        $formCategoria = $_POST['formCategoria']; 
        $formMarca = $_POST['formMarca'];
        $formNome = $_POST['formNome'];
        $formDescrizione = $_POST['formDescrizione'];
        $formPrezzo = $_POST['formPrezzo'];
        $formGenere = $_POST['formGenere'];
        $formTagliaS = $_POST['formTagliaS'];
        $formTagliaM = $_POST['formTagliaM'];
        $formTagliaL = $_POST['formTagliaL'];
        $formTagliaXL = $_POST['formTagliaXL'];

        $connessione->query("INSERT INTO Prodotto (nome_prodotto, descrizione, prezzo, genere, id_categoria, id_marca, id_promozione)
        VALUES ('$formNome', '$formDescrizione', $formPrezzo, '$formGenere', 
        (SELECT id FROM Categoria WHERE nome_categoria = '$formCategoria'),
        (SELECT id FROM Marca WHERE nome_marca = '$formMarca'), NULL);");

        $ultimoID = mysqli_insert_id($connessione);
        
        $connessione->query( "INSERT INTO Magazzino (id_prodotto, taglia, quantita)
        VALUES ('$ultimoID', 'S',  '$formTagliaS')");
          
        $connessione->query( "INSERT INTO Magazzino (id_prodotto, taglia, quantita)
        VALUES ('$ultimoID', 'M',  '$formTagliaM')");
            
        $connessione->query( "INSERT INTO Magazzino (id_prodotto, taglia, quantita)
        VALUES ('$ultimoID', 'L',  '$formTagliaL')");
          
        $connessione->query( "INSERT INTO Magazzino (id_prodotto, taglia, quantita)
        VALUES ('$ultimoID', 'XL',  '$formTagliaXL')");

        header("location:http://localhost/E-commerce/adminPHP/adminImmaginiProdotto.php?img=$ultimoID&no=1");
        exit();
       
       
        
    }

}
}
//display error 403
else{
    $temp = new Template("../skins/template/dtml/error403.html");
    $temp->close();
  
  }






 
