<?php

$xmlDoc = new DOMDocument();
$xmlDoc->load("binding.xml");

$views = $xmlDoc->getElementsByTagName("view"); 
foreach ($views AS $view)
{
	$view->setAttribute('urls','gg');
}

$xmlDoc->save("binding3.xml");

?>