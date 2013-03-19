<div class="filtros-busqueda">
	
	<div class="page-header">
		Filtros de Búsqueda
	</div>

	<div class="row">
		<div class="span8">	
				<form name="busquedaPedidos" id="busquedaPedidos" action="<?php echo $GLOBALS["baseURL"];?>crud.php" method="POST">
				<input type="hidden" name="view" value="listar-pedidos" />
				<input type="hidden" name="action" value="buscarPedidos" />
				<fieldset>
					<label for="nombre_cliente">Busqueda por cliente</label>
					<input name="id" id="nombre_cliente" class="input-xxlarge" type="hidden" />
				</fieldset>
		</div>
		<div class="span2">
			<input type="submit" id="boton_buscar_pedidos" style="position: relative; margin-top: 23px;" class="btn btn-success" value="Buscar" />
		</div>
	</div>
	<div class="row">
		<div class="span3">
			<fieldset>
				<label>Estado</label>
				<select id="estado_pedido" name="estado">
					<option value="NO DATA">Todos</option>
					<option value="0">Anulado</option>
					<option value="1">Procesado</option>
					<option value="2">Pendiente</option>
				</select>
			</fieldset>
		</div>
		<div class="span3">
			<fieldset>
				<label>Credito</label>
				<select id="credito_pedido" name="credito">
					<option value="NO DATA">Todos</option>
					<option value="0">Sin credito</option>
					<option value="15">15 Dias</option>
					<option value="30">30 Dias</option>
				</select>
			</fieldset>
		</div>
		<div class="span3">
			<fieldset>
				<label for="formaPago_pedido">Forma de pago</label>
				<select id="formaPago_pedido" name="formaPago">
					<option value="NO DATA">Todos</option>
					<option value="0">Efectivo</option>
					<option value="1">Deposito</option>
					<option value="2">Cheque</option>
				</select>
			</fieldset>
			</form>
		</div>
		
	</div>
</div>
<div class="page-header">
	Resultados
</div>
<div id="lista_pedidos"></div>
<script type="text/javascript">

$(document)
	.on('click','.boton-fact',function(event){
		event.preventDefault();
	})
	.on('click','.tabla_id_pedido',function(event){
		event.preventDefault();
		valor = $(this).attr('attr');
		prueba = <?php echo '"'.$GLOBALS["baseURL"].'"';?>;
		window.location = prueba+'detalle-pedidos&id_pedido='+valor;
	})
	.on('change','#nombre_cliente',function(event){
		if($('#boton_buscar_pedidos').hasClass('disabled')){
			$('#boton_buscar_pedidos').removeClass('disabled');
		}
	})
	.on('click','#guardar_cambios_modal',function(event){
		event.preventDefault();
		alert('bla');
	})
	.on('click','#boton_buscar_pedidos',function(event){
		event.preventDefault();
		$('#busquedaPedidos').ajaxSubmit({
			success : function(data){
				data = JSON.parse(data);
				var html = '<table class="table table-bordered"><thead><tr><th>Nº Pedido</th><th>Ultima modificacion</th><th>Credito</th><th>Forma de pago</th><th>Fact N°</th><th>Total Bs.</th><th>Status</th></tr></thead><tbody>';
				count = 0;
				for (var i in data) {
					count++
					 html += renderPedidos(data[i]);
				}
				if (count == 0){
					html = '<div class="alert alert-block span10">';
					html += '<button type="button" class="close" data-dismiss="alert">×</button>';
					html += '<h4>Error</h4>';
					html += 'No se encuentra informacion para mostrar';
					html += '</div>';
				}else{
					html += '</tbody></table>';	
				}
				$('#lista_pedidos').html('');
				$(html).appendTo('#lista_pedidos');
			}
		});
	});

