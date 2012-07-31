
		<form action="crud.php" method="post">
			<input type="hidden" name="action" value="update" />
		<input type='hidden' name='id' value='<?php echo $vars["entity"]->id; ?>' />

<p><b>Question</b></p>
	<input type='text' name='question' value='<?php echo $vars["entity"]->question; ?>' />

<p><b>Status</b></p>
	<input type='text' name='status' value='<?php echo $vars["entity"]->status; ?>' />

<input type="submit" name="submit" value="Save" />
		</form>