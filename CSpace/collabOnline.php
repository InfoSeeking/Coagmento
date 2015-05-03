<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	echo "<span style=\"color:green;font-size:10px;\">Online: ";
	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $base->getUserID();
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $base->getProjectID();
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$query = "SELECT * FROM memberships WHERE projectID='$projectID' AND userID!='$userID'";
		$results = $connection->commit($query);
		while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$cUserID = $line['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
			$results1 = $connection->commit($query1);
			while ($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
				if ($line1['status']) {
					$userName = $line1['username'];
					echo "$userName ";
				}
			}
		}
	}
	echo "</span>";
?>
