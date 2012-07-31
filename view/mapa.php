<div id="mapa">
<ul>
<?php 
	require_once('public_action/categorias_handler.php');
	require_once('public_action/tipos_handler.php');
	$cat = new categorias_handler;
	$tip = new tipos_handler;
	$inactivo = 0;
	$data = json_decode($cat->getCategorias($inactivo));
	foreach($data as $d){
		echo '<li><a class="main" title="Artículos para Perros" href="#">'.$d->{'nombre'}.'</a><ul>';
		$data1 = json_decode($tip->getTipos($inactivo, null, $d->{'id'}));
		foreach ($data1 as $d1){
			echo '<li><a title="Comederos" href="#">'.$d1->{'nombre'}.'</a></li>';
		}
		echo '</ul></li>';
	}
?>
</ul>
</div>