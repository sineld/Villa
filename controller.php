<?php
require_once("globals.php");
if(!$GLOBALS["debugMode"])
{
	error_reporting(E_ALL ^ E_NOTICE);
}
else
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
require_once("bootstrap.php");
require_once("phputils/xmlParser.php");
require_once("phputils/CocoasUser.class.php");
require_once('manager/error.manager.php');
require_once("manager/template.manager.php");
require_once("manager/info.manager.php");
require_once("manager/user.manager.php");
require_once("manager/catalog.manager.php");
session_name($GLOBALS["canisSessionName"]);
session_start();

//////////////////////////////////////////////////
//Este es el director de orquesta del framework, hace los enlaces
//entre las diferentes capas de la aplicacion
//////////////////////////////////////////////////

//////////Carga de el archivo binding.xml////////////
if($GLOBALS["debugMode"] || empty($_SESSION['CANIS_ROLES']))
{
	$file_name="binding.xml";
	$parser = new xmlParser();
	$contents = file_get_contents($file_name);//Or however you what it
	$_SESSION['CANIS_BINDINGS'] = $parser->xml2array($contents,1,'attribute');
}

if($GLOBALS["debugMode"] || empty($_SESSION['CANIS_ROLES']))
{
	$file_name="roles.xml";
	$parser = new xmlParser();
	$contents = file_get_contents($file_name);//Or however you what it
	$_SESSION['CANIS_ROLES'] = $parser->xml2array($contents,1,'attribute');
}
///////////////////////////////////////////////////////////////

if(!isset($_SESSION['user']))
{
	$_SESSION['user'] = new CocoasUser();
}
///////////Empiezo la carga de la informacion dentro de las vistas////////////
$infoManager = new InfoManager($_SESSION['CANIS_BINDINGS'],$_SESSION['CANIS_ROLES']);
$infoManager->setTemplate("default");
//////////////////////////////////////////////////////////////////////////////


if(isset($_REQUEST['view']))
{
	$infoManager->get($_REQUEST['view'],'view');
}
else if(isset($_REQUEST['panel']))
{

	$infoManager->get($_REQUEST['panel'],'panel');
}
else
{
	//$infoManager->get('','error');
}
?>