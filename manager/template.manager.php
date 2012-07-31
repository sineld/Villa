<?php

//////////////////////////////////////////////////
//Esta clase se encarga de renderizar la view deseada
//////////////////////////////////////////////////

class TemplateManager
{

	var $template = "";
	var $bindings = "";
	var $viewDirectory = "view/";
	var $panelDirectory = "view/";
	private $errors;

	public function TemplateManager($bindings)
	{
		$this->bindings = $bindings;
		$this->errors = ErrorManager::getInstance();
	}

	public function show($logicPath,$type,$vars,$permision,$styles)
	{
		$_CATALOGS = CatalogManager::getInstance();
		$root = "templates/".$this->template."/";

		switch($type)
		{
			case "view":
				$viewId = $logicPath;//El name logico de la view
				$openView = $this->getURLFromView($viewId,$this->bindings);//Obtengo el name url de esta view

				if(strlen($openView)>0) $view = $this->viewDirectory.$openView;
				else $view = $this->viewDirectory."error/404.php";

				if(!file_exists($view)) $view = $this->viewDirectory."error/404.php";

				//echo "Loading $view";
				if(file_exists("templates/".$this->template."/template.php"))
					require("templates/".$this->template."/template.php");
				else
					$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid template ".$this->template);

			break;
			case "panel":
				$panelId = $logicPath;

				$openView = $this->getURLFromView($panelId,$this->bindings);

				if(strlen($openView)>0) $view = $this->panelDirectory.$openView;
				else $view = $this->panelDirectory."error/404.php";

				require_once('templates/head.php');
				require($view);
			break;
			default:

				$view = $this->viewDirectory."error/404.php";
				require("templates/".$this->template."/template.php");
			break;
		}

		return $view;
	}

	private function getURLFromView($page,$result)
	{

		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					return $result["views"]["view"][$i]['attr']['url'];
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
				return $result["views"]["view"]['attr']['url'];


		}
		else
		{
			die("error leyendo binding.xml");
		}
	}
}
?>