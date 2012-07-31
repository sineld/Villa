
		<?php

		class roleDelegate
		{

			public function roleDelegate()
			{

				return "";
			}

			function insert($validator)
			{
				$entity = new role();
		$entity->name=$validator->getVar("name");
				$entity->save();

				return "controller.php?view=list-role";
			}

			function update($validator)
			{
				$id = $validator->getVar("id");$record = Doctrine::getTable("role")->find($id);
    	$record->name=$validator->getVar("name");
		    	$record->save();

				return "controller.php?view=list-role&idrole=".$validator->getVar("id");
			}

			function delete($validator)
			{
				$id = $validator->getVar("id");

				$q = Doctrine_Query::create()->delete("role a")->where("a.id = ".$id);
				$q->execute();

				return "controller.php?view=list-role";
			}

			function listRecords($validator)
			{

				$q = Doctrine_Query::create()->from("role");
				$records = $q->execute();

				return $records;
			}

			function getrole($validator)
			{
				$id = $validator->getVar("idrole");

				$records = Doctrine::getTable('role')->find($id);

				return $records;
			}

		}

		?>
		