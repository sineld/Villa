<pre>
	<div id="prueba_pedidos"></div>
</pre>
<script type="text/javascript">


$(document).ready(function() {
	getPedidos(1);
	
	
	$('#prueba_pedidos table tbody tr').live("click",function(event){
		
			valor=$(this).children('.tabla_id_pedido').attr('attr');
			event.preventDefault();
			prueba = <?php echo '"'.$GLOBALS["baseURL"].'"';?>;
			window.location = prueba+'pedidos&id_pedido='+valor;
		});
});

function getArticulosPedido(id){
	
}
function getPedidos(id) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'pedidos',
				action : 'listarPedido',
				id_cliente : id
			},
			success : function(data) {
				var html = '<table class="table table-striped"><thead><tr><th>Nº Pedido</th><th>Nº Cliente</th><th>Fecha de Creacion</th><th>Ultima modificacion</th><th>Credito</th><th>Forma de pago</th><th>Status</th></tr></thead><tbody>';
				for (var i in data) {
					 html += renderPedidos(data[i]);
				}
				html += '</tbody></table>';
				$('#prueba_pedidos').append(html);
			},
			error : function (xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                   }
		});
	}
	function renderPedidos(data){
		var content = '<tr>';
		content += '<td class="tabla_id_pedido" attr="'+data["id"]+'">'+data["id"]+'</td>';
		content += '<td>'+data["id_cliente"]+'</td>';
		content += '<td>'+data["fecha_creacion"]+'</td>';
		content += '<td>'+data["fecha_ult_mod"]+'</td>';
		switch(data["tipo_pago"]){
			case '0':
			content += '<td>Contado</td>';
			break;
			default:
			content += '<td>'+data['tipo_pago']+' Días.</td>';
			break;
		}
		switch(data["forma_pago"]){
			case '0':
			content += '<td>Efectivo</td>';
			break;
			case '1':
			content += '<td>Cheque</td>';
			break;
			case '3':
			content += '<td>Transferencia</td>';
			break;
			default:
			content += '<td>N/D</td>';
			break;
		}
		switch(data["estado"]){
			case '0':
			content += '<td>Activo</td>';
			break;
			case '1':
			content += '<td>Procesando</td>';
			break;
			case '3':
			content += '<td>Finalizado</td>';
			break;
			default:
			content += '<td>N/D</td>';
			break;	
		}
		return content;
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