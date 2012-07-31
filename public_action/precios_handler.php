<?php 
/**
 * 
 */
class precios_handler {
	
	public function setprecio($precio){
		require_once 'phputils/mysqlConexion.php';
		$newPrecio = new Precios;
		$newPrecio -> precio = $precio;
		try {
			$newPrecio -> save();
			return $newPrecio->id;
		} catch (Exception $e) {
			return 'false';
		}
	}
	
	public function getPrecios($id=null,$precio =null,$inactivo=-1) {
		require_once 'phputils/mysqlConexion.php';
		$count = 0;
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('precios');
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
		if ($precio != null){
			if ($count == 0){
				$q -> where('precio = ?',$precio);
				$count++;
			}
			else
				$q -> andwhere('precio = ?',$precio);	
		}
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}
	
	public function updatePrecio($id,$precio,$inactivo) {
		require_once 'phputils/mysqlConexion.php';
		$precios = Doctrine::getTable('precios')->findOneById($id); 
		if (count($precios)!=0){
		$precios->precio = $precio;
		$precios->inactivo = $inactivo;
		echo $precios->save();
		$return = 'true';
		}else {
			$return = 'false';
		}
		return $return;
	}
	
}

?>