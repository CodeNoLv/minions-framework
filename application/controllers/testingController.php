<?php
	Class testingController extends controller
	{
		public static function action_index()
		{
			$sample = new sampleModel();
			$objects = $sample->getAll()->asObjects();

			foreach ($objects as $key => $object)
			{
				echo $object->id;
				echo "<br/>";
			}

			$newSample = new sampleModel();
			$newSample->course_id = 1;
			$newSample->description = "test";
			$newSample->task = "test";
			$newSample->solution = "test";
			$newSample->difficulty_id = 2;
			$newSample->save();

			//var_dump($newSample);

			$view = new view();
			$view->setBlock("content", "testing/index");
			$view->output();
		}
	}
?>