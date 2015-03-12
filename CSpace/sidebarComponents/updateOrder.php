<?php
	session_start(); 
	if ((isset($_SESSION['CSpace_userID']))) {
		$table = $_GET['table'];
		$orderBy = $_GET['orderBy'];
		$webPage = $_GET['webPage'];
		$_SESSION['orderBy'.$table] = $orderBy;
                $userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
                $timestamp = time();
		$datetime = getdate();
		$date = date('Y-m-d', $datetime[0]);
		$time = date('H:i:s', $datetime[0]);
                require_once("../connect.php");
                $ip=$_SERVER['REMOTE_ADDR'];
                $aquery = "INSERT INTO actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES ('$userID', '$projectID', '$timestamp', '$date', '$time', 'updateOrder_$table', '$orderBy','$ip')";
                //echo $query;
                $results = mysql_query($aquery) or die(" ". mysql_error());
		require_once($webPage);
	}
?>