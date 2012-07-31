<?php

class CRUDGenerator
{
	//El nombre de la tabla de BD de la cual se quiere generar el CRUD
	var $recordName = 'tableName';

	//
	var $identifierName = '';

	var $bindingsPath = 'binding.xml';

	//
	var $inputs = array();

	//El documento XML con la definicion de las vistas (Bindings.xml normalmente)
	var $xmlDoc;

	public function CRUDGenerator($path="binding.xml")
	{
		$this->binding = $path;
	}

	function getViewInXML($xmlDoc,$vista)
	{
		$views = $xmlDoc->getElementsByTagName("view");
		if(!isset($views))
		{

		 	return null;
		}

		foreach ($views as $view)
		{
			if($view->hasAttribute('name'))
				if($view->getAttribute('name')==$vista)
				{
					echo "si/";
					return $view;
				}
		}

		echo "no-";
		return null;
	}

	public function generateCRUD()
	{
		$this->xmlDoc = new DOMDocument();

		echo "Cargando el archivo $this->binding ..<br />";
		$this->xmlDoc->load($path);

		echo "Parseando el xml..<br />";
		$childs = $this->xmlDoc->getElementsByTagName("views");

		echo "Obteniendo el nodo raiz 'views'..<br />";
		$root = $childs->item(0);

		echo "Insertando el nuevo nodo del formulario add-$this->recordName ..<br />";
		$this->insertAddNode($root);

		echo "Insertando el nuevo nodo del formulario list-$this->recordName ..<br />";
		$this->insertListNode($root);

		echo "Insertando el nuevo nodo del formulario update-$this->recordName ..<br />";
		$this->insertUpdateNode($root);

		echo "Guardando el archibo $this->binding ..<br /><br />";
		$xmlDoc->save($this->binding);

		echo "Generando la vista add-$this->recordName..<br />";
		$this->generateInsertForm();

		echo "Generando la vista update-$this->recordName..<br />";
		$this->generateUpdateForm();

		echo "Generando la vista list-$this->recordName..<br />";
		$this->generateListForm();

		echo "Generando el delegate ".$this->recordName."Delegate ..<br /><br />";
		$this->generateDelegate();

		echo "Ejecucion finalizada con exito ..<br />";
	}

	public function insertAddNode($root)
	{
		if($root->nodeName=='views')
		{
			$node = getViewInXML($xmlDoc,'add-'.$this->recordName);
			$item = $this->xmlDoc->createElement('view');
			$item->setAttribute('name','add-'.$this->recordName);
			$item->setAttribute('url','generated/add-'.$this->recordName.'.php');
			$item->setAttribute('class',$this->recordName.'Delegate');

			if(isset($node)) $this->xmlDoc->documentElement->replaceChild($item,$node);
			else $root->appendChild($item);

		}
	}

	public function insertListNode($root)
	{
		if($root->nodeName=='views')
		{
			$node = getViewInXML($this->xmlDoc,'list-'.$this->recordName);
			$item = $this->xmlDoc->createElement('view');
			$item->setAttribute('name','list-'.$this->recordName);
			$item->setAttribute('url','generated/list-'.$this->recordName.'.php');
			$item->setAttribute('class',$rthis->recordName.'Delegate');

			$getter = $this->xmlDoc->createElement('getter');
			$getter->setAttribute('destination','list'.$this->recordName);
			$getter->nodeValue = 'listRecords';
			$item->appendChild($getter);

			if(isset($node)) $this->xmlDoc->documentElement->replaceChild($item,$node);
			else $root->appendChild($item);
		}
	}

	public function insertUpdateNode($root)
	{
		if($root->nodeName=='views')
		{
			$node = getViewInXML($this->xmlDoc,'update-'.$this->recordName);

			$item = $xmlDoc->createElement('view');
			$item->setAttribute('name','update-'.$this->recordName);
			$item->setAttribute('url','generated/update-'.$this->recordName.'.php');
			$item->setAttribute('class',$this->recordName.'Delegate');

			$getter = $this->xmlDoc->createElement('getter');
			$getter->setAttribute('destination','entity');
			$getter->nodeValue = 'get'.$this->recordName;
			$item->appendChild($getter);

			if(isset($node)) $this->xmlDoc->documentElement->replaceChild($item,$node);
			else $root->appendChild($item);
		}
	}

	public function generateInsertForm()
	{
			$table = Doctrine::getTable($this->recordName);
			foreach ($table->getColumnNames() as $columnName) {
			        //$fieldName = $table->getClassnameToReturn()."[$columnName]";
					$fieldName = $columnName;
			        if ($table->isIdentifier($columnName)) {
							$identifierName = $columnName;
			                $inputs[] = new FormInput('hidden', $fieldName);
			        }
					else
					{
			                $printableName = ucwords(str_replace('_',' ',$columnName));
			                $inputs[] = new FormInput($table->getTypeOf($columnName), $fieldName, $printableName);
			        }
			}

			$content = '
			<form action="crud.php" method="post">
				<input type="hidden" name="action" value="insert" />
			';
				foreach ($inputs as $input)  $content .= $input->render();
			$content .= '<input type="submit" name="submit" value="Save" />
			</form>';

			if(!file_put_contents('view/generated/add-'.$this->recordName.'.php', $content)) return false;
			else return true;
	}

