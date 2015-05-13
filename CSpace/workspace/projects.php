<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<script src="assets/js/jquery-2.1.3.min.js"></script>
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	session_start();
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="container" class="container">
<h3>Select a Project</h3>

<?php

	require_once("../core/Connection.class.php");
	require_once("../core/Base.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>

<table class="body" width=100%>
	<tr><td>Click on a project title to bring up more information about it. You can click 'Select' to make a project your active project.</td></tr>
	<td><div id="sureDelete"></div></td>
<?php

	$userID = $base->getUserID();
	echo "<tr><td><table class=\"body\" width=\"100%\">";
	if (isset($_GET['projectID'])) {
		$projectID = $_GET['projectID'];
		$query = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$title = $line['title'];
		$query = "DELETE FROM memberships WHERE projectID='$projectID' AND userID='$userID'";
		$results = $connection->commit($query);
		echo "<tr><td colspan=3><font color=\"green\">Your membership to project <span style=\"font-weight:bold\">$title</span> has been canceled.</font></td></tr>";
		echo "<tr><td colspan=3><br/></td></tr>\n";
	}
	echo "<tr><th><span style=\"font-weight:bold\">Title</span></th><th>&nbsp;&nbsp;</th><th><span style=\"font-weight:bold\">Started on</span></th><th>&nbsp;&nbsp;</th><th><span style=\"font-weight:bold\">Select</span></th><th>&nbsp;&nbsp;</th><th><span style=\"font-weight:bold\">Membership</span></th></tr>\n";
	$query = "SELECT * FROM memberships WHERE userID='$userID' AND access=1 ORDER BY projectID";
	$results = $connection->commit($query);
	echo "<tr><td style=\"background:#EFEFEF;font-weight:bold\" colspan=7>Projects I Created</td></tr>";
	while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
		$access = $line['access'];
		$projectID = $line['projectID'];
		$query1 = "SELECT * FROM projects WHERE projectID='$projectID' AND status=1";
		$results1 = $connection->commit($query1);
		$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
		$projectID = $line1['projectID'];
		$title = $line1['title'];
		$startDate = $line1['startDate'];
		$dispTitle = $title;

		$query1 = "SELECT * FROM memberships WHERE projectID='$projectID'";
		$results1 = $connection->commit($query1);
		$members = "";
		while ($line1 = mysqli_fetch_array($results1, MYSQL_ASSOC)) {
			$uID = $line1['userID'];
			$access = $line1['access'];
			$query2 = "SELECT * FROM users WHERE userID='$uID'";
			$results2 = $connection->commit($query2);
			$line2 = mysqli_fetch_array($results2, MYSQL_ASSOC);
			$uName = $line2['username'];
			$firstName = $line2['firstName'];
			$lastName = $line2['lastName'];
			$members = $members . $firstName . " " . $lastName . ", ";
		}

        echo "<tr><td><a href='projectInfo.php?projectID=$projectID'>$dispTitle</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='editProject.php?projectID=$projectID'><img src=\"../assets/img/edit.jpg\" style=\"width: 15px; height: 15px; vertical-align:middle;border:0\" alt=\"Edit\" title=\"Edit\" /></a><br/>$members</td><td>&nbsp;&nbsp;</td><td align=center>$startDate</td><td>&nbsp;&nbsp;</td><td align=center><a href='selectProj.php?projectID=$projectID&projectTitle=$title'>Select</a></td><td>&nbsp;&nbsp;</td><td align=center>";
		if ($title=='Default')
			echo "N/A";
		else
			echo "<a href='javascript:void(0);' onClick=\"deleteProj('$projectID','$title');\">Leave</a>";

		echo "</td></tr><tr><td colspan=7><hr/></td></tr>\n";
	}

	echo "<tr><td colspan=7><br/></td></tr>\n";
	echo "<tr><td style=\"background:#EFEFEF;font-weight:bold\" colspan=7>Projects Others Created</td></tr>";

	// See if this user is a supervisor/teacher/admin
	$query = "SELECT * FROM users WHERE userID='$userID'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$type = $line['type'];
	$subject = $line['hear'];

	if ($type=="HS-teacher") {
		$query = "SELECT distinct memberships.projectID FROM users,memberships WHERE users.hear LIKE '%$subject%' AND users.userID=memberships.userID AND users.userID!=$userID ORDER BY projectID";
	}
	else if (($subject=="Administrator")&&($type=="HS")) {
		$query = "SELECT distinct memberships.projectID FROM users,memberships WHERE users.type='HS' AND users.userID=memberships.userID AND users.userID!=$userID ORDER BY projectID";
	}
	else
		$query = "SELECT * FROM memberships WHERE userID='$userID' AND access!=1 ORDER BY projectID";

	$results = $connection->commit($query);
	while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
		$access = $line['access'];
		$projectID = $line['projectID'];
		$query1 = "SELECT * FROM projects WHERE projectID='$projectID' AND status=1";
		$results1 = $connection->commit($query1);
		$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
		$projectID = $line1['projectID'];
		$title = $line1['title'];
		$startDate = $line1['startDate'];

		$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND access=1";
		$results1 = $connection->commit($query1);
		$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
		$uID = $line1['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$uID'";
		$results1 = $connection->commit($query1);
		$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
		$uName = $line1['username'];
		$dispTitle = $title . " (<span style=\"color:green;\">$uName</span>)";

		$query1 = "SELECT * FROM memberships WHERE projectID='$projectID'";
		$results1 = $connection->commit($query1);
		$members = "";
		while ($line1 = mysqli_fetch_array($results1, MYSQL_ASSOC)) {
			$uID = $line1['userID'];
			$access = $line1['access'];
			$query2 = "SELECT * FROM users WHERE userID='$uID'";
			$results2 = $connection->commit($query2);
			$line2 = mysqli_fetch_array($results2, MYSQL_ASSOC);
			$uName = $line2['username'];
			$firstName = $line2['firstName'];
			$lastName = $line2['lastName'];
			$members = $members . $firstName . " " . $lastName . ", ";
		}

        echo "<tr><td><a href='projectInfo.php?projectID=$projectID'>$dispTitle</a><br/>$members</td><td>&nbsp;&nbsp;</td><td align=center>$startDate</td><td>&nbsp;&nbsp;</td><td align=center><a href='selectProj.php?projectID=$projectID&projectTitle=$title'>Select</a></td><td>&nbsp;&nbsp;</td><td align=center>";
		echo "<a href='javascript:void(0);' onClick=\"deleteProj('$projectID','$title');\">Leave</a>";
		echo "</td></tr><tr><td colspan=7><hr/></td></tr>\n";
	}

	echo "</table></td></tr>\n";
	echo "<tr><td><br/></td></tr>\n";
	echo "<tr><td>See <a href='showPublicProjs.php'>open (public) projects</a>.</td></tr>\n";
	echo "</table>\n</center>\n<br/><br/>\n";
	}
?>

</body>
</html>
