<!--
Below goes all the head code you think you need to add,
never edit before this comment tag
-->
<?php if(isset($_REQUEST['view'])) {
	if (($view == 'view/articulos-modificar.php') && !isset($_GET['id'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listararticulos', true );
	}else if ($view == 'view/categorias-modificar.php' && !isset($_GET['id'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listarcategorias', true );
	}else if ($view == 'view/tipos-modificar.php' && !isset($_GET['id'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listartipos' );
	}else if ($view == 'view/tipocliente-modificar.php' && !isset($_GET['id'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listartipocliente' );
	}
	else if ($view == 'view/fotos-agregar.php' && !isset($_GET['id'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listararticulos' );
	}
	else if ($view == 'view/fotos-editar.php' && !isset($_GET['id']) && !isset($_GET['idart'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listararticulos' );
	}else if ($view == 'view/fotos-listar.php' && !isset($_GET['id'])){
		header( 'Location:'.$GLOBALS["baseURL"].'listararticulos' );
	}?>
<title>Importadora La Villa de las Mascotas, C.A.</title>

<?php }?>