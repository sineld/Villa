<?php

class httpRequestValidator
{
	var $errors;

	function httpRequestValidator()
	{
		if(!isset($GLOBALS["debugMode"])) $GLOBALS["debugMode"] = false;
		$this->errors = ErrorManager::getInstance();
	}

	function getVar($name,$validation='',$tipo='none')
	{
		if(isset($_REQUEST[$name]))
		{
			if(!empty($_REQUEST[$name]))
			{
				switch($tipo)
				{
					case "none":
						return $_REQUEST[$name];
					break;
					case "date":
						if($this->valDate($_REQUEST[$name],"Fecha $name invalida."))
							return $_REQUEST[$name];
					break;
					case "number":
					if($this->valNumber($_REQUEST[$name],"Numero $name invalido."))
							return $_REQUEST[$name];
					break;
					case "email":
					if($this->valEmail($_REQUEST[$name],"Email $name invalido."))
							return $_REQUEST[$name];
					break;
				}

			}
			else
			{
				if($validation!='') $this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La variable '.$validation.' no se ha definido.');
				else $this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La variable '.$name.' no se ha definido.');
			}
		}
			else
			{
				if($validation!='') $this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La variable '.$validation.' no se ha definido.');
				else $this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La variable '.$name.' no se ha definido.');
			}

		return '';
	}


	function getOptionalVar($name,$validation='',$tipo='none')
	{
		if(isset($_REQUEST[$name]))
		{
			if($_REQUEST[$name]!='')
			{
				switch($tipo)
				{
					case "none":
						return ($_REQUEST[$name]);
					break;
					case "date":
						if($this->valDate($_REQUEST[$name],"Fecha $name invalida"))
							return ($_REQUEST[$name]);
					break;
					case "number":
					if($this->valNumber($_REQUEST[$name],"Numero $name invalido"))
							return ($_REQUEST[$name]);
					break;
					case "email":
					if($this->valEmail($_REQUEST[$name],"Email $name invalido"))
							return ($_REQUEST[$name]);
					break;
				}

			}
		}

		return '';
	}

	function getRadio($name,$value='',$tipo='none')
	{
		if(isset($_REQUEST[$name]) && $value!='')
		{
			if($_REQUEST[$name]!='')
			{
				foreach($_REQUEST[$name] as $opt)
				{
					if($opt==$value)
						return true;
				}

				//echo "<pre>";print_r($_REQUEST[$name]);echo "</pre>";

			}
			else $this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La opcion '.$validation.' no tiene valor <br />');
		}
		else if($value!='')
		{
			return false;
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_USER_ERROR,'Debes especificar que opcion quieres conocer de la variable '.$_REQUEST[$name].'<br />');
		}

		return false;
	}

	function getCheckbox($name)
	{
		if(isset($_REQUEST[$name]))
		{
			return true;
		}

		return false;
	}

	function getSessionVar($name,$validation='',$tipo='none')
	{
		if(isset($_SESSION[$name]))
		{
			if($_SESSION[$name]!='')
			{
				switch($tipo)
				{
					case "none":
						return $_SESSION[$name];
					break;
					case "date":
						if($this->valDate($_SESSION[$name],"Fecha $name invalida"))
							return $_SESSION[$name];
					break;
					case "number":
					if($this->valNumber($_SESSION[$name],"Numero $name invalido"))
							return $_SESSION[$name];
					break;
					case "email":
					if($this->valEmail($_SESSION[$name],"Email $name invalido"))
							return $_SESSION[$name];
					break;
				}

			}
			else $this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La variable '.$validation.' no se ha definido');
		}
		else
		{
			$this->errors->addError(ErrorManager::CANIS_USER_ERROR,'La variable '.$validation.' no se ha definido');
		}

		return '';
	}


    // Validate text only
    function valTextOnly($theinput,$description = ''){
        $result = ereg ("^[A-Za-z0-9\ ]+$", $theinput );
        if ($result){
            return true;
        }else{
            $this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
            return false;
        }
    }

    // Validate text only, no spaces allowed
    function valTextOnlyNoSpaces($theinput,$description = ''){
        $result = ereg ("^[A-Za-z0-9]+$", $theinput );
        if ($result){
            return true;
        }else{
            $this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
            return false;
        }
    }

    // Validate email address
    function valEmail($themail,$description = ''){
        $result = preg_match ("/^[^@ ]+@[^@ ]+\.[^@ \.]+$/", $themail );
        if ($result){
            return true;
        }else{
            $this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
            return false;
        }

    }

    // Validate numbers only
    function valNumber($theinput,$description = ''){
        if (is_numeric($theinput)) {
            return true; // The value is numeric, return true
        }else{
            $this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description); // Value not numeric! Add error description to list of errors
            return false; // Return false
        }
    }

    // Validate date
    function valDate($thedate,$description = ''){

        if (strtotime($thedate) === -1 || $thedate == '') {
            $this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
            return false;
        }else{
            return true;
        }
    }

	/*
	 |----------------------------------------------------
	 | public function phone($val)
	 |----------------------------------------------------
	 |
	 | Parses $val and checks if it is a valid
	 | phone number.
	 |
	 | returns bool true| false
	 |
	 |----------------------------------------------------
	*/
	public function valphone($val, $description='')
	{
		$ereg = "/^(?:\([2-9]\d{2}\)\ ?|[2-9]\d{2}[- \.]?)[2-9]\d{2}[- \.]?\d{4}[- \.]?(?:x|ext)?\.?\ ?\d{0,5}$/";
		if(!preg_match($ereg,$val))
		{
			$this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
			return false;
		}
		return true;
	}

	/*
	 |----------------------------------------------------
	 | public function url($val)
	 |----------------------------------------------------
	 |
	 | Parses $val and checks if it is a valid url
	 |
	 | returns bool true| false
	 |
	 |----------------------------------------------------
	*/
	public function valUrl($val,$description='')
	{
		$ereg = "((https?|ftp|gopher|telnet|file|notes|ms-help):((//)|(\\\\))+[\w\d:#@%/;$()~_?\+-=\\\.&]*)";
		if(!eregi($ereg,$val))
		{
			$this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
			return false;
		}
		return true;
	}

	/*
	 |----------------------------------------------------
	 | public function db_prep($val)
	 |----------------------------------------------------
	 |
	 | Parses $val and prepares it for database input
	 |
	 | returns database ready $val
	 |
	 |----------------------------------------------------
	*/
	public function db_prep($val)
	{
		if(get_magic_quotes_gpc()) $val = stripslashes($val);
		return mysql_real_escape_string($val);
	}

	public function getErrorsCount()
	{
		return $this->errors->totalErrors(array(ErrorManager::CANIS_USER_ERROR));
	}

	public function getTotalErrors()
	{
		return $this->errors->totalErrors(array(ErrorManager::CANIS_ERROR,ErrorManager::CANIS_USER_ERROR,ErrorManager::CANIS_FATAL));
	}

	public function addError($description)
	{
		$this->errors->addError(ErrorManager::CANIS_USER_ERROR,$description);
	}

}

?>