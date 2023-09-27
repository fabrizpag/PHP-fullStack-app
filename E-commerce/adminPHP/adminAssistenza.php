<?php
require "../include/template2.inc.php";
require "../include/dbms.inc.php";
global $connessione;
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin']) {
// display per la form di risposta
if (isset($_GET['r'])) {
    $domanda = $_GET['r'];
    $id_u = $_GET['i'];
    $idRischiesta = $_GET['a'];
    $res = $connessione->query("SELECT * FROM Utente WHERE id = '$id_u'")->fetch_all(MYSQLI_ASSOC);
    $nome_utente = $res[0]['nome'];
    $temp = new Template("../skins/template/adminAssistenzaRisposta.html");
    $temp->setContent("NOME_UTENTE", $nome_utente);
    $temp->setContent("DOMANDA", $domanda);
    $temp->setContent("ID_A", $idRischiesta);
    $temp->close();
}
// risposta e redirect
if (isset($_POST['formRisposta'])) {
    $idRischiestaAss = $_POST['formIda'];
    $risposta = $_POST['formRisposta'];
    $connessione->query("UPDATE Messaggio_Assistenza SET risposta = '$risposta' WHERE id = '$idRischiestaAss'");
    header("location:http://localhost/E-commerce/admin.php");
    exit();
}

// display lista richieste aperte 
if ((!isset($_GET['r'])) && (!isset($_POST['formRisposta']))) {
    $admin_container3 = new Template("../skins/template/adminContainer3.html");
    $res = $connessione->query("SELECT * FROM Messaggio_Assistenza WHERE risposta IS NULL")->fetch_all(MYSQLI_ASSOC);
    foreach ($res as $r) {
        $item = new Template("../skins/template/dtml/dtml_items/assistenzaAdminItem.html");
        $idUtente = $r['id_utente'];
        $res2 = $connessione->query("SELECT * FROM Utente WHERE id = '$idUtente'")->fetch_all(MYSQLI_ASSOC);
        $item->setContent("NOME_UTENTE", $res2[0]['nome']);
        $item->setContent("RICHIESTA", $r['richiesta']);
        $item->setContent("ID", $idUtente);
        $item->setContent("ID_R", $r['id']);
        $admin_container3->setContent("item", $item->get());
    }
    $admin_container3->close();
}
}
//display error 403
else{
    $temp = new Template("../skins/template/dtml/error403.html");
    $temp->close();
  
  }