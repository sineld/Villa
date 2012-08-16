<?php

require_once 'phputils/mysqlConexion.php';

class PedidosDelegate {
	
	
	
	//Funcion para crear un pedido nuevo.
	public function crearPedido($validator){
		
		$tipo_pago = (int)$validator->getVar('tipo_pago'); //Credito asignado al cliente (en dias), numerico entero. 
		$id_cliente = (int)$validator->getVar('id_cliente'); //ID del cliente que realiza el pedido.
		$forma_pago = $validator->getVar('forma_pago'); //Texto, forma de pago seleccionada por el cliente, cheque, transferencia, etc.
		$newPedido = new Pedidos;
		$mysqldate = date( 'Y-m-d');
		$newPedido->fecha_creacion = $mysqldate;
		$newPedido->tipo_pago = $tipo_pago;
		$newPedido->id_cliente = $id_cliente;
		$newPedido->fecha_ult_mod = $mysqldate;
		$newPedido->forma_pago = $forma_pago;
		$newPedido->inactivo = 0;
		$newPedido->estado = 0;
		$newPedido->save();
		return 'void';
	}
	
	//Funcion para agregar un item al pedido.
	public function newItemPedido($validator){
		$codigo_item = (int)$validator->getVar('id_articulo');
		$cantidad_item = (int)$validator->getVar('cantidad');
		$pedido_item = (int)$validator->getVar('id_pedido');
		//Si el id de pedido es diferente de null, añade items al pedido
		if($pedido_item != null){
			$newItem = new ArticulosPedido;
			$newItem->id_articulo = (int)$codigo_item;
			$newItem->cantidad = (int)$cantidad_item;
			$newItem->id_pedido = (int)$pedido_item;
			$newItem->save();
			return 'existente';
		}//Si el id del pedido no existe, crea un pedido nuevo
		else{
			$id_cliente = $_SESSION['cliente']->id_usuario;
			$query = Doctrine_Query::create()
				->select('a.*')
				->from('cliente a')
				->where('id_usuario =?',$id_cliente);
			
			$data = $query->execute()->toArray();				
			$mysqldate = date( 'Y-m-d');
			$newPedido = new Pedidos;
			$newPedido->fecha_creacion = $mysqldate;
			$newPedido->tipo_pago = (int)$data[0]['credito'];
			$newPedido->id_cliente = (int)$id_cliente;
			$newPedido->fecha_ult_mod = $mysqldate;
			$newPedido->forma_pago = 'efectivo';
			$newPedido->inactivo = 0;
			$newPedido->estado = 0;
			$newPedido->save();
			try {
				$_SESSION['cliente']->pedido = $newPedido->id;
			} catch (Exception $e) {
				echo 'error';
			}
			$newItem = new ArticulosPedido;
			$newItem->id_articulo = (int)$codigo_item;
			$newItem->cantidad = (int)$cantidad_item;
			$newItem->id_pedido = (int)$_SESSION['cliente']->pedido;
			$newItem->save();
			
		}
		
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
