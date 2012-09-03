
<div id="container header" style="padding-top: 20px;">
	<div class="row span12">
		<div class="span6"><img src="assets/img/header.png"/></div>
	<?php
			if ($_SESSION['user']->status == 'invalid') {
	?>
		<div id="login" class="span4 well well-small pull-right">
			<form class="form-inline" action="crud.php" id="cloginForm">
						<input name="autenticate" type="hidden" value="" />
						<input name="user" class="input-text input-small" placeholder="e-mail" id="cuser" type="text" title="email"  />
						<input name="password" id="cpassword" class="input-small" placeholder="password" type="password" maxlength="15" />
						<input name="" id="csubmit" class="btn" type="submit" value="Log in" />
			</form>
			<div id="error_container" style="display: none">
				<ol>
				</ol>
			</div>
		</div>
	<?php } else { ?>
	<div id="login" class="span4 well well-small pull-right">
		<a href="crud.php?close_session"><button class="btn btn-inverse btn-small" id="cerrar_session">Cerrar Sesion</button></a>
	</div>
	<?php } ?>
	</div>
</div>

<script>
jQuery.fn.log = function (msg) {
      console.log("%s: %o", msg, this);
      return this;
	};
$(document).ready(function(){
	var container = $('div#error_container');
	$.validator.addMethod('caracteres', function (value) { 
    	return /^([a-zA-Z0-9]*)$/.test(value);
	}, 'La contrase&ntilde;a que introdujo no es v&aacute;lida');
	
	$('#csubmit').on('click',function(){
		$("#cloginForm").validate({
			errorContainer: container,
			errorLabelContainer: $("ol", container),
			wrapper: 'li',
			meta: "validate",
			rules: {
				user : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength: 5,
					maxlength: 15,
					caracteres : true
				}
			},
			messages : {
				user : "Por favor introduzca su Email",
				password : "Por favor introduzca su Contraseña (solo letras y n&uacute;meros entre 5 y 15 caracteres)"
			},
			submitHandler: function(form) {
				jQuery(form).ajaxSubmit({
						beforeSubmit: function(formData, jqForm, options){
							var pass = $("#cpassword").val();
							formData[formData.length] = { "name": "password", "value": $.sha1(pass) };
							return true;
						},
						target: ".login_error"
					});
			}	
		});	
		window.location('http://localhost/villa/catalogo2012');
	});
	
	
		
});
</script>