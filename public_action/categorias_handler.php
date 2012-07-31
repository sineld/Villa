<?php

require_once 'public_action/pagination_handler.php';
class categorias_handler {
	

	public function setCategoria($nombre) {
		require_once 'phputils/mysqlConexion.php';
		$newCategoria = new Categorias;
		$newCategoria -> nombre = $nombre;
		$return = 'true';
		try {
			$newCategoria -> save();
		} catch (Exception $e) {
			$return = 'false';
		}
		return $return;
	}

	public function getCategorias($id=null,$nombre=null,$paginaActual=null,$inactivo=-1,$porPagina=null) {
		require_once 'phputils/mysqlConexion.php';
		$handler = new pagination_handler();
		if ($porPagina == null){
			$porPagina = $handler -> porPagina;
		}
		$count = 0;
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('categorias');
		if($inactivo != -1){
			if ($count == 0){
				$q	-> where('inactivo = ?', $inactivo);
				$count++;
			}
			else 
				$q	-> andwhere('inactivo = ?', $inactivo);
		}
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
				$q -> where('nombre = ?',$nombre);
				$count++;
			}
			else
				$q -> andwhere('nombre = ?',$nombre);	
		}if ($paginaActual != null){
			$q-> limit($porPagina)-> offset($porPagina * ($paginaActual - 1));
		}
		
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}
	
	public function getCategoriasArray($id=null,$nombre=null,$paginaActual=null,$inactivo=-1) {
		require_once 'phputils/mysqlConexion.php';
		$handler = new pagination_handler();
		$porpagina = $handler->porPagina;
		$count = 0;
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('categorias');
		if($inactivo != -1){
			if ($count == 0){
				$q	-> where('inactivo = ?', $inactivo);
				$count++;
			}
			else 
				$q	-> andwhere('inactivo = ?', $inactivo);
		}
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
				$q -> where('nombre = ?',$nombre);
				$count++;
			}
			else
				$q -> andwhere('nombre = ?',$nombre);	
		}if ($paginaActual != null){
			$q-> limit($porpagina)-> offset($porpagina * ($paginaActual - 1));
		}
		
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return $qArray;
		} else {
			return "[]";
		}
	}

	public function updateCategoria($id,$nombre,$inactivo) {
		require_once 'phputils/mysqlConexion.php';
		$nombre = '\'' . $nombre . '\'';
		$q = Doctrine_Query::create() -> update('categorias') -> set('nombre', $nombre) -> set('inactivo', $inactivo) -> where('id = ' . $id);
		$q -> execute();
		if ($inactivo == 1){
		$q = Doctrine_Query::create() -> update('tipos') -> set('inactivo', $inactivo) -> where('id_cat = ' . $id);
		$q -> execute();
		}
		return 'true';
	}

}
?>