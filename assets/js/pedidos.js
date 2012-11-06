$(document).on('click','.boton-eliminar',function(){
	_DOMObject = $(this)
	$.when(deleteItemSesion($(this).attr('attr'))).done(function(){
		_DOMObject.parent().parent().remove()	
	})
})

$(document).on('click','.disabled',function(event){
	event.preventDefault
})

$(document).on('change','input[id^="cantidad-"]',function(event){
	event.preventDefault
	$.when(modItemSesion($(this).attr('attr'),$(this).val())).done(function(){
		 location.reload();
	})
})
function deleteItemSesion(id_art){
	return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'pedidos',
				action : 'eliminarArtSesion',
				id : id_art,
			}
	});
}
function modItemSesion(id_art,cant){
	return $.ajax({
			type : "POST",
			url : "crud.php",
			dataType : "json",
			data : {
				view : 'pedidos',
				action : 'modArtSesion',
				id : id_art,
				cantidad : cant,
			}
	})
}
