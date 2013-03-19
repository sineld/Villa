<?php
require_once('globals.php');
require_once('public_action/articulos_handler.php');
$prueba = new articulos_handler;
echo $prueba->busqueda_Articulos($_REQUEST['term']);
?>
