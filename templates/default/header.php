<script type="text/javascript">
	jQuery.fn.log = function (msg) {
      console.log("%s: %o", msg, this);
      return this;
	};
 $(document).ready(function(){
	$('.session_trigger').click(function(event){
		if($('#login_ui').attr('estado') == 'oculto' && $('#login_ui').attr('tamaño') == '105'){
			$('#login_ui').animate({'top':'+=105px'},600);
			$('#login_ui').attr('estado','visible');
			$('#mostrar_ocultar').html('<a href="#">Ocultar panel</a>');
			return;
		}
		if($('#login_ui').attr('estado') == 'visible' && $('#login_ui').attr('tamaño') == '105'){
			$('#login_ui').animate({'top':'-=105px'},600);
			$('#login_ui').attr('estado','oculto');
			$('#mostrar_ocultar').html('<a href="#">Mostrar pedido actual</a>');
			return;
		}
		if($('#login_ui').attr('estado') == 'oculto' && $('#login_ui').attr('tamaño') == '300'){
			$('#login_ui').animate({'top':'+=300px'},600);
			$('#login_ui').attr('estado','visible');
			$('#mostrar_ocultar').html('<a href="#">Ocultar panel</a>');
			return;
		}
		if($('#login_ui').attr('estado') == 'visible' && $('#login_ui').attr('tamaño') == '300'){
			$('#login_ui').animate({'top':'-=300px'},600);
			$('#login_ui').attr('estado','oculto');
			$('#mostrar_ocultar').html('<a href="#">Mostrar pedido actual</a>');
			return;
		}
	});
 	var container = $('div.container');
 	$.validator.addMethod('caracteres', function (value) { 
    	return /^([a-zA-Z0-9]*)$/.test(value);
	}, 'La contrase&ntilde;a que introdujo no es v&aacute;lida');
	
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
		//////CODIGO PARA MANEJO DE PEDIDOS//////
		$.ajax({
			type : "POST",
			url : "public_action/pedidos_handler.php",
			dataType : "HTML",
			data : {
				accion : 'mostrar',
			},
			success : function(data) {
				$('#listaScroll').append(data);
				calc_subtotal($('.pl_codigo'));
			},
			error: function(request,error) {
  					if (error == "timeout") {
						alert("The request timed out, please resubmit");
					}
					else {
						alert("ERROR: " + error);
					}
			}
		});
		$('.pedidos_boton').live('click',function(event){
			var _articulo = $(this).parent();
			var codigo = _articulo.find('.pedidos_codigo').text();
			var nombre = _articulo.find('.pedidos_nombre').text();
			var precio = _articulo.find('.pedidos_precio').text();
			var imagen = _articulo.find('.pedidos_imagen').attr('src');
			var cantidad = 1;
			$('.pl_codigo').each(function(index){
				if($(this).text() == codigo){
					var valor = parseInt($(this).parent().find('input').val());
					valor += 1;
					$(this).parent().find('input').val(valor);
					$(this).parent().find('input').attr('value',valor);
					cantidad = valor;
				}
			});
			$.ajax({
				type : "POST",
				url : "public_action/pedidos_handler.php",
				dataType : "json",
				data : {
					accion : 'agregar',
					codigo : codigo,
					descripcion : nombre,
					precio : precio,
					cantidad : cantidad,
					imagen: imagen
				},
				success : function(data) {
					if(data.cantidad >= '2'){
						calc_subtotal($('.pl_codigo'));
					}
					else{
						var content = "<tr class='pedido_listado_item'>";
						content += "<td class='pl_codigo'>"+data.codigo+"</td>";
						content += "<td class='pl_nombre'>"+data.descripcion+"</td>";
						content += "<td class='pl_imagen'><img src='"+data.imagen+"'></img></td>";
						content += "<td class='pl_precio'>"+data.precio+"</td>";
						content += "<td class='pl_cantidad'><input class='pl_input' type='text' value='"+data.cantidad+"'></input></td>";
						content += "<td class='pl_eliminar'><div class='boton'>Eliminar</div></td>";
						content += "</tr>";
						$('#listaScroll').append(content);
						calc_subtotal($('.pl_codigo'));
					}
				},
				error: function(request,error) {
  					if (error == "timeout") {
						alert("The request timed out, please resubmit");
					}
					else {
						alert("ERROR: " + error);
					}
				}
			});
			
		});
		$('.pl_eliminar').live('click',function(){
			var codigo = $(this).parent().find('.pl_codigo').text();
			$.ajax({
				type : "POST",
				url : "public_action/pedidos_handler.php",
				dataType : "text",
				data : {
					accion : 'eliminar',
					codigo : codigo,
				},
				success : function(data) {
				},
				error: function(request,error) {
						if (error == "timeout") {
							alert("The request timed out, please resubmit");
						}
						else {
							alert("ERROR: " + error);
						}
				}
			});
			$(this).parent().remove();
			calc_subtotal($('.pl_codigo'));
		});
		$('#pedidos_reiniciar').live('click',function(){
			$.ajax({
				type : "POST",
				url : "public_action/pedidos_handler.php",
				dataType : "text",
				data : {
					accion : 'reiniciar',
				},
				success : function(data) {
				},
				error: function(request,error) {
						if (error == "timeout") {
							alert("The request timed out, please resubmit");
						}
						else {
							alert("ERROR: " + error);
						}
				}
			});
			$('#pedidos_listado_articulos tbody').children('.pedido_listado_item').remove();
			calc_subtotal($('.pl_codigo'));
		});
		$('.pl_input').live('keyup',function(){
			var _articulo = $(this).parent().parent();
			var codigo = _articulo.find('.pl_codigo').text();
			if ( event.keyCode == 46 || event.keyCode == 8 || event.keycode == 116) {
			
			}
			else {
				if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
					event.preventDefault(); 
				}   
			}
			var cantidad = _articulo.find('input').val()
			$.ajax({
				type : "POST",
				url : "public_action/pedidos_handler.php",
				dataType : "html",
				data : {
					accion : 'cambiarCant',
					codigo : codigo,
					cantidad : cantidad,
				},
				success : function(data) {
					calc_subtotal($('.pl_codigo'));
				}
			});
			
		});
		
		function calc_subtotal(data){
			var subtotal = 0;
			data.each(function(){
				var cantidad = $(this).parent().find('input').val();
				var precio = parseFloat($(this).parent().find('.pl_precio').text().replace(' Bs. ', '')).toFixed(2);
				subtotal = (parseFloat(subtotal) + parseFloat(cantidad * precio)).toFixed(2);
			});
			var iva = parseFloat(subtotal*0.12).toFixed(2);
			var total = parseFloat(subtotal)+parseFloat(iva);
			$('#pl_subtotal').text(subtotal);
			$('#pl_iva').text(iva);
			$('#pl_total').text(total.toFixed(2));
		}
		
		
	});
