<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$ip=$_SERVER['REMOTE_ADDR'];
	$userID = $base->getUserID();
	$projectID = $base->getProjectID();
	$message = addslashes($_GET['message']);


	$query = "SELECT * FROM users WHERE userID='$userID'";
	$results = $connection->commit($query);
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$userName = $line['username'];
	$color = $line['color'];

	$timestamp = $base->getTimestamp();
	$date = $base->getDate();
	$time = $base->getTime();

	$query = "INSERT INTO chat VALUES('','$userID','$userName','','$projectID','$message','$timestamp','$date','$time')";
	$results = $connection->commit($query);

	$aQuery = "SELECT max(chatID) as num FROM chat";
	$aResults = $connection->commit($aQuery);
	$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
	$chatID = $aLine['num'];

	$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','chat','$chatID','$ip')";
	$aResults = $connection->commit($aQuery);
	$query = "SELECT * FROM users WHERE username='$userName'";
	$results = $connection->commit($query);
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$points = $line['points'];
	$newPoints = $points+5;
	$query = "UPDATE users SET points=$newPoints WHERE username='$userName'";
	$results = $connection->commit($query);
?>
