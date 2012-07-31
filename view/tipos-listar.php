<div class="page-header"><h4>Tipos de Articulos</h4></div>
<table id="listatipo" class="zebra-striped"></table>
<div class="pagination" id="paginador"></div>

<script type="text/javascript">
	var pp = 30;
	$(document).ready(function() {
		getPaginationTipos();
		getTipos(1);
		$('.paginador').live('click', function() {
			pag = $(this).html();
			event.preventDefault();
			getTipos(pag);
		});
	});
	function getPaginationTipos(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listartipos',
				action : 'getPaginationTipos',
				porPagina : pp
			},
			success : function(data) {
				if (data[0]>1){
					for(var i = 1; i<=data[0]; i++)
						$('#paginador').append('<a class="paginador" href="#">'+i+'</a>');
				}
			}
		});
	}
	function getTipos(pag) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listartipos',
				action : 'getTipos',
				pagina : pag,
				porPagina : 30
			},
			success : function(data) {
				$('#listatipo').html('');
				$('#listatipo').append("<thead><th>Nombre</th><th>Categoria</th><th>Estado</th></thead>");
				for (var i=0; i < data.length; i++) {
					 $('#listatipo').append(renderTipos(data[i]));
				 }
			},
			error : function(data){
				$('#listatipo').html('errror');
			}
		});
	}
	function renderTipos(data){
		if (data["inactivo"]==1){
			var inactivo = "inactivo"
		}else {
			var inactivo = "activo"
		}
		
		var content = '<tr>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'modificartipos' ?>&id='+data["id"]+'">'+data["nombre"]+'</a></td>';
		content += '<td>'+data['Categorias']['nombre']+'</td>';
		content += '<td>'+inactivo+'</td>';
		content += '</tr>';
		return content;
	}
</script>