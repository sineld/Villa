<?php
/**
 *
 */
require_once 'public_action/pagination_handler.php';
class articulos_handler {
	/**
	 * Regresa el ID del articulo que se acaba de agregar
	 * 
	 */
	public function setArticulo($nombre, $codigo, $descripcion, $alto, $ancho, $largo, $diametro, $peso, $empaque, $categoria, $tipo) {
		require_once 'phputils/mysqlConexion.php';
		/*$nombre = strtolower($nombre);
		$codigo = strtolower($codigo);
		$descripcion = strtolower($descripcion);*/
		
		$newArticulo = new Articulos;
		$newArticulo -> nombre = $nombre;
		$newArticulo -> codigo = $codigo;
		$newArticulo -> descripcion = $descripcion;
		if ($alto != null)
			$newArticulo -> alto = $alto;
		if ($ancho != null)
			$newArticulo -> ancho = $ancho;
		if ($largo != null)
			$newArticulo -> largo = $largo;
		if ($diametro != null)
			$newArticulo -> diametro = $diametro;
		if ($peso != null)
			$newArticulo -> peso = $peso;
		if ($empaque != null)
			$newArticulo -> empaque = $empaque;
		$today = time() - 18720; 
		$mysqldate = date('Y-m-d h:i:s',$today);
		$newArticulo -> fechaingreso = $mysqldate;
		$newArticulo -> categoria = $categoria;
		$newArticulo -> tipo = $tipo;

		try {
			$newArticulo -> save();
			return $newArticulo -> id;
		} catch (Exception $e) {
			return 'false';
		}      
		
	}

	public function getArticulos($id=null,$nombre=null,$codigo=null,$descripcion=null,$alto=null,$ancho=null,$largo=null, $diametro=null,$peso=null,$emapque=null, $fechaingreso=null,$inactivo=-1,$agotado=null,$categoria=null,$tipo=null,$paginaActual=null,$porPagina=null) {
		require_once 'phputils/mysqlConexion.php';
		$handler = new pagination_handler();
		if ($porPagina == null){
			$porPagina = $handler -> porPagina;
		}
		$count = 0;
		$q = Doctrine_Query::create() -> select('*') -> from('articulos');
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
		if ($nombre != null) {
			if ($count == 0) {
				$q -> where('nombre = ?', $nombre);
				$count++;
			} else
				$q -> andwhere('nombre = ?', $nombre);
		}
		if ($codigo != null) {
			if ($count == 0) {
				$q -> where('codigo = ?', $codigo);
				$count++;
			} else
				$q -> andwhere('codigo = ?', $codigo);
		}
		if ($descripcion != null) {
			if ($count == 0) {
				$q -> where('descripcion = ?', $descripcion);
				$count++;
			} else
				$q -> andwhere('descripcion = ?', $descripcion);
		}
		if ($alto != null) {
			if ($count == 0) {
				$q -> where('alto = ?', $alto);
				$count++;
			} else
				$q -> andwhere('alto = ?', $alto);
		}
		if ($ancho != null) {
			if ($count == 0) {
				$q -> where('ancho = ?', $ancho);
				$count++;
			} else
				$q -> andwhere('ancho = ?', $ancho);
		}
 		if ($largo != null) {
			if ($count == 0) {
				$q -> where('largo = ?', $largo);
				$count++;
			} else
				$q -> andwhere('largo = ?', $largo);
		}
		if ($diametro != null) {
			if ($count == 0) {
				$q -> where('diametro = ?', $diametro);
				$count++;
			} else
				$q -> andwhere('diametro = ?', $diametro);
		}
		if ($peso != null) {
			if ($count == 0) {
				$q -> where('peso = ?', $peso);
				$count++;
			} else
				$q -> andwhere('peso = ?', $peso);
		}
		if ($empaque != null) {
			if ($count == 0) {
				$q -> where('empaque = ?', $empaque);
				$count++;
			} else
				$q -> andwhere('empaque = ?', $empaque);
		}
		if ($fechaingreso != null) {
			if ($count == 0) {
				$q -> where('fechaingreso = ?', $fechaingreso);
				$count++;
			} else
				$q -> andwhere('fechaingreso = ?', $fechaingreso);
		}
		if ($agotado != null) {
			if ($count == 0) {
				$q -> where('agotado = ?', $agotado);
				$count++;
			} else
				$q -> andwhere('agotado = ?', $agotado);
		}
		if ($categoria != null && $categoria != 'null') {
			if ($count == 0) {
				$q -> where('categoria = ?', $categoria);
				$count++;
			} else
				$q -> andwhere('categoria = ?', $categoria);
		}
		if ($tipo != null && $tipo != 'null') {
			if ($count == 0) {
				$q -> where('tipo = ?', $tipo);
				$count++;
			} else
				$q -> andwhere('tipo = ?', $tipo);
		}
		if ($paginaActual != null) {
			$q -> offset($porPagina * ($paginaActual - 1));
			$q -> limit($porPagina);
		}
		$q->orderBy('inactivo ASC,codigo');

		$qArray = $q -> execute() -> toArray();
		if (count($qArray) >= 1) {
			return json_encode($qArray);
		} else {
			return "[]";
		}
	}

	public function updateArticulo($id, $nombre, $codigo, $descripcion, $alto, $ancho, $largo, $diametro, $peso, $empaque, $categoria, $tipo, $agotado, $inactivo) {
		require_once 'phputils/mysqlConexion.php';

		$articulo = Doctrine::getTable('articulos')->findOneById($id);
		if (count($articulo)!= 0){
		$articulo->nombre = $nombre;
		$articulo->codigo = $codigo;
		$articulo->descripcion = $descripcion;
		if ($alto !='null')
			$articulo->alto = $alto;
		else
			$articulo->alto = null;
		if ($ancho !='null')
			$articulo->ancho = $ancho;
		else 
			$articulo->ancho = null;
		if ($largo !='null')
			$articulo->largo = $largo;
		else
			$articulo->largo = null;
		if ($diametro !='null')
			$articulo->diametro = $diametro;
		else
			$articulo->diametro = null;
		if ($peso !='null')
			$articulo->peso = $peso;
		else
			$articulo->peso = null;
		if ($empaque !='null')
			$articulo->empaque = $empaque;
		else
			$articulo->empaque = 1;
		$articulo->categoria = $categoria;
		$articulo->tipo  = $tipo;
		$articulo->agotado = $agotado;
		$articulo->inactivo = $inactivo;
		$today = time() - 18720; 
		$mysqldate = date('Y-m-d h:i:s',$today);
		$articulo->fechaingreso = $mysqldate;
		$articulo->save();
		return 'true';
		}else {
			return 'false';
		}
	}
}
?>