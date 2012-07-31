
		<p>Agregar un nuevo registro <a class="agregarLink" href="controller.php?view=add-poll"></a></p>
		<p>Lista de poll</p>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>
		<td>id</td>
		<td>question</td>
		<td>date</td>
		<td>status</td>
			</tr>
			<?php foreach($vars["listpoll"] as $record) { ?>
			<tr>
				<td><?php echo $record->id; ?></td>
				<td><?php echo $record->question; ?></td>
				<td><?php echo $record->date; ?></td>
				<td><?php echo $record->status; ?></td>
				<td><a class="deleteLink" href="crud.php?action=delete&id=<?php echo $record->id; ?>"></a></td>
				<td><a class="editarLink" href="controller.php?view=update-poll&idpoll=<?php echo $record->id; ?>"></a></td>
			</tr>
			<?php } ?>
		</table>