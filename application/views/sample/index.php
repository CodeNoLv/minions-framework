<?php

	$link = url::createPageLink(array("page" => "index", "method" => "otherpage"));
	echo '<div>' . $contentVariable . ' | <a href="' . $link . '" >To other page >></a></div>';

?>