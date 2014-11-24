<?php

	class Response
	{
		/*
            Description: Redirects browser to other applications page
            Parameters: required (String) $endPoint - applications endpoint to which browser will be redirected
            Return: null
        */
		public static function redirectPage($endPoint)
		{
			header('Location: /' . $endPoint, true, 303);
			die();
		}
	}

?>