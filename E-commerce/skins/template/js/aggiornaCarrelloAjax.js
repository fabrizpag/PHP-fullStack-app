
function aggiornaDB(id,qt,tg){
    console.log("dentro aggiorna carrello");
    dataT = {id_prodotto:id,quantita:qt,taglia:tg};
    $.ajax({
        url: "./include/php-utils/modify-to-cart.php",
        type: "POST",
        data: { valore: dataT },
        success: function (response) {
           // console.log(response);
        },
        error: function (xhr, status, error) {
           console.log("Si è verificato un errore durante l'invio della richiesta.");
        },
     });

}