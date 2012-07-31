<form id="enviarcategoria" name="categoria" method="post" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="modificarcategorias" />
	<input type="hidden" name="action" value="updateCategoria" />
	<label>ID:</label>
	<input name="id" id="id" type="text"  readonly="true"/>
	<br />
	<label>Nombre Categor&iacute;a</label>
	<input name="nombre" id="nombre" type="text"/>
	<br />
	<label>Inactivo:</label>
	<select name="inactivo" id="inactivo">
		<option value="1">Activo</option>
		<option value="2">Inactivo</option>
	</select>
	<br />
	<input type="submit" value="Enviar" id="enviar" />
</form>
 <div id="container">
 	<ol></ol>
 </div>
<script type="text/javascript">
	$(document).ready(function() {
			getCategoria();
		var container = $('#container');
		$('#enviarcategoria').validate({
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
					required : "El nombre de la categoria es obligatorio",
					maxlength : "El nombre de la categoria debe ser m&aacute;ximo de 15 caracteres"
				}
			},
			submitHandler: function(form, data) {
   				jQuery(form).ajaxSubmit({
   					beforeSubmit : function(){
   						 $("#enviar").attr('disabled', 'disabled');
   					},
   					success: function(data) {
	    				window.location = "listarcategorias";
		 			}
   				});
   			}	
		});
	});
	function getCategoria() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'modificarcategorias',
				action : 'getCategorias',
				id : <?php echo $_GET['id'];?>
			},
			success : function(data) {
				$('#id').val(data[0]['id']);
				$('#nombre').val(data[0]['nombre']);
				var a = data[0]['inactivo'];
				$('#inactivo').val(Number(a)+1);
			}			
		});
	}
</script>
