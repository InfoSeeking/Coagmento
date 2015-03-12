<?php
	session_start();
	$ip=$_SERVER['REMOTE_ADDR'];
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	$message = addslashes($_GET['message']);
	require_once("connect.php");
	$query = "SELECT * FROM users WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$userName = $line['username'];
	$color = $line['color'];
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
    $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	$query = "INSERT INTO chat VALUES('','$userID','$userName','','$projectID','$message','$timestamp','$date','$time')";
	$results = mysql_query($query) or die(" ". mysql_error());

	$aQuery = "SELECT max(chatID) as num FROM chat";
	$aResults = mysql_query($aQuery) or die(" ". mysql_error());
	$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
	$chatID = $aLine['num'];
			
	$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','chat','$chatID','$ip')";
	$aResults = mysql_query($aQuery) or die(" ". mysql_error());
	$query = "SELECT * FROM users WHERE username='$userName'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$points = $line['points'];
	$newPoints = $points+5;
	$query = "UPDATE users SET points=$newPoints WHERE username='$userName'";
	$results = mysql_query($query) or die(" ". mysql_error());
?>