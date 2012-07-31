<?php

$dbhost = $GLOBALS["dbServer"];  // host del MySQL (generalmente localhost)
$dbusuario = $GLOBALS["dbUser"]; // aqui debes ingresar el nombre de usuario
				// para acceder a la base
$dbpassword = $GLOBALS["dbPassword"]; // password de acceso para el usuario de la
               // linea anterior
$db = $GLOBALS["dbName"];// Seleccionamos la base con la cual trabajar


if(strlen($dbhost)>0 && strlen($dbusuario)>0)				
	$conexion = mysql_connect($dbhost,$dbusuario,$dbpassword) or die('No se a podido conectar a mysql: ' . mysql_error());

if(strlen($db)>0 && isset($conexion))
	mysql_select_db($db, $conexion) or die('No se ha encontrado la base de datos '.$db);
					
//selecci�n de la base de datos con la que vamos a trabaja


?>