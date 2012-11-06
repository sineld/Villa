<?php
	
	
require_once('public_action/tipos_handler.php');
require_once('public_action/categorias_handler.php');
require_once('public_action/articulos_handler.php');
require_once('public_action/tipo_cliente_handler.php');
require_once('public_action/precios_handler.php');
require_once('public_action/precio_art_handler.php');
require_once('public_action/fotos_handler.php');
require_once('public_action/fotos_art_handler.php');
class ArticuloDelegate {
	

	public function setArticulos($validator) {
		$handler = new articulos_handler;
		$tc_handler = new tipo_cliente_handler;
		$prec_handler = new precios_handler;
		$prec_art_handler = new precio_art_handler;
		$nombre = $validator->getVar('nombre');
		$codigo = mb_strtoupper($validator->getVar('codigo'), 'UTF-8');
		$descripcion = $validator->getVar('descripcion');
		$alto = (float)$validator->getOptionalVar('alto');
		$ancho = (float)$validator->getOptionalVar('ancho');
		$largo = (float)$validator->getOptionalVar('largo');
		$diametro = (float)$validator->getOptionalVar('diametro');
		$peso = (float)$validator->getOptionalVar('peso');
		$empaque = (int)$validator->getOptionalVar('empaque');
		$categoria = $validator->getVar('categorias');
		$tipo = $validator->getVar('tipos');
		$id_art = $handler->setArticulo($nombre, $codigo, $descripcion, $alto, $ancho, $largo, $diametro, $peso, $empaque, $categoria, $tipo);
		$data = json_decode($tc_handler->getTipoClientes(null,null,0));
		foreach ($data as $d){
			$id_tipo_cliente = $d->{'id'};
			$precio = (float)$validator->getVar($d->{'nombre'});
			$id_precio = $prec_handler->setprecio($precio);
			$id_precio_art = $prec_art_handler->setprecio_art($id_art, $id_precio, $id_tipo_cliente);
		}
		if ($id_precio_art != false){
			echo '{"id":'.$id_art.'}';
		}
		else{
			echo 'false';
		}
		return 'void';
	}
	
	public function getArticulos($validator){
		$art_handler = new articulos_handler;
		$fot_art_handler = new fotos_art_handler;
		$fot_handler = new fotos_handler;
		$precio_art_handler = new precio_art_handler;
		$precio_handler = new precios_handler;
		$paginaActual = $validator->getOptionalVar('pagina');
		$id = $validator->getOptionalVar('id');
		$nombre = $validator->getOptionalVar('nombre');
		$codigo = $validator->getOptionalVar('codigo');
		$descripcion = $validator->getOptionalVar('descripcion');
		$alto = $validator->getOptionalVar('alto');
		$ancho = $validator->getOptionalVar('ancho');
		$largo = $validator->getOptionalVar('largo');
		$diametro = $validator->getOptionalVar('diametro');
		$peso  = $validator->getOptionalVar('peso');
		$empaque  = $validator->getOptionalVar('empaque');
		$fechaingreso = $validator->getOptionalVar('fechaingreso');
		$agotado = $validator->getOptionalVar('agotado');
		if ($validator->getOptionalVar('inactivo')!= null && $validator->getOptionalVar('inactivo') != 'null'){
			$inactivo = $validator->getOptionalVar('inactivo');
		}else {
			$inactivo = -1;
		}
		if ($validator->getOptionalVar('porPagina')!= null && $validator->getOptionalVar('porPagina') != 'null'){
			$porPagina = $validator->getOptionalVar('porPagina');
		}else {
			$porPagina = null;
		}
		$categoria = $validator->getOptionalVar('categoria');
		$tipo = $validator->getOptionalVar('tipo'); 
		$articulos = json_decode($art_handler->getArticulos($id,$nombre,$codigo,$descripcion,$alto,$ancho,$largo,$diametro,$peso, $empaque,$fechaingreso,$inactivo,$agotado,$categoria,$tipo,$paginaActual,$porPagina));
		
		foreach ($articulos as $art){
			$id_art = $art->{'id'};
			$cat = Doctrine_Core::getTable('Categorias')->find($art->{'categoria'});
			$art->{'categoria'} = $cat->nombre;
			$tipo = Doctrine_Core::getTable('Tipos')->find($art->{'tipo'});
			$art->{'tipo'} = $tipo->nombre;
			$id_precios_art = json_decode($precio_art_handler->getprecio_art(null,$id_art));
			foreach ($id_precios_art as $id_precio_art){
				$id_precio = $id_precio_art->{'id_precio'};
				$id_tipo_cliente = $id_precio_art->{'id_tipo_cliente'};
				$precio = json_decode($precio_handler->getPrecios($id_precio));
				$art->{'precio'}->{$id_tipo_cliente} = $precio;
			}
			$art->{'cantidad_fotos'} = $fot_art_handler->countFotos($id_art);
			$id_fotos_art = json_decode($fot_art_handler->getFotosArt(null,$id_art));
			$count = 0;
			foreach ($id_fotos_art as $id_foto_art){
				$id_foto = $id_foto_art->{'id_foto'};
				$foto = json_decode($fot_handler->getFotos($id_foto));
				$art->{'foto'}->{$count} = $foto;
				$count++;
			}
		}
		echo json_encode($articulos);
	}
	
