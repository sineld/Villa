<div class='span12'>
	<div class="page-header"><h4>Orden de pedido</h4></div>
	<div class="row">
		<div class="span9" id="datos_cliente">
			<div class="row">
				<legend><h5>Datos del cliente</h5></legend>
				<div class="span2">
					<form>
						<fieldset>
							<label for="codigo_cliente">Código:</label>
							<?php
								if(isset($_SESSION['vendedor']->cliente) and isset($_SESSION['vendedor']->cliente_nombre)){
									echo '<input type="hidden"  class="input-small disabled" disabled="disabled" id="codigo_cliente" readonly="readonly" value="'.$_SESSION['vendedor']->cliente.'"></input>';
								}else{
									echo '<input type="hidden"  class="input-small" id="codigo_cliente" />';
								}
							?>
						</fieldset>
					</form>
				</div>
				<div class="span4">
					<form>
						<fieldset>
							<label for="nombre_cliente">Nombre del cliente:</label>
							<?php
								if(isset($_SESSION['vendedor']->cliente) and isset($_SESSION['vendedor']->cliente_nombre)){
									echo '<input type="hidden" readonly="readonly" disabled="disabled"  class="input-xlarge disabled" id="nombre_cliente" value="'.$_SESSION['vendedor']->cliente_nombre.'"></input>';
								}else{
									echo '<input type="hidden"  class="input-xlarge" id="nombre_cliente"></input>';
								}
							?>
						</fieldset>
					</form>
				</div>
				<div class="span2">
					<button id="boton_cliente_añadir" style='position: relative; top: 25px;' class="btn btn-success disabled">Añadir</button>	
				</div>
			</div>
			
			<div class="row">
				<legend><h5>Articulos</h5></legend>
				<div class="span2">
					<form>
						<fieldset>
							<label for="searchCodigo">SKU: </label>
							<input type="text" class="input-small" id="searchCodigo" />
						</fieldset>
					</form>
				</div>
				<div class="span4">
					<form>
						<fieldset>
							<label>Descripcion: </label>
							<input type="text" class="input-xlarge" readonly="readonly"  id="item_descripcion"/>
						</fieldset>
					</form>
				</div>
				<div class="span2">
					<form>
						<fieldset>
							<label>Cantidad: </label>
							<input type="text" class="input-small" id="item_cantidad" value='1'/>
						</fieldset>
					</form>
				</div>
				<div class="span1">
					<button id="boton_item_añadir" style='position: relative; top: 25px;' class="btn btn-success disabled">Añadir</button>						
				</div>
			</div>
		</div>
		<div class="span2 well" style="padding: 8px 0;">
			<ul class="nav nav-list">
				<li class="nav-header"><strong>ORDEN ACTUAL</strong></li>
				<?php if($_SESSION['cliente']->status == 'procesado') { echo '<li class="disabled"><a>Procesar</a></li>';} else { echo '<li><a href="'.$GLOBALS['baseURL'].'procesar-pedidos">Procesar</a></li>';} ?>
				<li><a>Cancelar</a></li>
				<li class="nav-header"><strong>USUARIO</strong></li>
				<li><a href="<?php echo $GLOBALS['baseURL'].'crud.php?close_session' ?>">Cerrar Sesion</a></li>
			</ul>
		</div>
	</div>
	<div class="span10" id="datos_orden">
		<table id="tabla_articulos" class="table table-condensed table-bordered">
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
				if (isset($_SESSION['vendedor']->articulos) and count($_SESSION['vendedor']->articulos)>=1){
					$articulos = $_SESSION['vendedor']->articulos;
					$cliente = $_SESSION['vendedor'];
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
					
				} 
				?>			
		</table>
	</div>
