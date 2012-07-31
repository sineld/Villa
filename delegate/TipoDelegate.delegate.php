<?php
/**
 *
 */
require_once('public_action/tipos_handler.php');
require_once ('public_action/pagination_handler.php');
require_once('public_action/categorias_handler.php');
class TipoDelegate {

	public function setTipo($validator) {
		$handler = new tipos_handler();
		$nombre = $validator->getVar('nombre');
		$id_cat = $validator->getVar('categoria');
		echo $handler->setTipo($nombre, $id_cat);
		return 'void';
	}

	public function getTipos($validator) {
		$handler = new tipos_handler();
		$inactivo = $validator->getOptionalVar('inactivo');
		$id = $validator->getOptionalVar('id');
		$id_cat = $validator->getOptionalVar('categorias');
		$nombre = $validator->getOptionalVar('nombre');
		$paginaActual = $validator->getOptionalVar('pagina');
		if ($validator->getOptionalVar('porPagina')!= null && $validator->getOptionalVar('porPagina') != 'null'){
			$porPagina = $validator->getOptionalVar('porPagina');
		}else {
			$porPagina = null;
		}
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo = -1;			
		}
		echo $handler->getTipos($inactivo, $id, $id_cat, $nombre, $paginaActual,$porPagina);
	}

	public function updateTipo($validator) {
		$handler = new tipos_handler();
		$id = $validator->getVar('id');
		$id_cat = $validator->getVar('categorias');
		$nombre = $validator->getVar('nombre');
		$inactivo = (int)$validator->getVar('inactivo') -1;
		echo $handler->updateTipo($id, $id_cat, $nombre, $inactivo);
		return 'void';	
	}
	
	public function getPaginationTipos($validator) {
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
		echo $handler->getPagination('tipos',null,$porPagina,$inactivo);
	}
	
	public function getCategorias($validator){
		$handler = new categorias_handler();
		$id = $validator->getOptionalVar('id');
		$nombre = $validator->getOptionalVar('nombre');
		$paginaActual = $validator->getOptionalVar('pagina');
		$inactivo = $validator->getOptionalVar('inactivo');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo = -1;			
		}
		echo $handler->getCategorias($id, $nombre, $paginaActual, $inactivo);
	}

}
?>