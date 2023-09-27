<?php

const BLANK_T = "";

require "include/template2.inc.php";
require "include/register.inc.php";

session_start();

if (isset($_SESSION['auth']) && $_SESSION['auth']) {
    header('location: profile.php');
}


$main = new Template("skins/template/dtml/index_v2.html");
$register = new Template("skins/template/register.html");

// tiene aggiornato il numero di oggetti presenti nel carrello
require "include/php-utils/preferiti_carrello.php";



if (isset($_GET['error'])) {
    require_once "include/php-utils/alert.php";
    switch ($_GET['error']) {
        case 1:
            Alert::OpenAlert("Utente già esistente !", "login.php");
            break;
        case 2:
            Alert::OpenAlert("Ops! qualcosa è andato storto :(", "login.php");
            break;
        case 3:
            require "include/php-utils/trasferimento_dati_sessione.php";
            Alert::OpenAlert("Registrazione avvenuta con successo", "login.php");
            break;
    }
    $register->setContent("error", $error);
} else {
    $register->setContent("error", BLANK_T);
}

$main->setContent('body', $register->get());

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['nome']) ||
        isset($_POST['cognome']) ||
        isset($_POST['email']) ||
        isset($_POST['password'])
    ) {
        register([$_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['password'], 2]); // 2 è il tipo di utente User
    }
}

$main->close();
