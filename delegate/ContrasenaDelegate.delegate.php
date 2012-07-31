<?php

class ContrasenaDelegate {
	public function ContrasenaDelegate() {

	}// Fin del constructor

	public function CambiarContrasena($validator) {
		// función encargada de realizar  el cambio de contraseña de un usuario

		$contra = $validator -> getVar('contrasena', 'Contraseña');
		$contraNew = $validator -> getVar('contrasenaNew', 'Nueva Contraseña');
		$contraNew2 = $validator -> getVar('contrasenaReNew', 'Reescribir Contraseña');
		$usuario = $_SESSION['user'] -> id;

		$record = Doctrine::getTable('user') -> find($usuario);

		if ((!empty($record)) && ($contraNew == $contraNew2) && ($record -> password == $contra)) {
			$record -> password = $contraNew;
			$record -> save();

			return 'controller.php?view=private';

		} else {
			$validator -> addError('No se realizo el cambio de contraseña');

		}

	}// fin de la función CambiarContrasena

	public function CambiarContrasena2($validator) {
		// función encargada de realizar  el cambio de contraseña de un usuario

		$contraNew = $validator -> getVar('contrasenaNew');
		$contraNew2 = $validator -> getVar('contrasenaReNew');
		$usuario = $validator->getVar('email');
		$record = Doctrine::getTable("User") ->  findOneByEmail(trim($usuario));
		if ($record && ($contraNew == $contraNew2)) {
			$record -> password = $contraNew;
			$record -> save();
			echo 'true';
		} else {
			echo 'No se puso realizar el cambio de contreña';
		}
		return 'void';

	}// fin de la función CambiarContrasena


	public function OlvidarContrasena($validator) {

		$emailString = $validator -> getVar('emails');
		$record = Doctrine::getTable("User") ->  findOneByEmail($emailString);
		
		if ($record && $record -> status == "valid") {
			$val_code = $random = rand(0,999999999);
			
			
			$record -> validation_code = $val_code;
			$record->save();
			$enc_val_code = sha1($val_code); 
			$url = $GLOBALS["baseURL"]."controller.php?view=forgot2&email=".$emailString."&key=".$enc_val_code;
			require_once ('phputils/class.phpmailer.php');
			require_once ('phputils/class.smtp.php');
			try {

				$mail = new PHPMailer();
				$mail -> From = "support@villadelasmascotas.com";
				$mail -> FromName = "La Villa de las Mascotas";
				$mail -> AddAddress($emailString);
				$mail -> Subject =  utf8_decode("Solicitud de cambio de contraseña");
				$mail -> Body = "Hola ".$emailString."<p> Recibimos una solicitud de cambio de contrase&ntilde;a. 
								Para confirmar esta solicitud haz clic en la siguiente direcci&oacute;n ".$url."
								<p>Por favor, ignora este mensaje en el caso de no haber olvidado la contraseña.
								Saludos";
				$mail -> IsHTML(true);
				$mail -> IsSMTP();
				$mail -> SMTPSecure = "ssl";
				$mail -> Host = 'smtp.gmail.com';
				$mail -> Port = 465;
				$mail -> SMTPAuth = true;
				$mail -> Username = 'support@villadelasmascotas.com';
				$mail -> Password = 'm643140991';

				if (!$mail -> Send()) {
					echo 'Hubo un error al momento de enviar el correo electr&oacute;nico. Por favor intente nuevamente o p&oacute;ngase en contacto con los administradores';
				} else {
					echo 'true';
				}
			} catch (phpmailerException $e) {
				echo $e;
			}

			return 'void';
		} else {
			echo 'El email proporcionado no existe o no ha sido validado. Por favor verifique e intente nuevamente';
			return 'void';
		}
	} //fin de la función OlvidarContrasena

}// Fin de la clase Contrasena
?>
