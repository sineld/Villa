<div class="page-header">
	<h4>Editar Fotos</h4>
</div>
<div id="editarfotos">
	<form class="form-stacked" id="enviarfotos" name="fotos" method="post" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="editarfotos" />
	<input type="hidden" name="action" value="updateFotos" />
	<input type="hidden" name="id" id="id" value="<?php echo $_GET['id'];?>"/>
	<label for="prioridad">Prioridad</label>
	<select id="prioridad" name="prioridad">
		<option value=1>1</option>
		<option value=2>2</option>
		<option value=3>3</option>
		<option value=4>4</option>
		<option value=5>5</option>
		<option value=6>6</option>
		<option value=7>7</option>
		<option value=8>8</option>
		<option value=9>9</option>
		<option value=10>10</option>
	</select>
	<label for="inactivo">Estado</label>
	<select id="inactivo" name="inactivo">
		<option value=1>Activo</option>
		<option value=2>Inactivo</option>
	</select>
	<div class="row">
		<br />
	<input class="btn primary" type="submit" value="Enviar" id="enviar"/>
	</div>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		getFoto();
	});
	function getFoto() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'editarfotos',
				action : 'getFotosArt',
				id : <?php echo $_GET['id'];?>
			},
			success : function(data) {
				var a = data[0]['inactivo'];
				$('#inactivo').val(Number(a)+1);
				var b = data[0]['prioridad'];
				$('#prioridad').val(Number(b));
			}
		});
	}
	$('#enviarfotos').ajaxForm({
	    	success: function(data) {
	    	window.location = 'listarfotos&id='+<?php echo $_GET['idart'];?>
	    	}
	  });
</script>