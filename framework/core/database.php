<?php

	// Database abstracting class
	// Includes basic CRUD functions with mySQL database

	Class database
	{
		public $select;
		public $from;
		public $activeRelations;
		public $where = null;
		public $dbResult;
		public $connection;

		function __construct() 
		{
			$this->initiateProperties();
		}
		
		/* 
			@description: Creates connection with mySQL database
			@param: none
			@returns: (bool) indicates if connection was successeful 
		*/
		function connect_to_database()
		{
			mysql_query("SET CHARACTER SET 'utf8'");
   			mysql_query("SET NAMES 'utf8'");

   			$applicationConfig = $GLOBALS["app"]->applicationConfig;

			$connection = new mysqli($applicationConfig["database"]["host"], $applicationConfig["database"]["user"], $applicationConfig["database"]["password"], $applicationConfig["database"]["database"]);
			
			if ($connection->connect_error)
			{
				die("Connection failed: " . $connection->connect_error);
				return false;
			}

			$this->connection = $connection;
			return true;
		}

		/* 
			@description: Generates SQL queries SELECT part string using models properties
			@param: none
			@returns: (String) SQL queries SELECT part
		*/
		function getSelect()
		{
			$selectString = "SELECT * ";
			return $selectString;
		}

		/* 
			@description: Generates SQL queries FROM part string using models tablename
			@param: none
			@returns: (String) SQL queries FROM part
		*/
		function getFrom()
		{
			$fromString = " FROM " . $this->tableName;
			return $fromString;
		}

		/*
            Description: Cretes query's where part
            Parameters: required (Array) $conditions - contains column and it's condition which are seperated by AND
            Return: (Object Database) current Database object
        */
		function andWhere($conditions)
		{
			if ($this->where == null) $this->where = " WHERE ";
			else $this->where .= " AND ";

			$conditionsCount = count($conditions);
			$currentConditionNr = 1;
			foreach ($conditions as $condition)
			{
				$this->where .= $condition;
				if ($currentConditionNr != $conditionsCount) $this->where .= " AND ";
				$currentConditionNr++;
			}
			return $this;
		}

		/*
            Description: Cretes query's where part
            Parameters: required (Array) $conditions - contains column and it's condition which are seperated by OR
            Return: (Object Database) current Database object
        */
		function orWhere($conditions)
		{
			if ($this->where == null) $this->where = " WHERE ";
			else $this->where .= " OR ";

			$conditionsCount = count($conditions);
			$currentConditionNr = 1;
			foreach ($conditions as $conditionn)
			{
				$this->where .= $condition;
				if ($currentConditionNr != $conditionsCount) $this->where .= " OR ";
				$currentConditionNr++;
			}

			return $this;
		}

		/*
            Description: Cretes query's join part by using Model's $relations array
            Parameters: required (Array) $withArr - contains all relations which will include JOIN
            Return: (Object Database) current Database object
        */
		function with($withArr)
		{
			// TODO: Iimplement other relational types

			foreach ($withArr as $with)
			{
				$relation = $this->relations[$with];
				$relationModel = new $relation["relation_model"]();

				$joinQuery = " LEFT JOIN ";
				$joinQuery .= $relationModel->tableName . " ON ";
				$joinQuery .= $this->tableName . "." . $relation["this_column"] . "=";
				$joinQuery .= $relationModel->tableName . "." . $relation["relation_column"] . " ";
				$this->activeRelations[] = array(
					"title" => $with,
					"join" => $joinQuery,
					"model" => $relationModel,
				);
			}

			return $this;
		}

		/*
            Description: Starts database call on Model and gets default select and from parts
            Parameters: null
            Return: (Object Database) current Database object
        */
		function get()
		{
			$this->select = $this->getSelect();
			$this->from = $this->getFrom();
			return $this;
		}

		/*
            Description: Executes database call with all current query parts in object
            Parameters: null
            Return: (Object Database) current Database object
        */
		function execute()
		{
			self::connect_to_database();

			mysql_query("SET CHARACTER SET 'utf8'");
			mysql_query("SET NAMES 'utf8'");

			$query = $this->select . $this->from;

			if (isset($this->activeRelations)) {
				foreach ($this->activeRelations as $relation) {
					$query .= $relation["join"];
				}
			}

			if (isset($this->where)) $query .= $this->where;

			//var_dump($query);

			$this->runQuery($query);

			return $this;
		}

		/*
            Description: Parse object's dbResult as arrays in array
            Parameters: null
            Return: (Array) array which contains all dbResult rows as arrays
        */
		function asArray()
		{
			$returnArray = array();

			while($row = mysql_fetch_assoc($this->dbResult))
			{
				$object = array();
				foreach ($this->properties as $property)
				{
					$object[$property] = $row[$property];
				}
				$returnArray[] = $object;
			}

			return $returnArray;
		}

		/*
            Description: Parse object's dbResult as Model's objects in array
            Parameters: null
            Return: (Mixed) array which contains all dbResult rows as Model's objects
        */
		function asObjects()
		{
			$returnObjects = array();
			$className = get_class($this);

			while($row = $this->dbResult->fetch_assoc())
			{
				$object = new $className();

				foreach ($this->properties as $property)
				{
					$object->{$property} = $row[$property];
				}

				if (isset($this->activeRelations))
				{
					foreach ($this->activeRelations as $relation)
					{
						$object->{$relation["title"]} = new stdClass();
						
						foreach ($relation["model"]->properties as $property)
						{
							if (isset($row[$property])) $object->{$relation["title"]}->{$property} = $row[$property];
						}
					}
				}

				$returnObjects[] = $object;
			}

			return $returnObjects;
		}

		/*
            Description: Saves current model's Object's properties in database row
            Parameters: null
            Return: (Boolean) status if saving was successeful
        */
		function save()
		{
			self::connect_to_database();

			$insertationColumns = "(";
			$insertationValues = "(";

			$firstIterration = true;
			
			foreach ($this->properties as $key => $property)
			{
				if ($this->{$property} == null) continue;

				if (!$firstIterration)
				{
					$insertationColumns .= ", ";
					$insertationValues .= ", ";
				}

				$insertationColumns .= $property;
				$insertationValues .= "'" . $this->{$property} . "'";

				$firstIterration = false;
			}

			$insertationColumns .= ")";
			$insertationValues .= ")";

			$query = "INSERT INTO " . $this->tableName . " " . $insertationColumns . " VALUES " . $insertationValues;
			if ($this->runQuery($query)) return true;
			return false;
		}

		/*
            Description: Initiates Model's properties in object
            Parameters: null
            Return: null
        */
		function initiateProperties()
		{
			foreach ($this->properties as $property)
			{
				$this->{$property} = null;
			}
		}

		/*
            Description: Runs given SQL query and saves dbResult which can be parsed in array or objects
            Parameters: required (String) $query - SQL query which will be executed
            Return: (Object Database) current Database object
        */
		function runQuery($query)
		{
			$dbResult = $this->connection->query($query);

			if (!$dbResult === TRUE)
			{
			    echo "Error: " . $query . "<br>" . $this->connection->error;
			    return false;
			}
			else
			{
				$this->dbResult = $dbResult;
			}

			$this->connection->close();
			return $this;
		}

		/*
            Description: Connects with database and runs custom SQL query, and saves dbResult which can be parsed in array or objects
            Parameters: required (String) $query - custom SQL query which will be executed
            Return: (Object Database) current Database object
        */
		function runCustomQuery($query)
		{
			self::connect_to_database();

			$dbResult = $this->connection->query($query);
			
			if (!$dbResult === TRUE)
			{
			    echo "Error: " . $query . "<br>" . $this->connection->error;
			    return false;
			}
			else
			{
				$this->dbResult = $dbResult;
			}

			$this->connection->close();
			return $this;
		}
	}

?>