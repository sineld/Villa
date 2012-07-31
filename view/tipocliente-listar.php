<div class="page-header"><h4>Tipos de Clientes</h4></div>
<div class="row">
<div class="span6 offset5">
<table id="listatipocliente" class="zebra-striped"></table>
</div>
<div id="paginador"></div>
</div>
<script type="text/javascript">
		var pp = 30;
	$(document).ready(function() {
		getPaginationTipoCliente();
		getTipoCliente(1);
		$('.paginador').live('click', function() {
			pag = $(this).html();
			event.preventDefault();
			getTipoCliente(pag);
		});
	});
	function getPaginationTipoCliente(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listartipocliente',
				action : 'getPaginationTipoCliente',
				$porPagina : pp
			},
			success : function(data) {
				if (data[0]>1){
					for(var i = 1; i<=data[0]; i++)
						$('#paginador').append('<a class="paginador" href="#">'+i+'</a>');
				}
			}
		});
	}
	function getTipoCliente(pag) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listartipocliente',
				action : 'getTipoClientes',
				pagina : pag,
				porPagina : 30
			},
			success : function(data) {
				$('#listatipocliente').html('');
				$('#listatipocliente').append("<thead><th>Nombre</th><th>Estado</th></thead>");
				for (var i=0; i < data.length; i++) {
					 $('#listatipocliente').append(renderTipoCliente(data[i]));
				}
			}
		});
	}
	function renderTipoCliente(data){
		if (data["inactivo"]==1){
			var inactivo = "inactivo"
		}else {
			var inactivo = "activo"
		}
		var content = '<tr>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'modificartipocliente' ?>&id='+data["id"]+'">'+data["nombre"]+'</a></td>';
		content += '<td>'+inactivo+'</td>';
		content += '</tr>';
		return content;
	}
</script>