<div name="content">
<form name="olvidarForm" id="olvidarForm" method="post" action="crud.php">
<div align="center">
  <input type="hidden" name="action" value="OlvidarContrasena"/>
</div>

<p align="center"><strong> Olvidaste tu contrase&ntilde;a? </strong></p>

<p align="center">
   <label for="emails">Email</label><input type="text" name="emails" id="emails"><br/>
   <strong class="error" id="Error"></strong><br/>
   <strong class="error" id="emailError"></strong>
   <br/>
   <input type="submit" name="button" id="button" value="Enviar"></label>
</p>
<p align="center">Escribe tu email y te enviaremos informaci&oacute;n con los pasos a seguir</p>
</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("label").inFieldLabels();
		$('#olvidarForm').validate({
			errorLabelContainer: $("#emailError"),
			rules : {
				emails : {
					required : true,
					email : true
				}
			},
			messages : {
				emails : "Por favor introduzca su Email"
			},
			submitHandler: function(form, data) {
   				jQuery(form).ajaxSubmit({
   					success : function(data){
   						if ($.trim(data)==true || $.trim(data) == 'true'){
	    					window.location.replace("<?php echo $GLOBALS["baseURL"].$GLOBALS["DEFAULT_VIEW"]; ?>");
			    		}else {
			    			$('#Error').html(data);
			    		}
   					}
				});
   			}
		});
	});
</script>

