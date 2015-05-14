<?php
	session_start();
	require_once("../core/Connection.class.php");
	require_once("../core/Base.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
    	$date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	$projectID = $base->getProjectID();
	$userID = $_SESSION['CSpace_userID'];
        $ip=$_SERVER['REMOTE_ADDR'];
	$action = $_POST['action'];
 	$value = $_POST['value'];
	$query = "INSERT INTO actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES ('$userID', '$projectID', '$timestamp', '$date', '$time', '$action', '$value','$ip')";
	$results = $connection->commit($query);
?>
