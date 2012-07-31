<script type="text/javascript">
 $(document).ready(function(){

	$('#loginForm').ajaxForm({

					target:         '#standardError',
					beforeSubmit:     validateloginForm ,
					success: function() {
						$("#standardError").show();
					}
			});

 });

 function validateloginForm(formData, jqForm, options)
{
	var errors          = 0;

	$("#userError").empty().hide();
	$("#passwordError").empty().hide();

	var email         	= $("#user").val();
	var password         	= $("#password").val();

	if (noEmpty(email) || !isValidEmail(email))
	{
		$("#userError").show().append("*");
		errors++;
	}
	if (noEmpty(password))
	{
		$("#passwordError").show().append("*");
		errors++;
	}
    if (errors > 0)
    {
		$("#standardError").empty();
		$("#standardError").show().append("Invalid username or password");
        return false;
    }
	else
	{
		formData[formData.length] = { "name": "password", "value": $.sha1(password) };
		return true;
	}
}
 </script>
<div id="content">

	<span id="message">Please introduce your User name and Password to get in</span>
	<div class="error" id="standardError" style="display:none;"></div>

    <div id="login">
	<form action="crud.php" id="loginForm">
    <input name="autenticate" type="hidden" value="" />
		<input name="user" id="user" type="text" title="email" />	<strong class="error" id="userError"></strong>
        <input name="password" id="password" type="password" />		<strong class="error" id="passwordError"></strong>
        <input name="" type="submit" value="Log in" style="width:90px;" />
    </form>
    </div>

    <span><a href="controller.php?view=forgot">Forgot your password? </a></span>
</div>