<form id="enviarfoto" name="foto" method="post" enctype="multipart/form-data" action="<?php echo $GLOBALS["baseURL"];?>crud.php">
	<input type="hidden" name="view" value="agregarfotos" />
	<input type="hidden" name="action" value="setFoto" />
	<input type="hidden" name="id_art" value="<?php echo $_GET['id'];?>" />
	<input id="Filedata-0" name="Filedata-0" class="0" type="file" /><label id="0" class="lbl">X</label>
	<input type="submit" value="Enviar" id="enviar" />
</form>
	<a href="#">Agregar Archivo</a>
<script type="text/javascript">
   	var i = 1;
	$(document).ready(function() {
		$('a').click(function() {
			$('#enviar').before('<br class="'+i+'"/><input id="Filedata-'+i+'" name="Filedata-'+i+'" class="'+i+'" type="file" /><label id="'+i+'" class="lbl">X</label>');
			i++;
		});
		$('.lbl').live('click',function () {
			var id = $(this).attr('id');
			$('.'+id).detach();
			$('#'+id).detach();
		});
		$('#enviarfoto').ajaxForm({
			beforeSubmit : function(){
   				$("#enviar").attr('disabled', 'disabled');
   			},
	    	success: function(data) {
	    		window.location = 'agregararticulos'
	    	}
		});

	});

</script>