<?php

	Class sampleModel extends database
	{
		public $tableName = "tasks";
		
		public $properties = array(
			"id",
			"title",
			"users_id",
			"gist_id",
			"gist_filename",
		);

		public $relations = array(
			"user" => array(
				"this_column" => "users_id",
				"relation_column" => "id",
				"relation_model" => "usersModel",
				"type" => "belongs_to",
			),
		);
	}

?>