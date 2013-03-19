
<div id="container header" style="padding-top: 20px;">
	<div class="row span12">
		<div class="span6"><img src="assets/img/header.png"/></div>
	<?php
			if ($_SESSION['user']->status == 'invalid') {
	?>
		<div id="login" class="span4 well well-small pull-right borde">
			<p><em>Iniciar sesi&oacute;n</em></p>
			<form class="form-inline" action="crud.php" id="cloginForm">
						<input name="autenticate" type="hidden" value="" />
						<input name="user" class="input-text input-small" placeholder="e-mail" id="cuser" type="text" title="email"  />
						<input name="password" id="cpassword" class="input-small" placeholder="password" type="password" maxlength="15" />
						<input name="" id="csubmit" class="btn" type="submit" value="Log in" />
			</form>
			<div id="error_container" style="display: none">
				<ol>
				</ol>
			</div>
		</div>
	<?php } else { ?>
	<div id="login" class="span4 well well-small pull-right borde">
		<p><small>Bienvenido <strong><?php if(isset($_SESSION['cliente'])) echo $_SESSION['cliente']->nombre; elseif(isset($_SESSION['vendedor'])) echo $_SESSION['vendedor']->nombre; elseif(isset($_SESSION['empleado'])) echo $_SESSION['empleado']->nombre; ?></strong></p>
		<?php 
		if(isset($_SESSION['cliente']->articulos) and count($_SESSION['cliente']->articulos)>=1) {
			echo '<a class="btn btn-inverse btn-small" href="'.$GLOBALS["baseURL"].'pedidos">Pedidos</a>'; 
		} else {
			if(isset($_SESSION['vendedor'])){
				echo '<a class="btn btn-inverse btn-small" id="boton-pedidos" href="'.$GLOBALS["baseURL"].'vendedor-pedidos">Pedidos</a>';
			}else{
				echo '<a class="btn btn-inverse btn-small disabled" id="boton-pedidos" href="'.$GLOBALS["baseURL"].'pedidos">Pedidos</a>';	
			}
		}?>
		<a class="btn btn-inverse btn-small"href="crud.php?close_session" id="cerrar_session">Cerrar Sesion</a></small></p>
	</div>
	<?php } ?>
	</div>
</div>
