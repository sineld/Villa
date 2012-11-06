<?php
if(isset($_SESSION['cliente']->articulos) and count($_SESSION['cliente']->articulos)>=1){
?>
<div class="span12">
	<div class="page-header" style="margin-bottom: 0px">
		<h4>Resumen de Pedido</h4>
	</div>
</div>
<div class="span12 row" style="padding-top: 20px;">
	<div class="span2 well borde" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li class="nav-header"><strong>ORDEN ACTUAL</strong></li>
			<?php if($_SESSION['cliente']->status == 'procesado') { echo '<li class="disabled"><a>Procesar</a></li>';} else { echo '<li><a href="'.$GLOBALS['baseURL'].'procesar-pedidos">Procesar</a></li>';} ?>
			<li><a href="#">Cancelar</a></li>
			<li class="nav-header"><strong>USUARIO</strong></li>
			<li><a href="#">Cerrar Sesion</a></li>
		</ul>
	</div>
		
	<div class="span9 borde" style="padding: 10px; border: 1px solid #E3E3E3">
		<div>
			<table id='tabla-pedido' class="table table-bordered table-striped table-hover">
				<thead>
					<tr>
						<th><h6>Codigo</h6></th>
						<th><h6>Descripcion</h6></th>
						<th><h6>Imagen</h6></th>
						<th><h6>Precio unitario</h6></th>
						<th><h6>Cantidad</h6></th>
						<th><h6>Subtotal</h6></th>
						<th width='15px'></th>				
					</tr>
				</thead>
				<tbody>
<?php
	$articulos = $_SESSION['cliente']->articulos;
	$cliente = $_SESSION['cliente'];
	$total = 0;
	foreach($articulos as $key => $value){
			$qArticulo = Doctrine::getTable('articulos')->findOneById($key);
			$qFotosArt = Doctrine_Query::create()
				-> select('*')
				-> from('fotosArt')
				-> where('id_art = ?',$key)
				-> andWhere('inactivo = 0');
			$fotosArt = $qFotosArt -> execute() -> toArray();
			$qFotos = Doctrine_Query::create()
				-> select('*')
				-> from('fotos')
				-> where('id = ?',$fotosArt[0]['id_foto'])
				-> andWhere('inactivo = 0');
			$fotos = $qFotos -> execute() -> toArray();
			$qPrecioArt = Doctrine_Query::create()
				-> select('*')
				-> from('precioArt')
				-> where('id_art = ?',$key)
				-> andWhere('id_tipo_cliente = ?',$cliente->id_tipo)
				-> andWhere('inactivo = 0');
			$precioArt = $qPrecioArt -> execute() -> toArray();
			//Obtengo informacion de la tabla precio
			$qPrecio = Doctrine_Query::create()
				-> select('*')
				-> from('precios')
				-> where('id = ?',$precioArt[0]['id_precio'])
				-> andWhere('inactivo = 0');
			$precio = $qPrecio -> execute() -> toArray();
			echo '<tr>';
			echo '<td>'.$qArticulo->codigo.'</td>';
			echo '<td>'.$qArticulo->nombre.'</td>';
			echo '<td><img class="thumbnail" src="'.$GLOBALS['baseURL'].''.$fotos[0]['direccion'].'thumbs/'.$fotos[0]['descripcion'].'.jpg" /></td>';
			echo '<td>'.number_format($precio[0]['precio'],2,".",",").' Bs.</td>';
			echo '<td><input attr="'.$key.'" type="text" id="cantidad-'.$key.'" class="span1" value="'.$value.'"/></td>';
			$subtotal = $precio[0]['precio']*$value;
			echo '<td>'.number_format($subtotal,2,".",",").' Bs.</td>';
			echo '<td><img class="boton-eliminar" attr="'.$key.'" style="cursor: pointer;" width="100%" src="eliminar.png" /> </td>';
			echo '</tr>';
			$total += $subtotal;
			
	}
	echo '</tbody>';
	echo '<tfoot>';
	echo '<tr>';
	echo '<td colspan="5">Subtotal:</td>';
	echo '<td colspan="2">'.number_format($total,2, ".",",").' Bs.</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="5">I.V.A:</td>';
	echo '<td colspan="2">'.number_format($total*0.12,2,".",",").' Bs.</td>';
	echo '</tr>';
	echo '<tr>';
	echo '<td colspan="5">Total:</td>';
	echo '<td colspan="2">'.number_format($total*1.12, 2, ".", ",").' Bs.</td>';
	echo '</tr>';
	echo '</tfoot>';

				
?>
					
				
			</table>
		</div>
	</div>
</div>
<?php
}else{
?>
<div class="span12">
	<pre><p>No hay pedido definido</p></pre>
</div>
<?php
}
?>
<script type="text/javascript">


var tipo, cat, id_tipo_cliente;



</script>