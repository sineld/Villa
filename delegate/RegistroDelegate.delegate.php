<?php
require_once('public_action/tipo_cliente_handler.php');
require_once('public_action/roles_handler.php');
class RegistroDelegate
{

	public function RegistroDelegate()
	{
		return "hola mundo";
	}

	function getRoles($validator)
	{

		$q = Doctrine_Query::create()->from("role");
		$records = $q->execute();

		return $records;
	}

	function getLocations($validator)
	{

		$q = Doctrine_Query::create()->from("location");
		$records = $q->execute();

		return $records;
	}

	function newUser($validator)
	{
		/*if(isset($_POST["recaptcha_challenge_field"]) && isset($_POST["recaptcha_response_field"]))
		{
		require_once('phputils/recaptchalib.php');
		$privatekey = "6LeiIQgAAAAAAMA8rWONOb2rAlfIHlLB99N6mzlC ";
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$validator->addError('Debes colocar las palabras que ves en la imagen');
			}

		}
		else
		{
			$validator->addError('captcha invalido');
		}
		*/
		$email = $validator->getVar("email");
		$q = Doctrine_Query::create()
		    ->from('user u')
		    ->where("u.email='$email'");
		$rows = $q->execute();

		if(count($rows)==0)
		{
			$random = rand(0,999999999);
			$entity = new user();
			$entity->email=$email;
			$entity->password=$validator->getVar("password");
			$entity->validation_code=$random;
			$entity->role_id=$validator->getVar("tipousuarios");
			$entity->save();
			if ($entity->Role->name == 'cliente'){
				$cliente = new Cliente();
				$cliente->nombre = $validator->getVar("nombre_empresa");
				$cliente->rif = $validator->getVar("rif");
				$cliente->direccion = $validator->getVar("direccion");
				$cliente->telefono = $validator->getVar("telefono");
				$cliente->id_usuario = $entity->id;
				$cliente->id_tipo = $validator->getVar("tipoclientes");
				$cliente->save();
			}else if ($entity->Role->name == 'vendedor') {
				$vendedor = new Vendedor();
				$vendedor->nombre = $validator->getVar("nombre_vendedor");
				$vendedor->id_usuario = $entity->id;
				$vendedor->id_tipo = $validator->getVar("tipoclientes");
				$vendedor->save();
			}
			
		}
		else
			$validator->addError('The user "'.$email.'" already exists.');

		$idUsuario=mysql_insert_id();

		/*if($validator->getTotalErrors()==0)
		{
			require_once('phputils/class.phpmailer.php');

			try {
				$mail = new PHPMailer(true); //New instance, with exceptions enabled
				//$body             = file_get_contents('contents.html');
				$body = 'Hola '.$email.', Bienvenido al portal de Aprende de STC Solutions Developers, tu c&oacute;digo de validaci&oacute;n es: '.$random.'. Antes de entrar debes entrar a este enlace para validarte: http://proyectos.stcsolutions.com.ve/crud.php?public_action=validate&a='.$random.'&b='.$entity->id;
				$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
				//$mail->IsSMTP();                           // tell the class to use SMTP
				//$mail->SMTPAuth   = true;                  // enable SMTP authentication
				//$mail->Port       = 587;                    // set the SMTP server port
				//$mail->Host       = "smtp.gmail.com"; // SMTP server
				//$mail->Username   = "aalejo@gmail.com";     // SMTP server username
				//$mail->Password   = "";            // SMTP 	server password
				//$mail->IsSendmail();  // tell the class to use Sendmail
				//$mail->AddReplyTo("aalejo@gmail.com","gmail.com");
				$mail->From       = "proyectos@stcsolutions.com.ve";
				$mail->FromName   = "STC Solutions Developers";
				$mail->AddBCC($email);
				$mail->Subject  = "Registro en Aprende";
				//$mail->AltBody    = 'Hi '.$email.', welcome to ivoted.com!, your validation code is '.$random.'. Before you can log into the system you must copy the following link into you browser: http://www.ivoted.com/crud.php?public_action=validate&a='.$random.'&b='.$entity->id;
				$mail->WordWrap   = 80; // set word wrap
				$mail->MsgHTML($body);
				$mail->IsHTML(true); // send as HTML
				$mail->Send();

				$_SESSION['user']->status='pending';
				$_SESSION['user']->name = $email;

			} catch (phpmailerException $e) {
				//$validator->addError("PHPMailer:".$e->errorMessage());
			}

		}*/ 

		return 'controller.php?view=validate';
	}
	
	function getTipoClientes($validator)
	{
		$handler = new tipo_cliente_handler();
		$id = $validator->getOptionalVar('id');
		$nombre = $validator->getOptionalVar('nombre');
		$inactivo = $validator->getOptionalVar('inactivo');
		$paginaActual = $validator->getOptionalVar('pagina');
		if ($inactivo == null || $inactivo == 'null'){
			$inactivo = -1;			
		}
		echo $handler->getTipoClientes($id, $nombre, $inactivo, $paginaActual);
	}
	
	function getTipoUsuarios($validator){
		$handler = new roles_handler();
		$id = $validator->getOptionalVar('id');
		$nombre = $validator->getOptionalVar('nombre');
		echo $handler->getRoles($id,$nombre);
	}
	
	function getUsers($validator){
		$users = new User;
		$logs = new Log;
		$id = $validator->getOptionalVar('id');
		$email = $validator->getOptionalVar('email');
		$location_id = $validator->getOptionalVar('location_id');
		$role_id = $validator->getOptionalVar('role_id');
		$status = $validator->getOptionalVar('status');
		$validation_code = $validator->getOptionalVar('validation_code');
		$paginaActual = $validator->getOptionalVar('paginaActual');
		$porPagina = $validator->getOptionalVar('porPagina');
		$userslist = $users->getUsers($id,$email,$location_id,$role_id,$status,$validation_code,$paginaActual,$porPagina);
		foreach ($userslist as $keys => $values) {
			$userslist[$keys]['logCount'] = $logs->Count(null,$userslist[$keys]['id']);
			$maxFecha = $logs->MaxFecha(null,$userslist[$keys]['id']);
			if ($maxFecha[0]['MAX'] == null || $maxFecha[0]['MAX'] == 'null'){
				$maxFecha[0]['MAX'] = '';
			}
			$userslist[$keys]['maxFecha'] = $maxFecha[0]['MAX'];
		}
		echo json_encode($userslist);		
	}

	public function getPaginationUser($validator) {
		$handler = new pagination_handler();
		$role_id = $validator->getOptionalVar('role_id');
		$location_id = $validator->getOptionalVar('location_id');
		$status = $validator->getOptionalVar('status');
		$pp = $validator->getOptionalVar('porPagina');
		$inac = $validator->getOptionalVar('inactivo');
		$array = array();
		if($role_id!=null && $role_id != 'null'){
			$array['role_id'] = $role_id;
		}
		if($location_id!=null && $location_id != 'null'){
			$array['location_id'] = $location_id;
		}
		if($status!=null && $status != 'null'){
			$array['status'] = $status;
		}
		if ($pp!= null && $pp != 'null'){
			$porPagina = $pp;
		}else {
			$porPagina = null;
		}
		if ($inac!= null && $inac != 'null'){
			$inactivo = $inac;
		}else {
			$inactivo = null;
		}
		if (($role_id==null || $role_id == 'null') && ($location_id==null || $location_id == 'null') && ($status==null || $status == 'null')){ $array = null;}
		echo $handler->getPagination('User',$array,$porPagina,$inactivo);
	}

}
?>