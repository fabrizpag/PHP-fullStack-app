<?php

require "../dbms.inc.php";
session_start();

// IMPOSTAZIONE DELLA TAGLIA
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tagliatmp = $_POST['valore'];
    $product_id = $_POST['valore2'];

    $str1 = '<h6 class="pd-tags" style="margin-left: 20px">disponibili: (<span class="Q_value">';
    $q = 0;
    $str2 = '</span>)</h6></div>';

    $res = $connessione->query("SELECT quantita FROM Magazzino WHERE id_prodotto = {$product_id} AND taglia = '{$tagliatmp}'")->fetch_all(MYSQLI_ASSOC);
    if (!empty($res)) {
        $q = $res[0]['quantita'];
    }

    $stringData = $str1 . $q . $str2;
    echo $stringData;
}