	public function getArticulosCompleto($validator){
		$art_handler = new articulos_handler;
		$fot_art_handler = new fotos_art_handler;
		$fot_handler = new fotos_handler;
		$precio_art_handler = new precio_art_handler;
		$precio_handler = new precios_handler;
		$categoria_handler= new categorias_handler();
		$tipo_handler = new tipos_handler;
		$id_art = $validator->getVar('id');
		$id_tipo_cliente = $validator->getOptionalVar('id_tipo_cliente');
		$art = json_decode($art_handler->getArticulos($id_art,null,null,null,null,null,null,null,null,null,null,0));
		if ($id_tipo_cliente != null){
				$id_precios_art = json_decode($precio_art_handler->getprecio_art(null,$id_art,null,$id_tipo_cliente,0));
				if(count($id_precios_art)>0) {
					$precio = json_decode($precio_handler->getPrecios($id_precios_art[0]->{'id_precio'},null,0));
					$art[0]->{'precio'} = $precio[0]->{'precio'};	
				}else {
					$art[0]->{'precio'} = null;
				}
			}else {
				$art[0]->{'precio'} = null;
			}
			$id_fotos_art = json_decode($fot_art_handler->getFotosArt(null,$id_art,null,null,0));
			//echo $fot_art_handler->getFotosArt(null,$id_art,null,null,0).'<p>';
			if (count($id_fotos_art)>0){
				for ($i = 0; $i < count($id_fotos_art); $i++){
					//echo $id_fotos_art[$i]->{'id_foto'};
					//echo $fot_handler->getFotos($id_fotos_art[$i]->{'id_foto'},null,null).'<p>';
					$foto = json_decode($fot_handler->getFotos($id_fotos_art[$i]->{'id_foto'},null,null,0));
					$dir_fotos = $GLOBALS['baseURL'].$foto[0]->{'direccion'}.$foto[0]->{'descripcion'}.'.jpg';
					$art[0]->{'foto'}->{$i} = $dir_fotos;
				}
				
			}else {
				$art[0]->{'foto'}->{0} = $GLOBALS['baseURL'].'images/no_disponible.jpg';
			}
			$tipo = json_decode($tipo_handler->getTipos(-1,$art[0]->{'tipo'}));
			$art[0]->{'tipo'} = $tipo[0]->{'nombre'};
			$art[0]->{'categoria'} = $tipo[0]->{'Categorias'}->{'nombre'};
			echo json_encode($art);
	}

