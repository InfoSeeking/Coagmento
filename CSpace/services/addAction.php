<?php
	session_start();
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	require_once("../services/utilityFunctions.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$userID = $base->getUserID();
	$projectID = $base->getProjectID();
	$ip=$base->getIP();

	$timestamp = $base->getTimestamp();
	$date = $base->getDate();
	$time = $base->getTime();


	$action = $_GET['action'];
	$value = $_GET['value'];


	$newPoints = 0;
	if($action=='activate'){
		// Award point only if Coagmento was activated more than an hour before this activation.
		$results1 = $connection->commit("SELECT timestamp FROM actions WHERE userID='$userID' AND action='activate' ORDER BY timestamp desc");
		if (mysqli_num_rows($results1)!=0) {
			$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
			$oldTime = $line1['timestamp'];
			if ($oldTime<$timestamp-3600) {
				Util::getInstance()->saveAction("activate-count",'',$base);
				$newPoints=100;
			}
		}
		else {
			Util::getInstance()->saveAction("activate-count",'',$base);
			$newPoints=100;
		}
	}else if ($action == 'sidebar-docs' || $action == 'sidebar-query' || $action == 'sidebar-query-snapshot' || $action == 'sidebar-snippet'){
		$newPoints = 5;
	}else if($action == 'print'){
		// Award point only if Coagmento was activated more than an hour before this activation.
		$results1 = $connection->commit("SELECT timestamp FROM actions WHERE userID='$userID' AND action='print' ORDER BY timestamp desc");
		if (mysqli_num_rows($results1)!=0) {
			$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
			$oldTime = $line1['timestamp'];
			if ($oldTime<$timestamp-86400) {
				Util::getInstance()->saveAction("print-count",'',$base);
				$newPoints=100;
			}
		}
		else {
			Util::getInstance()->saveAction("print-count",'',$base);
			$newPoints=100;
		}
	}else if ($action == 'download'){
		$newPoints =100;
	}

	Util::getInstance()->saveAction("activate-count",$value,$base);
	addPoints($userID,$newPoints);
?>
