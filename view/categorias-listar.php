
<table id="listacat" class="zebra-striped"></table>
<div id="paginador"></div>

<script type="text/javascript">
	var pp = 30;
	$(document).ready(function() {
		getPaginationCat();
		getCategorias(1);
		$('.paginador').live('click', function() {
			pag = $(this).html();
			event.preventDefault();
			getCategorias(pag);
		});
	});
	function getPaginationCat(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listarcategorias',
				action : 'getPaginationCat',
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
	function getCategorias(pag) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listarcategorias',
				action : 'getCategorias',
				pagina : pag,
				porPagina : 30
			},
			success : function(data) {
				$('#listacat').html('');
				$('#listacat').append("<tr><td>Nombre</td><td>Estado</td></tr>");
				for (var i=0; i < data.length; i++) {
					 $('#listacat').append(renderCategorias(data[i]));
				 }
			}
		});
	}
	function renderCategorias(data){
		if (data["inactivo"]==1){
			var inactivo = "inactivo"
		}else {
			var inactivo = "activo"
		}
		var content = '<tr>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'modificarcategorias' ?>&id='+data["id"]+'">'+data["nombre"]+'</a></td>';
		content += '<td>'+inactivo+'</td>';
		content += '</tr>';
		return content;
	}
</script>