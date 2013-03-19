<?php

require_once("phputils/httpRequestValidator.php");

//////////////////////////////////////////////////
//Esta class se encarga de cargar todas las variables necesarias
//para renderizar la view deseada
//////////////////////////////////////////////////

class InfoManager
{
	var $bindings = array();
	var $roleBindings = array();
	var $viewDirectory = "view/";
	var $delegateDirectory = "delegate/";
	var $templateManager;
	var $user;
	private $errors;

	public function InfoManager($bindings,$roleBindings)
	{
		$this->bindings = $bindings;
		$this->roleBindings = $roleBindings;
		$this->templateManager = new TemplateManager($bindings);
		$this->user = $_SESSION['user'];
		$this->errors = ErrorManager::getInstance();
	}

	public function setTemplate($value)
	{
		$this->templateManager->template = $value;
	}

	public function get($logicPath,$type)
	{
		if($this->viewExists($logicPath,$this->bindings))
		{
			$permision = $this->validateRol($logicPath);
			$styles = $this->getStyles($logicPath,$this->bindings);

			$template = $this->getTemplateFromView($logicPath,$this->bindings);
			if(!empty($template)) $this->setTemplate($template);

			if($permision!=-1 && $permision!=0)
			{

				switch($type)
				{
					default:

						$metodos = $this->getMethodsFromView($logicPath,$this->bindings);
						$class = $this->getDelegateFromView($logicPath,$this->bindings);

						if($class)
							if(!file_exists("delegate/".$class.".delegate.php"))
							{
								$this->errors->addError(ErrorManager::CANIS_FATAL,"Missing delegate '".$class.".delegate.php"."'");
							}
							else
							{
								require("delegate/".$class.".delegate.php");

								//Esta sera la instancia que utilizaremos para nuestro delegate
								$newclass = new $class();
							}

							//Este arreglo contendr� todas las variables para renderizar la view
							$vars = array();

							if($class!='' && $metodos!='' && count($metodos)>0 && isset($newclass))
							{
								//lleno las variables requeridas por el usuario para renderizar la view
								foreach($metodos as $metodo)
								{
									if(method_exists($newclass,$metodo["value"]))$vars[$metodo["destination"]] = $newclass->$metodo["value"](new httpRequestValidator());
									else
									{
										$this->errors->addError(ErrorManager::CANIS_FATAL,"There is no method '".$metodo["value"]."' in delegate ".$class.". This method was defined in bindings.xml");
									}
								}

							}

								$view = $this->templateManager->show($logicPath,$type,$vars,$permision,$styles);

					break;
				}
			}
			else if($permision==0)
			{
				$this->user->roleName = "invalid";
				$this->user->status = "invalid";
				header("Location: controller.php?view=validate");
			}
			else if($GLOBALS["debugMode"]) echo $this->errors->getErrorsString();

		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,'La siguiente vista: "'.$logicPath.'" no ha sido definida.');
			if($GLOBALS["debugMode"]) echo $this->errors->getErrorsString();
		}

	}

	public function validateRol($logicPath)
	{
		$permision = $this->getViewsFromRole($this->user->roleName,$this->roleBindings);

		if(isset($permision[$logicPath]))
		{
			if($permision[$logicPath]['read']!='true')
			{
				//si no existe, envio a la p�gina de autentificacion
				if(!$GLOBALS["debugMode"]) header("Location: controller.php?view=".$GLOBALS["LOGIN_VIEW"]);
				else
				{
					$this->errors->addError(ErrorManager::CANIS_FATAL,"User role: ".$this->user->roleName.". This role cannot read this view ".$logicPath);
				}
				//ademas salgo de este script
				return -1;
			}
			else if($this->user->status=='pending')
			{
				return 0;
			}
			else
			{
				return $permision;
			}
		}
		else
		{
			//print_r($permision);
			//si no existe, envio a la p�gina de autentificacion
			if(!$GLOBALS["debugMode"]) header("Location: controller.php?view=".$GLOBALS["LOGIN_VIEW"]);
			else
			{
				$this->errors->addError(ErrorManager::CANIS_FATAL,'User role: '.$this->user->roleName.'. This role has no priviledges for this view '.$logicPath);
			}
			//ademas salgo de este script
			return -1;
		}
	}


	private function getMethodsFromView($page,$result)
	{
		$metodos = array();
		if($GLOBALS["showViewHierarchy"]){ echo "<pre>";print_r($result);echo "</pre>";}
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
				{
					if(isset($result["views"]["view"][$i]["getter"]))
						if(isset($result["views"]["view"][$i]['getter'][0]))
						{
							if($result["views"]["view"][$i]['getter'])
								for($j = 0;$j<count($result["views"]["view"][$i]['getter']);$j++)
								{
									$metodos[$j]["value"] = $result["views"]["view"][$i]["getter"][$j]['value'];
									$metodos[$j]["destination"] = $result["views"]["view"][$i]["getter"][$j]["attr"]["destination"];
								}
						}
						else
						{
							$metodos[0]["value"] = $result["views"]["view"][$i]["getter"]['value'];
							$metodos[0]["destination"] = $result["views"]["view"][$i]["getter"]["attr"]["destination"];
						}
				}
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
			{
				if(isset($result["views"]["view"]['getter']))
					if(isset($result["views"]["view"]['getter'][0]))
					{
						for($j = 0;$j<count($result["views"]["view"]['getter']);$j++)
						{
							$metodos[$j]["value"] = $result["views"]["view"]["getter"][$j]['value'];
							$metodos[$j]["destination"] = $result["views"]["view"]["getter"][$j]["attr"]["destination"];
						}
					}
					else
					{
						$metodos[0]["value"] = $result["views"]["view"]["getter"]['value'];
						$metodos[0]["destination"] = $result["views"]["view"]["getter"]["attr"]["destination"];
					}
			}
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}

		return $metodos;
	}

	private function getDelegateFromView($page,$result)
	{

		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					if(isset($result["views"]["view"][$i]['attr']['class'])) return $result["views"]["view"][$i]['attr']['class'];
					else return '';
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
				if(isset($result["views"]["view"]['attr']['class'])) return $result["views"]["view"]['attr']['class'];
				else return '';
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}
	}

	private function getTemplateFromView($page,$result)
	{

		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					if(isset($result["views"]["view"][$i]['attr']['template'])) return $result["views"]["view"][$i]['attr']['template'];
					else return '';
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
				if(isset($result["views"]["view"]['attr']['template'])) return $result["views"]["view"]['attr']['template'];
				else return '';
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}
	}

	private function getViewsFromRole($role,$result)
	{
		if($GLOBALS["showRoleHierarchy"]){ echo "<pre>";print_r($result);echo"</pre>";}
		$view = array();
		if(isset($result["roles"]["role"][0]))
		{
			$rolEncontrado = false;
			for($i = 0;$i<count($result["roles"]["role"]);$i++)
			{
				if($result["roles"]["role"][$i]['attr']['name']==$role)
					if(isset($result["roles"]["role"][$i]["view"]))
						if(isset($result["roles"]["role"][$i]['view'][0]))
						{
							$rolEncontrado = true;
							if($result["roles"]["role"][$i]['view'])
								for($j = 0;$j<count($result["roles"]["role"][$i]['view']);$j++)
								{
									$view[$result["roles"]["role"][$i]["view"][$j]['value']]['read'] = $result["roles"]["role"][$i]["view"][$j]["attr"]['read'];
									$view[$result["roles"]["role"][$i]["view"][$j]['value']]['add'] = $result["roles"]["role"][$i]["view"][$j]["attr"]['add'];
									$view[$result["roles"]["role"][$i]["view"][$j]['value']]['delete'] = $result["roles"]["role"][$i]["view"][$j]["attr"]['delete'];
									$view[$result["roles"]["role"][$i]["view"][$j]['value']]['edit'] = $result["roles"]["role"][$i]["view"][$j]["attr"]['edit'];
								}
						}
						else
						{
							$rolEncontrado = true;
							$view[$result["roles"]["role"][$i]["view"]['value']]['read'] = $result["roles"]["role"][$i]["view"]["attr"]['read'];
							$view[$result["roles"]["role"][$i]["view"]['value']]['edit'] = $result["roles"]["role"][$i]["view"]["attr"]['edit'];
							$view[$result["roles"]["role"][$i]["view"]['value']]['delete'] = $result["roles"]["role"][$i]["view"]["attr"]['delete'];
							$view[$result["roles"]["role"][$i]["view"]['value']]['add'] = $result["roles"]["role"][$i]["view"]["attr"]['add'];
						}
			}
			if($rolEncontrado==false)
			{
				$this->errors->addError(ErrorManager::CANIS_FATAL,"There is no rol $role.");
			}
		}
		else if($result["roles"]["role"]['attr']['name'])
		{

			if($result["roles"]["role"]['attr']['name']==$role)
			{
				if(isset($result["roles"]["role"]['view']))
					if(isset($result["roles"]["role"]['view'][0]))
					{
						for($j = 0;$j<count($result["roles"]["role"]['view']);$j++)
						{
							$view[$result["roles"]["role"]["view"][$j]['value']]['edit'] = $result["roles"]["role"]["view"][$j]["attr"]['edit'];
							$view[$result["roles"]["role"]["view"][$j]['value']]['delete'] = $result["roles"]["role"]["view"][$j]["attr"]['delete'];
							$view[$result["roles"]["role"]["view"][$j]['value']]['add'] = $result["roles"]["role"]["view"][$j]["attr"]['add'];
							$view[$result["roles"]["role"]["view"][$j]['value']]['read'] = $result["roles"]["role"]["view"][$j]["attr"]['read'];
						}
					}
					else
					{
						$view[$result["roles"]["role"]["view"]['value']]['edit'] = $result["roles"]["role"]["view"]["attr"]['edit'];
						$view[$result["roles"]["role"]["view"]['value']]['delete'] = $result["roles"]["role"]["view"]["attr"]['delete'];
						$view[$result["roles"]["role"]["view"]['value']]['add'] = $result["roles"]["role"]["view"]["attr"]['add'];
						$view[$result["roles"]["role"]["view"]['value']]['read'] = $result["roles"]["role"]["view"]["attr"]['read'];
					}
			}
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid roles.xml, there was a parsing problem.");
		}

		return $view;
	}

	private function viewExists($page,$result)
	{
		$metodos = array();
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
				{
					return true;
				}
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
			{
				return true;
			}
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}

		return false;
	}

	private function getStyles($page,$result)
	{
		$i = 0;
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					if(isset($result["views"]["view"][$i]['attr']['styles']) && $result["views"]["view"][$i]['attr']['styles']!='')
					{
						$array[$page] = $result["views"]["view"][$i]['attr']['styles'];
						return $array;
					}
			}
		}
		else if(isset($result["views"]["view"]['attr']['name']))
		{
			if($result["views"]["view"][$i]['attr']['name']==$page && isset($result["views"]["view"]['attr']['styles']))
				if($result["views"]["view"]['attr']['styles']!='')
				{
					$array[$page] = $result["views"]["view"]['attr']['styles'];
					return $array;
				}
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}

		return array();
	}

	private function getTemplate($page,$result)
	{
		$i = 0;
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					if(isset($result["views"]["view"][$i]['attr']['template']) && $result["views"]["view"][$i]['attr']['template']!='')
					{
						return $result["views"]["view"][$i]['attr']['template'];
					}
			}
		}
		else if(isset($result["views"]["view"]['attr']['name']))
		{
			if($result["views"]["view"][$i]['attr']['name']==$page && isset($result["views"]["view"]['attr']['template']))
				if($result["views"]["view"]['attr']['template']!='')
				{
					return $result["views"]["view"]['attr']['template'];
				}
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}

		return array();
	}


}

?>
