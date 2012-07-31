<?php
class pagination_handler {

	public $porPagina = 10;
	public function getPagination($tabla, $array = null,$pp=null, $inactivo =null) {
		require_once 'phputils/mysqlConexion.php';
		if ($pp!=null){
			$this->porPagina = $pp;
		}
		$porpagina = $this -> porPagina;
		$q = Doctrine_Query::create() -> select('*') -> from($tabla);
		$count = 0;
		if ($array != null && $array != 'null') {
			$keys = array_keys($array);
			for ($i = 0; $i < count($array); $i++) {
				$value = $array[$keys[$i]];
				if ($count == 0) {
					$q -> where($keys[$i] . ' = ?', $value);
					$count++;
				} else
					$q -> andwhere($keys[$i] . ' = ?', $value);
			}
		}if ($inactivo != null && $inactivo != 'null'){
			if ($count == 0) {
					$q -> where('inactivo = ?', $inactivo);
					$count++;
				} else
					$q -> andwhere('inactivo = ?', $inactivo);
		}
		$qArray = $q -> execute();
		if ($qArray -> count() > 0) {
			$cuenta = $qArray -> count();
			$paginas = ceil($cuenta / $porpagina);
		} else {
			$paginas = 1;
		}
		$array = array($paginas);
		return json_encode($array);
	}

}
?>