</script>
<div id="header">
		<?php
			if ($_SESSION['user']->status == 'invalid') {
		?>
		<div id="login_ui" estado="oculto" tamaño="105">
			
			<div id="login_bar">
				<div id="login_wrapper">
				<div id="login_cerrar" class="session_trigger"><a href="#">Cerrar panel</a></div>
				<ul id="login_text">
					<li class="login_texto" id="login_welcome"><a href="http://www.villadelasmascotas.com/catalogo">Cat&aacute;logo</a></li>
					<li class="login_texto"> | </li>
					<li class="login_texto" id="iniciar_sesion" ><a class="session_trigger" href="#">Iniciar Sesi&oacute;n</a></li>
					<li id="login_derecha"></li>
				</ul>
				</div>
			</div>
			<div id="login">
			<ul>
				<form action="crud.php" id="cloginForm">
					<input name="autenticate" type="hidden" value="" />
		   			<li>E-mail:</li>
					<li><input name="user" class="input-text" id="cuser" type="text" title="email"  /></li>
					<li>Contrase&ntilde;a:</li>
					<li><input name="password" id="cpassword" type="password" maxlength="15" /></li>
					<input name="" type="submit" value="Log in" />
				</form>
			</ul>
				<div class="login_error"></div>
				<div class="container" style="display: none">
					<ol>
					</ol>
				</div>
			</div> 
		</div>
	    <?php
			}else {
		?> 
		<div id="login_ui" estado="oculto" tamaño="300" style="height: 300px; top: -300px; background: #FFFFFF;">
			<div id="login_bar">
				<ul id="login_text">
					<li class="login_texto" id="login_welcome"><a href="http://www.villadelasmascotas.com/catalogo">Cat&aacute;logo</a></li>
					<li class="login_texto"> | </li>
					<li class="login_texto session_trigger" id="mostrar_ocultar"><a href="#">Mostrar pedido actual</a></li>
					<li class="login_texto"> | </li>
					<li class="login_texto"><a href="crud.php?close_session" style="text-decoration: none;">Cerrar Sesi&oacute;n</a></li>
					<li id="login_derecha"></li>
				</ul>
			</div>
			<!--- Interfaz de pedidos -->
			<div id="pedidos_contenido">
				<table id="pedidos_listado_articulos">
					<tr id="pedidos_listado_titulo">
						<td id="pl_codigo">C&oacute;digo</td>
						<td id="pl_nombre">Descripci&oacute;n</td>
						<td id="pl_imagen">Imagen</td>
						<td id="pl_precio">Precio</td>
						<td id="pl_cantidad">Cantidad</td>
						<td id="pl_eliminar"></td>
					</tr>
					<tr>
					<div id="listaScroll"></div>
					</tr>
				</table>
			</div>
			<div id="pedidos_footer">
				<div id="pedidos_total">
					<table>
						<tr>
							<td>Subtotal:</td>
							<td id="pl_subtotal"></td>
						</tr>
						<tr>
							<td>Iva (12%):</td>
							<td id="pl_iva"></td>
						</tr>
						<tr>
							<td>Total:</td>
							<td id="pl_total"></td>
						</tr>
					</table>
				</div>
				<div id="pedidos_botones">
					<div class="boton rojo" id="pedidos_reiniciar">
					<span>Reiniciar</span>
					</div>
					<div class="boton" id="pedidos_enviar">
					<span>Enviar pedido</span>
					</div>
				</div>
			</div>
		</div>			
		<?php
			}
		?> 
</div>

	