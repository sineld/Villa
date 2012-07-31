<?php

////////////////////////////////////////////////
// GLOBAL DEFINITIONS OF THE PROYECT          //
////////////////////////////////////////////////

/*--------THIS ARE THE MANDATORY DEFINITIONS----------*/

$GLOBALS["LOGIN_VIEW"]    = "inicio";
$GLOBALS["PRIVATE_VIEW"]  = "administracion";
$GLOBALS["DEFAULT_VIEW"]  = "inicio";  //The logic name for the deault view that has to be showed in the start page
$GLOBALS["DEFAULT_PANEL"] = "";
$GLOBALS["title"]         = "Importadora La Villa de las Mascotas, C.A.";

$GLOBALS["DEFAULT_USER"] = "anonymus";

/*--------END OF THE MANDATORY DEFINITIONS----------*/


/*--------THIS ARE THE SECURITY DEFINITIONS----------*/

$GLOBALS["DEFAULT_ROLE"]    = "invalid";
$GLOBALS["DEFAULT_ROLE_ID"] = "1";
$GLOBALS["PENDING_VIEW"]    = "validate";
$GLOBALS["canisSessionName"] = "usuarioVilla";

/*--------END OF THE MANDATORY DEFINITIONS----------*/

/*--------THIS ARE THE MYSQL CONECTION DEFINITIONS----------*/
//

$GLOBALS["connectionName"] = "villadel_villa";
$GLOBALS["dbName"]     = "villadel_villa";
$GLOBALS["dbServer"]   = "127.0.0.1";
$GLOBALS["dbUser"]     = "villadel_villa";
$GLOBALS["dbPassword"] = "cs158964";
$GLOBALS["doctrineConnection"] = "";

/*--------END OF THE MYSQL CONECTION DEFINITIONS----------*/

/*--------THIS ARE THE OPTIONAL DEFINITIONS----------*/

//Here are the global var to edit the keywords, description and language metatags of the proyect

$GLOBALS["keywords"]    = "mascotas, villa, perro, gato, animales, accesorios, comedero, bebedero";
$GLOBALS["description"] = "Sitio web de La Villa de las Mascotas. Importadora La Villa de las Mascotas C.A.";
$GLOBALS["language"]    = "spanish";

//for developing use, if you set this var to "true" errors and aditional information will be showed

$GLOBALS["showViewHierarchy"] = false;
$GLOBALS["showRoleHierarchy"] = false;
$GLOBALS["BDLazyMode"]        = false;

/*--------END OF THE OPTIONAL DEFINITIONS----------*/

/*--------THIS ARE THE ERROR REPORTING DEFINITIONS----------*/

$GLOBALS["debugMode"]  = false;
$GLOBALS["logErrors"]  = false;
$GLOBALS["mailErrors"] = false;

$GLOBALS["error_mailHost"]     = "mail.domain.com";
$GLOBALS["error_mailUserName"] = "user+domain.com";
$GLOBALS["error_mailPasswors"] = "password";
//$GLOBALS["error_mailAccount"]  = "cesarvilera8602@gmail.com";

/*--------FRENDLY URL DEFINITIONS----------*/

$GLOBALS["baseCountry"] = "ahJkZXZlbG9wZXJzLXNvY2lldHNyEgsSDENhdGFsb2dWYWx1ZRgjDA";
$GLOBALS["frendlyURL"] = true;
//$GLOBALS["baseURL"] = "http://localhost:3002/EmpleoSTCWEB/Canis/";
//$GLOBALS["baseURL"] = "http://pruebas.stcsolutions.com.ve/empleo/Canis/";

$GLOBALS["baseURL"] = "http://www.villadelasmascotas.com/";



?>
