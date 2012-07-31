<script type="text/javascript">
 $(document).ready(function(){
 	getTipoUsuarios();
	selectdependiente();
	validar();
 });


function getTipoClientes(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'signup',
				action : 'getTipoClientes',
				inactivo : 0				
			},
			success : function(data) {
				$('#TipoClientes').html('');
				for (var i = 0; i < data.length; i++){
				$('#TipoClientes').append(renderSelect(data[i]));
				}
			}
		});
	}
	
	function getTipoUsuarios(){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'signup',
				action : 'getTipoUsuarios',
				inactivo : 0				
			},
			success : function(data) {
				for (var i = 0; i < data.length; i++){
				$('#TipoUsuarios').append(renderSelect2(data[i]));
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
	function renderSelect2(data){
		var content = "";
		content +='<option value='+data["id"]+'>';
		content +=data["name"];
		content +='</option>';
		return content;
	}
	function selectdependiente() {
		$("#TipoUsuarios").change(function() {
			if ($("#TipoUsuarios option[value='"+$("#TipoUsuarios").val()+"']").text() == 'cliente'
			 || $("#TipoUsuarios option[value='"+$("#TipoUsuarios").val()+"']").text() == 'vendedor'){
				getTipoClientes();
				$("#td_tipocliente").show();
				if ($("#TipoUsuarios option[value='"+$("#TipoUsuarios").val()+"']").text() == 'cliente'){
					$(".cliente").show();
					$(".vendedor").hide();
				}else {
					$(".cliente").hide();
					$(".vendedor").show();
				}
			}else {
				$("#td_tipocliente").hide();
				$(".cliente").hide();
				$(".vendedor").hide();
			}
			
		});
	}
	
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
	    				window.location = "administracion";
		 			}
   				});
   			}	
		});
	}
	
</script>


<p style="text-align:center; font-size:14px; margin:0;">Welcome</p>

    <div id="login">

        <div class="error" id="standardError" style="display:none;"></div>

        <div id="loginForm">

            <form id="theform" name="theform" method="post" action="crud.php">
            <input name="action" type="hidden" value="newUser" />

            <table width="420" border="0" style="padding-left:20px;">

                <tr>
                	<td align="left"><label for="label">Email:</label></td>
                	<td align="left"><input type="text" name="email" id="email" title=""/><strong class="error" id="emailError"></strong></td>
              	</tr>

              	<tr>
                	<td align="left"><label for="label">Contrase&ntilde;a:</label></td>
                	<td align="left"><input type="password" name="password" id="password" class="blank" title="" /><strong class="error" id="passwordError"></strong></td>
              	</tr>

              	<tr>
                	<td align="left"><label for="label">Repetir Contrase&ntilde;a:</label></td>
                	<td align="left"><input type="password" name="rep_password" id="rep_password" class="blank" title="" /><strong class="error" id="rep_passwordError"></strong></td>
              	</tr>
              	<tr>
                	<td align="left"><label for="label">Tipo de Usuario:</label></td>
                	<td align="left" ><select id="TipoUsuarios" name="tipousuarios"></select></td>
              	</tr>
              	<tr class="cliente" style="display: none">
                	<td align="left"><label for="label">Nombre:</label></td>
                	<td align="left" ><input type="text" name="nombre_empresa" id="nombre_empresa" title=""/></td>
              	</tr>
              	<tr class="cliente" style="display: none"> 
                	<td align="left"><label for="label">RIF:</label></td>
                	<td align="left" ><input type="text" name="rif" id="rif" title=""/></td>
              	</tr>
              	<tr class="cliente" style="display: none">
                	<td align="left"><label for="label">Direcci&oacute;n:</label></td>
                	<td align="left" ><input type="text" name="direccion" id="direccion" title=""/></td>
              	</tr>
              	<tr class="cliente" style="display: none">
                	<td align="left"><label for="label">Tel&eacute;fono:</label></td>
                	<td align="left" ><input type="text" name="telefono" id="telefono" title=""/></td>
              	</tr>
              	<tr class="vendedor" style="display: none">
                	<td align="left"><label for="label">Nombre:</label></td>
                	<td align="left" ><input type="text" name="nombre_vendedor" id="nombre_vendedor" title=""/></td>
              	</tr>
              	<tr id="td_tipocliente" style="display: none">
                	<td align="left"><label for="label">Tipo de Cliente:</label></td>
                	<td align="left" ><select id="TipoClientes" name="tipoclientes"></select></td>
              	</tr>
            </table>
            <table width="320" border="0">
                <tr>
                	<td align="left">
            		<p>&nbsp;</p>
                  	<p style="text-align:center;"><input style="" type="submit" name="button" id="button" value="Enviar" /></p>
            		</td>
				</tr>
            </table>
          </form>
		<div id="container">
 			<ol></ol>
 		</div>
        </div>

    </div>

