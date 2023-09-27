<?php
require "dbms.inc.php";
require "php-utils/utility.php";


function register($credenziali)
{
    global  $connessione;

    $nome = $credenziali[0];
    $cognome = $credenziali[1];
    $email = $credenziali[2];
    $password  = utility::cryptify($credenziali[3]); // crypt password


    $utente_exist = $connessione->query("SELECT * FROM Utente WHERE email = '$email'")->fetch_assoc();

    if ($utente_exist) {
        // utente gia esistente
        header("location: register.php?error=1");
    } else {
        $oid = $connessione->prepare("INSERT INTO Utente (`nome`, `cognome`, `email`, `password`)
                                    VALUES (?, ?, ?, ?)");
        $oid->bind_param("ssss", $nome, $cognome, $email, $password);

        if ($oid->execute()) {
            $last_id = mysqli_insert_id($connessione);   // ottieni l'id dell'utente appena inserito
            $user_data = $connessione->query("SELECT * FROM Utente 
                                  WHERE email = '$email'
                                  AND password = '$password'")->fetch_array(MYSQLI_ASSOC);
            if (!$user_data) {
                // qualcosa è andato storto 
                header("location: register.php?error=2");
                exit();
            }

            session_start();
            $_SESSION['utente'] = $user_data;
            $_SESSION['auth'] = true;

            addPermission($last_id, $credenziali[4]);
        }
    }
}

function addPermission($id_utente, $tipologia_utente)
{
    global $connessione;

    $oid = $connessione->prepare("INSERT INTO User_has_ugroup (`id_utente`, `id_ugroup`)
                                    VALUES (?, ?)");
    $oid->bind_param("ii", $id_utente, $tipologia_utente);

    if ($oid->execute()) {
        $user_data = $connessione->query("SELECT * FROM User_has_ugroup 
                                  WHERE id_utente = '$id_utente'
                                  AND id_ugroup = '$tipologia_utente'")->fetch_array(MYSQLI_ASSOC);
        if (!$user_data) {
            // qualcosa è andato storto 
            $delete_query = "DELETE FROM Utente WHERE id = ?";
            $delete_stmt = $connessione->prepare($delete_query);
            $delete_stmt->bind_param("i", $id_utente);
            if ($delete_stmt->execute()) {
                // l'utente è stato eliminato con successo
            } else {
                // si è verificato un errore durante l'eliminazione dell'utente
            }
            header("location: register.php?error=2");
            exit();
        }
    }
    // registrazione avvenuta con successo
    header("location: register.php?error=3");
}
