<div id="index">
	<?php 
	require_once('public_action/categorias_handler.php');
	require_once('public_action/tipos_handler.php');
		echo '<a href="catalogo">Inicio</a> >';
		if (isset($_GET['categoria'])) {
			$id_cat = $_GET['categoria']; 
			$cat_handler = new categorias_handler;
	 		$cat = $cat_handler->getCategorias($id_cat,null,null,0);
			$cat_itm = json_decode($cat);
			echo '<a href="catalogo&categoria='.$id_cat.'"> '.ucfirst($cat_itm[0]->{'nombre'}).'</a> >';
		} 
		if (isset($_GET['tipo'])) {
			$id_tipo = $_GET['tipo']; 
			$tipo_handler = new tipos_handler;
			$tipo = $tipo_handler->getTipos(0,$id_tipo,null,null,null);
			$tipo_itm = json_decode($tipo);
			echo ' '.ucfirst($tipo_itm[0]->{'nombre'}).'</span>';
		} 
	?>
	<div id="cant_paginas"></div>

</div>
<div class="mostrar_items">
	<ul class="1ra_columna"></ul>
	<ul class="2da_columna"></ul>
</div>
<div id="paginador" class="jPaginate"></div>

<script type="text/javascript">
	$(document).ready(function() {
	
		$('#loadingDiv')
			.css('visibility','hidden')
    		.ajaxStart(function() {
        		$(this).css('visibility','visible');
        		$('.1ra_columna').hide();
        		$('.2da_columna').hide();
  		 })
    		.ajaxStop(function() {	
      		  	$(this).css('visibility','hidden');
	   		  	$('.1ra_columna').show();
        		$('.2da_columna').show();
   		 });
   		id_tipo_cliente = <?php if (isset($_SESSION['cliente'])) { echo $_SESSION['cliente']->id_tipo; } else if (isset($_SESSION['vendedor'])) { echo $_SESSION['vendedor']->id_tipo; }else { echo 'null'; } ?>;
		cat = <?php if (isset($_GET['categoria'])) { echo $_GET['categoria']; } else { echo 'null'; } ?>;
		tipo = <?php if (isset($_GET['tipo'])){ echo $_GET['tipo']; } else { echo 'null'; }?>;
		totalpag = getPaginationArt(cat,tipo);
		getArticulos(1,cat,tipo,id_tipo_cliente);
		var pagactual = 1;
		$('#cant_paginas').text('Pagina '+pagactual+' de '+totalpag);
		$("#paginador").paginate({
				count 		: totalpag,
				start 		: 1,
				display     : 10,
				border					: false,
				text_color  			: '#EA5200',
				background_color    	: 'none',	
				text_hover_color  		: '#2573AF',
				background_hover_color	: 'none', 
				images		: true,
				mouse		: 'press',
				onChange   	: function(page){
											getArticulos(page,cat,tipo,id_tipo_cliente);
										  }
		});	
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
				/*$('#paginador').append('Paginas: '+totalpag);*/
			}
		});
		return totalpag;
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
		var content = '<li>';
		content += '<img class="pedidos_imagen" src="'+data["foto"]+'">';
		content += '<h5 class="pedidos_codigo"><a class="single_image" id="'+data['id']+'" href="#">'+data["codigo"].toUpperCase()+'</a></h5>';
		content += '<span class="label '+data['agotado']+'">'+data['agotado']+'</span>';
		content += '<span class="pedidos_nombre">'+data["nombre"]+'</span>';
		if (data['precio'] != null){
		content += '<span><strong class="pedidos_precio"> Bs. '+data['precio']+'</strong></span><a href="#" class="pedidos_boton" name="'+data['id']+'">A'+String.fromCharCode(241)+'adir al pedido</a>';
		}
		content += '</li>';
		return content;
	}
	
</script>


<script type="text/javascript">
$(document).ready(function() {

	/* This is basic - uses default settings */
		$("a.single_image").livequery( function(){
			$(this).fancybox({ 
			'type' : 'iframe',
			'width' : 960,
			'height' : 500,
			'scrolling' : 'no',
			'margin' : 0,
			'padding' : 0,
			'autoscale' : true,
			'href' : 'articulo&id='+$(this).attr("id")
			});
		});
});
</script>
<div id="loadingDiv"></div>



<?php if (isset($_SESSION['cliente'])) {?>


<?php } ?>
