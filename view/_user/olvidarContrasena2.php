<?php
if (!isset($_GET["email"]) || !isset($_GET["key"])) {
	header('Location:' . $GLOBALS["baseURL"] . $GLOBALS["DEFAULT_VIEW"]);
} else {
	$emailString = $_GET["email"];
	$key = $_GET["key"];
	$result = Doctrine::getTable("User") -> findOneByEmail($emailString);
	if (sha1($result -> validation_code) != $key) {
		header('Location:' . $GLOBALS["baseURL"] . $GLOBALS["DEFAULT_VIEW"]);
	}
}
?>
<div id="forgot2">
<form name="NewContasenaForm" id= "NewContasenaForm" action="<?php echo $GLOBALS["baseURL"];?>crud.php" method="post">
	<input type="hidden" name="view" value="forgot2" />
	<input type="hidden" name="action" value="CambiarContrasena2"/>
	<input type="hidden" name="email" value="<?php echo $_GET["email"];?>" />
		<table>
			<tr>
			<td>
				<span>Nueva contrase&ntilde;a:</span>
			</td>
			<td	style="text-align: left; color: #606063">
			<label for="contrasenaNew">Nueva contrase&ntilde;a</label>
			<input name="contrasenaNew" type="password" id="contrasenaNew" maxlength="15">
			</td>
			</tr>
			<tr>
			<td>
				<span>Repetir contrase&ntilde;a:</span>
			</td>
			<td style="text-align: left; color: #606063">
			<label for="contrasenaReNew">Repetir Contrase&ntilde;a</label>
			<input name="contrasenaReNew" type="password" id="contrasenaReNew" maxlength="15">
			</td>
			</tr>
			<tr>
				<td>
				<label>
				<input type="submit" name="button" id="button" value="Aceptar">
				</label>	
				</td>
				<td>
				<strong class="error_cambio" style="text-align: left"></strong>
				<strong class="error" style="text-align: left"></strong>
				</td>
			</tr>


		</table>
</form>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("label").inFieldLabels();
		$.validator.addMethod('caracteres', function (value) { 
    		return /^([a-zA-Z0-9]*)$/.test(value);
		}, 'La contrase&ntilde;a debe estar formada solo por n&uacute;meros y letras');
		
		$('#NewContasenaForm').validate({
			groups: {
    			contrasena: "contrasenaNew contrasenaReNew"
 			},
			rules : {
				contrasenaNew: {
					required : true,
					minlength : 5,
					maxlength : 15,
					caracteres : true
				},
				contrasenaReNew : {
					required : true,
					minlength : 5,
					maxlength : 15,
					caracteres : true,
					equalTo : "#contrasenaNew"				
				}
			},
			messages : {
				contrasenaNew : {
					required : "Ambos campos son obligatorios",
					minlength : "La contrase&ntilde;a debe ser m&iacute;nimo de 5 caracteres",
					maxlength : "La contrase&ntilde;a debe ser m&aacute;ximo 15 caracteres"
					
				},
				contrasenaReNew : {
					required : "Ambos campos son obligatorios",
					minlength : "La contrase&ntilde;a debe ser minimo de 5 caracteres",
					maxlength : "La contrase&ntilde;a debe ser m&aacute;ximo 15 caracteres",
					equalTo : "Ambas contrase&ntilde;as deben coincidir"
				}
			},
			errorPlacement: function(error) {
				error.appendTo(".error");
			},
			submitHandler: function(form, data) {
   				jQuery(form).ajaxSubmit({
   					beforeSubmit: validatechangePassword,
   					success : function(data){
   						if ($.trim(data)==true || $.trim(data) == 'true'){
	    					window.location.replace("<?php echo $GLOBALS["baseURL"].$GLOBALS["DEFAULT_VIEW"]; ?>");
	    				}else {
	    					$('.error_cambio').html(data);
	    				}
   					}
				});
   			}
		});
	});

	function validatechangePassword(formData, jqForm, options)	{
		var contraNew = $("#contrasenaNew").val();
   		var contraReNew = $("#contrasenaReNew").val();
		formData[formData.length] = { "name": "contrasenaNew", "value": $.sha1(contraNew) };
		formData[formData.length] = { "name": "contrasenaReNew", "value": $.sha1(contraReNew) };
		return true;
	}
</script>