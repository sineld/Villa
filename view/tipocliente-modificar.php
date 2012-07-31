<form id="enviartipocliente" name="tipocliente" method="post" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="modificartipocliente" />
	<input type="hidden" name="action" value="updateTipoCliente" />
	<label>ID:</label>
	<input name="id" id="id" type="text"  readonly="true"/>
	<br />
	<label>Nombre Tipo de Cliente</label>
	<input name="nombre" id="nombre" type="text"/>
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
			getTipoCliente();
			var container = $('#container');
		$('#enviartipocliente').validate({
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
					required : "El nombre del tipo de cliente es obligatorio",
					maxlength : "El nombre del tipo de cliente debe ser m&aacute;ximo de 15 caracteres"
				}
			},
			submitHandler: function(form, data) {
   				jQuery(form).ajaxSubmit({
   					beforeSubmit : function(){
   						 $("#enviar").attr('disabled', 'disabled');
   					},
   					success: function(data) {
	    				window.location = "listartipocliente";
	    			}
   				});
   			}
		});
	});
	function getTipoCliente() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'modificartipocliente',
				action : 'getTipoClientes',
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
