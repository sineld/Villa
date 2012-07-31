<?php
class CocoasVendedor
{
	var $id;
	var $nombre;
	var $id_usuario;
	var $id_tipo;
	var $inactivo;
	
	public function CocoasVendedor($id=null, $id_usuario=null, $nombre=null, $id_tipo=null, $inactivo=null)
	{
		$this->id = $id;
		$this->id_usuario = $id_usuario;
		$this->nombre = $nombre;
		$this->id_tipo = $id_tipo;
		$this->inactivo = $inactivo;
	}	
}
?>