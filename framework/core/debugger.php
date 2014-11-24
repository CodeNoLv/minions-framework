<?php

	class Debugger
	{

		/*
            Description: Recursively prints variables in data structure
            Parameters: required (Mixed) $input - Input variable which will be printed
            			(Integer) $level - Recursion's depth level
            Return: null
        */
		public static function vardump($input, $level = 0)
		{
			$varType = gettype($input);

			if ($varType == "array" || $varType == "object")
			{
				echo "{" . $level . " ";
				echo "(" . $varType . ") ";

				$newLevel = $level + 1;

				foreach ($input as $key => $value)
				{
					echo "<br/>";
					echo $key . " : ";
					self::vardump($value, $newLevel);
				}
				echo " " . $level . "}";
			}

			if ($varType == "string" || $varType == "integer")
			{
				echo "(" . $varType . ") " . $input;
			}

			if ($varType == "boolean")
			{
				echo "(" . $varType . ") ";
				if ($input) echo "true";
				else echo "false";
			}
		}

		/*
            Description: Prints and logs code exceptions and errors
            Parameters: required (String) $suggestion - Custom error and suggestion text
            			(Object Exception) $e - cought or created excpetion object
            Return: null
        */
		public static function exception($suggestion, $e)
		{
			Logger::addLog($e->getFile(), $e->getMessage() . " " . $e->getFile() . " " . $e->getLine() . " " . $e->getTraceAsString() . " " . $suggestion);
			throw new Exception($suggestion);
		}
	}

?>