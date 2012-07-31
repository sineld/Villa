<?php
class CocoasUser
{
	var $id;
	var $name;
	var $status;
	var $locationId;
	var $roleId;
	var $roleName;
	var $lastRefreshTime;
	var $lasRefreshView;

	public function CocoasUser($name="anonymus",$locationId="none",$roleName='')
	{
		if ($roleName == '')
		{
			if(!empty($GLOBALS["DEFAULT_ROLE"])) $roleName = $GLOBALS["DEFAULT_ROLE"];
			else $roleName='invalid';
		}

		$this->roleName   = $roleName;
		$this->locationId = $locationId;
		$this->name       = $name;
		$this->status     = 'invalid';
	}

	public function isValid()
	{
		return !($this->status=='invalid');
	}

}


?>