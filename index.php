<?php

	$GLOBALS["applicationPath"] = "/application";
	$GLOBALS["frameworkPath"] = "/framework";

	include($GLOBALS["frameworkPath"] . "/core/application.php");
	application::init();
?>