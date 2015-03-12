<?php
	session_start();
	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			require_once("connect.php");
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
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
