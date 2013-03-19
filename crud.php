<?php
require_once("globals.php");
header('Content-type: text/html; charset=utf-8');
if($GLOBALS["debugMode"])
{
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
require_once("bootstrap.php");
require_once("phputils/xmlParser.php");
require_once("phputils/Url.class.php");
require_once('manager/error.manager.php');
require_once("manager/crud.manager.php");
require_once("phputils/CocoasUser.class.php");
require_once("manager/user.manager.php");
session_name($GLOBALS["canisSessionName"]);
session_start();

if (!$GLOBALS["debugMode"])
{
	error_reporting(E_ALL ^ E_NOTICE);
}

//---------------------------------------------------------------------
//Este es el director de orquesta del framework, hace los enlaces
//entre las diferentes capas de la aplicacion
//---------------------------------------------------------------------

//Carga de el archivo binding.xml
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

//---------------------------------------------------------------------

//Si no hay usuario seteo el usuario

if(!isset($_SESSION['user']))
{
	$_SESSION['user'] = new CocoasUser();
}

//Empiezo la carga de la informacion dentro de las vistas

$crudManager = new CRUDManager($_SESSION['CANIS_BINDINGS'],$_SESSION['CANIS_ROLES']);

//---------------------------------------------------------------------

if (isset($_REQUEST['action']))
{
	if (isset($_REQUEST['view']))
	{
		$view = $_REQUEST['view'];
	}
	else if (isset($_SERVER['HTTP_REFERER']))
	{
		$urlString = parse_url($_SERVER['HTTP_REFERER']);
		$urlParts = explode('/', $urlString["path"]);
		$view = array_pop($urlParts);
	}

	$action = $_REQUEST['action'];

	if (!isset($view) && !isset($panel))
	{
		if (isset($_REQUEST['view']))
		{
			$view = $_REQUEST['view'];
		}
		else if(isset($_REQUEST['panel']))
		{
			$view = $_REQUEST['panel'];
		}
		else if($GLOBALS["debugMode"])
		{
			die('Tu navegador no soporta HTTP_REFERER, Debes especificar la vista o panel en la transaccion action='.$action);
		}
	}
	$crudManager->excecuteTransaction($view,$action);
}
else if(isset($_REQUEST['autenticate']))
{
	$userManager = new UserManager();
	$userManager->loginUser();

	if($_SESSION['user']->status!='invalid')
	{
		if ($_SESSION['user']->roleName=='manager')
		{

			echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["MANAGER_VIEW"].'";</script>';
		}
		else if($_SESSION['user']->roleName=='resident' || $_SESSION['user']->roleName=='condo' || $_SESSION['user']->roleName=='applicant')
		{

			if(!isset($_SESSION["pendingCanisViewRequest"]) || $_SESSION["pendingCanisViewRequest"]=='')
			{
		   		echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["PRIVATE_VIEW"].'";</script>';
			}
		   	else if(isset($_SESSION["pendingCanisViewRequest"]) && $_SESSION["pendingCanisViewRequest"]!='')
		   	{
				$fowaerd = $_SESSION["pendingCanisViewRequest"];
				unset($_SESSION["pendingCanisViewRequest"]);
		   		echo '<script language="JavaScript1.1">window.location="'.$fowaerd.'";</script>';
		   		//echo "redireccionando a '".$fowaerd."'";
				//echo $fowaerd;
		   	}
			else
			{
		   		echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["PRIVATE_VIEW"].'";</script>';
			}
		}
		else if($_SESSION['user']->roleName=='houseresident')
		{

			if(empty($_SESSION["pendingCanisViewRequest"]))
		   		echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["HOUSE_HOME"].'";</script>';
		   	else
		   	{
				$fowaerd = $_SESSION["pendingCanisViewRequest"];
				unset($_SESSION["pendingCanisViewRequest"]);
		   		echo '<script language="JavaScript1.1">window.location="'.$fowaerd.'";</script>';
		   	}
		}
		else if($_SESSION['user']->roleName == 'admin')
		{
			echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["PRIVATE_VIEW"].'";</script>';
		
		}else if($_SESSION['user']->roleName == 'cliente'){
			
			echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["DEFAULT_VIEW"].'";</script>';
		}else if($_SESSION['user']->roleName == 'vendedor'){
			
			echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["DEFAULT_VIEW"].'";</script>';
		}
		else
			echo "Role desconocido o invalido '".$_SESSION['user']->roleName."'";
	}
    else
    {
		/*if($_REQUEST['view']!="login")
		{
			//$_SESSION["pendingCanisViewRequest"] = curPageURL();
			echo '<script language="JavaScript1.1">window.location="'.$GLOBALS["baseURL"].''.$GLOBALS["LOGIN_VIEW"].'";</script>';
		}*/
    		echo "Correo o contrase&ntilde;a inv&aacute;lidos.";
    }

	exit();
}
else if(isset($_REQUEST['close_session']))
{
	$userManager = new UserManager();
	$userManager->closeSession();
	header('Location: index.php');
	exit();
}
else if (isset($_REQUEST['public_action']))
{
	if (file_exists('public_action/'.$_REQUEST['public_action'].".php"))
	{
		require('public_action/'.$_REQUEST['public_action'].".php");
	}
	else if ($GLOBALS["debugMode"])
	{
		die('No se ha encontro la accion publica '.$_REQUEST['public_action']);
	}
}

else
{
	if ($GLOBALS["debugMode"])
	{
		die("Debes especificar una accion 'action' dentro de el formulario");
	}
	else
	{
		header("HTTP/1.0 404 Not Found");
	}
}

function curPageURL()
{
	$pageURL = 'http';
	if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
		$pageURL .= "s";

	$pageURL .= "://";
	if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80")
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];

	//die($pageURL);
	return $pageURL;
}

?>
