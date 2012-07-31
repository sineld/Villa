<div class="page-header">
	<h4>Fotos</h4>
</div>
<div id="fotos">
<ul class="media-grid">
</ul>
</div>
<script type="text/javascript">
 	var pp = 15;
 	var cuenta = 0;
 	var fotos = 0;
	$(document).ready(function() {
		getFotos(<?php echo $_GET['id'];?>);
	});
	
	function getFotos(id){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'listarfotos',
				action : 'getFotos',
				idart : id
			},
			success : function(data) {
				$.each(data,function(index,value){
					renderFotos(data[index][0].direccion+''+data[index][0].descripcion+'.jpg', data[index][0].id,data[index][0].idart);
				});
			},
			error : function(data) {
			}
		});
	}
	
	function renderFotos (data,id,idart) {
		/*if (fotos == 0){
			cuenta++;
			$('#fotos').append('<div class="row" id="'+cuenta+'"></div>');
		}*/
		var content = '';
		var identificador = "#"+cuenta;
		content = '<li class="span1"><a class="thumbnail" href="editarfotos&id='+id+'&idart='+idart+'"><img id="foto_'+fotos+'" name="'+id+'" src="<?php echo $GLOBALS["baseURL"];?>'+data+'"></a></li>';
		$(".media-grid").append(content);
		/*fotos++;
		if (fotos == 3) {fotos = 0;}*/
	}
</script>
