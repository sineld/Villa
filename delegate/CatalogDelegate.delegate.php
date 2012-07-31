<?php
class CatalogDelegate
{

	 public function catalog() 
	  {
	
	  } // Fin de Catalgo 

     public function insertCatalogo($validator)
	 {
	   // Función encargada de insertar nuevos catalogos
	   
	   $name = $validator->getVar('catalogoNombre', 'name del catalog');
	   
	   $catalog = new catalog();
	   $catalog->name = $name;
	   $catalog->save();
	   
       //$validator->exect("insert into catalog(id,name) values('','".$name."')" );
	   		
		return 'controller.php?view=catalog';
	 }// Fin de la función insertcatalog

     public function eliminarCatalogo($validator)
	 {
        // Función encargada de eliminar el catalog seleccionado
		
	      $id = $validator->getVar('id', 'ID del catalog');
		 
          $validator->exect("delete from catalog  where id = '".$id."'" );
	   	  
	     return 'controller.php?view=catalog';
	}// Fin de la función eliminar catalog 

    public function getCatalogo($validator)
	{
	   // Función encargada de recolectar todos los catalogs 
	    
		$result = $validator->exect("select id,name from catalog");
		$array = array();
		while($object = mysql_fetch_object($result))
		{
			array_push($array,$object);
			
		}
		
		return $array;
	} // Fin de la función getCatalogo
	
	public function updateCatalogo($validator)
	{		
	   // Función encargada de modicar el catalog seleccionado
	    	  
		$name = $validator->getVar('name','name del catalog');
		//echo 'name'.$name.'/'; 
		$id = $validator->getVar('id','ID del catalog'); 
		
		$result = $validator->exect("update catalog set name='".$name."' where id='".$id."'");
		
		return 'controller.php?view=catalog';
	} // Fin de la función updateCatalogo
	
	public function getRecursiva($validator)
	{
	  //Función encargada de regresar un arreglo con datos que  vinculan los registro de catalog con catalago_valor
	
	$result = $validator->exect("select DISTINCT A.id,A.valor as nombreValor,C.name as nombreCatalogo from catalog_valor A, catalog_valor B, catalog C where (A.idDepende = B.id  and A.idCatalogo = C.id) or (A.idCatalogo = C.id)");
		$array = array();
		while($object = mysql_fetch_object($result))
		{
			array_push($array,$object);
			
		}
		
      	return $array; 	    
	} // Fin función getRecursiva
	
	public function insertCatalogoValor($validator)
	{
       // Función encargada de insertar un registro en la tabla catalog_valor

	  $valor = $validator->getVar('catalogoNombre', 'name del catalog'); 
	  $id_cat = $validator->getVar('id_cat', 'catalog');
	  $id_dep = $validator->getVar('id_catalog', 'Depende');
      $blanco = 'blanco';
	     if (($id_cat == $blanco) && ($id_dep == $blanco))
	     {
 	      $validator->addError('Ha dejados campos campos obligatorios en blanco');
		  return 'controller.php?view=catalog-valor';  
	     }
	  else
	     if (($id_cat != $blanco) && ($id_dep != $blanco))
	     {
	      $validator->addError('Tiene que seleccionar un catalog o un valor del cual dependa pero no ambos'); 
	      return 'controller.php?view=catalog-valor';
		 }	  
	  else
	     if (($id_cat != $blanco)&& ($id_dep = $blanco))
	  {
	    $valor_depende = NULL; 
        $validator->exect("insert into catalog_valor(id,valor,idDepende,idCatalogo) values('','".$valor."','".$valor_depende."','".$id_cat."')");	   		
    	return 'controller.php?view=catalog-valor';
	  }
	  else 
	      if (($id_dep != $blanco)&& ($id_cat = $blanco)) 
		  {
		    $result = $validator->exect("select idDepende, IdCatalogo from catalog_valor where  id = '".$id_dep."'");
	        $row = mysql_fetch_row($result);
            $validator->exect("insert into catalog_valor(id,valor,idDepende,idCatalogo) values('','".$valor."','".$id_dep."','".$row[1]."')");	   		
    	     return 'controller.php?view=catalog-valor';
		  } 
	}// Fin de la función insertCatalogoValor
	
	public function insertCatalogoValor2($validator)
	{
       // Función encargada de insertar un registro en la tabla catalog_valor

	  $valor = $validator->getVar('catalogoNombre', 'name del catalog'); 
	  $id = $validator->getVar('id_catalog', 'id del catalog');
     $id_depende =  $validator->getVar('id_depende', 'id del catalog'); 
	 
$validator->exect("insert into catalog_valor(id,valor,idDepende,idCatalogo) values('','".$valor."','".$id_depende."','".$id."')");
	   		
	  return 'controller.php?view=catalog-valor-2';
	
	}// Fin de la función insertCatalogoValor

      public function getCatalogoValor($validator)
	  {
	   // Función encargada de recolectar todos los catalogos 
	    
        $result = $validator->exect("SELECT DISTINCT A.id, A.valor, A.idDepende, A.idCatalogo, B.name AS nombreCatalogo FROM                                     catalog_valor A, catalog B WHERE (A.idCatalogo = B.id)");
		$array = array();
		while($object = mysql_fetch_object($result))
		{
			array_push($array,$object);
			
		}
		
		return $array;
      } // Fin de la función getCatalogo
	  
	  public function getDepende($validator)
	  {
	   // Función encargada de recolectar todos los catalogos 
	    
	 $result = $validator->exect("select A.idDepende, A.id, B.valor as ValorRecursivo, B.id as idRecursivo from catalog_valor A, catalog_valor B where(A.idDepende =                              B.id)");
		$array = array();
		while($object = mysql_fetch_object($result))
		{
			array_push($array,$object);
			
		}
		
		return $array;
      } // Fin de la función getCatalogo
      
	  public function eliminarCatalogoValor($validator)
	  {
	    // Función encargada de eliminar el catalog seleccionado de la tabla catalog_valor
		
	      $id = $validator->getVar('id', 'ID del catalog');
		 
          $validator->exect("delete from catalog_valor  where id = '".$id."'" );
	   	  
	     return 'controller.php?view=catalog-valor';
	  }// Fin de la función eliminarCatalogoValor 
	  public function updateCatalogoValor($validator)
	  {
	    // Función encargada de actualizar los datos de un catalog en la tabla catalog__valor
		
		$name = $validator->getVar('name','name del catalog');		
		$id = $validator->getVar('id','ID del catalog'); 
		$id_depende= $validator->getVar('id_depende','Depende');
		$id_cat = $validator->getVar('id_cat','catalog');
		
		$result = $validator->exect("select IdCatalogo from catalog_valor where  id = '".$id_depende."'");
	    $row = mysql_fetch_row($result);
		$blanco = 'blanco';
		
		  if ($id_depende != $blanco)
          { 
			 
			$result = $validator->exect("update catalog_valor set valor='".$name."', idDepende='".$id_depende."', idCatalogo='".$row[0]."' where id='".$id."'");
		    return 'controller.php?view=catalog-valor';
		  }
		else
		  
		  {  		   
		    
			$result = $validator->exect("update catalog_valor set valor='".$name."', idCatalogo='".$id_cat."' where id='".$id."'");
		    return 'controller.php?view=catalog-valor';
		   }
          	
	  
	  
	  }// Fin de la función updateCatalogoValor
	  
	  
   
   

} // Fin clase catalog 


?>
