<?php
	session_start();
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");

	$base = Base::getInstance();
	$connection = Connection::getInstance();
	$projectID = $base->getProjectID();

	$query = "SELECT * FROM projects WHERE projectID='$projectID'";
	$results = $connection->commit($query);
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$projectTitle = $line['title'];
	echo "Active project: <span style=\"color:green;\">$projectTitle</span>";
?>
