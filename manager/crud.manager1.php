<?php

require_once("phputils/mysqlConexion.php");
require_once("phputils/httpRequestValidator.php");
//////////////////////////////////////////////////
//Esta class se encarga de cargar todas las variables necesarias
//para renderizar la view deseada
//////////////////////////////////////////////////

class CRUDManager
{
	var $bindings = array();
	var $roleBindings = array();
	var $viewDirectory = "view/";
	var $delegateDirectory = "delegate/";
	var $templateManager;
	var $validator;
	var $connection;
	private $errors;

	public function CRUDManager($bindings,$roleBindings)
	{
		$this->bindings = $bindings;
		$this->roleBindings = $roleBindings;
		$this->validator = new httpRequestValidator('CRUDManager');
		if($GLOBALS["dbServer"]!='' && $GLOBALS["dbName"]!='' && $GLOBALS["user"]!='') $this->connection = Doctrine_Manager::connection();
		$this->errors = ErrorManager::getInstance();
	}

	public function excecuteTransaction($view,$transactionName)
	{
		//echo "begin transaction connection: ".$this->connection->getName().'.';
		if($this->connection) $this->connection->beginTransaction();

		$class = $this->getClassFromView($view,$this->bindings);
		$permision = $this->getViewsFromRole($_SESSION['user']->roleName,$this->roleBindings);

		if(!isset($permision[$view]) || $permision[$view]['edit']!='true')
		{

			$this->errors->addError(ErrorManager::CANIS_INFO,"User role: '".$_SESSION['user']->roleName."' cannot perform this action '$transactionName'");

			//ademas salgo de este script
			exit();
		}

		if(isset($class))
		{
			if(file_exists("delegate/".$class.".delegate.php"))
				require("delegate/".$class.".delegate.php");
			else if(file_exists("delegate/generated/".$class.".delegate.php"))
				require("delegate/generated/".$class.".delegate.php");
			else
				$this->errors->addError(ErrorManager::CANIS_FATAL,"Delegate '".$class.".delegate.php"."' not found for view '".$view."'");

			$newclass = new $class();

			if(method_exists($newclass,$transactionName)) $destination = $newclass->$transactionName($this->validator);
			else
			{
				$this->errors->addError(ErrorManager::CANIS_FATAL,'Method not found: '.$transactionName.' in class '.$class);
				exit();
			}
		}
		else
			$this->errors->addError(ErrorManager::CANIS_FATAL,"View '".$view."' not found, or it has no 'class' property on bindings.xml");

		if($this->errors->totalErrors(array(ErrorManager::CANIS_FATAL,ErrorManager::CANIS_ERROR,ErrorManager::CANIS_USER_ERROR))==0 && $destination!='')
		{
			//echo "Commit:".$this->connection->getName().'.';
			if($this->connection) $this->connection->commit();
			echo '<script language="JavaScript1.1">window.location="'.$destination.'";</script>';
		}
		else
		{
			//echo "trying Rollback: ".$this->connection->getName().'.';
			if($this->connection) $this->connection->rollback();

			if(isset($destination) && $destination=='') $this->errors->addError(ErrorManager::CANIS_ERROR,"There is no return value on that getter.");

			echo $this->errors->getErrorsString(array(ErrorManager::CANIS_FATAL,ErrorManager::CANIS_ERROR,ErrorManager::CANIS_USER_ERROR));
		}
	}


	function getTransactionsFromView($page,$result)
	{
		$transacciones = array();
		$i = 0;
		//echo "<pre>";print_r($result);echo "</pre>";
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
				{
					if(isset($result["views"]["view"][$i]['getter']))
						if(isset($result["views"]["view"][$i]['getter'][0]))
						{
							for($j = 0;$j<count($result["views"]["view"][$i]['getter']);$j++)
							{
								$transacciones[$j]["value"] = $result["views"]["view"][$i]["getter"][$j]['value'];
								$transacciones[$j]["destination"] = $result["views"]["view"][$i]["getter"][$j]["attr"]["destination"];
							}
						}
						else
						{
							$transacciones[0]["value"] = $result["views"]["view"][$i]["getter"]['value'];
							$transacciones[0]["destination"] = $result["views"]["view"][$i]["getter"]["attr"]["destination"];
						}
				}
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
			{
				if(isset($result["views"]["view"][$i]['getter']))
					if(isset($result["views"]["view"]['getter'][0]))
					{
						for($j = 0;$j<count($result["views"]["view"]['getter']);$j++)
						{
							$transacciones[$j]["value"] = $result["views"]["view"]["getter"][$j]['value'];
							$transacciones[$j]["destination"] = $result["views"]["view"]["getter"][$j]["attr"]["destination"];
						}
					}
					else
					{
						$transacciones[0]["value"] = $result["views"]["view"]["getter"]['value'];
						$transacciones[0]["destination"] = $result["views"]["view"]["getter"]["attr"]["destination"];
					}
			}
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there is a parsing problem.");
		}

		return $transacciones;
	}

	function getClassFromView($page,$result)
	{

		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					if(isset($result["views"]["view"][$i]['attr']['class']))
						return $result["views"]["view"][$i]['attr']['class'];
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
				return $result["views"]["view"]['attr']['class'];
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, there was a parsing problem.");
		}

		return NULL;
	}

	private function getRolFromView($page,$result)
	{
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					if(isset($result["views"]["view"][$i]['attr']['role'])) return $result["views"]["view"][$i]['attr']['role'];
					else return '';
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
				if(isset($result["views"]["view"]['attr']['role'])) return $result["views"]["view"]['attr']['role'];
				else return '';
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_FATAL,"Invalid binding.xml, here was a problem finding the Role");
		}
	}

	private function getViewsFromRole($role,$result)
	{
		if($GLOBALS["showRoleHierarchy"]){ echo "<pre>";print_r($result);echo"</pre>";}
		$view = array();
		if(isset($result["roles"]["role"][0]))
		{

			for($i = 0;$i<count($result["roles"]["role"]);$i++)
			{
				if($result["roles"]["role"][$i]['attr']['name']==$role)
					if(isset($result["roles"]["role"][$i]["view"]))
						if(isset($result["roles"]["role"][$i]['view'][0]))
						{

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
							$view[$result["roles"]["role"][$i]["view"]['value']]['read'] = $result["roles"]["role"][$i]["view"]["attr"]['read'];
							$view[$result["roles"]["role"][$i]["view"]['value']]['edit'] = $result["roles"]["role"][$i]["view"]["attr"]['edit'];
							$view[$result["roles"]["role"][$i]["view"]['value']]['delete'] = $result["roles"]["role"][$i]["view"]["attr"]['delete'];
							$view[$result["roles"]["role"][$i]["view"]['value']]['add'] = $result["roles"]["role"][$i]["view"]["attr"]['add'];
						}
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
}

?>