$(function(){
	$('#form_factData').validate({
		onsubmit: false,
		debug: true,
		rules: {
			input_monto: {
				required: true,
				number: true
			},
			input_fact: {
				required: true,
				digits: true
			},
			seleccion_chofer: {
				required: true,
			},
		},
		messages: {
			input_monto: {
				required: 'El campo: "Total Bs." es requerido.',
				number: 'Existe un error en el monto de la factura, verificar que solo sean números.'	
			},
			input_fact: {
				required: 'El campo: "N° Fact:" es requerido',
				digits: 'Existe un error en el número de factura, verificar que solo sean números.'
			},
			seleccion_chofer: {
				required: 'Hay un error en la selección del chofer empleado.'
			}
		},
		errorPlacement: function(error,element){
			error.appendTo('.error_container').addClass('alert-danger');
		}
	});
	$('#nombre_cliente').select2({
		allowClear: true,
		minimumInputLength: 2,
		placeholder: 'Nombre del cliente',
		ajax: {
			quietMillis: 100,
			type : "POST",
    		url : "crud.php",
    		dataType : "json",
    		data : function(term,page){
    			return {
    				view : 'listar-pedidos',
    				action : 'busquedaCliente',
    				term : term,
    				campo : 'nombre'
    			}
			},
			results : function(data,page){
				var key, count = 0;
				for(key in data){
					count++;
				}
				var more = (page*10) < count;
				return {
					results : data,
					more : more
				}
			}	
		},
		formatSelection : function(items){
			return items.nombre;
		},
		formatResult: function(items){
			markup = '';
			markup += '<div>'+items.nombre+'</div>';
			markup += '<div>'+items.direccion+'</div>';
			return markup;
		}
	});	 
});

function renderPedidos(data){
	switch(data["estado"]){
		case '0':
		var content = '<tr class="error">';
		break;
		case '1':
		var content = '<tr class="warning">';
		break;
		case '3':
		var content = '<tr class="success">';
		break;
		default:
		var content = '<tr class="error">';
		break;	
	}
	content += '<td class="tabla_id_pedido" attr="'+data["id"]+'">'+data["id"]+'</td>';
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
	if(data['fact_nro']==0){
		content += '<td><button data-toggle="modal" data-target="#modal-fact" class="btn btn-info boton-fact">Agregar</button></td>';
	}else{
		content += '<td>'+data['fact_nro']+'</td>';
	}
	if(data['monto']==0){
		content += '<td><button data-toggle="modal" data-target="#modal-fact" class="btn btn-info boton-monto" >Agregar</button></td>';
	}else{
		content += '<td>'+data['monto']+' Bs.</td>';
	}
		
	switch(data["estado"]){
		case '2':
		content += '<td>Pendiente</td>';
		break;
		case '1':
		content += '<td>Procesando</td>';
		break;
		case '3':
		content += '<td>Entregado</td>';
		break;
		default:
		content += '<td>N/D</td>';
		break;	
	}
	return content;
}
</script>
<!--MODAL PARA AGREGAR N° FACTURA -->
<form id="form_factData" action="crud.php" method="post">
<div id='modal-fact' class="modal hide fade">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3>Información del  Pedido</h3>
	</div>
	<div class="modal-body">
		<div class="row">
			<div class="seleccion-chofer span2">
				
				<label for="seleccion_chofer">Entregado por:</label>
				<select name="seleccion_chofer" id="seleccion_chofer" class="input-medium seleccion-estado">
					<option selected="selected">Sin entregar</option>
					<?php
						$query = json_decode($vars['chofer']);
						foreach($query as $key => $objeto){
							echo '<option value="'.$objeto->id.'">'.$objeto->nombre.'</option>';
						} 
					?>
				</select>
			</div>
			<div class="div-factura span1">
				<label for="input_fact">N° Fact:</label>
				<input name="input_fact" type='text' class="input-mini" id="input_fact"></input>
			</div>
			<div class="div-monto span2">
				<label for="input_monto	">Total Bs.:</label>
				<input name="input_monto" type='text' class="input-small" id="input_monto"></input>
			</div>		
		</div>
		<div class="error_container"></div>
		
	</div>
	<div class="modal-footer">
		<a href="#" data-dismiss="modal" class="btn">Cerrar</a>
		<button type="submit" href="#" id='guardar_cambios_modal' class="btn btn-primary">Guardar cambios</a>
		
	</div>
</div>
</form>
<!--MODAL PARA AGREGAR MONTO TOTAL -->