 <form id="enviarcategoria" name="categoria" method="post" action="<?php echo $GLOBALS["baseURL"]; ?>crud.php">
 	<input type="hidden" name="view" value="agregarcategorias" />
	<input type="hidden" name="action" value="setCategoria" />
	<label>Nombre Categoria:</label><input type="text" name="nombre"/><br>
	<input type="submit" value="Enviar" id="enviar" />
 </form>
 <div class="error"  style="display:none">
 	<p>Error al insertar la Categoria</p>
 </div>
 <div id="container">
 	<ol></ol>
 </div>
<script type="text/javascript">
	$(document).ready(function(){
		var container = $('div.container');
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
	});
</script>