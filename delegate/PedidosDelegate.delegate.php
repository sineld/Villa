<?php
require_once 'phputils/mysqlConexion.php';
class PedidosDelegate {
	
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
	
	//Funcion para renderizar cada articulo de la lista de articulos en cada pedido, 
	//la salida es el codigo, descripcion, imagen, precio y cantidad.
	
	public function renderArticulos($validator){
		$id = (int)$validator->getVar('id_articulo');
		$pedido = (int)$validator->getVar('id_pedido');
		//Obtengo informacion de la tabla articulos
		$qArticulo = Doctrine::getTable('articulos')->findOneById($id);
		//Obtengo informacion de la tabla fotos_art
		$qFotosArt = Doctrine_Query::create()
				-> select('*')
				-> from('fotosArt')
				-> where('id_art = ?',$id)
				//-> andWhere('prioridad = 1')
				-> andWhere('inactivo = 0');
		$fotosArt = $qFotosArt -> execute() -> toArray();
		//Obtengo informacion de la tabla fotos
		$qFotos = Doctrine_Query::create()
				-> select('*')
				-> from('fotos')
				-> where('id = ?',$fotosArt[0]['id_foto'])
				-> andWhere('inactivo = 0');
		$fotos = $qFotos -> execute() -> toArray();
		//Obtengo informacion de la tabla Pedido
		$qPedido = Doctrine_Query::create()
				-> select('*')
				-> from('pedidos')
				-> where('id = ?',$pedido)
				-> andWhere('inactivo = 0');
		$pedido = $qPedido -> execute() -> toArray();
		//Obtengo informacion de la tabla cliente
		$qCliente = Doctrine_Query::create()
				-> select('*')
				-> from('cliente')
				-> where('id = ?',$pedido[0]['id_cliente'])
				-> andWhere('inactivo = 0');
		$cliente = $qCliente -> execute() -> toArray();
		//Obtengo informacion de la tabla precio_art
		$qPrecioArt = Doctrine_Query::create()
				-> select('*')
				-> from('precioArt')
				-> where('id_art = ?',$id)
				-> andWhere('id_tipo_cliente = ?',$cliente[0]['id_tipo'])
				-> andWhere('inactivo = 0');
		$precioArt = $qPrecioArt -> execute() -> toArray();
		//Obtengo informacion de la tabla precio
		$qPrecio = Doctrine_Query::create()
				-> select('*')
				-> from('precios')
				-> where('id = ?',$precioArt[0]['id_precio'])
				-> andWhere('inactivo = 0');
		$precio = $qPrecio -> execute() -> toArray();
		//Obtengo informacion de la tabla articulos_pedido
		$qArticulosPedido = Doctrine_Query::create()
				-> select('*')
				-> from('ArticulosPedido')
				-> where('id_pedido = ?',$pedido[0]['id'])
				-> andWhere('id_articulo = ?', $id)
				-> andWhere('inactivo = 0');
		$articulosPedido = $qArticulosPedido -> execute() -> toArray();
		
		$salida = array(
			"codigo" => $qArticulo->codigo,
			"nombre" => $qArticulo->nombre,
			"foto" => $fotos[0]['direccion'].'thumbs/'.$fotos[0]['descripcion'].'.jpg',
			"precio" => $precio[0]['precio'],
			"cantidad" => $articulosPedido[0]['cantidad'],
			"id_articulo" => $articulosPedido[0]['id'],
		);
		if (count($salida) >= 1) {
			echo json_encode($salida);
		} else {
			echo "[]";
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
