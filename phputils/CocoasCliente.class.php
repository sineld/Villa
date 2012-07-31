<?php
class CocoasCliente
{
	var $id;
	var $id_usuario;
	var $nombre;
	var $rif;
	var $direccion;
	var $id_tipo;
	var $telefono;
	var $inactivo;
	var $pedido;
	
	public function CocoasCliente($id=null, $id_usuario=null, $nombre=null, $rif=null, $direccion=null, $id_tipo=null, $telefono=null, $inactivo=null, $pedido=null)
	{
		$this->id = $id;
		$this->id_usuario = $id_usuario;
		$this->nombre = $nombre;
		$this->rif  = $rif;
		$this->direccion = $direccion;
		$this->id_tipo = $id_tipo;
		$this->telefono = $telefono;
		$this->inactivo = $inactivo;
		$this->pedido = $pedido;
	}	
}
?>