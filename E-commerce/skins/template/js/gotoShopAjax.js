function gotoShop(genere){
    let arrCategoria = [];
    let arrGenere = [];
    let arrMarca = [];
    let arrPrezzo = [];
    let changePage = 1;
    size = "U";
    arrCategoria.push(-1);
    arrGenere.push(genere);
    arrMarca.push(-1);
    min = "$5"; max = "$1000";arrPrezzo.push(min);arrPrezzo.push(max);
    dataT = { arrCategoria: arrCategoria, arrGenere: arrGenere, arrMarca: arrMarca, arrPrezzo: arrPrezzo, size, changePage };
        $.ajax({
           url: "shop.php",
           type: "POST",
           data: { valore: dataT},
           success: function (response) {
            $(document).ready(function () {
                response = addScript(response);
                $("#index_v2Body").html(response);
             });
           },
           error: function (xhr, status, error) {
              console.log("Si è verificato un errore durante l'invio della richiesta.");
           },
        });     

}




function addScript(response){
    string = `<script src="skins/template/js/jquery-3.3.1.min.js"></script>
    <script src="skins/template/js/bootstrap.min.js"></script>
    <script src="skins/template/js/jquery-ui.min.js"></script>
    <script src="skins/template/js/jquery.countdown.min.js"></script>
    <script src="skins/template/js/jquery.nice-select.min.js"></script>
    <script src="skins/template/js/jquery.zoom.min.js"></script>
    <script src="skins/template/js/jquery.dd.min.js"></script>
    <script src="skins/template/js/jquery.slicknav.js"></script>
    <script src="skins/template/js/owl.carousel.min.js"></script>
    <script src="skins/template/js/main.js"></script>`;
    response = response + string;
    return response;

}