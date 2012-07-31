<script type="text/javascript">
	$(document).ready(function() {	
		$('.nav_borde').click(function event(){
			if($('.navegacion').attr('estado')=='visible'){
				$('.navegacion').animate({'top':'-=200px'},400);
				$('.navegacion').attr('estado','oculto');
				return;
			}
			if($('.navegacion').attr('estado')=='oculto'){
				$('.navegacion').animate({'top':'+=200px'},400);
				$('.navegacion').attr('estado','visible');
				return;
			}
			
		});
	});
</script>
<div class="navegacion" estado="oculto">
	<div class="nav_contenido">
		<ul>
<?php 
	require_once('public_action/categorias_handler.php');
	require_once('public_action/tipos_handler.php');
	$cat = new categorias_handler;
	$tip = new tipos_handler;
	$data = json_decode($cat->getCategorias(null,null,null,0));
	foreach($data as $d){
		$aux = strtoupper($d->{'nombre'});
		$id_cat = $d->{'id'};
		echo '<li><a title="Art&iacute;culos para '.ucfirst(strtolower($aux)).'" href="#">'.ucfirst(strtolower($aux)).'</a><ul>';
		$data1 = json_decode($tip->getTipos(0, null, $d->{'id'}));
		foreach ($data1 as $d1){
			$aux1 = ucfirst($d1->{'nombre'});
			$id_tipo = $d1->{'id'};
			echo '<li class="clearfix"><a title="'.$aux1.'" href="#">'.$aux1.'</a></li>';
		}
		echo '</ul></li>';
	}
?>
		</ul>
	</div>
	<div class="nav_borde">
	</div>
</div>
