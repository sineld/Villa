<?php

/**
 *
 */
require_once('public_action/categorias_handler.php');
require_once ('public_action/pagination_handler.php');

class CategoriaDelegate {

	public function setCategoria($validator) {	
		$handler = new categorias_handler();
		$nombre = $validator->getVar('nombre');
		echo $handler->setCategoria($nombre);
		return 'void';
	}

	public function getCategorias($validator) {
		$handler = new categorias_handler();	
		$id = $validator->getOptionalVar('id');
		$nombre = $validator->getOptionalVar('nombre');
		$paginaActual = $validator->getOptionalVar('pagina');
		$inactivo = $validator->getOptionalVar('inactivo');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo = -1;			
		}
		if ($validator->getOptionalVar('porPagina')!= null && $validator->getOptionalVar('porPagina') != 'null'){
			$porPagina = $validator->getOptionalVar('porPagina');
		}else {
			$porPagina = null;
		}
		echo $handler->getCategorias($id, $nombre, $paginaActual, $inactivo,$porPagina);
	}

	public function getPaginationCat($validator) {
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
		echo $handler->getPagination('categorias',null,$porPagina,$inactivo);
	}

	public function updateCategoria($validator) {
		$handler = new categorias_handler();
		$id = $validator->getVar('id');
		$nombre = $validator->getVar('nombre');
		$inactivo = (int)$validator->getVar('inactivo') -1;
		echo $handler->updateCategoria($id, $nombre, $inactivo);
		return 'void';
	}

}
?>