	public function generateUpdateForm()
	{
		$inputs = array();

		$table = Doctrine::getTable($this->recordName);
		foreach ($table->getColumnNames() as $columnName) {
		        //$fieldName = $table->getClassnameToReturn()."[$columnName]";
				$fieldName = $columnName;
		        if ($table->isIdentifier($columnName)) {
						$identifierName = $columnName;
		                $inputs[] = new FormInput('hidden', $fieldName);
		        }
				else
				{
		                $printableName = ucwords(str_replace('_',' ',$columnName));
		                $inputs[] = new FormInput($table->getTypeOf($columnName), $fieldName, $printableName);
		        }
		}

		$content = '
		<form action="crud.php" method="post">
			<input type="hidden" name="action" value="update" />
		';
			foreach ($inputs as $input)  $content .= $input->render('<?php echo $vars["entity"]->'.$input->name.'; ?>');
		$content .= '<input type="submit" name="submit" value="Save" />
		</form>';

		if(!file_put_contents('view/generated/update-'.$this->recordName.'.php', $content)) return false;
		else return true;
	}

	public function generateListForm()
	{
		$content =
		'
		<p>Agregar un nuevo registro <a class="agregarLink" href="controller.php?view=add-'.$this->recordName.'"></a></p>
		<p>Lista de '.$this->recordName.'</p>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		    <tr>';

			foreach ($table->getColumnNames() as $columnName)
				$content .= PHP_EOL.'		<td>'.$columnName.'</td>';

		$content .=  '
			</tr>
			<?php foreach($vars["list'.$this->recordName.'"] as $record) { ?>
			<tr>';

		foreach ($table->getColumnNames() as $columnName)
		$content .= '
				<td><?php echo $record->'.$columnName.'; ?></td>';
		$content .= '
				<td><a class="deleteLink" href="crud.php?action=delete&id=<?php echo $record->'.$identifierName.'; ?>"></a></td>';
		$content .= '
				<td><a class="editarLink" href="controller.php?view=update-'.$this->recordName.'&id'.$this->recordName.'=<?php echo $record->'.$identifierName.'; ?>"></a></td>';
		$content .= '
			</tr>
			<?php } ?>
		</table>';

		if(!file_put_contents('view/generated/list-'.$this->recordName.'.php', $content)) return false;
		else return true;
	}

	public function generateDelegate()
	{
		$content = '
		<?php

		class '.$this->recordName.'Delegate
		{

			public function '.$this->recordName.'Delegate()
			{

				return "";
			}

			function insert($validator)
			{
				$entity = new '.$this->recordName.'();';

				foreach ($table->getColumnNames() as $columnName)
				{
					if (!$table->isIdentifier($columnName))
					{
							$content .= PHP_EOL.'		$entity->'.$columnName.'=$validator->getVar("'.$columnName.'");';
					}
				}

		$content .=
		'
				$entity->save();

				return "controller.php?view=list-'.$this->recordName.'";
			}

			function update($validator)
			{
				$q = Doctrine_Query::create()
				->update("'.$this->recordName.'")';

				foreach ($table->getColumnNames() as $columnName)
				{
		        	$fieldName = $table->getClassnameToReturn()."[$columnName]";
					if (!$table->isIdentifier($columnName))
					{
							$content .= PHP_EOL.'    	->set("'.$columnName.'",$validator->getVar("'.$columnName.'"))';
					}
				}

		$content .=
		'
		    	->where("'.$identifierName.' = ".$validator->getVar("'.$identifierName.'"));

				return "controller.php?view='.$this->recordName.'&id'.$this->recordName.'=".$validator->getVar("'.$identifierName.'");
			}

			function delete($validator)
			{
				$id = $validator->getVar("'.$identifierName.'");

				$q = Doctrine_Query::create()->delete("'.$this->recordName.' a")->where("a.'.$identifierName.' = ".$id);
				$q->execute();

				return "controller.php?view=list-'.$this->recordName.'";
			}

			function listRecords($validator)
			{

				$q = Doctrine_Query::create()->from("'.$this->recordName.'");
				$records = $q->execute();

				return $records;
			}

			function get'.$this->recordName.'($validator)
			{
				$id = $validator->getVar("id'.$this->recordName.'");

				$q = Doctrine_Query::create()->from("'.$this->recordName.' a")->where("a.'.$identifierName.' = ".$id);
				$records = $q->execute();

				return $records;
			}

		}

		?>
		';


		if(!file_put_contents('delegate/generated/'.$this->recordName.'Delegate.delegate.php', $content)) return false;
		else return true;
	}
}
?>