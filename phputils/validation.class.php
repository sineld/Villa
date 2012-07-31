<?php
class Validator {

    var $errors; // A variable to store a list of error messages

    // Validate something's been entered
    // NOTE: Only this method does nothing to prevent SQL injection
    // use with addslashes() command
    function valEmpty($theinput,$description = ''){
        if (trim($theinput) && $theinput!="") {
            return true;
        }else{
            $this->errors[] = $description;
            return false;
        }
    }
    
    // Validate text only
    function valTextOnly($theinput,$description = ''){
        $result = ereg ("^[A-Za-z0-9\ ]+$", $theinput );
        if ($result){
            return true;
        }else{
            $this->errors[] = $description;
            return false;
        }
    }

    // Validate text only, no spaces allowed
    function valTextOnlyNoSpaces($theinput,$description = ''){
        $result = ereg ("^[A-Za-z0-9]+$", $theinput );
        if ($result){
            return true;
        }else{
            $this->errors[] = $description;
            return false;
        }
    }
        
    // Validate email address
    function valEmail($themail,$description = ''){
        $result = ereg ("^[^@ ]+@[^@ ]+\.[^@ \.]+$", $themail );
        if ($result){
            return true;
        }else{
            $this->errors[] = $description;
            return false;
        }
            
    }
    
    // Validate numbers only
    function valNumber($theinput,$description = ''){
        if (is_numeric($theinput)) {
            return true; // The value is numeric, return true
        }else{
            $this->errors[] = $description; // Value not numeric! Add error description to list of errors
            return false; // Return false
        }
    }
    
    // Validate date
    function valDate($thedate,$description = ''){

        if (strtotime($thedate) === -1 || $thedate == '') {
            $this->errors[] = $description;
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
			$this->errors[] = $description;
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
			$this->errors[] = $description;
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
	
    // Check whether any errors have been found (i.e. validation has returned false)
    // since the object was created
    function foundErrors() {
        if (count($this->errors) > 0){
            return true;
        }else{
            return false;
        }
    }

    // Return a string containing a list of errors found,
    // Seperated by a given deliminator
    function listErrors($delim = ' '){
        return implode($delim,$this->errors);
    }
    
    // Manually add something to the list of errors
    function addError($description){
        $this->errors[] = $description;
    }    
        
}
?>