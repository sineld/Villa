<?php

require_once("phputils/httpRequestValidator.php");
$validator = new httpRequestValidator('CRUDManager');
$bool = validateFromMail($validator);

if($bool) $destination = 'controller.php?view='.$GLOBALS["LOGIN_VIEW"];
else $destination = 'controller.php?view='.$GLOBALS["LOGIN_VIEW"];

header('Location: '.$destination);
exit();
	function validateFromMail($validator)
	{

		$validationCode = $validator->getVar('a','validation code');
		$userCod = $validator->getVar('b','user code');

		$result = $validator->exect("select validation_code from user where user.id='".$userCod."' and validation_code='".$validationCode."'");

		$cuenta = mysql_num_rows($result);
		if($cuenta==1)
		{
			$validator->exect("update user set status='valid' WHERE id='".$userCod."'");
			$_SESSION['user']->status='valid';

			$result = $validator->exect("select role.name, user.id as idUsuario from user,role WHERE user.id='".$userCod."' and role.id=user.roleid");
			$objRole = mysql_fetch_object($result);

			$_SESSION['user']->roleName = $objRole->name;
			$_SESSION['user']->id = $objRole->idUsuario;

			return true;
		}

		return false;

	}
?>