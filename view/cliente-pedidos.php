<div class="span12 row" style="padding-top: 20px;">
	<div class="span2 well borde" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li class="nav-header"><strong>ORDEN ACTUAL</strong></li>
			<li><a href="#">Procesar</a></li>
			<li><a href="#">Cancelar</a></li>
			<li class="nav-header"><strong>USUARIO</strong></li>
			<li><a href="#">Cerrar Sesion</a></li>
		</ul>
	</div>
	<div class="span8 borde" style="padding: 10px;">
		<div id="prueba_pedidos">
			<table class="table table-hover table-condensed">
				<thead>
					<tr>
						<th>Codigo</th>
						<th>Descripcion</th>
						<th>Imagen</th>
						<th>Precio unitario</th>
						<th>Cantidad</th>
						<th>Total</th>
						<th>Eliminar</th>				
					</tr>
				</thead>
				<tbody id="render_pedido">
				</tbody>
			</table>
		</div>
	</div>
</div>

<script type="text/javascript">

var cat,tipo,id_tipo_cliente;
$(document).ready(function() {
	id_pedido = <?php if(isset($_SESSION['cliente']->pedido)){echo $_SESSION['cliente']->pedido;}else{echo "null";}?>; 
	$.when(getArticulosPedido(id_pedido)).then(function(){
	});
	
	$(document).on('click','.boton-eliminar',function(){
		$(this).parent().parent().remove();
		$.when(eliminarArticuloPedido($(this).attr("attr"))).done(function(){
			$(this).parent().html("");
		});
	});
});
function getArticulosPedido(id) {
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
					 	$.when(cargarArticulosPedido(data[i]['id_articulo'],id_pedido)).then(function(){
					 	$('#render_pedido').append(salidaArticulos);
					 	varTotal += parseFloat(total);
					 });
				}
				html = '<tr><td></td><td></td><td></td><td></td><td><strong>Subtotal:</strong></td><td><strong>'+varTotal.toFixed(2)+' Bs.</strong></td></tr>'
				html += '<tr><td></td><td></td><td></td><td></td><td><strong>I.V.A:</strong></td><td><strong>'+(varTotal*0.12).toFixed(2)+' Bs.</strong></td></tr>'
				iva=(varTotal*0.12).toFixed(2);
				total = (parseFloat(varTotal)+parseFloat(iva));
				html += '<tr><td></td><td></td><td></td><td></td><td><strong>Total:</strong></td><td><strong>'+total.toFixed(2)+' Bs.</strong></td></tr>'
				$('#render_pedido').append(html);
			},
			error : function (xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                   }
		});
	}
	
	function eliminarArticuloPedido(id_articulo){
		return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'pedidos',
				action : 'eliminarArticulo',
				id_articulo : id_articulo
			},
			success : function(data){
				alert($(this).html());
			}
		});
	}
	
	function cargarArticulosPedido(id, pedido){
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
				html =  '<tr>';
				html += '<td>'+data['codigo']+'</td>';
				html += '<td>'+data['nombre']+'</td>';
				html += '<td><img src="http://localhost/villa'+data['foto']+'" /></td>';
				html += '<td>'+data['precio']+' Bs. </td>';
				html += '<td><input type="text" class="span1" value="'+data['cantidad']+'"/></td>';
				html += '<td>'+(data['cantidad']*data['precio']).toFixed(2)+' Bs. </td>';
				html += '<td><img class="boton-eliminar" attr="'+data['id_articulo']+'" width="30%" src="eliminar.png" /> </td>';
				html += '</tr>';
				total = (data['cantidad']*data['precio']).toFixed(2);
				salidaArticulos = html;
			}

		});
	}
</script>