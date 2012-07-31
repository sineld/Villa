<?php
/**
 * 
 */
require_once('public_action/tipo_cliente_handler.php');
class TipoClienteDelegate {
	
	public function setTipoCliente($validator){
		$handler = new tipo_cliente_handler;
		$nombre = $validator->getVar('nombre');
		echo $handler->setTipoCliente($nombre);
		return 'void';
	}
	
	public function getTipoClientes($validator){
		$handler = new tipo_cliente_handler();
		$id = $validator->getOptionalVar('id');
		$nombre = $validator->getOptionalVar('nombre');
		$inactivo = $validator->getOptionalVar('inactivo');
		$paginaActual = $validator->getOptionalVar('pagina');
		$paginaActual = $validator->getOptionalVar('pagina');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo = -1;			
		}
		if ($validator->getOptionalVar('porPagina')!= null && $validator->getOptionalVar('porPagina') != 'null'){
			$porPagina = $validator->getOptionalVar('porPagina');
		}else {
			$porPagina = null;
		}
		echo $handler->getTipoClientes($id, $nombre, $inactivo, $paginaActual,$porPagina);
	}
	
	public function updateTipoCliente($validator) {
		$handler = new tipo_cliente_handler();
		$id = $validator->getVar('id');
		$nombre = $validator->getVar('nombre');
		$inactivo = (int)$validator->getVar('inactivo') -1;
		echo $handler->updateTipoCliente($id, $nombre, $inactivo);
		return 'void';	
	}
	
	public function getPaginationTipoCliente($validator) {
		$handler = new pagination_handler();
		$pp = $validator->getOptionalVar('porPagina');
		$inac = $validator->getOptionalVar('inactivo');
		if ($pp!= null && $pp != 'null'){
			$porPagina = $pp;
		}else {
			$porPagina = null;
		}
		if ($inac!= null && $inac != 'null'){
			$inactivo = $inac;
		}else {
			$inactivo = null;
		}
		echo $handler->getPagination('tipocliente',null,$porPagina,$inactivo);
	}
}

?>