<?php
	require_once("connect.php");
	$projectID = $_GET['projectID'];
	$query = "SELECT count(*) as num FROM snippets WHERE projectID='$projectID'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$num = $line['num'];
	echo $num;
?>