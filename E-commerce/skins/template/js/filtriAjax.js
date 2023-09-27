function inviaRichiestaFil(arrCategoria, arrGenere, arrMarca, arrPrezzo, size) {
   if (arrCategoria.length === 0) {
      arrCategoria.push(-1);
   }
   if (arrGenere.length === 0) {
      arrGenere.push(-1);
   }
   if (arrMarca.length === 0) {
      arrMarca.push(-1);
   }
   if (size === undefined) {
      size = "U";
   }
   dataT = { arrCategoria: arrCategoria, arrGenere: arrGenere, arrMarca: arrMarca, arrPrezzo: arrPrezzo, size };
   $.ajax({
      url: "shop.php",
      type: "POST",
      data: { valore: dataT },
      success: function (response) {
         $(document).ready(function () {
            $("#divItems").html(response);
         });
      },
      error: function (xhr, status, error) {
         console.log("Si è verificato un errore durante l'invio della richiesta.");
      },
   });
}

function inviaRichiestaFilAdmint(arrCategoria, arrGenere, arrMarca, arrPrezzo, size) {
   if (arrCategoria.length === 0) {
      arrCategoria.push(-1);
   }
   if (arrGenere.length === 0) {
      arrGenere.push(-1);
   }
   if (arrMarca.length === 0) {
      arrMarca.push(-1);
   }
   if (size === undefined) {
      size = "U";
   }
   dataT = { arrCategoria: arrCategoria, arrGenere: arrGenere, arrMarca: arrMarca, arrPrezzo: arrPrezzo, size };
   $.ajax({
      url: "admin.php",
      type: "POST",
      data: { valore: dataT },
      success: function (response) {
         response = '<a style="font-size: 20px;" class="d-flex justify-content-center btn btn-primary">aggiungi un nuovo prodotto</a>' + response;
         $(document).ready(function () {
            $("#divAdminContProductItems").html(response);
         });
      },
      error: function (xhr, status, error) {
         console.log("Si è verificato un errore durante l'invio della richiesta.");
      },
   });
}
