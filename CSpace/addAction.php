<?php
	session_start();
	require_once("connect.php");
	// Get the date, time, and timestamp
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
	$date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];

	$action = $_GET['action'];
	$value = $_GET['value'];	

	$query = "SELECT * FROM users WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$points = $line['points'];
	$newPoints = $points;
	switch ($action) {
		case 'activate':
			// Award point only if Coagmento was activated more than an hour before this activation.
			$query1 = "SELECT timestamp FROM actions WHERE userID='$userID' AND action='activate' ORDER BY timestamp desc";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			if (mysql_num_rows($results1)!=0) {
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$oldTime = $line1['timestamp'];
//				echo "$oldTime, $timestamp";
				if ($oldTime<$timestamp-3600) {
					$ip=$_SERVER['REMOTE_ADDR'];
					$query2 = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','activate-count','','$ip')";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$newPoints+=100;
				}
			}
			else {
				$ip=$_SERVER['REMOTE_ADDR'];
				$query2 = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','activate-count','','$ip')";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				$newPoints+=100;
			}
			break;
		
		case 'sidebar-docs':
		case 'sidebar-query':
		case 'sidebar-query-snapshot':
		case 'sidebar-snippet':
			$newPoints = $points+5;
			break;
			
		case 'print':
			// Award point only if Coagmento was activated more than an hour before this activation.
			$query1 = "SELECT timestamp FROM actions WHERE userID='$userID' AND action='print' ORDER BY timestamp desc";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			if (mysql_num_rows($results1)!=0) {
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$oldTime = $line1['timestamp'];
//				echo "$oldTime, $timestamp";
				if ($oldTime<$timestamp-86400) {
					$ip=$_SERVER['REMOTE_ADDR'];
					$query2 = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','print-count','','$ip')";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$newPoints+=100;
				}
			}
			else {
				$ip=$_SERVER['REMOTE_ADDR'];
				$query2 = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','print-count','','$ip')";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				$newPoints+=100;
			}
			break;
			
		case 'download':
			$newPoints = $points+100;
			break;
		
		default:
			break;
	}
	
	$ip=$_SERVER['REMOTE_ADDR'];
	$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','$action','$value','$ip')";
	$aResults = mysql_query($aQuery) or die(" ". mysql_error());

	$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	
	mysql_close($dbh);
?>
