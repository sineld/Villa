<?php 
	/**
	 * 
	 */
	class AgregararticuloDelegate {
		
		public function setArticulo1($validator)
		{
			$nombre = $validator->getVar("nombre");
			$codigo = $validator->getVar("codigo");
			$descripcion = $validator->getVar("descripcion");
			$categoria = $validator->getOptionalVar("categoria");
			$tipo = $validator->getOptionalVar("tipo");
			
			require_once 'phputils/mysqlConexion.php';
			
			$newArticulos = new Articulos;
			
			$newArticulos->nombre = $nombre;
			$newArticulos->codigo = $codigo;
			$newArticulos->descripcion = $descripcion;
			
			$mysqldate = date( 'Y-m-d');
			$newArticulos->fechaingreso = $mysqldate;
			
			$newArticulos->categoria = 1;

			//$newTipos = new Tipos;
			$newArticulos->tipo = 1;
			
			$newArticulos->save();
			
			$articulo = $newArticulos->id;
			
			//$articulo = $newArticulos->getLastModified();
			
			echo 'true';//json_encode($articulo);
			
			return 'void';
			
		}
		
		public function getProducts($validator)
		{
			$id = null;
			$id= $validator->getOptionalVar("id");
			require_once 'phputils/mysqlConexion.php';
			
			if ($id!="") {
				
				$q = Doctrine_Query::create()
	             ->select('a.id')
	             ->from('Articulos a')
				 ->where('codigo =?',$id);
				
			}else{
			
				$q = Doctrine_Query::create()
		             ->select('a.id')
		             ->from('Articulos a');
				 
			}

			$qArray = $q->execute()->toArray();
			
			if(count($qArray)>=1){
				echo json_encode($qArray);
			}else{
				echo "[]";
			}
			
			//return 'controller.php?view=agregar-producto';
			//REVISAR LOS DELEGATES EJEMPLO: http://localhost/Villa/Canis/crud.php?view=ver-productos&action=getProducts
		}
		
	}
	

?>