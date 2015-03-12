<?php
	session_start();
	require_once("connect.php");
	
	if ((isset($_SESSION['userID']))) {
		echo "Online collaborators: ";
		$userID = $_SESSION['userID'];
		if (isset($_SESSION['projectID']))
			$projectID = $_SESSION['projectID'];
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
					$color = $line1['color'];
					echo "<font color=\"#$color\">$userName</font> ";
				}
			}
		}
	}
	else {
		echo "You are not logged in. Visit your <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" target=_content><font color=blue>CSpace</font></a> to do so.\n";
	}
?>