<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	// Get the date, time, and timestamp
	$timestamp = $base->getTimestamp();
	$date = $base->getDate();
	$time = $base->getTime();

	$ip=$_SERVER['REMOTE_ADDR'];

	$userID = $base->getUserID();
	$projectID = $base->getProjectID();

	$action = $_GET['action'];
	$value = $_GET['value'];


	$newPoints = 0;
	switch ($action) {
		case 'activate':
			// Award point only if Coagmento was activated more than an hour before this activation.
			$results1 = $connection->commit("SELECT timestamp FROM actions WHERE userID='$userID' AND action='activate' ORDER BY timestamp desc");
			if (mysql_num_rows($results1)!=0) {
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$oldTime = $line1['timestamp'];
				if ($oldTime<$timestamp-3600) {
					$results2 = $connection->commit("INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','activate-count','','$ip')");
					$newPoints+=100;
				}
			}
			else {
				$results2 = $connection->commit("INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','activate-count','','$ip')");
				$newPoints+=100;
			}
			break;

		case 'sidebar-docs':
		case 'sidebar-query':
		case 'sidebar-query-snapshot':
		case 'sidebar-snippet':
			$newPoints += 5;
			break;

		case 'print':
			// Award point only if Coagmento was activated more than an hour before this activation.
			$results1 = $connection->commit("SELECT timestamp FROM actions WHERE userID='$userID' AND action='print' ORDER BY timestamp desc");
			if (mysql_num_rows($results1)!=0) {
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$oldTime = $line1['timestamp'];
				if ($oldTime<$timestamp-86400) {
					$results2 = $connection->commit("INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','print-count','','$ip')");
					$newPoints+=100;
				}
			}
			else {
				$results2 = $connection->commit("INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','print-count','','$ip')");
				$newPoints+=100;
			}
			break;

		case 'download':
			$newPoints +=100;
			break;

		default:
			break;
	}

	$aResults = $connection->commit("INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','$action','$value','$ip')");
	require_once("utilityFunctions.php");
	addPoints($userID,$newPoints);
?>
