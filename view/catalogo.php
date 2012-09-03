
	<div class="span12 row">
	<div class="span4 breacrumb">
		<ul class="breadcrumb borde">
			<?php 
			require_once('public_action/categorias_handler.php');
			require_once('public_action/tipos_handler.php');
				echo '<li><a href="catalogo">Inicio</a><span class="divider">></span></li>';
				if (isset($_GET['categoria'])) {
					$id_cat = $_GET['categoria']; 
					$cat_handler = new categorias_handler;
			 		$cat = $cat_handler->getCategorias($id_cat,null,null,0);
					$cat_itm = json_decode($cat);
					echo '<li><a href="catalogo2012&categoria='.$id_cat.'">'.ucfirst($cat_itm[0]->{'nombre'}).'</a><span class="divider">></span</li>';
				} 
				if (isset($_GET['tipo'])) {
					$id_tipo = $_GET['tipo']; 
					$tipo_handler = new tipos_handler;
					$tipo = $tipo_handler->getTipos(0,$id_tipo,null,null,null);
					$tipo_itm = json_decode($tipo);
					echo '<li class="active"><a href="#">'.ucfirst($tipo_itm[0]->{'nombre'}).'</a></li>';
				} 
			?>		
		</ul>
	</div>
	</div>
	<div class="span12 row">
	<div class="span2 well borde" style="padding: 8px 0;">
		<ul class="nav nav-list">
		<?php 
		$cat = new categorias_handler;
		$tip = new tipos_handler;
		$data = json_decode($cat->getCategorias(null,null,null,0));
		foreach($data as $d){
			$aux = strtoupper($d->{'nombre'});
			$id_cat = $d->{'id'};
			echo '<li class="nav-header"><strong>'.ucfirst(strtolower($aux)).'</strong></li>';
			$data1 = json_decode($tip->getTipos(0, null, $d->{'id'}));
			foreach ($data1 as $d1){
				$aux1 = ucfirst($d1->{'nombre'});
				$id_tipo = $d1->{'id'};
				echo '<li categoria-id="'.$id_cat.'" tipo-id="'.$id_tipo.'"><a title="'.$aux1.'" href="catalogo2012&categoria='.$id_cat.'&tipo='.$id_tipo.'">'.$aux1.'</a></li>';
			}
		}
		?>
		</ul>
	</div>
	<div class="span8 recuadro_degradado">
		<div class="row mostrar_items" style="margin-right: -20px;">
			<div class="span4 1ra_columna"></div>
			<div class="span4 2da_columna"></div>
		</div>
		<div id="paginador" class="pagination pagination-centered"></div>		
	</div>
</div>


<script type="text/javascript">
id_tipo_cliente = <?php if (isset($_SESSION['cliente'])) { echo $_SESSION['cliente']->id_tipo; } else if (isset($_SESSION['vendedor'])) { echo $_SESSION['vendedor']->id_tipo; }else { echo 'null'; } ?>;
cat = <?php if (isset($_GET['categoria'])) { echo $_GET['categoria']; } else { echo 'null'; } ?>;
tipo = <?php if (isset($_GET['tipo'])){ echo $_GET['tipo']; } else { echo 'null'; }?>;
id_cliente_pedidos = <?php if (isset($_SESSION['cliente'])){ echo $_SESSION['cliente']->id_usuario; }  else { echo 'null';} ?>;	
</script>
<div id="display_articulo_completo" class="modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Ficha de Art&iacute;culo</h3>
  </div>
  <div class="modal-body">
    <div class="row">
    	<div class="span3">
    		<div class="page-header">
				<h5>Im&aacute;genes</h5>
			</div>
    		<ul class="thumbnails" id="item_preliminar">
    			
    		</ul>
    		<ul class="thumbnails" id="item_imagenes">
    			
    		</ul>
    	</div>
    	<div class="span5">
    		<div class="page-header">
    			<h5>C&oacute;digo de Art&iacute;culo
					<span class='label label-info' id="codigo"></span>
					<span class='label pull-right' id="agotado"></span>
				</h5>
			</div>
			<div class="well">
					<div>
						<strong id="nombre"></strong>
					</div>	
					</br>
					<div id="descripcion" style="color:gray;">
					</div>
			</div>
    		<div class="span4">
				<table class="table table-bordered" style="width: 100%">
					<thead>
						<tr>
							<th width="16%" style="text-align:center;" id="altolbl"><img src="img/alto-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="ancholbl"><img src="img/ancho-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="largolbl"><img src="img/largo-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="diametrolbl"><img src="img/diametro-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="pesolbl"><img src="img/peso-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="empaquelbl"><img src="img/unidad-ico.png" /></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="alto">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="ancho">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="largo">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="diametro">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="peso">
								<IMG SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20" ALT="No disponible">
							</td>
							<td id="empaque">
								<IMG SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20" ALT="No disponible">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
    	</div>
    </div>
  </div>
  <div class="modal-footer">
  </div>
</div>