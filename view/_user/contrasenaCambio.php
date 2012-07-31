<script type="text/javascript">
 $(document).ready(function(){

	$('#NewContasenaForm').ajaxForm({

					target:         '#standardError',
					beforeSubmit:     validatechangePassword ,
					success: function() {
						$("#standardError").show();
					}
			});

 });
//Para el estudiante
function validatechangePassword(formData, jqForm, options)
{

   $("#contrasenaError").empty().hide();
   $("#contrasenaNewError").empty().hide();
   $("#contrasenaNewReError").empty().hide();

   var errors               = 0;
   var contra           	= $("#contrasena").val();
   var contraNew         	= $("#contrasenaNew").val();
   var contraReNew         	= $("#contrasenaReNew").val();

   if (noEmpty(contra))
	{
		$("#contrasenaError").show().append("This field is empty.");
		errors++;
	}
   if (noEmpty(contraNew))
	{
		$("#contrasenaNewError").show().append("This field is empty.");
		errors++;
	}
   if (noEmpty(contraReNew) || contraReNew!=contraNew)
	{
		$("#contrasenaNewReError").show().append("This field is empty.");
		errors++;
	}



	 if (errors > 0)
    {
		$("#standardError").empty();
	    return false;
    }
	 else
	{
	    formData[formData.length] = { "name": "contrasena", "value": $.sha1(contra) };
		formData[formData.length] = { "name": "contrasenaNew", "value": $.sha1(contraNew) };
		formData[formData.length] = { "name": "contrasenaReNew", "value": $.sha1(contraReNew) };
		return true;
	}



}//Fin de la funci�n validatechangePassword
 </script>
<form name="NewContasenaForm" id= "NewContasenaForm" action="crud.php" method="post">
 <input type="hidden" name="action" value="CambiarContrasena"/>
 <div class="error" id="standardError" style="display:none;"></div>

                      <p align="center" class="Estilo1">Change of Password:</p>
                      <p align="center"> <strong>Password:</strong>:
                        <label>
                        <input name="contrasena" type="password" id="contrasena" maxlength="15"><strong class="error" id="contrasenaError"></strong>
                        </label>
                          </p>
                      <p align="center"><br />
                          <strong>New Password:</strong>:
                        <label>
                          <input name="contrasenaNew" type="password" id="contrasenaNew" maxlength="15"><strong class="error" id="contrasenaNewError"></strong>
                                                    </label>
                          </p>
                      <p align="center"><br />
                          <strong>Retype New Password:</strong>:
                        <label>
                          <input name="contrasenaReNew" type="password" id="contrasenaReNew" maxlength="15"><strong class="error" id="contrasenaNewReError"></strong>
                                                                              </label>
                                                                  </p>
                      <p align="center">
                        <label>
                        <input type="submit" name="button" id="button" value="Aceptar">
                        </label>
                      </p>
                      <p> </p>
                      <p align="center"><br />
                      </p>
</form>