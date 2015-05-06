<?php
	session_start();
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	$option = $_GET['option'];
	$value = $_GET['value'];
	require_once("connect.php");
	$query = "SELECT count(*) as num FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='$option'";
	$results = $connection->commit($query);
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$num = $line['num'];
	if ($num==0)
		$query = "INSERT INTO options VALUES('','$userID','$projectID','$option','$value')";
	else
		$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND projectID='$projectID' AND `option`='$option'";
	$results = $connection->commit($query);
?>