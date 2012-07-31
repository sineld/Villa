
		<?php

		class pollDelegate
		{

			public function pollDelegate()
			{

				return "";
			}

			function insert($validator)
			{
				$entity = new poll();
		$entity->question=$validator->getVar("question");
		$entity->date=$validator->getVar("date");
		$entity->status=$validator->getVar("status");
				$entity->save();

				return "controller.php?view=list-poll";
			}

			function update($validator)
			{
				$id = $validator->getVar("id");$record = Doctrine::getTable("poll")->find($id);
    	$record->question=$validator->getVar("question");
    	$record->date=$validator->getVar("date");
    	$record->status=$validator->getVar("status");
		    	$record->save();

				return "controller.php?view=list-poll&idpoll=".$validator->getVar("id");
			}

			function delete($validator)
			{
				$id = $validator->getVar("id");

				$q = Doctrine_Query::create()->delete("poll a")->where("a.id = ".$id);
				$q->execute();

				return "controller.php?view=list-poll";
			}

			function listRecords($validator)
			{

				$q = Doctrine_Query::create()->from("poll");
				$records = $q->execute();

				return $records;
			}

			function getpoll($validator)
			{
				$id = $validator->getVar("idpoll");

				$records = Doctrine::getTable('poll')->find($id);

				return $records;
			}

		}

		?>
		