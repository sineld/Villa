
<div id="prueba_pedidos">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Codigo</th>
				<th>Descripcion</th>
				<th>Imagen</th>
				<th>Precio unitario</th>
				<th>Cantidad</th>
				<th>Total</th>				
			</tr>
		</thead>
		<tbody id="render_pedido">
		</tbody>
	</table>
</div>
<script type="text/javascript">


$(document).ready(function() {
	id_pedido = <?php if(isset($_GET['id_pedido'])){echo $_GET['id_pedido'];}else{echo null;}?>; 
	$.when(getArticulos(id_pedido)).then(function(){
	});
	
});
function getArticulos(id) {
		 return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			//async : false,
			data : {
				view : 'pedidos',
				action : 'listarArticulos',
				id_pedido : id
			},
			success : function(data) {
				varTotal = 0;
				for (var i in data) {
					 	$.when(cargarArticulos(data[i]['id_articulo'],id_pedido)).then(function(){
					 	$('#render_pedido').append(salidaArticulos);
					 	varTotal += parseFloat(total);
					 });
				}
				html = '<tr><td></td><td></td><td></td><td></td><td><h4>Subtotal:</h4></td><td><h4>'+varTotal+' Bs.F</h4></td></tr>'
				html += '<tr><td></td><td></td><td></td><td></td><td><h4>I.V.A:</h4></td><td><h4>'+(varTotal*0.12).toFixed(2)+' Bs.F</h4></td></tr>'
				iva=(varTotal*0.12).toFixed(2);
				total = (parseFloat(varTotal)+parseFloat(iva));
				html += '<tr><td></td><td></td><td></td><td></td><td><h4>Total:</h4></td><td><h4>'+total+' Bs.F</h4></td></tr>'
				$('#render_pedido').append(html);
			},
			error : function (xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                   }
		});
	}
	
	function cargarArticulos(id, pedido){
		return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			async : false,
			data : {
				view : 'pedidos',
				action : 'renderArticulos',
				id_articulo : id,
				id_pedido : pedido
			},
			success : function(data) {
				html = '<tr>';
				html += '<td>'+data['codigo']+'</td>';
				html += '<td>'+data['nombre']+'</td>';
				html += '<td><img src="http://localhost/villa'+data['foto']+'" /></td>';
				html += '<td>'+data['precio']+' Bs. </td>';
				html += '<td>'+data['cantidad']+'</td>';
				html += '<td>'+(data['cantidad']*data['precio']).toFixed(2)+' Bs. </td>';
				html += '</tr>';
				total = (data['cantidad']*data['precio']).toFixed(2);
				salidaArticulos = html;
			}

		});
	}
</script>
<!--

<form class='form-stacked' name='pedidos' method='post' action='<?php echo $GLOBALS["baseURL"];?>crud.php'>
	<input type="hidden" name="view" value="pedidos" />
	<input type="hidden" name="action" value="procesarItem" />
	<label>Cliente</label>
	<input type="text" id="id_cliente" name="id_cliente" />
	<label>Credito</label>
	<input type="text" id="tipo_pago" name="tipo_pago" />
	<label>Forma de pago</label>
	<input type="text" id="tipo_pago" name="forma_pago" />
	<label>Id Articulo</label>
	<input type="text" id="tipo_pago" name="id_articulo" />
	<label>Cantidad</label>
	<input type="text" id="tipo_pago" name="cantidad" />
	<input class="btn primary" type="submit" value="Enviar" id="enviar"/>
</form>

	
	$tipo_pago = (int)$validator->getOptionalVar('tipo_pago');
			$id_cliente = (int)$validator->getOptionalVar('id_cliente');
			$forma_pago = (int)$validator->getOptionalVar('forma_pago');
			$id_articulo = (int)$validator->getOptionalVar('id_articulo');
			$cantidad = (int)$validator->getOptionalVar('cantidad');
			
<table>
	<thead>
		<tr>
			<th class="header">#</td>
			<th class="header">C&oacute;digo</td>
			<th class="header">Descripci&oacute;n</td>
			<th class="header">Cantidad</td>
			<th class="header">P.Unidad</td>
			<th class="header">Precio</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>#</td>
			<td><input class="codigo" type="text" name="codigo"></td>
			<td><label class="descripcion" name="descripcion">Descripcion corta del art&iacute;culo</label></td>
			<td><input class="cantidad" type="text" name="cantidad"></td>
			<td><label class="preciounitario" name="preciounitario">20</label></td>
			<td><label class="precio" name="precio">20</label></td>
		</tr>
	</tbody>
</table>
-->