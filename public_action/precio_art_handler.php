<?php 
/**
 * 
 */
class precio_art_handler {
	
	public function setprecio_art($id_art,$id_precio,$id_tipo_cliente){
		require_once 'phputils/mysqlConexion.php';
		$newPrecioArt = new PrecioArt;
		$newPrecioArt -> id_art = $id_art;
		$newPrecioArt -> id_precio = $id_precio;
		$newPrecioArt -> id_tipo_cliente = $id_tipo_cliente;		
		try {
			$newPrecioArt -> save();
			return $newPrecioArt->id;
		} catch (Exception $e) {
			return 'false';
		}
	}
	
	public function getprecio_art($id=null, $id_art=null, $id_precio=null, $id_tipo_cliente=null, $inactivo=-1){
		require_once 'phputils/mysqlConexion.php';
		
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('precioart');
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
		if ($id_art != null){
			if ($count == 0){
				$q -> where('id_art = ?',$id_art);
				$count++;
			}
			else
				$q -> andwhere('id_art = ?',$id_art);	
		}
		if ($id_precio != null){
			if ($count == 0){
				$q -> where('id_precio = ?',$id_precio);
				$count++;
			}
			else
				$q -> andwhere('id_precio = ?',$id_preci);	
		}
		if ($id_tipo_cliente != null){
			if ($count == 0){
				$q -> where('id_tipo_cliente = ?',$id_tipo_cliente);
				$count++;
			}
			else
				$q -> andwhere('id_tipo_cliente = ?',$id_tipo_cliente);	
		}
		$qArray = $q -> execute() -> toArray();

		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	} 
	
}

?>