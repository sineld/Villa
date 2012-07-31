<?php

require_once('phputils/caniserror.class.php');
require_once('phputils/class.phpmailer.php');
require_once('phputils/canislogging.class.php');

class ErrorManager
{
	//Estos son todos los posibles tipos de errores en canis
	const CANIS_ERROR = 'error';//Un error que debe interrumpir el flijo de la aplicacion
	const CANIS_USER_ERROR = 'userError';//Solo errores generados por el usuario final (Ejm: validaciones.)
	const CANIS_WARNING = 'warning';//No interrumpe la ejecucion, pero es importante
	const CANIS_INFO = 'information';//Informaciones que solo son mostradas en DEBUG_MODE
	const CANIS_FATAL = 'fatalerror';//Un error fatal, interrumpe el flujo y es enviado por correo

	private static $errors = array();
	private $logger;
	private static $instancia;

   private function __construct()
   {
   		//$this->validator = new httpRequestValidator('CatalogManager');
   		$this->logger = new CanisLogging();
   }

	//Solo puede haber una instancia manejadora de errores en la aplicacion.
   public static function getInstance()
   {
       if (self::$instancia == NULL) {
          self::$instancia = new ErrorManager();
       }
       return self::$instancia;
   }

    // Manually add something to the list of errors
    public function addError($type,$description)
    {
    	if(!isset($this->errors[$type])) $this->errors[$type] = array();

    	$err = new CanisError();
    	$err->errorMsg = $description;
    	$err->type = $type;
    	$err->dateTime = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

		//incluyo el error a la lista de errores de su tipo
    	array_push($this->errors[$type],$err);

		if($GLOBALS["logErrors"])
		{
			if(!file_exists('admin/logs/')) mkdir("admin/logs/",777);
	    	$this->logger->logToFile("admin/logs/$type.log",$description);
		}

		//Si es un error fatal, entonces lo envio por correo
    	switch($type)
    	{
    		case ErrorManager::CANIS_FATAL:
				if($GLOBALS["mailErrors"]) $this->sendErrorMail($err);
				die($err->errorMsg);
    		break;
    		case ErrorManager::CANIS_INFO:
				if($GLOBALS['debugMode']==true) echo $err->errorMsg;
    		break;
    	}
    }

	//Return a String with all the errors of the specifed types.
	public function getErrorsString($types = array())
	{
		$errors_string = '';

		foreach($types as $type)
		{
			if(!isset($this->errors[$type])) $this->errors[$type] = array();
			foreach($this->errors[$type] as $error)
			{
				$errors_string .= $error->errorMsg." <br />";
			}
		}

		return $errors_string;
	}

	//Return the number of errores of the specifed type
	public function totalErrors($types = array())
	{
		$total = 0;
		foreach($types as $type)
		{
			$total += (isset($this->errors[$type])) ? count($this->errors[$type]) : 0;
		}

		return $total;
	}

	//Sends a error by email.
	private function sendErrorMail($err)
	{
		try {
			$mail = new PHPMailer(true); //New instance, with exceptions enabled
			//$body             = file_get_contents('contents.html');
			$body = $err->errorMsg;
			$body             = preg_replace('/\\\\/','', $body); //Strip backslashes
			$mail->IsSMTP();                           // tell the class to use SMTP
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = 25;                    // set the SMTP server port
			$mail->Host       = $GLOBALS["error_mailHost"]; // SMTP server
			$mail->Username   = $GLOBALS["error_mailUserName"];     // SMTP server username
			$mail->Password   = $GLOBALS["error_mailPasswors"];            // SMTP server password
			$mail->IsSendmail();  // tell the class to use Sendmail
			//$mail->AddReplyTo("user@domain.com","domain.com");
			//$mail->From       = "user@domain.com";
			//$mail->FromName   = "user.com";
			$mail->AddBCC($GLOBALS["error_mailAccount"]);
			$mail->Subject  = "Faltal error on ".$GLOBALS["error_mailHost"];
			$mail->AltBody    = $err->errorMsg;
			$mail->WordWrap   = 80; // set word wrap
			$mail->MsgHTML($body);
			//$mail->IsHTML(true); // send as HTML
			$mail->Send();
		} catch (phpmailerException $e) {
			$this->addError(ErrorManager::CANIS_INFO,"PHPMailer:".$e->errorMessage());
		}
	}

}

?>