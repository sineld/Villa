
	<script type="text/javascript">
		$(document).ready(function() {
			$("textarea").markItUp(mySettings);
		});
	</script>
	<script type="text/javascript" src="js/markitup/jquery.markitup.js"></script>
	<script type="text/javascript" src="js/htmleditor/set.js"></script>
	<link rel="stylesheet" type="text/css" href="js/markitup/skins/markitup/style.css" />
	<link rel="stylesheet" type="text/css" href="js/htmleditor/style.css" />
	<div class="page-header"><h4>Modificar ficha de art&iacute;culo</h4></div>	

	<form class="form-stacked" id="enviararticulos" name="articulos" method="post" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="modificararticulos" />
	<input type="hidden" name="action" value="updateArticulos" />
	<input type="hidden" name="id_art" id="id_art"/>
	<label>C&oacute;digo</label>
	<input type="text" id="codigo" name="codigo" />
	<label>Nombre</label>
	<input type="text" id="nombre" name="nombre" />
	<label>Descripci&oacute;n</label>
	<textarea id="descripcion" name="descripcion" rows="5" cols="40"></textarea>
	<label>Categor&iacute;a</label>
	<select id="categorias" name="categorias">
		<option value=0 disabled="disabled">Seleccione una Categoria</option>
		<?php 
		foreach ( $vars['entity'] as $result )
		{
		    echo '<option value="'.$result->id.'">'.$result->nombre.'</option>';
		}
		?>
	</select>
	<label>Tipo</label>
	<select id="tipos" name="tipos">
		<option value=0 disabled="disabled">Seleccione un Tipo</option>
	</select>
	<label id="altolbl">Alto</label>
	<input type="text" id="alto" name="alto" />
	<label id="ancholbl">Ancho</label>
	<input type="text" id="ancho" name="ancho" />
	<label id="largolbl">Largo</label>
	<input type="text" id="largo" name="largo" />
	<label id="diametrolbl">Di&aacute;metro</label>
	<input type="text" id="diametro" name="diametro" />
	<label>Peso</label>
	<input type="text" id="peso" name="peso" />
	<label>Empaque</label>
	<input type="text" id="empaque" name="empaque" />
	<?php
		require_once('public_action/tipo_cliente_handler.php');
		$tc = new tipo_cliente_handler;
		$data = json_decode($tc->getTipoClientes(null,null,0));
		foreach ($data as $d){
			echo '<label for="'.$d->{'nombre'}.'">Precio '.ucfirst($d->{'nombre'}).'</label><input type="text" name="'.$d->{'nombre'}.'" id="'.$d->{'id'}.'" />';
		}
	?>
	<label for="agotado">Stock</label>
	<select id="agotado" name="agotado">
		<option value=1>Disponible</option>
		<option value=2>Agotado</option>
	</select>
	<label for="inactivo">Estado</label>
	<select id="inactivo" name="inactivo">
		<option value=1>Activo</option>
		<option value=2>Inactivo</option>
	</select>
	<div class="row">
		<br />
	<input class="btn primary" type="submit" value="Enviar" id="enviar"/>
	</div>
	</form>


	
<div class="container2" style="display: none">
	<ol>
	</ol>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		cambiotipo();
		$('#codigo').bestupper({ clear : false });
		
		selectdependiente();
		//cargarselectcat();
		getArticulo();
    	recibirformulario();	
	});
	function getArticulo() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'modificararticulos',
				action : 'getArticulos',
				id : <?php echo $_GET['id'];?>
			},
			async : false,
			success : function(data) {
				$('#id_art').val(data[0]['id']);
				$('#codigo').val(data[0]['codigo']);
				$('#nombre').val(data[0]['nombre']);
				$('#descripcion').val(data[0]['descripcion']);
				$('#alto').val(data[0]['alto']);
				$('#ancho').val(data[0]['ancho']);
				$('#largo').val(data[0]['largo']);
				$('#diametro').val(data[0]['diametro']);
				$('#peso').val(data[0]['peso']);
				$('#empaque').val(data[0]['empaque']);
				var a = data[0]['inactivo'];
				$('#inactivo').val(Number(a)+1);
				var b = data[0]['agotado'];
				$('#agotado').val(Number(b)+1);
				//cargar precios
				data2 = data[0]['precio'];
				$.each(data2, function(key, value) {
      				$('#'+key).val(data2[key][0]['precio']);
				});
				//fin cargar precios 
				$('#categorias').val($("#categorias option:contains('"+data[0]['categoria']+"')").val());
				cargarselecttipo(data[0]['tipo']);
			}	
		});
	}
	
