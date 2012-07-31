<?php

// bootstrap.php

/**
 * Bootstrap Doctrine.php, register autoloader specify
 * configuration attributes and load models.
 */

require_once('plugins/Doctrine/lib/Doctrine.php');

spl_autoload_register(array('Doctrine', 'autoload'));

$dsn = 'mysql:dbname='.$GLOBALS["dbName"].';host='.$GLOBALS["dbServer"];
$dbName = $GLOBALS["dbName"];
$dbServer = $GLOBALS["dbServer"];
$user = $GLOBALS["dbUser"];
$password = $GLOBALS["dbPassword"];

if(!empty($dbName) && !empty($dbServer))
{

	//Conexion PDO entre PHP y MySQL
	$conn = Doctrine_Manager::connection('mysql://'.$user.':'.$password.'@'.$dbServer.'/'.$dbName, $GLOBALS["connectionName"]);

	//Para decirle a doctrine el usuario y pass
	$conn->setOption('username', $user);
	$conn->setOption('password', $password);
	$conn->setCharset('utf8');
	
	$manager = Doctrine_Manager::getInstance();
	
	$manager->setCharset('latin1');
	$manager->setCollate('latin1_spanish_ci');
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
	if(file_exists('model/generated')) Doctrine::loadModels('model/generated');
	else if($GLOBALS["debugMode"]) echo "No se ha encontrador el directorio 'model/generated'";

	if(file_exists('model')) Doctrine::loadModels('model');
	else if($GLOBALS["debugMode"]) echo "No se ha encontrador el directorio 'model'";

}
?>