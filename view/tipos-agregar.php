 <form id="enviartipos" name="tipos" method="post" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="agregartipos" />
	<input type="hidden" name="action" value="setTipo" />
	<label>Nombre Tipo:</label>
	<input type="text" name="nombre"/>
	<br>
	<label>Categoria:</label>
	<select id="categorias" name="categoria">
		<!--Todas las categorias-->
	</select>
	<br />
	<input type="submit" value="Enviar" id="enviar" />
</form>
<div class="error"  style="display:none">
	<p>
		Error al insertar el Tipo
	</p>
</div>
<div id="container">
 	<ol></ol>
 </div>
<script type="text/javascript">
	$(document).ready(function() {
		cargarselect();
		recibirformulario();
	});
	function recibirformulario() {
		var container = $('#container');
		$('#enviartipos').validate({
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
	    				$('.error').fadeOut();
	    				if(data=='false'){
	    					$('.error').fadeIn();
	    				}
	    					$(':input','#enviarcategoria').not(':button, :submit, :reset, :hidden').val('').removeAttr('checked').removeAttr('selected');
		 					$("#enviar").removeAttr("disabled");
		 			}
   				});
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
