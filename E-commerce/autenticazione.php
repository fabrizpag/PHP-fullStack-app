<?php

require_once('include/auth.inc.php');

// serve praticamente quando premo su "prova login" --> in teoria dovrebbe essere un bottone con l'icona del profilo
// se sei già loggato ti dovrebbe portare a una schermata X (ancora non implementato)
// se non sei ancora loggato ti porta alla pagina del login (implementato)


// se ti chiedi perché fare un file cos' inutile, perché se da Index_v2 al posto di mettere "autenticazione.php" metto il 
// percorso di "include/auth.inc.php" non mi trova la pagina.
// nell'URL non ci mette localhost/ecommerce/...    ma ci mette localhost/auth.inc.php che non esiste !!!
