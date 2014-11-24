<?php

	class Logger
	{

		/*
            Description: Creates log files and populate them with log messages
            Parameters: required (String) $tag - Custom log tag
            			(required (String) $content - Custom log content
            Return: null
        */
		public static function addLog($tag, $content)
		{
			$logFilePath = "framework/log/";
			$fileName = date("d-m-y");
			$logFile = fopen($logFilePath . $fileName . ".txt", "a");
			fwrite($logFile, "\n" . date("H:i:s", time()) . " | " . $tag . " | " . $content);
			fclose($logFile);
		}
	}

?>