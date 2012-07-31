<?php
	class ObtenerpDelegate{
		
		public function getAplicants($validator){
			require_once 'phputils/mysqlConexion.php';
			
			
			$page = $validator->getOptionalVar("page");
			$total = $validator->getOptionalVar("total");
			if(!$page) $page = 0;
			if(!$total) $total = 2;
			
			$q = Doctrine_Query::create()
             ->select('a.id,a.nombre,a.apellido,a.universidad')
             ->from('Aplicantp a')->where("estado='por revisar'")
			 ->orderBy("a.creado DESC");

			$pager = new Doctrine_Pager(
			      $q,
			      $page, // Current page of request
			      $total // (Optional) Number of results per page. Default is 25
			);
			$records = $pager->execute()->toArray();
			
			if($pager->getPage()>=$page)
		   		echo json_encode($records);
			else
				echo "[]";
		}
		
		public function getAplicant($validator){
			require_once 'phputils/mysqlConexion.php';
			
			
			$page = $validator->getOptionalVar("page");
			$total = $validator->getOptionalVar("total");
			$id = $validator->getVar("id");
			if(!$page) $page = 0;
			if(!$total) $total = 2;
			
			$q = Doctrine_Query::create()
             ->select('a.nombre,a.apellido,a.fecha_nac,a.genero,a.email,a.telefono,a.universidad,a.empezar,a.carrera,a.carta,a.curriculum,a.creado,b.lunes,b.martes,b.miercoles,b.jueves,b.viernes,b.sabado,b.domingo')
             ->from('Aplicantp a, a.Dias b')
			 ->where('a.id=?',$id);

			$pager = new Doctrine_Pager(
			      $q,
			      $page, // Current page of request
			      $total // (Optional) Number of results per page. Default is 25
			);
			$records = $pager->execute()->toArray();
			
			if($pager->getPage()>=$page)
		   		echo json_encode($records);
			else
				echo "[]";
		}
		
		public function sendMail($validator){
	 		
		    $name = $validator->getVar('nombre','Nombre del aplicante');
		    $apellido = $validator->getVar('apellido','Apellido del aplicante');
			$email =$validator->getVar('email','Email del aplicante');
			$id = $validator->getVar("id");
			//$type = $validator->getVar('aceptado','Aceptado o no aceptado');
			//$title = $validator->getVar('title','Razon de contacto');
		    //$text = $validator->getVar('text','Texto a enviar');
		     
		    require_once('phputils/class.phpmailer.php');
	
			
			try {
				$error_reporting = error_reporting();
				error_reporting(0);
				$mail = new PHPMailer(true); //New instance, with exceptions enabled
				$mail->IsMail();
				$mail->AddAddress($email);
				$mail->Host = 'localhost';
				$mail->From = 'proyectos@stcsolutions.com.ve';
				$mail->FromName = 'STC Solutions Developers';
				$mail->ContentType = 'text/html';
				$mail->AddEmbeddedImage('images/logo.png', 'logoimg', 'logo.png');
				
				$mensaje = ' ';
				$aplicante = Doctrine::getTable("Aplicantp")->find($id);//FB::info($aplicante->exportTo('json'));
				
				if($_POST["aceptado"] == "Aceptar"){//
				
				/*$u = Doctrine_Query::create()->update("Aplicant a")->set('a.estado',"'preseleccionado'")->where('a.id='.$id);
				FB::info("info: ".$u->getSqlQuery());
				$filas = $u->execute();*/
				
				//*** Se cambia el estado a preseleccionado.***//
				
				
				$aplicante->set('estado', 'preseleccionado', true);
				
					/*$subj = "Bienvenido a STC";
					$mensaje .= "Ya formas parte de nuestro equipo, para formalizar tu ingreso entra en el siguiente link: \n\n";
					$mensaje .= "https://www.google.com/a/stcsolutions.com.ve/ServiceLogin?service=mail&passive=true&rm=false&continue=http%3A%2F%2Fmail.google.com%2Fa%2Fstcsolutions.com.ve%2F&bsv=llya694le36z&ltmpl=default&ltmplcache=2&from=login#inbox";
					$login = strtolower($name[0]);
					$login .= strtolower($apellido);
					$login .= "@stcsolutions.com.ve";
					$passw = "17928657";//$validator->getVar('cedula','Cedula del aplicante');
					$mensaje .= "\n\nLogin: ".$login;
					$mensaje .= "\n\nPassword: ".$passw;
					$mensaje .= "\n\nSaludos\nEquipo STC Solutions Developers";*/
					
					$subj = "Felicidades, usted ha sido preseleccionado";
					
					$mensaje = utf8_decode("&iexcl;Felicitaciones!<br /><br /><p>Hola $name $apellido queremos informarte que has sido pre-seleccionado para pertenecer a nuestra comunidad de STC Solutions.</p><p>Ya estás a solo un paso de poder trabajar con nosotros, en breve te estaremos llamando para realizar una entrevista.</p><p>Si tienes alguna duda puede escribirnos a proyectos@stcsolutions.com.ve</p><br />Gracias<br />Saludos<br />Equipo de STC Solutions Developers<br/><center><img src=\"cid:logoimg\" alt=\"[LOGO DE STC SOLUTIONS]\" /></center>");
					
				}else{
					$subj = "Su solicitud no fue aceptada";
					$mensaje = utf8_decode("<p>Lo sentimos, pero su aplicación para pertenecer a nuestra comunidad STC Solutions no ha sido aprobada. Esto puede deberse a dos razones: no tiene el perfil que solicitamos o la vacante para el trabajo ya no está disponible.</p><p>Para emprender solo hace falta dar el primer paso, por eso agradecemos que haya optado por elegirnos.</p><p>Siempre hemos pensado que nunca hay que darse por vencido, por eso le decimos que siga intentando y aplique en otra oportunidad.</p><p>Gracias<br />Saludos</p><p>Equipo de STC Solutions Developers</p><center><img src=\"cid:logoimg\" alt=\"[LOGO DE STC SOLUTIONS]\" /></center>");
					$aplicante->set('estado', 'rechazado', true);
				}
					
				$aplicante->save();	
					
					$mail->Subject  = $subj;
					
					$mail->Body = $mensaje;
					
					
					if(!$mail->Send()){
					   echo false;
					}else{
					   echo true;
					   //return 'void';
					}
					
					//echo "true";
					error_reporting($error_reporting);
	
				} catch (phpmailerException $e) {
					//FB::info('$e->errorMessage()');
					//$validator->addError("PHPMailer:".$e->errorMessage());
				} 
				
				return 'void';
		 }
		
		
	 	/*public function sendMail($validator){
	 		
		    $name = $validator->getVar('nombre','Nombre del aplicante');
		    $apellido = $validator->getVar('apellido','Apellido del aplicante');
			$email =$validator->getVar('email','Email del aplicante');
			$id = $validator->getVar("id");
			//$type = $validator->getVar('aceptado','Aceptado o no aceptado');
			//$title = $validator->getVar('title','Razon de contacto');
		    //$text = $validator->getVar('text','Texto a enviar');
		     
		    require_once('phputils/class.phpmailer.php');
	
			
			try {
				$mail = new PHPMailer(true); //New instance, with exceptions enabled
				$mail->IsMail();
				$mail->AddAddress($email);
				
				$mensaje = ' ';
				
				if($_POST["aceptado"] == "Aceptar"){//
					$subj = "Bienvenido a STC";
					$mensaje .= "Ya formas parte de nuestro equipo, para formalizar tu ingreso entra en el siguiente link: \n\n";
					$mensaje .= "https://www.google.com/a/stcsolutions.com.ve/ServiceLogin?service=mail&passive=true&rm=false&continue=http%3A%2F%2Fmail.google.com%2Fa%2Fstcsolutions.com.ve%2F&bsv=llya694le36z&ltmpl=default&ltmplcache=2&from=login#inbox";
					$login = strtolower($name[0]);
					$login .= strtolower($apellido);
					$login .= "@stcsolutions.com.ve";
					$passw = "17928657";//$validator->getVar('cedula','Cedula del aplicante');
					$mensaje .= "\n\nLogin: ".$login;
					$mensaje .= "\n\nPassword: ".$passw;
					$mensaje .= "\n\nSaludos\nEquipo STC Solutions Developers";
				}else{
					$subj = "Su solicitud no fue aceptada";
					$mensaje = "Lo sentimos, pero su aplicación para pertenecer a nuestra comunidad STC Solutions no ha sido aprobada. Esto puede deberse a dos razones: no tiene el perfil que solicitamos o la vacante para el trabajo ya no está disponible.\n\nPara emprender solo hace falta dar el primer paso, por eso agradecemos que haya optado por elegirnos.\n\nSiempre hemos pensado que nunca hay que darse por vencido, por eso le decimos que siga intentando y aplique en otra oportunidad.\n\nGracias\nSaludos\n\nEquipo de STC Solutions Developers";
				}
					
					
					
					$mail->Subject  = $subj;
					
					$mail->Body = $mensaje;
					
					if(!$mail->Send())
					{
					   echo false;
					}else{
					   echo true;
					   //return 'void';
					}
	
				} catch (phpmailerException $e) {
					//$validator->addError("PHPMailer:".$e->errorMessage());
				} 
		 }*/
	}
	
?>