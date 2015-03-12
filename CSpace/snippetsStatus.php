<?php
	require_once("connect.php");
	$projectID = $_GET['projectID'];
	$query = "SELECT count(*) as num FROM snippets WHERE projectID='$projectID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$num = $line['num'];
	echo $num;
?>