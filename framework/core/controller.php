<?php

	Class controller
	{
		function __construct()
		{
			if (session_status() == PHP_SESSION_NONE)
			{
			    session_start();
			    if (method_exists($this, "before")) $this->before();	// If child controller has defined before(), it is automaticly called
			}
		}
	}
	
?>