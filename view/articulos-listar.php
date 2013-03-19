<div class="page-header"><h4>Listado de Art&iacute;culos</h4></div>
<div class="hero-unit">
<table id="listaart" class="zebra-striped">
	<thead>
		<th>Codigo</th>
		<th>Nombre</th>
		<th>Categoria</th>
		<th>Tipo</th>
		<th>Modificar Fotos</th>
		<th>Agregar Fotos</th>
		<th>Estado</th>
		<th>Fecha</th>
	</thead>
	<tbody id="artbody">
	</tbody>			
</table>

<div id="paginador">
	<select id="paginador_lista">
		<option class="paginador_lista" value=0>Seleccione una p&aacute;gina</option>
	</select>
</div>
</div>
<script type="text/javascript">
	var pp = 30;
	$(document).ready(function() {
		//$("#listaart").tablesorter(); 
		getPaginationArt();
		getArticulos(1);
		$('#paginador_lista').change(function(event){
				var pag = $(this).find(':selected').attr('value');
				event.preventDefault();
				getArticulos(pag);
		});
	});
	function getPaginationArt(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listararticulos',
				action : 'getPaginationArt',
				porPagina : pp
			},
			success : function(data) {
				if (data[0]>1){
					for(var i = 1; i<=data[0]; i++)
					$('#paginador_lista').append('<option class="paginador_lista" value='+i+'>Pagina '+i+'</option>');
				}
			}
		});
	}
	function getArticulos(pag) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listararticulos',
				action : 'getArticulos',
				pagina : pag,
				porPagina : pp
			},
			success : function(data) {
				$('#artbody').html('');
				for (var i=0; i < data.length; i++) {
					 $('#artbody').append(renderArticulos(data[i]));
				 }
				 $("#listaart").trigger("update");
			}
		});
	}
	function renderArticulos(data){
		if (data["inactivo"]==1){
			var inactivo = "inactivo"
		}else {
			var inactivo = "activo"
		}
		var content = '<tr>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'modificararticulos' ?>&id='+data["id"]+'">'+data["codigo"]+'</a></td>';
		content += '<td>'+data["nombre"]+'</td>';
		content += '<td id="categoria_'+data['id']+'">'+data["categoria"]+'</td>';
		content += '<td id="tipo_'+data['id']+'">'+data["tipo"]+'</td>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'listarfotos' ?>&id='+data["id"]+'">fotos ('+data["cantidad_fotos"]+')</a></td>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'agregarfotos' ?>&id='+data["id"]+'">agregar</a></td>';
		content += '<td>'+inactivo+'</td>';
		content += '<td>'+data['fechaingreso']+'</td>';
		content += '</tr>';
		return content;
	}
</script>