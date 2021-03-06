<?php

/**
 * Precios
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Precios extends BasePrecios
{
	public function getPrecios($id_art=NULL,$id_tipo_cliente=NULL){
		$count = 0;
		$q = Doctrine_Query::create() 
			-> select('*') 
			-> from('precios');
		if($id_art!=NULL){
			if($count == 0){
				$q->where('id_art = ?',$id_art);
				$count++;	
			}else{
				$q->andwhere('id_art = ?',$id_art);
			}
			
		}else{
			return 'ERROR: Debe especificar un id_art';
		}
		if($id_tipo_cliente!=NULL){
			if($count == 0){
				$q->where('id_tipo_cliente = ?',$id_tipo_cliente);
				$count++;	
			}else{
				$q->andwhere('id_tipo_cliente = ?',$id_tipo_cliente);
			}
		}else{
			return 'ERROR: Debe especificar un id_tipo_cliente';
		}
		$salida = $q->execute()->toArray();
		return json_encode($salida);
	}
}