	public function getArticulosCorto($validator){
		$art_handler = new articulos_handler;
		$fot_art_handler = new fotos_art_handler;
		$fot_handler = new fotos_handler;
		$precio_art_handler = new precio_art_handler;
		$precio_handler = new precios_handler;
		$categoria = $validator->getOptionalVar('categoria');
		$tipo = $validator->getOptionalVar('tipo');
		$paginaActual = $validator->getOptionalVar('pagina');
		$id_tipo_cliente = $validator->getOptionalVar('id_tipo_cliente');
		$art = json_decode($art_handler->getArticulos(null,null,null,null,null,null,null,null,null,null,null,0,0,$categoria,$tipo,$paginaActual));
		$articulos = array();
		for ($i = 0; $i < count($art); $i++){
			$articulos[$i]['id'] = $art[$i]->{'id'};
			$articulos[$i]['codigo'] = $art[$i]->{'codigo'};
			$articulos[$i]['nombre'] = ucfirst($art[$i]->{'nombre'});
			if ($art[$i]->{'agotado'} == 0){
				$articulos[$i]['agotado'] = 'Disponible';
			} else {
				$articulos[$i]['agotado'] = 'Agotado';
			}
			/*$articulos[$i]['categoria'] = $art[$i]->{'categoria'};
			$articulos[$i]['tipo'] = $art[$i]->{'tipo'};*/
			$id_art = $art[$i]->{'id'};
			if ($id_tipo_cliente != null){
				$id_precios_art = json_decode($precio_art_handler->getprecio_art(null,$id_art,null,$id_tipo_cliente,0));
				if(count($id_precios_art)>0) {
					$precio = json_decode($precio_handler->getPrecios($id_precios_art[0]->{'id_precio'},null,0));
					$articulos[$i]['precio'] = $precio[0]->{'precio'};	
				}else {
					$articulos[$i]['precio'] = null;
				}
			}else {
				$articulos[$i]['precio'] = null;
			}
			$id_fotos_art = json_decode($fot_art_handler->getFotosArt(null,$id_art,null,null,0));
			if (count($id_fotos_art)>0){
				$foto = json_decode($fot_handler->getFotos($id_fotos_art[0]->{'id_foto'},null,null,0));
				$articulos[$i]['foto'] = $GLOBALS['baseURL'].$foto[0]->{'direccion'}.'thumbs/'.$foto[0]->{'descripcion'}.'.jpg';
			}else {
				$articulos[$i]['foto'] = $GLOBALS['baseURL'].'images/no_disponible.jpg';
			}
		}
		echo json_encode($articulos);
	}

	public function updateArticulos($validator) {
		$handler = new articulos_handler;
		$tc_handler = new tipo_cliente_handler;
		$prec_handler = new precios_handler;
		$prec_art_handler = new precio_art_handler;
		$id_art = $validator->getVar('id_art');
		$nombre = $validator->getVar('nombre');
		$codigo = mb_strtoupper($validator->getVar('codigo'), 'UTF-8');
		$descripcion = $validator->getVar('descripcion');
		if ($validator->getOptionalVar('alto')!= null && $validator->getOptionalVar('alto')!='null'&& $validator->getOptionalVar('alto')!=0){
			$alto = $validator->getOptionalVar('alto');
		}else {
			$alto = 'null';
		}
		if ($validator->getOptionalVar('ancho')!= null && $validator->getOptionalVar('ancho')!='null'&& $validator->getOptionalVar('ancho')!=0){
			$ancho = $validator->getOptionalVar('ancho');
		}else {
			$ancho = 'null';
		}
		if ($validator->getOptionalVar('largo')!= null && $validator->getOptionalVar('largo')!='null'&& $validator->getOptionalVar('largo')!=0){
			$largo = $validator->getOptionalVar('largo');
		}else {
			$largo = 'null';
		}
		if ($validator->getOptionalVar('diametro')!= null && $validator->getOptionalVar('diametro')!='null'&& $validator->getOptionalVar('diametro')!=0){
			$diametro = $validator->getOptionalVar('diametro');
		}else {
			$diametro = 'null';
		}
		if ($validator->getOptionalVar('peso')!= null && $validator->getOptionalVar('peso')!='null'&& $validator->getOptionalVar('peso')!=0){
			$peso = $validator->getOptionalVar('peso');
		}else {
			$peso = 'null';
		}
		if ($validator->getOptionalVar('empaque')!= null && $validator->getOptionalVar('empaque')!='null'&& $validator->getOptionalVar('empaque')!=0){
			$empaque = $validator->getOptionalVar('empaque');
		}else {
			$empaque = 1;
		}
		$categoria = $validator->getVar('categorias');
		$tipo = $validator->getVar('tipos');
		$agotado = (int)$validator->getVar('agotado')-1;
		$inactivo = (int)$validator->getVar('inactivo')-1;
		$handler->updateArticulo($id_art, $nombre, $codigo, $descripcion, $alto, $ancho, $largo, $diametro, $peso, $empaque, $categoria, $tipo, $agotado, $inactivo);
		$data = json_decode($tc_handler->getTipoClientes(null,null,0));
		foreach ($data as $d){
			$id_tipo_cliente = $d->{'id'};
			$precio = (float)$validator->getVar($d->{'nombre'});
			$precio_art = json_decode($prec_art_handler->getprecio_art(null,$id_art,null,$id_tipo_cliente));
			$prec_handler->updatePrecio($precio_art[0]->{'id_precio'}, $precio, $inactivo);
		}
		echo 'true';
		return 'void';
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
	
	public function getTipos($validator) {
		$handler = new tipos_handler();
		$inactivo = $validator->getOptionalVar('inactivo');
		$id = $validator->getOptionalVar('id');
		$id_cat = $validator->getOptionalVar('categorias');
		$nombre = $validator->getOptionalVar('nombre');
		$paginaActual = $validator->getOptionalVar('pagina');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo = -1;			
		}
		echo $handler->getTipos($inactivo, $id, $id_cat, $nombre, $paginaActual);
	}

