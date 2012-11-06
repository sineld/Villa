<?php
require_once 'phputils/mysqlConexion.php';
class PedidosDelegate {
	public function eliminarArtSesion($validator){
		unset($_SESSION['cliente']->articulos[$validator->getVar('id')]);
	}
	public function modArtSesion($validator){
		$_SESSION['cliente']->articulos[$validator->getVar('id')] = $validator->getVar('cantidad'); 
	}
	public function listarPedido($validator){
		$cliente = (int)$validator->getOptionalVar('id_cliente');
		$q = Doctrine_Query::create() -> select('*') -> from('pedidos');
		if ($cliente >= 0) {
			$q -> where('id_cliente = ?',$cliente);
		}
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			echo json_encode($qArray);
		} else {
			return "[]";
		}
	}
	
	public function listarArticulos($validator){
		$id_pedido = (int)$validator->getOptionalVar('id_pedido');
		$q = Doctrine_Query::create() -> select('*') -> from('articulospedido');
		if ($cliente >= 0) {
			$q -> where('id_pedido = ?',$id_pedido);
		}
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			echo json_encode($qArray);
		} else {
			echo "[]";
		}
	}
	
	
	public function eliminarArticulo($validator){
		$argumentos['id_articulo'] = (int)$validator->getVar('id_articulo');
		$argumentos['campo'] = 'inactivo';
		$argumentos['valor'] = 1;
		$eliminar = new Pedidos;
		echo var_dump($argumentos);
		return $eliminar->modArticulos($argumentos);
	}	
 	//Funcion para modificar items en pedido y/o inactivar/activar.
	public function modItemPedido($validator){
		$id = $validator->getVar('key_articulo');
		$cantidad = $validator->getVar('cantidad');
		$id_articulo = $validator->getVar('id_articulo');
		$id_pedido = $validator->getVar('id_pedido');
		$eliminar = $validator->getVar('eliminar');
		$item = Doctrine::getTable('ArticulosPedido')->findOneById($id);
		
		if(count($item) >= 1 ){
			if($id_articulo != ""){
				$item->id_articulo = $id_articulo;	
			}
			if($cantidad != ""){
				$item->cantidad = $cantidad;	
			}
			if($eliminar == "true"){
				$item->inactivo = 1;
			}
			if($eliminar == "false"){
				$item->inactivo = 0;
			}
			$item->save();
			return 'true';
		} else {
			return 'false';
		}
	}
	
	//Funcion para cambiar estados de los pedidos.
	public function cambiarEstadoPedido($validator){
		$id = $validator->getVar('id');
		$estado = $validator->getVar('estado');
		//Se obtiene la estructura del pedido seleccionado por el id.
		$pedido = Doctrine::getTable('pedidos')->findOneById($id);
		
		if(count($pedido) >= 1){
			$pedido->estado = (int)$estado; // 0 Pendiente, 1 Procesando, 2 Completado, 3 Cancelado.
			$today = time() - 18720; 
			$mysqldate = date('Y-m-d h:i:s',$today);
			$pedido->fecha_ult_mod = $mysqldate; 
			$pedido->save();
			return 'true';
		}	else{
			return 'false';
		}
	}

   //Funcion para devolver los items añadidos a un pedido. 
   public function getItemsPedido($validator){
   	 $id_pedido = $validator->getVar('id_pedido');
	 $query = Doctrine_Query::create()
	 			->select('a.*')
	    		->from('articulos_pedido a')
				->where('a.id_pedido =?',$id_pedido);
	 $data = $query->execute()->toArray();
	 
	 if(count($data) != 0){
	 	echo json_encode($qArray);
	 }else{
	 	echo '[]';
	 }
   }
   
	
	
	
}

?>
