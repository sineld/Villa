		<link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
			<div class="offset1">
			<div class="row">	
				<div class="span5">
						<div class="page-header">
							<h5>Im&aacute;genes</h5>
						</div>
						<div class="well">
							<ul class="media-grid" id="item_preliminar">
							</ul>
							<ul class="media-grid" id="item_imagenes">			
							</ul>
						</div>
					</div>
					<div class="span8">
						<div class="page-header"><h5>C&oacute;digo de Art&iacute;culo
						<span class='label notice' style="background-color: #0066FF;" id="codigo"></span>&nbsp;<span class="label" id="agotado"></span></h5></div>
						<div class="well">
							<div>
								<strong id="nombre"></strong>
							</div>	
						</br>
							<div id="descripcion" style="color:gray;" >
							</div>
						</div>
						<div class="row">
							<div class="span8">
							<table class="bordered-table">
							<thead>
								<tr>
									<th width="16%" style="text-align:center;" id="altolbl">Alto</th>
									<th width="16%" style="text-align:center;" id="ancholbl">Ancho</th>
									<th width="16%" style="text-align:center;" id="largolbl">Largo</th>
									<th width="16%" style="text-align:center;" id="diametrolbl">Di&aacute;metro</th>
									<th width="16%" style="text-align:center;" id="pesolbl">Peso</th>
									<th width="16%" style="text-align:center;" id="empaquelbl">Empaque</th>
								</tr>
							</thead>
							<tbody>
								<tr>
							<td id="alto">
								<IMG style="padding-left:19px; padding-top:5px;" SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="ancho">
								<IMG style="padding-left:19px; padding-top:5px;" SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="largo">
								<IMG style="padding-left:19px; padding-top:5px;" SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="diametro">
								<IMG style="padding-left:19px; padding-top:5px;" SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="peso">
								<IMG style="padding-left:19px; padding-top:5px;" SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20" ALT="No disponible">
							</td>
							<td id="empaque">
								<IMG style="padding-left:19px; padding-top:5px;" SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20" ALT="No disponible">
							</td>
						</tr>
							</tbody>
							</table>
							</div>
						</div>
					</div>
					</div>
					</div>
				

<script type="text/javascript">
	$(document).ready(function() {
		$('.mini').live("click",function(event){
			event.preventDefault();
			$('.big').attr('src',$(this).attr('src'));
		});
		id = <?php if (isset($_GET['id'])) { echo $_GET['id']; } else { echo 'null'; } ?>;
		id_tipo_cliente = <?php if (isset($_SESSION['cliente'])) { echo $_SESSION['cliente']->id_tipo; } else if (isset($_SESSION['vendedor'])) { echo $_SESSION['vendedor']->id_tipo; }else { echo 'null'; } ?>;
		if (id != null && id != 'null') {
		getArticulos(id,id_tipo_cliente);
		}
		
	});
	function getArticulos(id,itc) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'articulo',
				action : 'getArticulosCompleto',
				id : id,
				id_tipo_cliente : itc
			},
			success : function(data) {
				var content;
				var i = 0;
				jQuery.each(data[0]['foto'], function() {
					if(i==0){
						$('#item_preliminar').append('<li><a href="#"><img class="thumbnail big" width="230px" height="230px" src="'+this+'" /></a></li>');
					}
					fotos = '<li><a href="#"><img width="50px" height="50px" class="thumbnail mini" id="foto_'+i+'" src="'+this+'" /></a></li>'
					i++;		
   					$('#item_imagenes').append(fotos);
   				});
				$('#codigo').html(data[0]['codigo']);
				$('#nombre').html(data[0]['nombre']);
				if (data[0]['agotado'] == 0){
					$('#agotado').addClass('success')
					$('#agotado').html('Disponible');
				}else {
					$('#agotado').addClass('important')
					$('#agotado').html('Agotado');
				}
				
				if (data[0]['precio']!=null){
					$('.precio').html('Precio: '+data[0]['precio']+' Bs.F');
				} 
				if (data[0]['alto']!=null) {
					$('#alto').html(data[0]['alto']+' cms.');
				}	
				if (data[0]['ancho']!=null) {
					$('#ancho').html(data[0]['ancho']+' cms.');
				}	
				if (data[0]['largo']!=null) {
					$('#largo').html(data[0]['largo']+' cms.');
				}	
				if (data[0]['diametro']!=null) {
					$('#diametro').html(data[0]['diametro']+' cms.');
				}
				if (data[0]['peso']!=null) {
					peso = data[0]['peso'];
					unidad = 'grs.';
					if (peso>1000){peso = peso/1000; unidad = 'kgs.'}
					$('#peso').html(peso+' '+unidad);
				}
				if (data[0]['empaque']!=null) {
					$('#empaque').html(data[0]['empaque']);
				}	
				$('#descripcion').html(data[0]['descripcion']);	
				if (data[0]['tipo'] == 'pecheras' || data[0]['tipo'] == 'Pecheras'){
					$('#altolbl').text("Tronco");
					$('#ancholbl').text("Pecho");
					$('#largolbl').text("Paseador");
					$('#diametrolbl').text("Cuello");
				}
				
			}
		});
		
		
	}
</script>
