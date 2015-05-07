<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./services/utilityFunctions.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$ip=$base->getIP();
	$userID = $base->getUserID();
	$projectID = $base->getProjectID();

	$message = addslashes($_GET['message']);

	$query = "SELECT * FROM users WHERE userID='$userID'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$userName = $line['username'];
	$color = $line['color'];

	$timestamp = $base->getTimestamp();
	$date = $base->getDate();
	$time = $base->getTime();

	$query = "INSERT INTO chat VALUES('','$userID','$userName','','$projectID','$message','$timestamp','$date','$time')";
	$results = $connection->commit($query);
	$chatID = $connection->getLastID();

	Util::getInstance()->saveAction('chat',"$chatID",$base);
	addPoints($userID,5);
?>
