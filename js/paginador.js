 jQuery.fn.log = function (msg) {
      console.log("%s: %o", msg, this)
      return this
  }
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