 jQuery.fn.log = function (msg) {
      console.log("%s: %o", msg, this)
      return this
  }

	$(document).ready(function() {			
		$('.enviar-item').live("click",function(event){
			event.preventDefault();
			//ACA LLAMAS A AÑADIR ITEMS AL PEDIDO
			
			$.when(procesarItem(1,$(this).attr('attr'))).then(function(data){
				
			});
			
		});
		//alert($('.articulos').find('stock').text());
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
		content += '<div class="span1 imagen"><a id="'+data['id']+'" class="thumbnail link_articulo" style="background: white;" href="#"><img src="'+data["foto"]+'"></a></div>';
		content += '<div class="span3"><div class="row titulo"><div class="span2"><h5><a class="link_articulo" id="'+data['id']+'" href="#"><strong>'+data["codigo"].toUpperCase()+'</strong></a></h5></div><div class="span1">';
		var stock;
		switch(data["agotado"]){
			case "Agotado":
				content += '<span class="stock label label-warning pull-right">'+data['agotado']+'</span>';
				stock = false;
			break;
			case "Disponible":
				content += '<span class="stock label label-success pull-right">'+data['agotado']+'</span>';
				stock = true;
			break;
			default:
			break;
		}
		content += '</div></div><p class="pedidos_nombre">'+data["nombre"]+'</p>';
		if (data['precio'] != null){
			switch(stock){
				case true:
				content += '<p><strong class="pedidos_precio"> Bs. '+data["precio"]+'</strong></p><div class="pedidos_frame pull-right"><a class="pedidos-cant enviar-item btn btn-small btn-info" attr="'+data["id"]+'" href="#"><i class="icon-shopping-cart icon-white"></i> Añadir al pedido</a></div>';
				break;
				case false:
				content += '<p><strong class="pedidos_precio"> Bs. '+data["precio"]+'</strong></p><div class="pedidos_frame pull-right"><a class="pedidos-cant btn btn-small btn-danger disabled" attr="'+data["id"]+'" href="#"><i class="icon-shopping-cart icon-white"></i> No disponible</a></div>';
				break;	
			}	
		}
		content += '</div></div>';
		return content;
	}
	
  
//*****FUNCiONES DEL PAGINADOR EN LA VISTA DE CATALOGO**********************//
function llenarpaginador(ultima_pag,primera_pag,pags_show,total,selector,pagina_actual){
	$(selector).html("")
	$(selector).append("<ul></ul>")
	var html = ""
	
	//Verifico que las paginas a mostrar no sean superior a la cantidad de paginas de la categoria
	/*if(total < pags_show){
		pags_show = total
	}*/
	//Render de cada boton en el paginador
	for(var count = primera_pag; count <= ultima_pag; count++){
		switch(count){
				case 1: 
					html += "<li class='primer-boton'><a href='#' id='"+count+"'>"+count+"</a></li>"
				break;
				case primera_pag:
					html += "<li class='primer-boton'><a href='#' id='"+count+"'>"+count+"</a></li>"		
				break;
				case ultima_pag:
					html += "<li class='ultimo-boton'><a href='#' id='"+count+"'>"+count+"</a></li>"
				break;
				default:
					html += "<li><a href='#' id='"+count+"'>"+count+"</a></li>"
				break;
		}

			
	}
	$(selector+" ul").append("<li class='prev'><a href='#'>Anterior</a></li>")
	$(selector+" ul").append(html)
	$(selector+" ul").append("<li class='next'><a href='#'>Siguiente</a></li>")
	$(selector+" ul li a#"+pagina_actual).parent().addClass("active")
	
	if($(selector+" ul li.active").children().attr("id")==1){
		$(selector+" ul li.prev").addClass("disabled")
	}
	paginador(primera_pag,ultima_pag,pags_show,total,selector,function(){
	alert("hola mundo")
	})	
}

function paginador(primera_pagina,ultima_pagina,pags_show,total,selector,callback){
	$(selector+" ul li").on("click",function(event){
		event.preventDefault
		$(this).log()
		var _activa = $(selector+" ul li.active")
		_next = $(selector+" ul li.next")
		_prev = $(selector+" ul li.prev")
		//Comportamiento de los botones
		switch($(this).attr("class")){
			//Comportamiento para boton "anterior"
			case "prev":
				if(_prev.hasClass("disabled")){
					break;
				}
				if(_next.hasClass("disabled")){
					_next.removeClass("disabled")

				}
				if(_activa.hasClass("primer-boton")){
					if(_activa.children().attr("id")==1){
						_prev.addClass("disabled")

					}else{
						var pagina_previa = parseInt(_activa.children().attr("id"))-1
						_activa.removeClass("active")
						$(selector+" ul li a#"+pagina_previa).parent().addClass("active")
					}
					break;
				}
				if (_activa.children().attr("id") != null){
					var pagina_previa = parseInt(_activa.children().attr("id"))-1
					if(pagina_previa==1){
						_prev.addClass("disabled")

					}
					_activa.removeClass("active")
					$(selector+" ul li a#"+pagina_previa).parent().addClass("active")	
				}
			break;
			
			//Comportamiento para boton "siguiente"
			case "next":
				if(_next.hasClass("disabled")){
					break;
				}
				if(_prev.hasClass("disabled")){
					_prev.removeClass("disabled")

				}
				if(_activa.hasClass("ultimo-boton")){
					if(_activa.children().attr("id")==total){
						_next.addClass("disabled")

					}else{
						var pagina_siguiente = parseInt(_activa.children().attr("id"))+1
						_activa.removeClass("active")
						$(selector+" ul li a#"+pagina_siguiente).parent().addClass("active")
					}
					break;
				}
				if (_activa.children().attr("id") != null){
					var pagina_siguiente = parseInt(_activa.children().attr("id"))+1
					if(pagina_siguiente==total){
						_next.addClass("disabled")

					}
					_activa.removeClass("active")
					$(selector+" ul li a#"+pagina_siguiente).parent().addClass("active")	
				}
			break;
			

			//Comportamiento para la primera página del paginador actual
			
			case "primer-boton":
				if($(this).children().attr("id")==1){
					_prev.addClass("disabled")

				}
				if(_next.hasClass("disabled")){
					_next.removeClass("disabled")
				}
				_activa.removeClass("active")
				$(this).addClass("active")	
			break;
			
			//Comportamiento para la ultima página del paginador actual
			case "ultimo-boton":
				if($(this).children().attr("id")==total){
					_next.addClass("disabled")

				}
				if(_prev.hasClass("disabled")){
					_prev.removeClass("disabled")
				}
				_activa.removeClass("active")
				$(this).addClass("active")
			break;
			
			//Comportamiento para cada boton numerico del paginador
			default:
				if($(this).hasClass('disabled')){
					break;
				}
				if(_prev.hasClass("disabled")){
					_prev.removeClass("disabled")

				}
				if(_next.hasClass("disabled")){
					_next.removeClass("disabled")

				}
				if($(this).hasClass("primera-pagina")){
					_prev.addClass("disabled")

				}
				_activa.removeClass("active")
				$(this).addClass("active")	
			break;
		}
		var pag_activa = $(selector+" ul li.active")
		getArticulos(pag_activa.children().attr("id"),cat,tipo,id_tipo_cliente);
		//Respuesta para avanzar en el paginador
		if(total>pags_show){
			
			if(pag_activa.hasClass("primer-boton")){
				var margen = pags_show/2
				if(pag_activa.children().attr("id")<= margen){
					llenarpaginador(pags_show,1,pags_show,total,selector,pag_activa.children().attr("id"))	
				}else{
					var nuevo_min = primera_pagina-(pags_show/2)
					var nuevo_max = ultima_pagina-(pags_show/2)
					llenarpaginador(nuevo_max,nuevo_min,pags_show,total,selector,pag_activa.children().attr("id"))
				}	
			}
			if(pag_activa.hasClass("ultimo-boton")){
				var margen = total-(pags_show/2)
				if(pag_activa.children().attr("id")>= margen){
					var nuevo_min = total-pags_show+1
					llenarpaginador(total,nuevo_min,pags_show,total,selector,pag_activa.children().attr("id"))
				}else{
					var nuevo_min = primera_pagina+(pags_show/2)
					var nuevo_max = ultima_pagina+(pags_show/2)
					llenarpaginador(nuevo_max,nuevo_min,pags_show,total,selector,pag_activa.children().attr("id"))
				}			
			}
		}
	})
}
//*****FIN FUNCIONES DEL PAGINADOR EN LA VISTA DE CATALOGO**********************//