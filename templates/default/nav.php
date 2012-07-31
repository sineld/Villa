<div id="navcontainer">
	<script> 
		$(document).ready(function() { 
			$('ul.sf-menu').superfish({ 
				delay:       100,                            // one second delay on mouseout 
				animation:   {opacity:'show',height:'show'},  // fade-in and slide-down animation 
				speed:       'fast',                          // faster animation speed 
				autoArrows:  false,                           // disable generation of arrow mark-up 
	
			}); 
		});  
	</script>
	<ul class="sf-menu">
		<li class="main home"><a href="inicio">Home</a>
			<ul>
				<li>Noticias</li>
				<li>Quienes somos</li>
				<li><a title="Cat&aacute;logo" href="catalogo">Cat&aacutelogo</a></li>
			</ul>
		</li>
	<?php 
	require_once('public_action/categorias_handler.php');
	require_once('public_action/tipos_handler.php');
	$cat = new categorias_handler;
	$tip = new tipos_handler;
	$data = json_decode($cat->getCategorias(null,null,null,0));
	foreach($data as $d){
		$aux = strtoupper($d->{'nombre'});
		$id_cat = $d->{'id'};
		echo '<li class="main" id="boton_'.$aux.'"><a title="Art&iacute;culos para '.ucfirst(strtolower($aux)).'" href="catalogo&categoria='.$id_cat.'">'.ucfirst(strtolower($aux)).'</a><ul>';
		$data1 = json_decode($tip->getTipos(0, null, $d->{'id'}));
		foreach ($data1 as $d1){
			$aux1 = ucfirst($d1->{'nombre'});
			$id_tipo = $d1->{'id'};
			echo '<li><a title="'.$aux1.'" href="catalogo&categoria='.$id_cat.'&tipo='.$id_tipo.'">'.$aux1.'</a></li>';
		}
		echo '</ul></li>';
	}
?>
	</ul>
</div>