</div>
<script type="text/javascript">  
$(document).ready(function(){
		if($('#codigo_cliente').hasClass('disabled')){
			$('#searchCodigo').focus();
		}
    	if(($('#nombre_cliente').val() == '') && ($('#codigo_cliente').val() == '')){
    		$('#searchCodigo').attr('disabled','disabled');
    		$('#item_descripcion').attr('disabled','disabled');
    		$('#item_cantidad').attr('disabled','disabled');        		
    	}
    	
    	
    	$(document).on('keydown','#item_cantidad',function(event){
    		if(event.keyCode == 13){
    			event.preventDefault();
    			$('#boton_item_añadir').focus();
    		}
    	});
    	$(document).on('click','#boton_item_añadir',function(event){
			event.preventDefault;
			if($(this).hasClass('disabled')){
				alert('No ha seleccionado ningun articulo');
			}else{
    			$.when(procesarArticuloPedido($('#item_cantidad').attr('value'),$('#item_descripcion').attr('id_codigo'),vista))
    				.then(function(a1){
    					location.reload();
    			});
			}
		});
		$(document).on('click','#boton_cliente_añadir',function(event){
			event.preventDefault;
			$("#codigo_cliente").attr('disabled','disabled');
			$("#nombre_cliente").attr('disabled','disabled');
			$( "#codigo_cliente" ).autocomplete( "option", "disabled", true );
			$( "#nombre_cliente" ).autocomplete( "option", "disabled", true );
			$('#boton_cliente_añadir').addClass('disabled');
			$('#searchCodigo').val('').focus();
			$('#searchCodigo').removeAttr('disabled');
    		$('#item_descripcion').removeAttr('disabled');
    		$('#item_cantidad').removeAttr('disabled');
    		$.ajax({
    			type : "POST",
    			url : "crud.php",
    			dataType : "json",
    			data : {
    				view : 'vendedor-pedidos',
    				action : 'setCliente',
    				id : $('#codigo_cliente').attr('id_cliente'),
    				cliente_nombre : $('#nombre_cliente').val()
    			}
    		});
		});
		
		$('#codigo_cliente').select2({
			placeholder: 'Codigo',
			ajax: {
				type : "POST",
    			url : "crud.php",
    			dataType : "json",
    			data : function(term,page){
    				return {
    					view : 'vendedor-pedidos',
    					action : 'busquedaCliente',
    					term : term,
    					campo : 'codigo'
    				}
				},
				results : function(data,page){
					var more = (page*10) < data.total;
					return {
						results : data,
						more : more
					}
				}	
			},
			formatSelection : function(items){
				return '<span>'+items.id+'<span>';
			},
			formatResult: function(items){
				return '<span>'+items.id+' '+items.nombre+'<span>';
			}
		});
		
		$('#nombre_cliente').select2({
			placeholder: 'Nombre del cliente',
			ajax: {
				type : "POST",
    			url : "crud.php",
    			dataType : "json",
    			data : function(term,page){
    				return {
    					view : 'vendedor-pedidos',
    					action : 'busquedaCliente',
    					term : term,
    					campo : 'nombre'
    				}
				},
				results : function(data,page){
					var more = (page*10) < data.total;
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
				return items.nombre;
			}
		});

		
    	/*
    	$('#nombre_cliente').autocomplete({
    		source: function(request, response){
    			$.ajax({
    				type : "POST",
    				url : "crud.php",
    				dataType : "json",
    				data : {
    					view : 'vendedor-pedidos',
    					action : 'busquedaCliente',
    					term : request.term,
    					campo : 'nombre'
    				},
    				success : function(data){
    					response($.map(data,function(cliente){
    						return{
    							label: cliente.nombre,
    							value: cliente.nombre,
    							codigo: cliente.id	
    						}
    					}));
    				}
    			});
    		},
    		minLength: 2,
    		select: function( event, ui ) {
    			$('#codigo_cliente').val(ui.item.codigo);
    			if($('#boton_cliente_añadir').hasClass('disabled')){
    				$('#boton_cliente_añadir').removeClass('disabled');
    				$('#boton_cliente_añadir').focus();	
    			}
    			
            }
    	});
    	
    	$('#searchCodigo').autocomplete({
    		source: function(request, response){
    			$.ajax({
					type : "POST",
					url : "crud.php",
					dataType : "json",
					data : {
						view : 'vendedor-pedidos',
						action : 'busquedaItem',
						term : request.term
					},
					success : function(data){
						response($.map(data, function(item){
							return {
								label: item.codigo,
								value: item.codigo,
								nombre: item.nombre,
								id: item.id,
								precio: 'N/D'
								
							}
						}));
					}
				});
    		},
    		minLength: 2,
    		select: function( event, ui ) {
    			$('#item_descripcion').val(ui.item.nombre);
    			$('#item_descripcion').attr('id_codigo',ui.item.id);
    			$('#item_cantidad').focus();
    			if($('#boton_item_añadir').hasClass('disabled')){
    				$('#boton_item_añadir').removeClass('disabled');
    			}
            }
    	});*/
});

function procesarArticuloPedido(cant,id,vista){
	return $.ajax({
	    type : "POST",
	   	url : "crud.php",
	    dataType : "json",
	    data : {
	    	view : 'vendedor-pedidos',
	    	action : 'añadirArticuloSesion',
	    	cantidad : cant,
			id_articulo : id
   		},
   		success : function(data){
			respuesta = data;
		}
   });	
}
</script>