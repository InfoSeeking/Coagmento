<?php
	session_start();
	require_once("connect.php");
	$userID = $_SESSION['CSpace_userID'];
	$query2 = "SELECT * FROM options WHERE userID='$userID' AND `option`='current-page'";
	$results2 = mysql_query($query2) or die(" ". mysql_error());
	$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
	$value = $line2['value'];
	echo $value;
?>
