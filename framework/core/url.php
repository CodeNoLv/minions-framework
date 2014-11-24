<?php

	class url
	{
		/*
            Description: Creates applications page link
            Parameters: required (Array) $array - contains of applications "page" and "method"
            Return: (String) applications link of given $array
        */
		public static function createPageLink($array)
		{
			$resultLink = "/";
			$first = true;

			if (isset($array["page"])){
				$resultLink .= $array["page"];
				$first = false;

				if (isset($array["method"])){
					$resultLink .= "/" . $array["method"];
					$first = false;
				} else {
					$resultLink .= "/index";
					$first = false;
				}

				return $resultLink;
			}

			return false;
		}
	}

?>