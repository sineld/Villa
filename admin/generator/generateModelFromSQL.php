<?php
/*
Este archivo se incova cuando se desea generar una libreria de clases Data Transfer Objects
a partir de una base de datos dada
*/

require_once('../../globals.php');

require_once('../../plugins/Doctrine/lib/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));

$dsn = 'mysql:dbname='.$GLOBALS["dbName"].';host='.$GLOBALS["dbServer"];
$dbName = $GLOBALS["dbName"];
$dbServer = $GLOBALS["dbServer"];
$user = $GLOBALS["dbUser"];
$password = $GLOBALS["dbPassword"];

//Conexion PDO entre PHP y MySQL
$conn = Doctrine_Manager::connection('mysql://'.$user.':'.$password.'@'.$dbServer.'/'.$dbName, $GLOBALS["dbName"]);

//Para decirle a doctrine el usuario y pass
$conn->setOption('username', $user);
$conn->setOption('password', $password);

$manager = Doctrine_Manager::getInstance();

//Le digo a doctrine que exporte constrains, tablas y todo lo que pueda
$manager->setAttribute(Doctrine::ATTR_EXPORT, Doctrine::EXPORT_ALL);

//Le digo a doctrine que realice todas las validaciones de integridad: valores nulos, constrains, etc.
$manager->setAttribute(Doctrine::ATTR_VALIDATE, Doctrine::VALIDATE_ALL);


//Permito los override en las clases
$manager->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, true);


if($GLOBALS["BDLazyMode"])
	$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_CONSERVATIVE);
else
 	$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_AGGRESSIVE);

//Cargo todo el modelo
if(file_exists('../../model/generated')) Doctrine::loadModels('../../model/generated');
else if($GLOBALS["debugMode"]) echo "No se ha encontrador el directorio 'model/generated'";

if(file_exists('../../model')) Doctrine::loadModels('../../model');
else if($GLOBALS["debugMode"]) echo "No se ha encontrador el directorio 'model'";

//Debo hace un aggressive loadin de manera obligarotia, sino no se cargan todas las clases
$manager->setAttribute(Doctrine::ATTR_MODEL_LOADING, Doctrine::MODEL_LOADING_AGGRESSIVE);

Doctrine::generateModelsFromDb('../../model', array($GLOBALS["dbName"]), array('generateTableClasses' => true));

?>