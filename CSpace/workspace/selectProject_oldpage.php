<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="container">
<h3>View Select Project</h3>

<?php
	session_start();
	ob_start();
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();
	$pageName = "CSpace/workspace/selectProject_oldpage.php";
	//require_once("../counter.php");

	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $base->getUserID();
		$query1 = "SELECT * FROM users WHERE userID='$userID'";
		$results1 = $connection->commit($query1);
		$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
		$firstName = $line1['firstName'];
		$lastName = $line1['lastName'];
		if (isset($_GET['projectID'])) {
			$projectID = $_GET['projectID'];
			$_SESSION['projectID'] = $projectID;
			setcookie("CSpace_projectID", $projectID);
			echo "<br/><br/><center>\n<table class=\"body\">\n";
			echo "<tr bgcolor=#DDDDDD><td>Hello, <strong>$firstName $lastName</strong>.</td></tr>\n";
			$query = "SELECT * FROM projects WHERE projectID='$projectID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$title = $line['title'];
			echo "<tr><td>You have selected <strong>$title</strong> as your active project.<br/></td></tr>\n";
			echo "<tr><td>Once you activate <em>Coagmento</em> from your toolbar, the following things will be recorded:\n";
			echo "<ul><li>URLs you enter</li>\n<li>Queries you execute on search sites</li>\n<li>Any link you click on a webpage</li>\n";
			echo "</td></tr>\n";
			echo "<tr><td>You can also save a page, collect snippets, and make annotations to a page or snippets.<br/>These will all be saved under the project you have selected.</td></tr>\n";
			echo "<tr><td>You can access all these records by clicking on '<a href=\"log.php\">My logs</a>' on your <a href=\"index.php\">CSpace</a>.</td></tr>\n";
			echo "<tr><td>You can change your active project by visiting your <a href=\"index.php\">CSpace</a>.</td></tr>\n";
			echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
		}
		else {
			echo "<br/><br/><center>\n<table class=\"body\">\n";
			echo "<tr><td>Sorry. Looks like we had trouble knowing what project you want to work on!<br/>Please try <a href=\"index.php\">selecting this</a> again.</td></tr>\n";
			echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
		}
	}
	else {
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr><td>Sorry. Looks like we had trouble knowing who you are!<br/>Please try <a href=\"index.php\">logging in</a> again.</td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	}

?>
  <!-- end #footer --></div>
<!-- end #container --></div>


</body>
</html>
