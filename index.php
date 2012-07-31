<?php
require("globals.php");
require_once("phputils/CocoasUser.class.php");
session_start();

if(isset($_SESSION["user"]) && $_SESSION["user"]->roleName!='invalid')
{
        if(isset($GLOBALS["PRIVATE_VIEW"]) && $GLOBALS["PRIVATE_VIEW"]!='')
                header("Location: ".$GLOBALS["baseURL"]."".$GLOBALS["PRIVATE_VIEW"]);
        else
        {
                if($GLOBALS["DEBUG_MODE"]) die('Debes definir la vista por defecto en globals.php (PRIVATE_VIEW)');
        }
}
else
{
        if(isset($GLOBALS["DEFAULT_VIEW"]) && $GLOBALS["DEFAULT_VIEW"]!='')
                header("Location: ".$GLOBALS["baseURL"]."".$GLOBALS["DEFAULT_VIEW"]);
        else if(isset($GLOBALS["DEFAULT_PANEL"]) && $GLOBALS["DEFAULT_PANEL"]!='')
                header("Location: ".$GLOBALS["baseURL"]."panel/".$GLOBALS["DEFAULT_PANEL"]);
        else
        {
                if($GLOBALS["DEBUG_MODE"]) die('Debes definir la vista por defecto en globals.php (DEFAULT_VIEW, DEFAULT_PANEL)');
        }
}
?>