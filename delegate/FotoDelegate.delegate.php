<?php

/**
 *
 */

require_once ('public_action/fotos_handler.php');
require_once ('public_action/articulos_handler.php');
require_once ('public_action/foto_uploader.php');
require_once ('public_action/fotos_art_handler.php');
class FotoDelegate {
	public function setFoto($validator) {
		$uploader = new foto_uploader;
		$handler = new fotos_handler;
		$art_handler = new articulos_handler;
		$fa_handler = new fotos_art_handler;
		$id_art = $validator->getVar('id_art');
		$art = json_decode($art_handler->getArticulos($id_art,null,null,null,null,null,null,null,null,null,0));
		$nombretmp = $art[0]->{'codigo'};
		$i = 0;
		foreach (array_keys($_FILES) as $keys) {
			if (($keys != 'view') && ($keys != 'action')) {
				if ($_FILES[$keys]["error"] == 0) {
					
					$foto = $_FILES[$keys]['tmp_name'];
					$direccion = getcwd() . '/fotos2/' . $nombretmp . '/';
					$direccion2 = '/fotos2/' . $nombretmp . '/';
					$glob = glob($direccion . "*.jpg");
					if ($glob[0]== ''){
						$cuenta = 1;
					}else {
						$cuenta = count($glob);
						$cuenta += 1;
					}
					$nombre = $nombretmp . '-' . $cuenta;
					$ret = $uploader->upload($nombre, $direccion, $foto, 250);
					$ret2 =$uploader->upload($nombre, $direccion.'thumbs/', $foto, 55);
					$id_foto = $handler->setFoto($nombre,$direccion2);
					$fa_handler->setFotos_Art($id_art,$id_foto,1);
				} else {
					$i=1;
				}
			}
		}
		if($i == 0){
			echo 'true';
		}else {
			echo 'false';
		}
		return 'void';
	}

	public function getFotos($validator)
	{
		$fotos_art_handler = new fotos_art_handler;
		$fotos_handler = new fotos_handler;
		$art_handler = new articulos_handler;
		$id = $validator->getOptionalVar('id');
		$idart = $validator->getOptionalVar('idart');
		$idfoto = $validator->getOptionalVar('idfoto');
		$prioridad = $validator->getOptionalVar('prioridad');
		$inactivo = $validator->getOptionalVar('inactivo');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo=-1;
		}
		$porPagina = $validator->getOptionalVar('porPagina');
		$pagina = $validator->getOptionalVar('pagina'); 
		$fotos_art = json_decode($fotos_art_handler->getFotosArt($id,$idart,$idfoto,$prioridad,$inactivo));
		$fotos = array();
		foreach ($fotos_art as $d){
			$id_foto = $d->{'id_foto'};
			$fotos1 = json_decode($fotos_handler->getFotos($id_foto)); 
			$fotos1[0]->idart = $idart;
			array_push($fotos , $fotos1);
			//print_r($foto);
		}
		echo json_encode($fotos);
	}

	public function getFotosArt($validator)
	{
		$fotos_art_handler = new fotos_art_handler;
		$id = $validator->getOptionalVar('id');
		$idart = $validator->getOptionalVar('idart');
		$idfoto = $validator->getOptionalVar('idfoto');
		$prioridad = $validator->getOptionalVar('prioridad');
		$inactivo = $validator->getOptionalVar('inactivo');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo=-1;
		}
		$fotos_art = json_decode($fotos_art_handler->getFotosArt($id,$idart,$idfoto,$prioridad,$inactivo));
		echo json_encode($fotos_art);
	}
	
	public function updateFotos($validator)
	{
		$fotos_art_handler = new fotos_art_handler; 
		$id = $validator->getVar('id');
		$prioridad = $validator->getVar('prioridad');
		$inactivo = (int)$validator->getVar('inactivo')-1;
		echo $fotos_art_handler->updateFotosArt($id,$prioridad,$inactivo);
		return 'void';
	}

}
