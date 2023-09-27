<?php


require "../include/template2.inc.php";
require "../include/dbms.inc.php";
global $connessione;
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin']) {
 //display form per la modifica della marca
 if(isset($_REQUEST['mc_id'])){
    $idC = $_REQUEST['mc_id'];
    $form = new Template("../skins/template/adminModificaCat_Mar.html");
    $form->setContent("ACTION_FORM","adminMarche.php");
    $form->setContent("CAT_MAR","marca");
    $form->setContent("NAME_FIELD","formMarca");
    $form->setContent("id_mc",$_REQUEST['mc_id']);

    $res = $connessione->query("SELECT * FROM Marca WHERE id ='$idC' ")->fetch_all(MYSQLI_ASSOC);
    $form->setContent("DEF_VAL",$res[0]['nome_marca']);

    $form ->close();


}
//modifica effettuata 
if(isset($_POST['formMarca'])){
  $nome_mar = $_POST['formMarca'];
  $idC = $_POST['formId'];
  $connessione->query("UPDATE Marca SET nome_marca = '$nome_mar' WHERE id = '$idC'");
  header("location:http://localhost/E-commerce/admin.php");
  exit();

}
// display aggiungi una nuova marca
if(isset($_GET['add'])){
  $add_template = new Template ("../skins/template/adminAggiungiCat_Mar.html");
  $add_template->setContent("CAT_MAR", "aggiungi una nuova marca");
  $add_template->setContent("NAME_FIELD","formAddMarca");
  $add_template->setContent("ACTION_FORM","adminMarche.php");
  $add_template->setContent("errore","");
  $add_template->close();

}
//aggiunta nuova marca
if(isset($_POST['formAddMarca'])){
  $nuovaMarca = $_POST['formAddMarca'];
  $res = $connessione->query("SELECT * FROM Marca WHERE nome_marca = '$nuovaMarca'")->fetch_all(MYSQLI_ASSOC); 
  if (count($res)===0){
    $connessione->query("INSERT INTO Marca (nome_marca) VALUES ('$nuovaMarca')");
    header("location:http://localhost/E-commerce/admin.php");
    exit();
  }else{
    // caso in cui l'elemento già esiste
    $add_template = new Template ("../skins/template/adminAggiungiCat_Mar.html");
    $add_template->setContent("CAT_MAT", "aggiungi una nuova marca");
    $add_template->setContent("NAME_FIELD","formAddMarca");
    $add_template->setContent("ACTION_FORM","adminMarche.php");
    $add_template->setContent("errore",' <label style="color: red;">marca già esistente</label>');
    $add_template->close();
  }

}
// cancellazione di una marca
if(isset($_GET['deleteMC_id'])){
  $idToDel = $_GET['deleteMC_id'];
  $connessione->query("DELETE FROM Marca WHERE id = $idToDel");
  header("location:http://localhost/E-commerce/admin.php");
    exit();

}


 
// display lista marche
if( (!isset($_REQUEST['mc_id']))&&(!isset($_POST['NAME_FIELD']))&&(!isset($_GET['add']))&&(!isset($_POST['formAddMarca']))&&(!isset($_GET['deleteMC_id'])) ){
    $admin_container = new Template("../skins/template/adminContainer2.html");
    $admin_container->setContent('elemento',"una nuova marca");
    $admin_container->setContent("HREF","adminPHP/adminMarche.php?add=1");
    $res = $connessione->query("SELECT * FROM Marca")->fetch_all(MYSQLI_ASSOC); 
    foreach($res as $r){
        $item = new Template ("../skins/template/dtml/dtml_items/marca_categoriaAdminIteam.html");
        $item->setContent("MARCA_CATEGORIA", $r['nome_marca']);
        $item->setContent("ID_MC", $r['id']);
        $item->setContent("FILE","adminMarche");
        $tmpid = $r['id'];
        $q = $connessione->query("SELECT * FROM Prodotto WHERE id_marca = $tmpid")->fetch_all(MYSQLI_ASSOC); 
        //caso in cui nessun prodotto appartiene alla categoria
        if(count($q)===0){
          $str1='<a href="adminPHP/adminMarche.php?deleteMC_id=';
          $str2='" class="btn btn-danger">Cancella</a>';
          $str3=$str1.$tmpid.$str2;
          $item->setContent("delete",$str3);
        }
        //caso in cui almeno un prodotto appartiene alla categoria
        else{
          $item->setContent("delete",'<a style="pointer-events: none; opacity: 0.5;" class="btn btn-danger">Cancella</a>');
        }
        $admin_container->setContent('item',$item->get());
    }
    $admin_container ->close();
}

}
//display error 403
else{
  $temp = new Template("../skins/template/dtml/error403.html");
  $temp->close();

}
