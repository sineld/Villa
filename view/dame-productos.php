<script type="text/javascript">
	$(document).ready(function() { 
		getProductos();
		
		$('.codigoSend').live('click', function() {
			getProductos($(this).val());
		});
		
	}
	
function getProductos(id){
	$.ajax({
	   type: "POST",
	   url: "crud.php",
	   dataType: "json",
	   data: {view: 'ver-productos', action: 'getProducts',id: id},
	   success: function(data)
	   {
	   		if(data.length){
	   			for (var i=0; i < data.length; i++) {
					 $('#content').append(renderProductos(data[i]));
				   };
	   		}	
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown)
	   {
	   	 $('#bottomContent').append("<h4>There was a error fetching the aplicant.</h4>");
	   	  $('#bottomContent').append("<div>"+XMLHttpRequest+"</div>");
	   	  $('#bottomContent').append("<div>"+textStatus+"</div>");
	   	  $('#bottomContent').append("<div>"+errorThrown+"</div>");
	   }
 	});
}

function renderProductos (data) {
	
	var content = "";
	content += '<label>Nombre Producto:</label><input name="nombre" type="text" value="'+data["nombre"]+'"/><label>Codigo:</label><a href="<?php echo $GLOBALS['baseURL'].'agregar-producto' ?>&codigo='+data["codigo"]+'">CODE</a><input class="codigoSend" name="codigo" type="text" value="'+data["codigo"]+'"/><label>Descripcion:</label><input name="descripcion" type="text" value="'+data["descripcion"]+'"/><label>Categoria:</label><input name="categoria" type="text" value="'+data["categoria"]+'"/><label>Tipo:</label><input name="tipo" type="text" value="'+data["tipo"]+'"/><br/>';

	return content;
}
	
</script>