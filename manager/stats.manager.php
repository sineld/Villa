<?php

//////////////////////////////////////////////////
//Esta clase se encarga de monitoriear el comportamiento
//del usuario.
//////////////////////////////////////////////////

class StatsManager
{

	var $granularity = "";
	var $viewDirectory = "view/";

	public function StatsManager($bindings)
	{
		$this->bindings = $bindings;
	}
	
	public function registerView($logicPath,$user)
	{
		
		return $view;
	}
	
	private function getMonitorFromView($page,$result)
	{
	
		if(isset($result["views"]["view"][0]))
		{
			for($i = 0;$i<count($result["views"]["view"]);$i++)
			{
				if($result["views"]["view"][$i]['attr']['name']==$page)
					return $result["views"]["view"][$i]['attr']['monitor'];
			}
		}
		else if($result["views"]["view"]['attr']['name'])
		{
			if($result["views"]["view"]['attr']['name']==$page)
				return $result["views"]["view"]['attr']['monitor'];
		}
		else
		{
			die("error leyendo binding.xml");
		}
	}
}
?>