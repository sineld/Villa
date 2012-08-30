
	<div class="span12 row">
	<div class="span4 breacrumb">
		<ul class="breadcrumb">
			<?php 
			require_once('public_action/categorias_handler.php');
			require_once('public_action/tipos_handler.php');
				echo '<li><a href="catalogo">Inicio</a><span class="divider">></span></li>';
				if (isset($_GET['categoria'])) {
					$id_cat = $_GET['categoria']; 
					$cat_handler = new categorias_handler;
			 		$cat = $cat_handler->getCategorias($id_cat,null,null,0);
					$cat_itm = json_decode($cat);
					echo '<li><a href="catalogo&categoria='.$id_cat.'">'.ucfirst($cat_itm[0]->{'nombre'}).'</a><span class="divider">></span</li>';
				} 
				if (isset($_GET['tipo'])) {
					$id_tipo = $_GET['tipo']; 
					$tipo_handler = new tipos_handler;
					$tipo = $tipo_handler->getTipos(0,$id_tipo,null,null,null);
					$tipo_itm = json_decode($tipo);
					echo '<li class="active"><a href="#">'.ucfirst($tipo_itm[0]->{'nombre'}).'</a></li>';
				} 
			?>		
		</ul>
	</div>
	<div class="span2 pull-right" id="cant_paginas"></div>
</div>
<div class="span12 row">
	<div class="span2 well" style="padding: 8px 0;">
		<ul class="nav nav-list">
		<?php 
		$cat = new categorias_handler;
		$tip = new tipos_handler;
		$data = json_decode($cat->getCategorias(null,null,null,0));
		foreach($data as $d){
			$aux = strtoupper($d->{'nombre'});
			$id_cat = $d->{'id'};
			echo '<li class="nav-header"><strong>'.ucfirst(strtolower($aux)).'</strong></li>';
			$data1 = json_decode($tip->getTipos(0, null, $d->{'id'}));
			foreach ($data1 as $d1){
				$aux1 = ucfirst($d1->{'nombre'});
				$id_tipo = $d1->{'id'};
				echo '<li categoria-id="'.$id_cat.'" tipo-id="'.$id_tipo.'"><a title="'.$aux1.'" href="catalogo2&categoria='.$id_cat.'&tipo='.$id_tipo.'">'.$aux1.'</a></li>';
			}
		}
		?>
		</ul>
	
		
	</div>
	<div class="span8">
		<div class="row mostrar_items ">
			<div class="span4 1ra_columna"></div>
			<div class="span4 2da_columna"></div>
		</div>
		<div id="paginador" class="pagination pagination-centered"></div>		
	</div>
</div>


