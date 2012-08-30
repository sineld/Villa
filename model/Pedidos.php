<?php
require_once 'phputils/mysqlConexion.php';
/**
 * Pedidos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Pedidos extends BasePedidos
{
	//Funcion para introducir un item nuevo al pedido.
	public function newItemPedido($data){
		if (isset($data['id_pedido'])) {
			foreach($data as $valor => $_dato){
				if (!is_numeric($_dato)){
					return 'error: un campo del array no es numerico. '.$valor.' = '.$_dato;
				}
			}
			$newItem = new ArticulosPedido();
			$newItem->id_articulo = (int)$data['id_articulo'];
			$newItem->cantidad = (int)$data['cantidad'];
			$newItem->id_pedido = (int)$data['id_pedido'];
			$newItem->inactivo = (int)$data['inactivo'];
		} else
		{
			 return 'error: no hay pedido definido.';
		}
		
		try {
			$newItem->save();
	   		return $newItem->id;			
		} catch (Exception $e){
			return 'Message: ' .$e->getMessage();
		}
	}
	
	
	//Funcion para procesar el ingreso de items a un pedido.
	
	public function newPedido($data){
		foreach($data as $verificar){
			if(!is_numeric($verificar)) return 'error, dato no numerico.';
		}
		$id_cliente = $data['id_cliente']; //ID del cliente que realiza el pedido.
		$newPedido = new Pedidos;
		$mysqldate = date('Y-m-d');
		try {
			$newPedido->fecha_creacion = $mysqldate;
			$newPedido->fecha_ult_mod = $mysqldate;
		//Si el tipo de pago es superior o igual a 0 dias, lo procesa, sino usa el default 0.
			if ($tipo_pago >= 0) {
				$newPedido->tipo_pago = (int)$data['tipo_pago'];	
			} else {
				$newPedido->tipo_pago = 0;
			}
		//Si el id de cliente no existe, retorna error, sino lo procesa.
			if (($id_cliente == null) || ($id_cliente == 'undefined')) {
				return 'cliente invalido';
			}else{
				$newPedido->id_cliente = (int)$id_cliente;	
			}
		//Si la forma de pago no existe, usa el default, 0 = efectivo, 1 = cheque, 2 = transferencia. 
			if ($forma_pago >= 0){
				$newPedido->forma_pago = (int)$data['forma_pago'];
			}else{
				$newPedido->forma_pago = 0;
			}
			$newPedido->inactivo = (int)$data['inactivo'];
			$newPedido->estado = 0;
		
			$newPedido->save();
			return $newPedido->id;
		} catch (Exception $e){
			return 'Message: ' .$e->getMessage();
		}
		
	}
	public function infoCliente($data){
		$qCliente = Doctrine_Query::create()
				-> select('*')
				-> from('cliente')
				-> where('id = ?',(int)$data);
		$query = $qCliente->execute()->toArray();
		return $query[0];
	}

	public function modPedidos($data){
		$pedido = Doctrine::getTable('pedidos')->findOnebyId($data['id_pedido']);
		$mysqldate = date('Y-m-d');
		switch($data['campo'])
		{
			case 'estado':
				$pedido->estado = $data['valor'];
				$pedido->fecha_ult_mod = $mysqldate; 
				break;
			case 'tipo_pago':
				$pedido->tipo_pago = $data['valor'];
				$pedido->fecha_ult_mod = $mysqldate; 
				break;
			case 'forma_pago':
				$pedido->forma_pago = $data['valor'];
				$pedido->fecha_ult_mod = $mysqldate; 
				break;
			case 'id_cliente':
				$pedido->id_cliente = $data['valor'];
				$pedido->fecha_ult_mod = $mysqldate; 
				break;
			default:
				$pedido->fecha_ult_mod = $mysqldate; 
				break;
		}
		try {
			$pedido->save();
			echo 'true';
			return 'void';
		} catch (Exception $e){
			return 'error';
		}
	}
	
}