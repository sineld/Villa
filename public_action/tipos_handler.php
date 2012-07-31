<?php
/**
 * 
 */
require_once 'public_action/categorias_handler.php';
require_once 'public_action/pagination_handler.php';
class tipos_handler {
	
	public function setTipo($nombre, $id_cat) {
		require_once 'phputils/mysqlConexion.php';	
		$newTipo = new Tipos;
		$newTipo -> nombre = $nombre;
		$newTipo -> id_cat = $id_cat;
		$return = 'true';
		try {
			$newTipo -> save();
		} catch (Exception $e) {
			$return = 'false';
		}
		return $return;
	}

	public function getTipos($inactivo=-1,$id=null,$id_cat=null,$nombre=null,$paginaActual=null,$porPagina=null) {
		require_once 'phputils/mysqlConexion.php';
		$handler = new pagination_handler();
		if ($porPagina == null){
			$porPagina = $handler -> porPagina;
		}
		$cat = new categorias_handler();
		$q = Doctrine_Query::create() 
			-> select('t.id, c.id') 
			-> from('tipos t')
			->leftJoin('t.Categorias c');
		$count = 0;
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
		if ($id_cat != null){
			if ($count == 0){
				$q -> where('id_cat = ?',$id_cat);
				$count++;
			}
			else
				$q -> andwhere('id_cat = ?',$id_cat);
		}
		if ($nombre != null){
			if ($count == 0){
				$q -> where('nombre = ?',$nombre);
				$count++;
			}
			else
				$q -> andwhere('nombre = ?',$nombre);	
		}
		if ($paginaActual != null){
			$q-> limit($porPagina)-> offset($porPagina * ($paginaActual - 1));
		}
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}

	public function updateTipo($id,$id_cat,$nombre,$inactivo) {
		require_once 'phputils/mysqlConexion.php';
		$nombre = '\'' . $nombre . '\'';
		$q = Doctrine_Query::create() -> update('tipos') -> set('id_cat',$id_cat) -> set('nombre', $nombre) -> set('inactivo', $inactivo) -> where('id = ' . $id);
		$q -> execute();
		return 'true';

	}
}
 
?>