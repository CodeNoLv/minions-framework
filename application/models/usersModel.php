<?php

	Class usersModel extends database
	{
		public $tableName = "users";
		
		public $properties = array(
			"id",
			"username",
			"password",
		);
	}

?>