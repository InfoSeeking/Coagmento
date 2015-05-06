<?php

	$q = $_GET['q'];

	if ($q != "")
	{
		$pieces = explode("-", $q);
		$displayMode = $pieces[5];
	}

	switch ($displayMode)
	{
		case "timeline":
						include("../timeline.php");
						break;

		case "coverflow":
						include("extern_test.php");
						break;
						
		case "3D":
						include("../impress/impress.php");
						break;
	}

?>
