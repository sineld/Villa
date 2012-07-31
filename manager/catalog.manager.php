<?php
//////////////////////////////////////////////////
//Esta class se encarga de cargar todas las variables necesarias
//para renderizar la view deseada
//////////////////////////////////////////////////

class CatalogManager
{
	static private $instancia = NULL;
	private $contador;
	private $validator;

	private $session;

   private function __construct() {$this->validator = new httpRequestValidator('CatalogManager');}

   public static function getInstance()
   {
       if (self::$instancia == NULL) {
          self::$instancia = new CatalogManager();
       }
       return self::$instancia;
   }

	public function getCatalog($nameCatalog, $dependentId=0)
    {
		   if ($dependentId==0)
				   $result = $this->validator->exect("SELECT catalog_value.id AS id, catalog_value.value AS value FROM catalog_value, catalog WHERE catalog.name = '$nameCatalog' AND catalog.id = catalog_value.id_catalog");
		   else
				   $result = $this->validator->exect("SELECT catalog_value.id AS id, catalog_value.value AS value FROM catalog_value, catalog WHERE catalog.id= catalog_value.id_catalog AND catalog.name ='$nameCatalog' AND catalog_value.id_depend=".$dependienteId."");

		   $array = array();
		   while($object = mysql_fetch_object($result))
		   {
				   array_push($array,$object);
		   }

		   return $array;
     }

	public function getLabelOfId($nameCatalog, $id)
    {

		$result = $this->validator->exect("select valor from catalogo_valor, catalogo where catalogo.id= catalogo_valor.idCatalogo and catalogo.nombre ='".$nameCatalog."' and catalogo_valor.id=".$id."");
		$object = mysql_fetch_object($result);

		if(mysql_num_rows($result)==1)
			return $object->valor;
		else return 'no definido '.$id;
     }

	public function getValueRecurs($id)
    {
      // echo 'Hola'.'</br>';
	   //echo $id.'</br>' ;
$result = $this->validator->exect("select A.idDepende, B.id as idRecursivo, B.valor as valorRecursivo from catalogo_valor A, catalogo_valor B where(A.id ='".$id."') and (A.idDepende = B.id)");
		//$row = mysql_fetch_row($result);
	    //return $row[2];
		$object = mysql_fetch_object($result);
		if(mysql_num_rows($result)==1)
			return $object->valorRecursivo;
		else return 'NO TIENE';
   }// END function getValueRecurs

}

?>
