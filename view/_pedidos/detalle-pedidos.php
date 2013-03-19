<div class="page-header clearfix span12">
	<h4>Detalles de pedido</h4>
</div>
<div class="row">
	<div class="span3">
		<div class="page-header">
			<h4>Menu</h4>
		</div>
		<div class="seleccion-estado">
			<label for="seleccion-status" class="seleccion-estado">Estado del pedido:</label>
			<select id="seleccion-status" class="input-medium seleccion-estado">
				<!--<?php
				$qUser = Doctrine_Query::create()
					->select('*')
					->from('user')
					->where('role_id = ?',4)
					->andwhere('status = ?','valid');
				$qUser = $qUser -> execute() -> toArray();
				foreach($qUser as $index => $valor){
					
				} 
				?>-->
				<option value="0" selected="selected">Sin procesar</option>
				<option value="1">Chequeado</option>
				<option value="2">Entregado</option>
				<option value="3">Anulado</option>
			</select>
		</div>
		<div class="procesado-estado" style="display: none;">
			<label for="procesado-status">Procesado por:</label>
			<select id="procesado-status" class="input-medium">
				<option selected="selected">Sin Procesar</option>
				<?php
					$query = json_decode($vars['chequeador']);
					foreach($query as $key => $objeto){
						echo '<option value="'.$objeto->id.'">'.$objeto->nombre.'</option>';
					} 
				?>
			</select>
		</div>
		
		<div class="entregado-estado" style="display: none;">
			<label for="chofer-status">Entregado por:</label>
			<select id="chofer-status" class="input-medium">
				<option selected="selected">Sin entregar</option>
				<?php
					$query = json_decode($vars['chofer']);
					foreach($query as $key => $objeto){
						echo '<option value="'.$objeto->id.'">'.$objeto->nombre.'</option>';
					} 
				?>
			</select>
		</div>
	</div>
	<div class="span8">
		<div class="page-header">
			<h4>Articulos</h4>
		</div>
		<?php
		if(isset($_REQUEST['id_pedido']) and $_REQUEST['id_pedido'] != 0) {
			$qArticulos = Doctrine_Query::create()
				->select('*')
				->from('articulosPedido')
				->where('id_pedido = ?',$_REQUEST['id_pedido']);
			$query = $qArticulos->execute()->toArray();
			$salida = '';
			foreach($query as $indice => $item){
				$salida .= '<tr><td>'.$item['codigo'].'</td>';
				$salida .= '<td>'.$item['descripcion'].'</td>';
				$salida .= '<td><img style="margin: auto;" class="thumbnail" src="'.$GLOBALS['baseURL'].''.$item['foto'].'" /></td>';
				$salida .= '<td style="text-align: center;">'.$item['cantidad'].'</td>';
				$salida .= '<td>'.number_format($item['precio'],2,".",",").' Bs.</td>';
				$salida .= '<td>'.number_format(($item['precio']*$item['cantidad']),2,".",",").' Bs.</td></tr>';
			}
		}else{
			$salida = '';
		}
		$html  = '<table class="table" id="tabla-articulos" data-pedido="'.$_REQUEST['id_pedido'].'">';
		$html .= '<thead>';
		$html .= '<tr>';
		$html .= '<th style="margin: auto;">Codigo</th>';
		$html .= '<th style="margin: auto;">Descripcion</th>';
		$html .= '<th style="margin: auto; text-align: center;">Imagen</th>';
		$html .= '<th style="margin: auto;">Cantidad</th>';
		$html .= '<th style="margin: auto;">P/Unidad</th>';
		$html .= '<th style="margin: auto;">Total</th>';
		$html .= '</tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$html .= $salida;
		$html .= '</tbody>';
		$html .= '</table>';
		echo $html;
		?>
	</div>
</div>
<script>
$(document).on('change','#seleccion-status',function(event){
	switch($(this).val()){
		case '1':
			$('.procesado-estado').show();
			$('.entregado-estado').hide();
		break;
		case '2':
			$('.procesado-estado').show();
			$('.entregado-estado').show();
		break;
		case '3':
			$('.procesado-estado').hide();
			$('.entregado-estado').hide();
		break;
		case '4':
			$('.procesado-estado').hide();
			$('.entregado-estado').hide();
		break;
	}
});
$(document).on('change','#procesado-status',function(event){
	event.preventDefault();
	$.when(selProcesado($('#tabla-articulos').attr('data-pedido'),1)).then(function(a1){
	},function(){
		alert('error');
	});
});
$(document).on('change','#chofer-status',function(event){
	event.preventDefault();
	$.when(selProcesado($('#tabla-articulos').attr('data-pedido'),2)).then(function(a1){
	},function(){
		alert('error');
	});
});
function selProcesado(id_pedido,id_estado){
	return $.ajax({
		type : "POST",
		url : "crud.php",
		dataType : "html",
		data : {
			view : 'detalle-pedidos',
			action : 'cambiarEstadoPedido',
			id : id_pedido,
			estado : id_estado
		},
		success : function(data){
			respuesta = data;
		}
	});
}

$(function(){});
</script>