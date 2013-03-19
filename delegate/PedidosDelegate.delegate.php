<?php
require_once 'phputils/mysqlConexion.php';
class PedidosDelegate {
	public function añadirArticuloSesion($validator){
		if (isset($_SESSION['cliente'])){ $sujeto = $_SESSION['cliente']; }
		if (isset($_SESSION['vendedor'])){ $sujeto = $_SESSION['vendedor']; }
		if(isset($sujeto->articulos)){
			$match = false;
			$datos = $sujeto->articulos;
			foreach($datos as $key => &$value){
				if($key == $validator->getVar('id_articulo')){
					$value = (int)$value + (int)$validator->getVar('cantidad');
					$match = true;
					break;
				}
			}
			if(!$match){
				$datos[(string)$validator->getVar('id_articulo')] = (int)$validator->getVar('cantidad');
			}
			$sujeto->articulos = $datos;
		}
		else {
			$sujeto->articulos = array(
			(string)$validator->getVar('id_articulo') => (int)$validator->getVar('cantidad'),
			);
		}
		if (isset($_SESSION['cliente'])){ $_SESSION['cliente'] = $sujeto; echo json_encode($_SESSION['cliente']->articulos);}
		if (isset($_SESSION['vendedor'])){ $_SESSION['vendedor'] = $sujeto; echo json_encode($_SESSION['vendedor']->articulos);}
		return 'void';
	}
	
	
	//Funcion para seleccionar y asignar el id del cliente en la vista de pedidos para vendedores.
	public function setCliente($validator){
		$_SESSION['vendedor']->cliente = $validator->getVar('id');
		$_SESSION['vendedor']->cliente_nombre = $validator->getVar('cliente_nombre');
		return 'void';
	}	
	public function busquedaCliente($validator){
		$query = $validator->getVar('term');
		$campo = $validator->getVar('campo');
		$busqueda = new Cliente;
		switch($campo){
			case 'codigo':
				$respuesta = $busqueda->searchCliente_byID($query);
				break;
			case 'nombre':
				$respuesta = $busqueda->searchCliente_byNombre($query);
				break;
			default:
				$respuesta = '[]';
				break;
		}
		
		echo json_encode($respuesta);
	}
	
	public function busquedaItem($validator){
		$query = $validator->getVar('term');
		$busqueda = new Articulos;
		$precios = new Precios;
		$cliente = new Cliente;
		$respuesta = $busqueda->Busqueda_articulo($query);
		foreach($respuesta as &$item){
			//$item->precio = $precios->getPrecio($item->id,FALTA AGREGAR EL ID_TIPO_CLIENTE)
			foreach($item as $key => $value){
				if($key == 'id' or $key == 'codigo' or $key == 'nombre' ){
					
				}else{
					unset($item[$key]);
				}
			}
		}
		if (count($respuesta) >= 1) {
			echo json_encode($respuesta);
		} else {
			echo "[]";
		}	
	}
	
	public function eliminarArtSesion($validator){
		if(isset($_SESSION['cliente'])){
			unset($_SESSION['cliente']->articulos[$validator->getVar('id')]);
		}
		if(isset($_SESSION['vendedor'])){
			unset($_SESSION['vendedor']->articulos[$validator->getVar('id')]);
		}
		return 'void';
	}
	public function modArtSesion($validator){
		if(isset($_SESSION['cliente'])){
			$_SESSION['cliente']->articulos[$validator->getVar('id')] = $validator->getVar('cantidad');
		}
		if(isset($_SESSION['vendedor'])){
			$_SESSION['vendedor']->articulos[$validator->getVar('id')] = $validator->getVar('cantidad');
		} 
	}
	
	public function filtrarDatosSQL($datos,$tabla){
		//Datos es un array, donde $key es el nombre exacto en la base de datos y $value el valor que se desea buscar
		$q = Doctrine::getTable($tabla)->createQuery('u');
		$switch = false;
		$q -> select('u.*');
		foreach($datos as $key => $value){
			if($switch == false){
				$q -> where('u.'.$key.' = ?',$value);
				$switch = true;
			}else{
				$q -> andwhere('u.'.$key.' = ?',$value);
			}
		}
	}
	public function buscarPedidos($validator){
		//Llenamos los parametros con los datos pasados por el usuario
		$parametros = array(
			'id' => $validator->getOptionalVar('id'),
			'credito' => $validator->getOptionalVar('credito'),
			'formaPago' => $validator->getOptionalVar('formaPago'),
			'estado' => $validator->getOptionalVar('estado')
		);
		//Iniciamos el query sacando todos los datos de la tabla
		$switch = 'true';
		$q = Doctrine::getTable('pedidos') -> createQuery('u');
		if($parametros['id'] > 0) {
			$q -> where('u.id_cliente = ?',$parametros['id']);
			$switch = 'false';
		}else{
			$q -> select('u.*');
			$q -> where('u.inactivo = ?','0');
			$switch = 'false';
		}
		if(isset($parametros['credito']) && ($parametros['credito'] != 'NO DATA')){
			if($switch=='true'){
				$q -> where('u.tipo_pago = ?',$parametros['credito']);
				$switch = 'false';	
			}else{
				$q -> andwhere('u.tipo_pago = ?',$parametros['credito']);
			}	
		}
		if(isset($parametros['formaPago']) && ($parametros['formaPago'] != 'NO DATA')){
			if($switch=='true'){
				$q -> where('u.forma_pago = ?',$parametros['formaPago']);
				$switch = 'false';
			}else{
				$q -> andwhere('u.forma_pago = ?',$parametros['formaPago']);
			}		
		}
		if(isset($parametros['estado']) && ($parametros['estado'] != 'NO DATA')){
			if($switch=='true'){
				$q -> where('u.estado = ?',$parametros['estado']);
				$switch = 'false';
			}else{
				$q -> andwhere('u.estado = ?',$parametros['estado']);
			}		
		}
		$qArray = $q -> fetchArray();
		if (count($qArray) >= 1) {
			echo json_encode($qArray);
		} else {
			echo "[]";
		}	
		return 'void';
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
			$pedido->estado = (int)$estado; // 0 Pendiente, 1 Procesando, 2 Entregado, 3 Anulado.
			$today = time() - 18720; 
			$mysqldate = date('Y-m-d h:i:s',$today);
			$pedido->fecha_ult_mod = $mysqldate; 
			$pedido->save();
			echo 'true';
		}else{
			echo 'false';
		}
		return 'void';
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
   
	
	///////////EMPLEADOS GETTER//////////////
	
	public function getEmpleadosChofer($validator){
		$handler = new Empleado;
		return json_encode($handler->getEmpleados($id=null,$id_user=null,$nombre=null,$rif=null,1));
	}
	
	public function getEmpleadosChequeo($validator){
		$handler = new Empleado;
		return json_encode($handler->getEmpleados($id=null,$id_user=null,$nombre=null,$rif=null,2));
	}
}

?>
