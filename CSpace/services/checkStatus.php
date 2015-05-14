<?php
	session_start();
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (isset($_SESSION['CSpace_userID'])) {
		$base = Base::getInstance();
		$connection = Connection::getInstance();

		$userID = $base->getUserID();
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $base->getProjectID();
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$version = $_GET['version'];

			echo $userID.":".$projectID;
	}
	else
		echo "0:Your session has expired. Please login again.";
?>
