<?php

require "../template2.inc.php";
require "../dbms.inc.php";
// alert


session_start();

global $connessione;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon = $_POST['coupon'];
    $response;

    $res = $connessione->query("SELECT * FROM Coupon WHERE codice_coupon = '{$coupon}' LIMIT 1")->fetch_all(MYSQLI_ASSOC);
    if (empty($res)) {
        // Coupon non valido
        $response = array(
            "success" => false,
            "messaggio" => 'Coupon non valido'
        );
    } else {
        // Coupon valido
        // $response = array('success' => true, 'message' => 'Coupon valido', 'dati' => array('id_coupon' => $res[0]['id'], 'sconto_percentuale' => $res[0]['sconto_percentuale']));
        $response = array(
            "success" => true,
            "messaggio" => 'Coupon valido',
            'dati' => array('id_coupon' => $res[0]['id'], 'sconto_percentuale' => $res[0]['sconto_percentuale'])
        );
    }

    header("Content-Type: application/json");
    echo json_encode($response);
}
