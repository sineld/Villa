<?php

class ValidateDelegate
{

	function validateUser($validator)
	{
		require("manager/user.manager.php");

		$validationCode = $validator->getVar('validationCode','Codigo de validacion');

		$result = $validator->exect("select validation_code from usuario where user='".$_SESSION['usuario']->name."' and validation_code='".$validationCode."'");

		$cuenta = mysql_num_rows($result);
		if($cuenta==1)
		{
			$validator->exect("update usuario set status='valid' WHERE user='".$_SESSION['usuario']->name."'");
			$_SESSION['usuario']->status='valid';

			$result = $validator->exect("select role.name,usuario.id as idUsuario from usuario,role WHERE user='".$_SESSION['usuario']->name."' and role.id=roleId");
			$objRole = mysql_fetch_object($result);

			$_SESSION['usuario']->roleName = $objRole->name;
			$_SESSION['usuario']->id = $objRole->idUsuario;

			return 'controller.php?view=invitar';
		}

		return 'controller.php?view='.$GLOBALS["LOGIN_VIEW"];

	}

	function validateFromMail($validator)
	{

		$validationCode = $validator->getVar('a','validation code');
		$userCod = $validator->getVar('b','user code');

		$result = $validator->exect("select validation_code from usuario where usuario.id='".$userCod."' and validation_code='".$validationCode."'");

		$cuenta = mysql_num_rows($result);
		if($cuenta==1)
		{
			$validator->exect("update usuario set status='valid' WHERE id='".$userCod."'");
			$_SESSION['usuario']->status='valid';

			$result = $validator->exect("select role.name from usuario,role WHERE usuario.id='".$userCod."' and role.id=roleId");
			$objRole = mysql_fetch_object($result);

			$_SESSION['usuario']->roleName = $objRole->name;

			return 'controller.php?view='.$GLOBALS["PRIVATE_VIEW"];
		}

		return 'controller.php?view='.$GLOBALS["LOGIN_VIEW"];

	}

}


?>