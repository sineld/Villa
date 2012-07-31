<form id="enviartipo" name="tipo" method="post" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="modificartipos" />
	<input type="hidden" name="action" value="updateTipo" />
	<label>ID:</label>
	<input name="id" id="id" type="text"  readonly="true"/>
	<br />
	<label>Nombre Tipo:</label>
	<input name="nombre" id="nombre" type="text"/>
	<br />
	<label>Categoria</label>
	<select name="categorias" id="categorias">
		<!--Todas las categorias-->
	</select>
	<br />
	<label>Inactivo:</label>
	<select name="inactivo" id="inactivo">
		<option value="1">Activo</option>
		<option value="2">Inactivo</option>
	</select>
	<br />
	<input type="submit" value="Enviar" id="enviar"/>
</form>
<div id="container">
 	<ol></ol>
 </div>
<script type="text/javascript">
	$(document).ready(function() {
			cargarselect();
			var container = $('#container');
		$('#enviartipo').validate({
			errorContainer: container,
			errorLabelContainer: $("ol", container),
			wrapper: 'li',
			meta: "validate",
			rules : {
				nombre : {
					required : true,
					maxlength : 15
				}
			},
			messages : {
				nombre : {
					required : "El nombre del tipo de art&iacute;culo es obligatorio",
					maxlength : "El nombre del tipo de art&iacute;culo debe ser m&aacute;ximo de 15 caracteres"
				}
			},
			submitHandler: function(form, data) {
   				jQuery(form).ajaxSubmit({
   					beforeSubmit : function(){
   						 $("#enviar").attr('disabled', 'disabled');
   					},
   					success: function(data) {
   						window.location = "listartipos";
   					}
   				});
   			}
		});
	});
	function getTipo() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'modificartipos',
				action : 'getTipos',
				id : <?php echo $_GET['id'];?>
			},
			success : function(data) {
				$('#id').val(data[0]['id']);
				$('#nombre').val(data[0]['nombre']);
				$('#categorias').val(Number(data[0]['id_cat']));
				var a = data[0]['inactivo'];
				$('#inactivo').val(Number(a)+1);
			}			
		});
	}
	function cargarselect() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'agregartipos',
				action : 'getCategorias',
				inactivo : 0
			},
			success : function(data) {
				for (var i = 0; i < data.length; i++){
				$('#categorias').append(renderSelect(data[i]));
				}
				getTipo();
			}
		});
	}
	
	function renderSelect(data){
		var content = "";
		content +='<option value='+data["id"]+'>';
		content +=data["nombre"];
		content +='</option>';
		return content;
	}
</script>