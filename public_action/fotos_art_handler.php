<?php 
/**
 * 
 */
class fotos_art_handler {
	
	public function setFotos_Art($id_art,$id_foto,$prioridad){
		require_once 'phputils/mysqlConexion.php';
		$newFotosArt = new FotosArt;
		$newFotosArt->id_art = $id_art;
		$newFotosArt->id_foto = $id_foto;
		$newFotosArt->prioridad = $prioridad;
		try {
			$newFotosArt -> save();
			return $newFotosArt->id;
		} catch (Exception $e) {
			return $e;
		}
	}
	
	public function getFotosArt($id = null, $id_art = null, $id_foto = null, $prioridad = null, $inactivo = -1) {
		require_once 'phputils/mysqlConexion.php';
		$q = Doctrine_Query::create() -> select('*') -> from('fotosart');
		$count = 0;
		if ($inactivo != -1) {
			if ($count == 0) {
				$q -> where('inactivo = ?', $inactivo);
				$count++;
			} else
				$q -> andwhere('inactivo = ?', $inactivo);
		}
		if ($id != null) {
			if ($count == 0) {
				$q -> where('id = ?', $id);
				$count++;
			} else
				$q -> andwhere('id = ?', $id);
		}
		if ($id_art != null) {
			if ($count == 0) {
				$q -> where('id_art = ?', $id_art);
				$count++;
			} else
				$q -> andwhere('id_art = ?', $id_art);
		}
		if ($id_foto != null) {
			if ($count == 0) {
				$q -> where('id_foto = ?', $id_foto);
				$count++;
			} else
				$q -> andwhere('id_foto = ?', $id_foto);
		}
		if ($prioridad != null) {
			if ($count == 0) {
				$q -> where('prioridad = ?', $prioridad);
				$count++;
			} else
				$q -> andwhere('prioridad = ?', $prioridad);
		}
		$q->orderBy('prioridad DESC, inactivo ASC');
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}

	public function updateFotosArt($id, $prioridad, $inactivo) {
		$foto_art = Doctrine::getTable('FotosArt')->findOneById_foto($id);
		if (count($foto_art)!= 0){
			$foto_art->prioridad = $prioridad;
			$foto_art->inactivo = $inactivo;
			$foto_art->save();
			return 'true';
		}else {
			return 'false';
		}
	}
	
	public function countFotos($id_art,  $inactivo = -1){
		$q = Doctrine_Query::create() -> select('*') -> from('fotosart')->where('id_art = ?',$id_art);
		if ($inactivo != -1) {
			$q -> andwhere('inactivo = ?', $inactivo);
		}
		$qArray = $q -> execute()->toArray();
		return count($qArray);
	}
	
}
?>