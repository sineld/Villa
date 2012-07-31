
		<form action="crud.php" method="post">
			<input type="hidden" name="action" value="update" />
		<input type='hidden' name='id' value='' />

<p><b>User</b></p>
	<input type='text' name='user' value='<?php echo $vars["entity"]->user; ?>' />

<p><b>Password</b></p>
	<input type='text' name='password' value='<?php echo $vars["entity"]->password; ?>' />

<p><b>Locationid</b></p>
	<input type='text' name='locationid' value='<?php echo $vars["entity"]->locationid; ?>' />

<p><b>Roleid</b></p>
	<input type='text' name='roleid' value='<?php echo $vars["entity"]->roleid; ?>' />

<p><b>Status</b></p>
	<input type='text' name='status' value='<?php echo $vars["entity"]->status; ?>' />

<p><b>Validation Code</b></p>
	<input type='text' name='validation_code' value='<?php echo $vars["entity"]->validation_code; ?>' />

<input type="submit" name="submit" value="Save" />
		</form>