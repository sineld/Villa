<?php
    class MailDelegate{
  
	  public function sendMail($validator)
	  {
	    $name = $validator->getVar('name','Id de mensaje');
	    //$to = ;
		$from = $validator->getVar('from','Email del cliente');
		//$title = $validator->getVar('title','Razon de contacto');
	    $text = $validator->getVar('text','Texto a enviar');
		
		
	     
	    if($name!=null && $from!=null && $text!=null){
	        require_once('phputils/class.phpmailer.php');

			try {
				$mail = new PHPMailer(true); //New instance, with exceptions enabled
				//$body             = file_get_contents('contents.html');
				//$mail->IsSMTP();                           // tell the class to use SMTP
				//$mail->SMTPAuth   = true;                  // enable SMTP authentication
				//$mail->Port       = 587;                    // set the SMTP server port
				//$mail->Host       = "smtp.gmail.com"; // SMTP server
				//$mail->Username   = "aalejo@gmail.com";     // SMTP server username
				//$mail->Password   = "";            // SMTP server password
				//$mail->IsSendmail();  // tell the class to use Sendmail
				//$mail->AddReplyTo("aalejo@gmail.com","gmail.com");
				$mail->IsMail();
				$mail->AddAddress("contacto@stcsolutions.com.ve");
				$subj = "[WebSTC]".$name." esta interesado(a) en ";
				$opts = "";
				
				if(isset($_POST['option'])){
					foreach($validator->getVar('option') as $value){
						
						$subj .= " ".$value;
						$opts .= " ".$value."\n";
					}
				}
				
				$mail->Subject  = $subj;
				
				$mensaje = "El Sr(a). ".$name." escribio interesado en:\n\n".$opts."\n\n Descripcion del mensaje: '".$text."'\n\n Correo: ".$from;
				
				$mail->Body = $mensaje;
				//echo $mail->Body;
				//$mail->From       = $from;
				//echo $mail->From;
				//$mail->FromName   = $name;
				//$mail->AddBCC($email);
				
				//echo $mail->FromName;
				
				//$mail->AltBody    = $text;
				//$mail->WordWrap   = 80; // set word wrap
				//$mail->MsgHTML($text);
				//$mail->IsHTML(true); // send as HTML
				//return "Su mensaje ha sido procesado y enviado. Pronto contactaremos con usted.";
				
				if(!$mail->Send())
				{
				   echo false;
				}
				else
				{
				   //echo "Letter is sent";
				   echo true;
				}

				//$_SESSION['user']->status='pending';
				//$_SESSION['user']->name = $email;
				

			} catch (phpmailerException $e) {
				//$validator->addError("PHPMailer:".$e->errorMessage());
			} 
	    }else{
	        
	        $mensaje = 'No se pudo enviar el mensaje, error en los datos';
	      
	        return $GLOBALS["baseURL"].'contactanos'; 
	  
	    }
	  }
  }
?>