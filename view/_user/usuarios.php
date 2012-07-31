<div class="page-header"><h4>Listado de Usuarios</h4></div>
<div class="hero-unit">
<table id="listauser" class="zebra-striped">
	<thead>
		<th>ID</th>
		<th>Email</th>
		<th>Tipo</th>
		<th>Ultima Visita</th>
	</thead>
	<tbody id="listsuserbody">
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
	jQuery.fn.log = function (msg) {
      console.log("%s: %o", msg, this);
      return this;
	};
	$(document).ready(function() {
		$("#listauser").tablesorter(); 
		getPaginationUser();
		getUsers(1);
		$('#paginador_lista').change(function(event){
				var pag = $(this).find(':selected').attr('value');
				event.preventDefault();
				getUsers(pag);
				$(this).log(pag);
		});
	});
	function getPaginationUser(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listarusuarios',
				action : 'getPaginationUser',
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
	function getUsers(pag) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listarusuarios',
				action : 'getUsers',
				paginaActual : pag,
				porPagina : pp
			},
			success : function(data) {
				$('#listsuserbody').html('');
				for (var i=0; i < data.length; i++) {
					 $('#listsuserbody').append(renderUsers(data[i]));
				 }
				 $("#listauser").trigger("update");
			}
		});
	}
	function renderUsers(data){
		var content = '<tr>';
		content += '<td><a href="<?php echo $GLOBALS['baseURL'].'modificararticulos' ?>&id='+data["id"]+'">'+data["id"]+'</a></td>';
		content += '<td>'+data["email"]+'</td>';
		content += '<td>'+data['Role']['name']+'</td>';
		content += '<td>'+data['maxFecha']+' ('+data['logCount']+')</td>';
		content += '</tr>';
		return content;
	}
</script>