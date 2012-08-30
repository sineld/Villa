$(document).ready(function(){
	$('.barra_catalogo ul li').hover( function(){
			$(this).children('ul').fadeToggle('fast')
		},
		function(){
			$(this).children('ul').fadeToggle('fast')
		}
	);
})
