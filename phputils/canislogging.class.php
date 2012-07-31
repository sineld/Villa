<?php

//CREATE TABLE `log`
//(
//`date` TIMESTAMP NOT NULL,
//`type` TINYINT NOT NULL,
//`msg` VARCHAR(255) NOT NULL
//);

class CanisLogging {

    function CanisLogging() {
    }

	function logToFile($filename, $msg)
	{
		// open file
		$fd = fopen($filename, "a");

		// append date/time to message
		$str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg;

		// write string
		fwrite($fd, $str . "\n");

		// close file
		fclose($fd);
	}

	function logToDB($msg, $type)
	{

		// formulate and execute query
		$query = "INSERT INTO log (date, type, msg) VALUES(NOW(),
	'$type', '$msg')";
		mysql_query($query);
	}

}
?>