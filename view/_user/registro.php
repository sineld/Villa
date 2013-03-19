<?php 
require_once('public_action/tipo_cliente_handler.php'); 
require_once('public_action/roles_handler.php');
?>
<script type="text/javascript">

$(function(){
	$(document).on('change','#TipoUsuarios',function(event){
		event.preventDefault();
		switch($(this).val()){
			case '2':
				$('.cliente').show();
				$('.empleado').hide();
				$('.vendedor').hide();
				$(".tipocliente").show();
			break;
			case '4':
				$('.cliente').hide();
				$('.empleado').show();
				$('.vendedor').hide();
				$(".tipocliente").hide();
			break;
			case '3':
				$('.cliente').hide();
				$('.empleado').hide();
				$('.vendedor').show();
				$(".tipocliente").show();
			break;
			case '1':
				$('.cliente').hide();
				$('.empleado').hide();
				$('.vendedor').hide();
				$(".tipocliente").hide();
			break;
			default:
				$('.cliente').hide();
				$('.empleado').hide();
				$('.vendedor').hide();
				$(".tipocliente").hide();
			break;
		}
	});
});
function validar() {
	$.validator.addMethod('caracteres', function (value) { 
   	return /^([a-zA-Z0-9]*)$/.test(value);
	}, 'La contrase&ntilde;a que introdujo no es v&aacute;lida');
	var container = $('#container');
	$('#theform').validate({
		errorContainer: container,
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate",
		rules : {
			email : {
				required : true,
				email : true
			},
			password : {
				required : true,
				minlength: 5,
				maxlength: 15,
				caracteres : true				
			},
			rep_password : {
				required : true,
				caracteres : true,
				equalTo : "#password"				
			},
		},
		messages : {
			email : {
				required : "El campo email es requerido",
				email : "Debe introducir un email valido"
			},
			password : {
				required : "El campo contraseña es obligatorio",
				minlength : "La contrase&ntilde;a debe ser m&iacute;nimo de 5 caracteres",
				maxlength : "La contrase&ntilde;a debe ser m&aacute;ximo 15 caracteres"
				
			},
			rep_password : {
				required : "El campo repetir contraseña es obligatorio",
				equalTo : "Ambas contrase&ntilde;as deben coincidir"
			}
		},  
		submitHandler: function(form, data) {
			jQuery(form).ajaxSubmit({
  				success: function(data) {
    				//window.location = "administracion";
    				alert("Usuario registrado con exito");
	 			}
  			});
  		}
	});
}
	
</script>
<div id="login">
	<div class="error" id="standardError" style="display:none;"></div>
        <div id="loginForm">
            <form id="theform" name="theform" method="post" action="crud.php">
	            <input name="action" type="hidden" value="newUser" />
	       	    <label for="email">Email:</label>
	           	<input type="text" name="email" id="email" title=""/><strong class="error" id="emailError"></strong>
			  	<label for="password">Contrase&ntilde;a:</label></td>
	           	<input type="password" name="password" id="password" class="blank" title="" /><strong class="error" id="passwordError"></strong>
			   	<label for="rep_password">Repetir Contrase&ntilde;a:</label>
				<input type="password" name="rep_password" id="rep_password" class="blank" title="" /><strong class="error" id="rep_passwordError"></strong>
				<label for="TipoUsuarios">Tipo de Usuario:</label>
				<select id="TipoUsuarios" name="tipousuarios">
					<?php
						$handler = new roles_handler();
						$query =  json_decode($handler->getRoles());
						foreach($query as $retorno){
							echo '<option value="'.$retorno->id.'">'.$retorno->name.'</option>';
						}
					?>
				</select>
				<div class="cliente" style="display:none;">
	               	<label for="nombre_empresa">Nombre:</label>
	           		<input type="text" name="nombre_empresa" id="nombre_empresa" title=""/>
					<label for="rif">RIF:</label>
					<input type="text" name="rif" id="rif" title=""/>
					<label for="direccion">Direcci&oacute;n:</label>
					<input type="text" name="direccion" id="direccion" title=""/>
	           		<label for="telefono">Tel&eacute;fono:</label>
	               	<input type="text" name="telefono" id="telefono" title=""/>
	            </div>
	            <div class="empleado" style="display:none;">
	               	<label for="nombre_empleado">Nombre:</label>
	           		<input type="text" name="nombre_empleado" id="nombre_empleado" title=""/>
					<label for="rif_empleado">RIF:</label>
					<input type="text" name="rif_empleado" id="rif_empleado" title=""/>
					<label for="tipo_empleado">Tipo de empleado:</label>
					<select id="tipo_empleado" name="tipo_empleado">
						<?php
							$query = new Empleado;
							$resultado = $query->getTipos();
							foreach($resultado as $aux){
									echo '<option value="'.$aux['id'].'">'.$aux['nombre'].'</option>';
							} 
						?>
					</select>
	            </div>
	            <div class="vendedor" style="display:none;">
	               	<label for="nombre_vendedor">Nombre:</label>
	               	<input type="text" name="nombre_vendedor" id="nombre_vendedor" title=""/>
	            </div>
				<div class="tipocliente" style="display:none;">
					<label for="TipoClientes">Tipo de Cliente:</label>
					<select id="TipoClientes" name="tipoclientes">
						<?php
							$handler = new tipo_cliente_handler();
							$id_tc = null;
							$nombre_tc = null;
							$inactivo_tc = 0;
							$paginaActual_tc = null;
							$query = json_decode($handler->getTipoClientes($id_tc, $nombre_tc, $inactivo_tc, $paginaActual_tc));
							foreach($query as $index => $valor){
								echo '<option value="'.$valor->id.'">'.$valor->nombre.'</option>';
							} 
						?>
					</select>
				</div>
				<input style="" type="submit" name="button" id="button" value="Enviar" />
		    </form>
		<div id="container">
 			<ol></ol>
 		</div>
	</div>
</div>