function recibirformulario() {
		var container = $('div.container2');
		$("#enviararticulos").validate({
			errorContainer: container,
			errorLabelContainer: $("ol", container),
			wrapper: 'li',
			meta: "validate",
			rules : {
				codigo : {
					required : true,
					maxlength : 15
				},
				nombre : {
					required : true,
					maxlength : 50
				},
				descripcion : {
					required : true,
					maxlength : 500
				},
				alto : {
					number : true
				},
				ancho : {
					number : true
				},
				largo : {
					number : true
				},
				diametro : {
					number : true
				},
				peso : {
					number : true
				},
				empaque : {
					number : true
				},
				categorias : { valueNotEquals: 0 },
				tipos : { valueNotEquals: 0 }
			},
			messages : {
				codigo : {
					maxlength : "El código debe ser máximo de 15 caracteres",
					required : "El código no puede estar vacio"
				},
				nombre : {
					maxlength : "El nombre debe ser máximo de 50 caracteres",
					required : "El nombre no puede estar vacio"
				},
				descripcion : {
					maxlength : "La descripcion debe ser máximo de 500 caracteres",
					required : "La descripcion no puede estar vacia"
				},
				categorias : {
					valueNotEquals : "Debe seleccionar una categoria"
				},
				tipos : {
					valueNotEquals : "Debe seleccionar un tipo"
				},
				alto : {
					number : "El alto solo puede contener numeros (decimales con punto '.')"
				},
				ancho : {
					number : "El ancho solo puede contener numeros (decimales con punto '.')"
				},
				largo : {
					number : "El largo solo puede contener numeros (decimales con punto '.')"
				},
				diametro : {
					number : "El diametro solo puede contener numeros (decimales con punto '.')"
				},
				peso : {
					number : "El peso solo puede contener numeros (decimales con punto '.')"
				},
				empaque : {
					number : "El empaque solo puede contener numeros enteros"
				}
			},
			submitHandler: function(form, data) {
   				jQuery(form).ajaxSubmit({
   					beforeSubmit : function(){
   						 $("#enviar").attr('disabled', 'disabled');
   					},
   					success : function(data){
   						 window.location = "<?php echo $GLOBALS["baseURL"];?>listararticulos";
   					}
				});
   			}
		});
	}
	
	$.validator.addMethod("valueNotEquals", function(value, element, arg){
  		return arg != value;
 	}, "Value must not equal arg.");


	/*function cargarselectcat() {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'modificararticulos',
				action : 'getCategorias',
				inactivo : 0
			},
			async : 'false',
			success : function(data) {
				for(var i = 0; i < data.length; i++) {
					$('#categorias').append(renderSelect(data[i]));
				}
			}
		});
	}*/

	function cargarselecttipo(value) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'modificararticulos',
				action : 'getTipos',
				inactivo : 0,
				categorias : $("#categorias").val()
			},
			async : 'false',
			success : function(data) {
				$('#tipos').html('<option value=0 disabled="disabled">Seleccione un Tipo</option>');
				for(var i = 0; i < data.length; i++) {
					$('#tipos').append(renderSelect(data[i]));
				}
				if (value!=null){
					$('#tipos').val($("#tipos option:contains('"+value+"')").val());
					tipo(value);
				}
			}
		});
	}


	function renderSelect(data) {
		var content = "";
		content += '<option value=' + data["id"] + '>';
		content += data["nombre"];
		content += '</option>';
		return content;
	}

	function selectdependiente() {
		$("#categorias").change(function() {
			cargarselecttipo(null);
		});
	}
	
	function cambiotipo() {
		$("#tipos").change(function() {
			tipo($("#tipos ").find("option:selected").text());
		});
	}
	
	function tipo(value){
		if (value == 'pecheras' || value == 'Pecheras'){
			$('#altolbl').text("Tronco");
			$('#ancholbl').text("Pecho");
			$('#largolbl').text("Paseador");
			$('#diametrolbl').text("Cuello");
		}else if ($('#altolbl').text() == "Tronco"){
			$('#altolbl').text("Alto");
			$('#ancholbl').text("Ancho");
			$('#largolbl').text("Largo");
			$('#diametrolbl').text("Diametro");
		}
	}
</script>	