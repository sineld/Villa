$(document).on('click','.boton-eliminar',function(event){
	_DOMObject = $(this)
	$.when(deleteItemSesion($(this).attr('attr'))).done(function(){
		_DOMObject.parent().parent().remove()	
		location.reload();
	})
})
$(document).on('click','.disabled',function(event){
	event.preventDefault
})
$(document).on('change keypress','input[id^="cantidad-"]',function(event){
	event.preventDefault
	this.value = this.value.replace(/[^0-9\.]/g,'')
	if(event.type=="change"){
		$.when(modItemSesion($(this).attr('attr'),$(this).val())).then(function(){
		 		location.reload()
			},function(){
			alert('Error cambiando cantidad de articulos')
		})
	}else{
		if(event.which==13){
			$(this).focusout()
		}
	}	
})
function deleteItemSesion(id_art){
	return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'vendedor-pedidos',
				action : 'eliminarArtSesion',
				id : id_art
			}
	});
}
function modItemSesion(id_art,cant){
	return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'vendedor-pedidos',
				action : 'modArtSesion',
				id : id_art,
				cantidad : cant,
			}
	})
}
