<?php 

?>

<form class='form-stacked' name='pedidos' method='post' action='<?php echo $GLOBALS["baseURL"];?>crud.php'>
	<input type="hidden" name="view" value="pedidos" />
	<input type="hidden" name="action" value="crearPedidos" />
	<label>Cliente</label>
	<input type="text" id="id_cliente" name="id_cliente" />
	<label>Credito</label>
	<input type="text" id="tipo_pago" name="tipo_pago" />
	<label>Forma de pago</label>
	<input type="text" id="tipo_pago" name="forma_pago" />
	<input class="btn primary" type="submit" value="Enviar" id="enviar"/>
</form>
<!--
<table>
	<thead>
		<tr>
			<th class="header">#</td>
			<th class="header">C&oacute;digo</td>
			<th class="header">Descripci&oacute;n</td>
			<th class="header">Cantidad</td>
			<th class="header">P.Unidad</td>
			<th class="header">Precio</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>#</td>
			<td><input class="codigo" type="text" name="codigo"></td>
			<td><label class="descripcion" name="descripcion">Descripcion corta del art&iacute;culo</label></td>
			<td><input class="cantidad" type="text" name="cantidad"></td>
			<td><label class="preciounitario" name="preciounitario">20</label></td>
			<td><label class="precio" name="precio">20</label></td>
		</tr>
	</tbody>
</table>
-->