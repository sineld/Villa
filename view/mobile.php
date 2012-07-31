<script type="text/javascript">
	$(document).ready(function() {	
   		id_tipo_cliente = <?php if (isset($_SESSION['cliente'])) { echo $_SESSION['cliente']->id_tipo; } else if (isset($_SESSION['vendedor'])) { echo $_SESSION['vendedor']->id_tipo; }else { echo 'null'; } ?>;
		cat = <?php if (isset($_GET['categoria'])) { echo $_GET['categoria']; } else { echo 'null'; } ?>;
		tipo = <?php if (isset($_GET['tipo'])){ echo $_GET['tipo']; } else { echo 'null'; }?>;
		getArticulos(cat,tipo,id_tipo_cliente);
	});
	
	function getArticulos(cat, tipo, itc) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'catalogo',
				action : 'getArticulosCorto',
				categoria : cat,
				tipo : tipo,
				id_tipo_cliente : itc
			},
			success : function(data) {
				for (var i=0; i < (data.length); i++) {
					 $('#tabla_articulos').append(renderArticulos(data[i]));
				}
			}
		});
	}
	
	function renderArticulos(data){
		var content = '<tr><td class="td_imagen">';
		content += '<img width="150px" height="150px" src="'+data["foto"]+'" /></td>';
		content += '<td class="td_descripcion"><div class="art_wrapper"><div class="art_desc"><p>'+data["codigo"].toUpperCase()+'</p>';
		content += '<p>'+data["nombre"]+'</p>';
		if (data['precio'] != null){
		content += '<p>'+data['precio']+'</p>';
		}
		content += '</div><div class="art_cart"><a href="#"><p>></p></a></div></div></td></tr>';
		return content;
	}
	
</script>
	
	<table>
		<thead>
			<th class="th_imagen">
			<h6>Imagen</h6>
			</th>
			<th>
			<h6>Descripci&oacute;n</h6>
			</th>
		</thead>
		
		<tbody id="tabla_articulos">
		</tbody>
	</table>

