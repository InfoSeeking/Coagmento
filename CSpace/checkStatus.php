<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (isset($_SESSION['CSpace_userID'])) {
		$base = Base::getInstance();
		$connection = Connection::getInstance();

		$userID = $base->getUserID();
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $base->getProjectID();;
		else {
			require_once("connect.php");
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$version = $_GET['version'];
/*		if ($version<200)
			echo "-1:Incompatible version. Please download the latest version of Coagmento plug-in.";
		else
*/
			echo $userID.":".$projectID;
	}
	else
		echo "0:Your session has expired. Please login again.";
?>
