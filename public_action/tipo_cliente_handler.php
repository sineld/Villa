<?php 
/**
 * 
 */
require_once 'public_action/pagination_handler.php';
class tipo_cliente_handler {
	
	public function setTipoCliente($nombre){
		require_once 'phputils/mysqlConexion.php';
		$newTipoCliente = new TipoCliente;
		$newTipoCliente->nombre = $nombre;
		$return = 'true';
		try {
			$newTipoCliente -> save();
		} catch (Exception $e) {
			$return = 'false';
		}
		return $return;
	}
	
	public function getTipoClientes($id=null, $nombre=null, $inactivo=-1, $paginaActual=null,$porPagina=null){
		require_once 'phputils/mysqlConexion.php';
		$handler = new pagination_handler();
		if ($porPagina == null){
			$porPagina = $handler -> porPagina;
		}
		
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('tipocliente');
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

	public function updateTipoCliente($id, $nombre, $inactivo) {
		require_once 'phputils/mysqlConexion.php';
		$nombre = '\'' . $nombre . '\'';
		$q = Doctrine_Query::create() -> select('inactivo') -> from('tipocliente') -> where('id = '.$id);
		$aux = $q->execute()->toArray();
		$inactivoant = $aux[0]['inactivo'];
		$q = Doctrine_Query::create() -> update('tipocliente') -> set('nombre', $nombre) -> set('inactivo', $inactivo) -> where('id = ' . $id);
		$q -> execute();
		if ($inactivoant != $inactivo){
			$q = Doctrine_Query::create() -> select('u.id_precio, p.id') -> from('precioart u') ->leftJoin('u.Precios p') ->where('u.id_art = '.$id); 
			$data = $q -> execute()->toArray();
			for ($i = 0;$i< count($data); $i++){
				$q = Doctrine_Query::create() -> update('precioart') -> set('inactivo', $inactivo) -> where('id = ' . $data[$i]['id']);
				$q->execute();
				$q = Doctrine_Query::create() -> update('precios') -> set('inactivo', $inactivo) -> where('id = ' . $data[$i]['id_precio']);
				$q->execute();
			}
		}
		
		return 'true';

	}
}


?>