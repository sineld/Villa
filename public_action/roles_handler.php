<?php

class roles_handler {
	

	public function setRole($nombre) {
		require_once 'phputils/mysqlConexion.php';
		$newRole = new Role;
		$newRole -> name = $nombre;
		$return = 'true';
		try {
			$newRole -> save();
		} catch (Exception $e) {
			$return = 'false';
		}
		return $return;
	}

	public function getRoles($id=null,$nombre=null) {
		require_once 'phputils/mysqlConexion.php';
		$handler = new pagination_handler();
		$count = 0;
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('role');
		if($id != null){
			if ($count == 0){
				$q -> where('id = ?',$id);
				$count++;
				}
			else
				$q -> andwhere('id = ?',$id);
		}
		if ($nombre != null){
			if ($count == 0){
				$q -> where('name = ?',$nombre);
				$count++;
			}
			else
				$q -> andwhere('name = ?',$nombre);	
		}
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}

	public function updateRole($id,$nombre) {
		require_once 'phputils/mysqlConexion.php';
		$nombre = '\'' . $nombre . '\'';
		$q = Doctrine_Query::create() -> update('role') -> set('name', $nombre) -> where('id = ' . $id);
		$q -> execute();
		return 'true';
	}

}
?>