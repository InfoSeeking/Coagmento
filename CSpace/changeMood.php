<?php
        session_start();
        require_once('./core/Base.class.php');
      	require_once("./core/Connection.class.php");
        $base = Base::getInstance();
      	$connection = Connection::getInstance();

        if (isset($_SESSION['CSpace_userID'])) {
            $userID = $base->getUserID();
            $projectID = $base->getProjectID();
            $value = $_GET['value'];

            $ip=$_SERVER['REMOTE_ADDR'];
            $timestamp = $base->getTimestamp();
          	$date = $base->getDate();
          	$time = $base->getTime();

            $query = "INSERT INTO mood (userID, projectID, value, date, time, timestamp) VALUES('$userID','$projectID','$value','$date','$time','$timestamp')";
            $results = $connection->commit($query);

            $aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','change_mood','$value','$ip')";
            $aResults = $connection->commit($aQuery);

            $pQuery = "SELECT points FROM users WHERE userID='$userID'";
            $pResults = $connection->commit($pQuery);
            $pLine = mysql_fetch_array($pResults, MYSQL_ASSOC);
            $totalPoints = $pLine['points'];
            $newPoints = $totalPoints+1;
        }
