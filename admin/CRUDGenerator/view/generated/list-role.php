
		<p>Agregar un nuevo registro <a class="agregarLink" href="controller.php?view=add-role"></a></p>
		<p>Lista de role</p>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		<td>id</td>
		<td>name</td>
			</tr>
			<?php foreach($vars["listrole"] as $record) { ?>
			<tr>
				<td><?php echo $record->id; ?></td>
				<td><?php echo $record->name; ?></td>
				<td><a class="deleteLink" href="crud.php?action=delete&id=<?php echo $record->id; ?>"></a></td>
				<td><a class="editarLink" href="controller.php?view=update-role&idrole=<?php echo $record->id; ?>"></a></td>
			</tr>
			<?php } ?>
		</table>