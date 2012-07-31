<?php


class PHPSecureURL{

	var $var_name="hash"; 			// you hide all your URL in thid parmeter "aku" is an example you can redefine
	var $decode_url;				// url unhide
	function code_param_url(){
		$this->decode();
	}
//*******************************************
	function encode($url){ 			// methode to encode $url = par1=toto&par2=tioti& ...
		$pos_debut=strpos($url,"?"); if(!$pos_debut){$sep="&";}
		$pos_fin=strpos($url," ");
		if($pos_fin){
			$pos_long=$pos_fin-$pos_debut-1; $fin=substr($url,$pos_fin); 
		}else{
			$pos_long=strlen($url)-$pos_debut-1;
		}
		$debut=substr($url,0,$pos_debut+1);
		$param=substr($url,$pos_debut+1,$pos_long);
		$code = base64_encode($param);
		return $debut.$sep.$this->var_name."=".$code.$fin;
	} // methode return ?aku=dfgdfgdgdfgdgdfhgjdfhjghj all parameter are hide in one
	
	// methode returm something like ?aku=dfgdfgdgdfgdgdfhgjdfhjghj all parameters un one
	// $url can be 				"http://www.moserveur.com/monfichier.php?var1=dfdf& var2=fdsgdf&var3=dfg "
	// or 						"http://www.moserveur.com/monfichier.php?var1=dfdf& var2=fdsgdf&var3=dfg target=_self"
	// or only					"?var1=dfdf& var2=fdsgdf&var3=dfg"
//*********************************************

//**************** decode and change global variables
	function decode (){ // methode to unhide
		
		if($_REQUEST[$this->var_name]){ 
			$this->decode_url=base64_decode($_REQUEST[$this->var_name]); 
			parse_str($this->decode_url, $tbl); 
			
			foreach($tbl as $k=>$v){
				$_REQUEST[$k]=$v;
				global $$k; 
				$$k=$v;
			}
		} 
	}
//*********************************
}


?>
