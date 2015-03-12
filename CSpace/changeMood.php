<?php
        session_start();
	require_once("connect.php");
        if (isset($_SESSION['CSpace_userID'])) {
            $userID = $_SESSION['CSpace_userID'];
            $projectID = $_SESSION['CSpace_projectID'];
            $value = $_GET['value'];
            date_default_timezone_set('America/New_York');
            $timestamp = time();
            $datetime = getdate();
            $ip=$_SERVER['REMOTE_ADDR'];
            $date = date('Y-m-d', $datetime[0]);
            $time = date('H:i:s', $datetime[0]);

                    $query = "INSERT INTO mood (userID, projectID, value, date, time, timestamp) VALUES('$userID','$projectID','$value','$date','$time','$timestamp')";
                    $results = mysql_query($query) or die(" ". mysql_error());

                    $aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','change_mood','$value','$ip')";
                    $aResults = mysql_query($aQuery) or die(" ". mysql_error());

                    $pQuery = "SELECT points FROM users WHERE userID='$userID'";
                    $pResults = mysql_query($pQuery) or die(" ". mysql_error());
                    $pLine = mysql_fetch_array($pResults, MYSQL_ASSOC);
                    $totalPoints = $pLine['points'];
                    $newPoints = $totalPoints+1;
                    $pQuery = "UPDATE users SET points=$newPoints WHERE userID='$userID'";

        }
//	fclose($fout);
	mysql_close($dbh);