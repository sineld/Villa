<?php

/**
 * Articulos
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Articulos extends BaseArticulos
{

	public function Busqueda_articulo($data){
		$data = '%'.$data.'%';
		$query = Doctrine_Query::create()
					//-> select('id as ID, codigo as COD')
					-> select('*')
					-> from('articulos')
					-> where('codigo LIKE ?',$data)
					-> limit(10);
		$verificador = $query->execute()->toArray();
		return $verificador;
	}
}