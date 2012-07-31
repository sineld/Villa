<?php

class Sharer
{
    var $page;
	var $msg;
	var $destinations;
	var $errors;
	
	var $description;
	var $title;
	var $image;

    function Sharer($url,$msg) 
    {
        $this->page       = $url;
		$this->msg       = $msg;
		$this->destinations = array();
    }
	
	function addDestinations($emails)
	{
		array_push($this->destinations,$emails);
	}
	
	function parseDestinations($emailString)
	{
		$emails = explode(",", trim($emailString,' '));
		
		foreach($emails as $email)
		{
			$array_push($this->destinations,$email);
		}
	}
	
	function parsePage()
	{
		$tags = get_meta_tags($this->page);
		$this->description = $tags['page-description'];
		$this->image = $tags['page-image'];
		$this->title = $tags['page-title'];
	}
	
	function sendByMail()
	{
		require_once "sendmail.class.php";

		$sendmail = new sendMail();
		$sendmail->set('html', true);
		
		foreach($this->destinations as $email)
		{
			$mensaje['to'] = $email;
			$mensaje['subject'] = $title;
			$mensaje['from'] = 'noreply@activistasenred.com';
			
			$sendmail->getParams($mensaje);
			$sendmail->setBody(urldecode($content));
			$sendmail->setHeaders();
			if(!$sendmail->send())
				echo "Error enviando el email.<br />";
			else
				echo "Invitación enviada con exito.";
		}
	}

}
?>