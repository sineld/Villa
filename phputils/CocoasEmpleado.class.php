<?php
class CocoasEmpleado
{
	var $id;
	var $id_user;
	var $nombre;
	var $rif;
	var $id_tipo;
	var $inactivo;
	
	public function CocoasEmpleado($id=null, $id_user=null, $nombre=null, $rif=null, $id_tipo, $inactivo=null)
	{
		$this->id = $id;
		$this->id_user = $id_user;
		$this->nombre = $nombre;
		$this->rif  = $rif;
		$this->id_tipo = $id_tipo;
		$this->inactivo = $inactivo;

	}	
}
?>