<?php
	require_once("../connect.php");
	session_start();
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
    	$date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	$projectID = $_SESSION['CSpace_projectID'];
	$userID = $_SESSION['CSpace_userID'];
        $ip=$_SERVER['REMOTE_ADDR'];
	$action = $_GET['action'];
 	$value = $_GET['value'];
	$query = "INSERT INTO actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES ('$userID', '$projectID', '$timestamp', '$date', '$time', '$action', '$value','$ip')";
	//echo $query;
	$results = mysql_query($query) or die(" ". mysql_error());
	//mysql_close($dbh);
	//}

        $query = "SELECT * FROM users WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$points = $line['points'];
	$newPoints = $points;
	$newPoints+=5; //1 point is given after loading the page.

	$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());

	//mysql_close($dbh);
?>