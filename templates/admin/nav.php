<script type="text/javascript">
$(document).ready(function() { 
	var d = '.dropdown-toggle , .dropdown-menu';

	$(d).on({
		mouseenter:function (){
			clearMenus();
			$(this).parent('li').addClass('open');
		},
		mouseleave:function (){
			clearMenus();
		}
	});
	
	
	function clearMenus() {
		$(d).parent('li').removeClass('open');
	}

});

</script>
<div class="topbar" data-dropdown="dropdown">
	<div class="topbar-inner">
	<div class="container">
	<h3><a href="administracion">M&oacute;dulo de Administraci&oacute;n</a></h3>
	<ul>
		<li><a href="inicio">P&aacute;gina de Inicio</a></li>
		<li><a href="#" class="dropdown-toggle">Articulos</a>
			<ul class="dropdown-menu">
				<li><a href="agregararticulos">Agregar</a></li>
				<li><a href="listararticulos">Eliminar/Modificar</a></li>

			</ul>
		</li>
		<li><a href="#" class="dropdown-toggle">Categorias</a>
			<ul class="dropdown-menu">
				<li><a href="agregarcategorias">Agregar</a></li>
				<li><a href="listarcategorias">Eliminar/Modificar</a></li>

			</ul>
		</li>
		<li><a href="#" class="dropdown-toggle">Tipo de Articulos</a>
			<ul class="dropdown-menu">
				<li><a href="agregartipos">Agregar</a></li>
				<li><a href="listartipos">Eliminar/Modificar</a></li>

			</ul>
		</li>
		<li><a href="#" class="dropdown-toggle">Tipo de Cliente</a>
			<ul class="dropdown-menu">
				<li><a href="agregartipocliente">Agregar</a></li>
				<li><a href="listartipocliente">Eliminar/Modificar</a></li>
				
			</ul>
		</li>
	</ul>
	</div>
	</div>
</div>