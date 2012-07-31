<?php
session_start();
 if(isset($_POST['accion']) && !empty($_POST['accion'])) {
	switch($_POST['accion']){
		case 'agregar':
			$agregar_articulo = new pedidos_handler;
			$art = array('codigo'=>$_POST['codigo'],'descripcion'=>$_POST['descripcion'],'imagen'=>$_POST['imagen'],'precio'=>$_POST['precio'],'cantidad'=>$_POST['cantidad']);
			$agregar_articulo->setArticulo($art);
		break;
		case 'eliminar':
			$eliminar_pedido = new pedidos_handler;
			$eliminar_pedido->unsetArticulos($_POST['codigo']);
		break;
		case 'mostrar':
			$mostrar_pedido = new pedidos_handler;
			$mostrar_pedido->mostrarArticulos();		
		break;
		case 'reiniciar':
			$reiniciar_pedido = new pedidos_handler;
			$reiniciar_pedido->reiniciarArticulos();
		break;
		case 'cambiarCant':
			$cambiarCant_pedido = new pedidos_handler;
			$cambiarCant_pedido->cambiarCantidad($_POST['codigo'],$_POST['cantidad']);
		break;
	}
 }
class pedidos_handler {	
	function setArticulo($articulo){
		$articulos = array();
		if ($_SESSION['cliente']->pedido != null){
			$articulos = $_SESSION['cliente']->pedido;
		}
		foreach($articulos as $elementoKey => $elemento){
			if($elemento['codigo'] == $articulo['codigo']){
				$articulos[$elementoKey]['cantidad'] = $articulo['cantidad'];
				$_SESSION['cliente']->pedido = $articulos;
				echo json_encode($articulos[$elementoKey]);
				return;
			}
		}
		array_push($articulos,$articulo);
		$_SESSION['cliente']->pedido = $articulos;
		echo json_encode($articulo);
	}
	
	function cambiarCantidad($cod,$cantidad){
		$articulos = array();
		if ($_SESSION['cliente']->pedido != null){
			$articulos = $_SESSION['cliente']->pedido;
			foreach($articulos as $elementoKey => $elemento){
				echo $cod;
				if($elemento['codigo'] == $cod){
					$articulos[$elementoKey]['cantidad'] = $cantidad;
					$_SESSION['cliente']->pedido = $articulos;
					echo "hola ".$cantidad;
					return;
				}
			}
		}	
	}
	
	function unsetArticulos($cod){

		if ($_SESSION['cliente']->pedido != null){
			$articulos = $_SESSION['cliente']->pedido;
			foreach($articulos as $elementoKey => $elemento){
				if($elemento['codigo'] == $cod){
					unset($articulos[$elementoKey]);
				}
			}
			$_SESSION['cliente']->pedido = $articulos;
		}
	}
	
	function mostrarArticulos(){
		if ($_SESSION['cliente']->pedido != null) {
			$articulos = $_SESSION['cliente']->pedido;
			foreach($articulos as $art){
				echo "<tr class='pedido_listado_item'>";
				echo "<td class='pl_codigo'>".$art['codigo']."</td>";
				echo "<td class='pl_nombre'>".$art['descripcion']."</td>";
				echo "<td class='pl_imagen'><img src='".$art['imagen']."'></img></td>";
				echo "<td class='pl_precio'>".$art['precio']."</td>";
				echo "<td class='pl_cantidad'><input class='pl_input' type='text' value='".$art['cantidad']."'></input></td>";
				echo "<td class='pl_eliminar'><div class='boton'>Eliminar</div></td>";
				echo "</tr>";
			}
		}
	}
	
	function reiniciarArticulos(){
		if ($_SESSION['cliente']->pedido != null) {
			unset($_SESSION['cliente']->pedido);
			session_close();
		}
	}
}
?>
