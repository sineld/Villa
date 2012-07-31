
		<form action="crud.php" method="post">
			<input type="hidden" name="action" value="update" />
		<input type='hidden' name='id' value='<?php echo $vars["entity"]->id; ?>' />

<p><b>Name</b></p>
	<input type='text' name='name' value='<?php echo $vars["entity"]->name; ?>' />

<input type="submit" name="submit" value="Save" />
		</form>