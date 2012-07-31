<?php
/**
 *
 */
class fotos_handler {

	public function setFoto($descripcion, $direccion) {
		require_once 'phputils/mysqlConexion.php';
		$newFoto = new Fotos;
		$newFoto -> descripcion = $descripcion;
		$newFoto -> direccion = $direccion;
		try {
			$newFoto -> save();
			$return = $newFoto -> id;
		} catch (Exception $e) {
			$return = 'false';
		}
		return $return;
	}

	public function getFotos($id = null, $descripcion = null, $direccion = null, $inactivo = -1) {
		require_once 'phputils/mysqlConexion.php';
		$q = Doctrine_Query::create() -> select('*') -> from('fotos');
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
		if ($descripcion != null) {
			if ($count == 0) {
				$q -> where('descripcion = ?', $descripcion);
				$count++;
			} else
				$q -> andwhere('descripcion = ?', $descripcion);
		}
		if ($direccion != null) {
			if ($count == 0) {
				$q -> where('direccion = ?', $direccion);
				$count++;
			} else
				$q -> andwhere('direccion = ?', $direccion);
		}
		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}
	
	public function updateFoto($id,$descripcion,$direccion,$inactivo) {
		require_once 'phputils/mysqlConexion.php';
		$descripcion = '\'' . $descripcion . '\'';
		$ireccion = '\'' . $direccion . '\'';
		$q = Doctrine_Query::create() -> update('fotos') -> set('direccion', $direccion) -> set('descripcion', $descripcion) -> set('inactivo', $inactivo) -> where('id = ' . $id);
		$q -> execute();
		return 'true';
	}

}
?>