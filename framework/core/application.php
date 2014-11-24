<?php

	class Application
	{
		public $applicationConfig; // Application's config array
		public $frameworkConfig; // Framework's config array

		function __construct()
		{
			$applicationConfigPath = $GLOBALS["applicationPath"] . "/config/main-config.php";
			$frameworkConfigPath = $GLOBALS["frameworkPath"] . "/config/main-config.php";

			$this->applicationConfig = require($applicationConfigPath);
			$this->frameworkConfig = require($frameworkConfigPath);
		}

		/*
            Description: Initializes application object, print includes and initializes Router
            Parameters: null
            Return: (Object Application) initialized Application object
        */
		function init()
		{
			$app = new Application();
			echo $app->getIncludes();
			$GLOBALS['app'] = $app;

			if (array_key_exists("timezone", $app->applicationConfig))
			{
				date_default_timezone_set($app->applicationConfig["timezone"]);	
			}
			else
			{
				$exception = new Exception();
				Debugger::exception("Define 'timezone' index in Application config", $exception);
			}

			Router::init()->findContent();
			return $app;
		}

		/*
            Description: Creates output buffer for application's and framework's includes
            Parameters: null
            Return: (String) Application's and Framework's includes
        */
		function getIncludes()
		{
			ob_start();

			foreach ($this->frameworkConfig["core"] as $core)
			{
				include($GLOBALS["frameworkPath"] . "/core/" . $core . ".php");
			}

			if (array_key_exists("packages", $this->frameworkConfig))
			{
				foreach ($this->frameworkConfig["packages"] as $package)
				{
					include($GLOBALS["frameworkPath"] . "/packages/" . $package . ".php");
				}
			}
			
			if (array_key_exists("models", $this->applicationConfig))
			{
				foreach ($this->applicationConfig["models"] as $model)
				{
					include($GLOBALS["applicationPath"] . "/models/" . $model . "Model.php");
				}
			}

			if (array_key_exists("controllers", $this->applicationConfig))
			{
				foreach ($this->applicationConfig["controllers"] as $controller)
				{
					include($GLOBALS["applicationPath"] . "/controllers/" . $controller . "Controller.php");
				}
			}

			$includes = ob_get_contents();
			ob_end_clean();
			return $includes;
		}
	}
	
?>