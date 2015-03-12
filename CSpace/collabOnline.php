<?php
	session_start();
	require_once("connect.php");
	
	echo "<span style=\"color:green;font-size:10px;\">Online: ";
	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$query = "SELECT * FROM memberships WHERE projectID='$projectID' AND userID!='$userID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$cUserID = $line['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
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