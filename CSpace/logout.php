<?php
                session_start();
                require_once("connect.php");
		$userID = $_SESSION['CSpace_userID'];
                date_default_timezone_set('America/New_York');
                $timestamp = time();
                $datetime = getdate();
                $date = date('Y-m-d', $datetime[0]);
                $time = date('H:i:s', $datetime[0]);
                $ip=$_SERVER['REMOTE_ADDR'];
		$projectID = $_SESSION['CSpace_projectID'];
		$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','logout','from_toolbar','$ip')";
		$aResults = mysql_query($aQuery) or die(" ". mysql_error());
		session_destroy();
		setcookie("CSpace_userID");
?>