<?php

//////////////////////////////////////////////////////
//Esta clase se encarga de renderizar la view deseada
//////////////////////////////////////////////////////
include_once('phputils/CocoasCliente.class.php');
include_once('phputils/CocoasVendedor.class.php');
include_once('phputils/CocoasEmpleado.class.php');
class UserManager
{
	var $validator;

	public function UserManager()
	{
		$this->validator = new httpRequestValidator('UserManager');
	}

	public function validate($user,$password)
	{
		if (!isset($user)) $user = new CocoasUser();
		$cuenta = 0;

		try
		{
			$this->connection = Doctrine_Manager::connection();
			$q = Doctrine_Query::create()
				->from('user u')
				->where("u.email='".$user->name."'");
			$rows = $q->execute();
			$cuenta = count($rows);
		}
		catch(Exception $e)
		{
			if($GLOBALS["debugMode"]) $this->validator->errors->addError(ErrorManager::CANIS_FATAL,$e->getMessage());
		}

		if($cuenta == 1)
		{
			$auxUser = $rows[0];
			//Si los hash de la clave coinciden
			if ($password == $auxUser->password)
			{
				$user->roleName   = $auxUser->Role->name;
				$user->locationId = $auxUser->Location->id;
				$user->status     = $auxUser->status;
				$user->id         = $auxUser->id;
				
				$log = new Log;
				$logType = Doctrine::getTable('LogType')->findOneByNombre('inicio');
				$log->idlogtype = $logType->id;
				$log->iduser = $user->id;
				$today = time() - 18720; 
				$mysqldate = date('Y-m-d h:i:s',$today);
				$log->fecha = $mysqldate;
				$ip=$_SERVER['REMOTE_ADDR'];
				$log->ip = $ip;
				$log->save();
					
				
				if ($user->roleName == 'cliente' && $user->status != 'pending'){
					try
					{
						$this->connection = Doctrine_Manager::connection();
						$q = Doctrine_Query::create()
							->from('cliente c')
							->where("c.id_usuario='".$user->id."'");
						$rows = $q->execute();
						$cuenta = count($rows);
					}
					catch(Exception $e)
					{
						if($GLOBALS["debugMode"]) $this->validator->errors->addError(ErrorManager::CANIS_FATAL,$e->getMessage());
					}
					if($cuenta == 1)
					{
						$auxCliente = $rows[0];
						$cliente = new CocoasCliente();
						$cliente->id = $auxCliente->id;
						$cliente->id_tipo = $auxCliente->id_tipo;
						$cliente->id_usuario = $auxCliente->id_usuario;
						$cliente->nombre = $auxCliente->nombre;
						$cliente->rif = $auxCliente->rif;
						$cliente->direccion = $auxCliente->direccion;
						$cliente->telefono = $auxCliente->telefono;
						$cliente->inactivo = $auxCliente->inactivo;
						$cliente->pedido = null;
						$_SESSION['cliente'] = $cliente;						
					}
				}else if ($user->roleName == 'vendedor'){
					try
					{
						$this->connection = Doctrine_Manager::connection();
						$q = Doctrine_Query::create()
							->from('vendedor v')
							->where("v.id_usuario='".$user->id."'");
						$rows = $q->execute();
						$cuenta = count($rows);
					}
					catch(Exception $e)
					{
						if($GLOBALS["debugMode"]) $this->validator->errors->addError(ErrorManager::CANIS_FATAL,$e->getMessage());
					}
					if($cuenta == 1)
					{
						$auxVendedor = $rows[0];
						$vendedor = new CocoasVendedor();
						$vendedor->id = $auxVendedor->id;
						$vendedor->id_tipo = $auxVendedor->id_tipo;
						$vendedor->id_usuario = $auxVendedor->id_usuario;
						$vendedor->nombre = $auxVendedor->nombre;
						$vendedor->inactivo = $auxVendedor->inactivo;
						$_SESSION['vendedor'] = $vendedor;						
					}
				}else if ($user->roleName == 'empleado'){
					try
					{
						$this->connection = Doctrine_Manager::connection();
						$q = Doctrine_Query::create()
							->from('empleado e')
							->where("e.id_user='".$user->id."'");
						$rows = $q->execute();
						$cuenta = count($rows);
					}
					catch(Exception $e)
					{
						if($GLOBALS["debugMode"]) $this->validator->errors->addError(ErrorManager::CANIS_FATAL,$e->getMessage());
					}
					if($cuenta == 1)
					{
						$auxEmpleado = $rows[0];
						$empleado = new CocoasEmpleado();
						$empleado->id = $auxEmpleado->id;
						$empleado->id_tipo = $auxEmpleado->id_tipo;
						$empleado->id_user = $auxEmpleado->id_user;
						$empleado->rif = $auxEmpleado->rif;
						$empleado->nombre = $auxEmpleado->nombre;
						$empleado->inactivo = $auxEmpleado->inactivo;
						$_SESSION['empleado'] = $empleado;						
					}
				}
			}
		}
		else
		{
			$user = new CocoasUser();
		}
		return $user;
	}

	public function loginUser()
	{
		$_SESSION["user"] = new CocoasUser();

		$user = $this->validator->getVar('user');
		$password = $this->validator->getVar('password');

		if ($password && $user)
		{
			//evito que haya colocado mas de una palabra en el login (para evitar sql injection)
			$usu = explode(" ",trim($user));

			//Guardo la identidad del cliente que desea autenticarse
			$_SESSION["user"]->name = $user;

			$_SESSION["user"] = $this->validate($_SESSION["user"],$password);			
			//echo $_SESSION["usuario"]->roleId;
		}
	}

	public function closeSession()
	{
		$user = $_SESSION["user"];
		$log = new Log;
		$logType = Doctrine::getTable('LogType')->findOneByNombre('cierre');
		$log->idlogtype = $logType->id;
		$log->iduser = $user->id;
		$today = time() - 18720; 
		$mysqldate = date('Y-m-d h:i:s',$today);
		$log->fecha = $mysqldate;
		$ip=$_SERVER['REMOTE_ADDR'];
		$log->ip = $ip;
		$log->save();
		
		session_destroy();
		$_SESSION["user"] = new CocoasUser();
	}

	function exect($query)
	{
		$result = null;
		try
		{
			$result = mysql_query($query);
			if($GLOBALS["debugMode"]) if(!$result) $this->validator->errors->addError(ErrorManager::CANIS_FATAL,'No se ha podido realizar la accion: '.$query.' -> '.mysql_error());
		}
		catch(Exception $e)
		{
			$this->validator->errors->addError(ErrorManager::CANIS_FATAL,'No se ha podido realizar la accion: '.$e->getMessage());
		}
		
		return $result;
	}
	
}

?>