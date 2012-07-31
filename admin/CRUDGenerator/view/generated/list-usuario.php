
		<p>Agregar un nuevo registro <a class="agregarLink" href="controller.php?view=add-usuario"></a></p>
		<p>Lista de usuario</p>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		<td>id</td>
		<td>user</td>
		<td>password</td>
		<td>locationid</td>
		<td>roleid</td>
		<td>status</td>
		<td>validation_code</td>
			</tr>
			<?php foreach($vars["listusuario"] as $record) { ?>
			<tr>
				<td><?php echo $record->id; ?></td>
				<td><?php echo $record->user; ?></td>
				<td><?php echo $record->password; ?></td>
				<td><?php echo $record->locationid; ?></td>
				<td><?php echo $record->roleid; ?></td>
				<td><?php echo $record->status; ?></td>
				<td><?php echo $record->validation_code; ?></td>
				<td><a class="deleteLink" href="crud.php?action=delete&id=<?php echo $record->id; ?>"></a></td>
				<td><a class="editarLink" href="controller.php?view=update-usuario&idusuario=<?php echo $record->id; ?>"></a></td>
			</tr>
			<?php } ?>
		</table>