<script type="text/javascript">
	id_tipo_cliente = <?php if (isset($_SESSION['cliente'])) { echo $_SESSION['cliente']->id_tipo; } else if (isset($_SESSION['vendedor'])) { echo $_SESSION['vendedor']->id_tipo; }else { echo 'null'; } ?>;
	cat = <?php if (isset($_GET['categoria'])) { echo $_GET['categoria']; } else { echo 'null'; } ?>;
	tipo = <?php if (isset($_GET['tipo'])){ echo $_GET['tipo']; } else { echo 'null'; }?>;
	id_cliente_pedidos = <?php if (isset($_SESSION['cliente'])){ echo $_SESSION['cliente']->id_usuario; }  else { echo 'null';} ?>;
	$(document).ready(function() {
		$('.enviar-item').live("click",function(event){
			event.preventDefault();
			//ACA LLAMAS A AÑADIR ITEMS AL PEDIDO
			_valores = $(this).siblings('.pedidos-cant');
			$.when(procesarItem(_valores.attr('value'),_valores.attr('attr'))).then(function(data){
				alert('Item agregado: '+respuesta);
			});
			
		});
		$('.mini').live("click",function(event){
			event.preventDefault();
			$('.big').attr('src',$(this).attr('src'));
		});
		$("#display_articulo_completo").hide();
   		$('.link_articulo').live("click",function(event){
   			event.preventDefault;
   			var id = $(this).attr("id");
   			getArticuloSolo(id,id_tipo_cliente);
   			$("#display_articulo_completo").modal('toggle');
   		});
   		$('[data-dismiss="modal"]').live("click",function(event){
   			event.preventDefault;
   			$("#display_articulo_completo").modal('toggle');
   		});
		$('.nav-list li[tipo-id="'+tipo+'"] ').addClass("active");
		
		totalpag = getPaginationArt(cat,tipo);
		getArticulos(1,cat,tipo,id_tipo_cliente);
		var pagactual = 1;
		if(totalpag<=10){
			llenarpaginador(totalpag,pagactual,totalpag,totalpag,'#paginador',pagactual);
		}else{
			llenarpaginador(10,1,10,totalpag,"#paginador",pagactual);
		}
	});

	function getPaginationArt(cat,tipo){
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'catalogo',
				action : 'getPaginationArt',
				categoria : cat,
				tipo : tipo,
				inactivo : 0
			},
			async : false,
			success : function(data) {
				totalpag = data[0];
			}
		});
		return totalpag;
	}
	function getArticuloSolo(id,itc) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'catalogo',
				action : 'getArticulosCompleto',
				id : id,
				id_tipo_cliente : itc
			},
			success : function(data) {
				var content;
				var i = 0;
				$('#item_preliminar').html("");
				$('#item_imagenes').html("");
				jQuery.each(data[0]['foto'], function() {
					if(i==0){
						$('#item_preliminar').append('<li class="span3"><a href="#"><img class="thumbnail big" width="230px" height="230px" src="'+this+'" /></a></li>');
					}
					fotos = '<li class="span1"><a href="#"><img class="thumbnail mini" id="foto_'+i+'" src="'+this+'" /></a></li>'
					i++;		
   					$('#item_imagenes').append(fotos);
   				});
   				
				$('#codigo').html(data[0]['codigo']);
				$('#nombre').html(data[0]['nombre']);	
							
				if (data[0]['agotado'] == 0){
					if($('#agotado').hasClass("label-warning")) $('#agotado').removeClass('label-warning');
					$('#agotado').addClass('label-success');
					$('#agotado').html('Disponible');
				}else {
					if($('#agotado').hasClass("label-success")) $('#agotado').removeClass('label-success');
					$('#agotado').addClass('label-warning');
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

	
	//Funcion para enviar por AJAX los items que el cliente quiere al pedido abierto.
	function procesarItem(cant,id_art){
		return $.ajax({
		type : "POST",
		url : "crud.php",
		dataType : 'html',
		data : {
			view: 'catalogo',
			action: 'añadirItemPedido',
			cantidad : cant,
			id_articulo : id_art,
			},
		success : function(data){
			respuesta = data;
			}
		});
	}
	
	
	function getArticulos(pag, cat, tipo, itc) {
		$.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'catalogo',
				action : 'getArticulosCorto',
				pagina : pag,
				categoria : cat,
				tipo : tipo,
				id_tipo_cliente : itc
			},
			success : function(data) {
				$('.1ra_columna').html('');
				$('.2da_columna').html('');
				for (var i=0; i < (data.length)/2; i++) {
					 $('.1ra_columna').append(renderArticulos(data[i]));
				}for (var i=Math.ceil((data.length)/2); i < data.length; i++) {
					 $('.2da_columna').append(renderArticulos(data[i]));
				}
			}
		});
	}
	function renderArticulos(data){
		var content = '<div class="row articulos">';
		content += '<div class="span1 imagen"><a id="'+data['id']+'" class="thumbnail link_articulo" href="#"><img src="'+data["foto"]+'"></a></div>';
		content += '<div class="span3"><div class="row titulo"><div class="span2"><h5><a class="link_articulo" id="'+data['id']+'" href="#"><strong>'+data["codigo"].toUpperCase()+'</strong></a></h5></div><div class="span1">';
		switch(data["agotado"]){
			case "Agotado":
				content += '<span class="label label-warning pull-right">'+data['agotado']+'</span>';
			break;
			case "Disponible":
				content += '<span class="label label-success pull-right">'+data['agotado']+'</span>';
			break;
			default:
			break;
		}
		content += '</div></div><p class="pedidos_nombre">'+data["nombre"]+'</p>';
		if (data['precio'] != null){
			content += '<p><strong class="pedidos_precio"> Bs. '+data["precio"]+'</strong></p><div class="pedidos_frame pull-right"><input type="text" attr="'+data["id"]+'" class="pedidos-cant span1" style="text-align: center; height: 17px; margin-top: 10px;" value="" /><a class="enviar-item btn btn-small btn-info" href="#"><i class="icon-shopping-cart icon-white"></i></a></div>';
		}
		content += '</div></div>';
		return content;
	}
	
	
	//<a href="#" class="pedidos_boton pull-right" name="'+data['id']+'">A'+String.fromCharCode(241)+'adir al pedido</a>
	
</script>
<div id="display_articulo_completo" class="modal">
  <div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h3>Ficha de Art&iacute;culo</h3>
  </div>
  <div class="modal-body">
    <div class="row">
    	<div class="span3">
    		<div class="page-header">
				<h5>Im&aacute;genes</h5>
			</div>
    		<ul class="thumbnails" id="item_preliminar">
    			
    		</ul>
    		<ul class="thumbnails" id="item_imagenes">
    			
    		</ul>
    	</div>
    	<div class="span5">
    		<div class="page-header">
    			<h5>C&oacute;digo de Art&iacute;culo
					<span class='label label-info' id="codigo"></span>
					<span class='label pull-right' id="agotado"></span>
				</h5>
			</div>
			<div class="well">
					<div>
						<strong id="nombre"></strong>
					</div>	
					</br>
					<div id="descripcion" style="color:gray;">
					</div>
			</div>
    		<div class="span4">
				<table class="table table-bordered" style="width: 100%">
					<thead>
						<tr>
							<th width="16%" style="text-align:center;" id="altolbl"><img src="img/alto-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="ancholbl"><img src="img/ancho-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="largolbl"><img src="img/largo-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="diametrolbl"><img src="img/diametro-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="pesolbl"><img src="img/peso-ico.png" /></th>
							<th width="16%" style="text-align:center;" id="empaquelbl"><img src="img/unidad-ico.png" /></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="alto">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="ancho">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="largo">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="diametro">
								<IMG  SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20px" ALT="No disponible">
							</td>
							<td id="peso">
								<IMG SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20" ALT="No disponible">
							</td>
							<td id="empaque">
								<IMG SRC="images/no_disponible2.jpg" WIDTH="20px" HEIGHT="20" ALT="No disponible">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
    	</div>
    </div>
  </div>
  <div class="modal-footer">
  </div>
</div>