function changeCenterToCategoria(){
    $.ajax({
        url: "./adminPHP/adminCategoria.php",
        type: "POST",
        data: { valore: "" },
        success: function (response) {
            console.log("success");
           $(document).ready(function () {
              $("#adminSetCenter").html(response);
           });
        },
        error: function (xhr, status, error) {
           console.log("Si è verificato un errore durante l'invio della richiesta.");
        },
     });
    
}
function changeCenterToMarca(){
    $.ajax({
        url: "./adminPHP/adminMarche.php",
        type: "POST",
        data: { valore: "" },
        success: function (response) {
         console.log("success");
           $(document).ready(function () {
              $("#adminSetCenter").html(response);
           });
        },
        error: function (xhr, status, error) {
           console.log("Si è verificato un errore durante l'invio della richiesta.");
        },
     });
    
}
function changeCenterToPromozioni(){
    $.ajax({
        url: "./adminPHP/adminPromozione.php",
        type: "POST",
        data: { valore: "" },
        success: function (response) {
         console.log("success");
           $(document).ready(function () {
              $("#adminSetCenter").html(response);
           });
        },
        error: function (xhr, status, error) {
           console.log("Si è verificato un errore durante l'invio della richiesta.");
        },
     });
    
}
function changeCenterToAssistenza(){
    $.ajax({
        url: "./adminPHP/adminAssistenza.php",
        type: "POST",
        data: { valore: "" },
        success: function (response) {
         console.log("success");
           $(document).ready(function () {
              $("#adminSetCenter").html(response);
           });
        },
        error: function (xhr, status, error) {
           console.log("Si è verificato un errore durante l'invio della richiesta.");
        },
     });
    
}