	public function getPaginationArt($validator) {
		$handler = new pagination_handler();
		$categoria = $validator->getOptionalVar('categoria');
		$tipo = $validator->getOptionalVar('tipo');
		$pp = $validator->getOptionalVar('porPagina');
		$inac = $validator->getOptionalVar('inactivo');
		$array = array();
		if($categoria!=null && $categoria != 'null'){
			$array['categoria'] = $categoria;
		}
		if($tipo!=null && $tipo != 'null'){
			$array['tipo'] = $tipo;
		}
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
		if (($tipo==null || $tipo == 'null') && ($categoria==null || $categoria == 'null')){ $array = null;}
		echo $handler->getPagination('articulos',$array,$porPagina,$inactivo);
	}
	
	public function getCategoriasArray($validator){
		$handler = new categorias_handler();
		return json_decode($handler->getCategorias(null,null,null,0));
	}
	
	public function buscador($validator){
		$inactivo = $validator->getOptionalVar('inactivo');
		if ($inactivo == 0){
			$q = Doctrine_Query::create()
	    		->from('Articulos')
				->where('inactivo = 0');
		}else if ($inactivo == 1){
			$q = Doctrine_Query::create()
	    		->from('Articulos')
				->where('inactivo = 1');
		}else {
			$q = null;
		}
		$buscar = $validator->getOptionalVar('index');
		$query = Doctrine_Core::getTable('Articulos')->search($buscar,$q);
		$newsItems = $query->execute();

		echo json_encode($newsItems->toArray());
	}
	public function añadirArticuloSesion($validator){
		if(isset($_SESSION['cliente']->articulos)){
			$match = false;
			$datos = $_SESSION['cliente']->articulos;
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
			$_SESSION['cliente']->articulos = $datos;
		}
		else {
			$_SESSION['cliente']->articulos = array(
			(string)$validator->getVar('id_articulo') => (int)$validator->getVar('cantidad'),
			);
		}		
		echo json_encode($_SESSION['cliente']->articulos);
		return 'void';
	}
	public function añadirItemPedido($validator){
		try{		
			$pedido = new Pedidos;
			$cliente = $pedido->infoCliente($_SESSION['cliente']->id);
			$parametros = array(
				'id_articulo' => $validator->getVar('id_articulo'),
				'cantidad' => $validator->getVar('cantidad'),
				'forma_pago' => 0,
				'tipo_pago' => $cliente['credito'],
				'id_cliente' => $cliente['id'],
				'inactivo' => 0,
			);
			foreach($parametros as $verificar){
				if(!is_numeric($verificar)) return 'error, un dato ingresado no es numerico.';
			}
			if (isset($_SESSION['cliente']->pedido) and ((int)$_SESSION['cliente']->pedido >= 0)) //Si existe la variable global de pedido, añade un item a dicho pedido. 
			{
				$parametros['id_pedido'] = $_SESSION['cliente']->pedido;
				$salida['articulo'] = $pedido->newItemPedido($parametros);
				if(!is_numeric($salida['articulo'])) return 'error al ingresar el item al pedido';
			}
			else //Si no existe la variable global con el numero de pedido, llama a crear un pedido nuevo y luego añadir un item nuevo.
			{
				$salida['pedido'] = $pedido->newPedido($parametros);
				$_SESSION['cliente']->pedido = (int)$salida['pedido'];
				$parametros['id_pedido'] = (int)$salida['pedido'];
				$salida['articulo'] = $pedido->newItemPedido($parametros);
				if(!is_numeric($salida['articulo']) or !is_numeric($salida['pedido'])) return 'error al ingresar item/pedido nuevo.';
			}
			echo 'success';
			return 'void';
			}
		catch (Exception $e){
			return 'Message: ' .$e->getMessage();
		}
	}
}
?>