<?php
        function insertAction($action, $value)
        {
            require_once("connect.php");
            date_default_timezone_set('America/New_York');
            $timestamp = time();
            $datetime = getdate();
            $date = date('Y-m-d', $datetime[0]);
            $time = date('H:i:s', $datetime[0]);
            $projectID = $_SESSION['CSpace_projectID'];
            $userID = $_SESSION['CSpace_userID'];
            $ip=$_SERVER['REMOTE_ADDR'];
            $query = "INSERT INTO actions (userID, projectID, timestamp, date, time, action, value, ip) VALUES ('$userID', '$projectID', '$timestamp', '$date', '$time', '$action', '$value','$ip')";
            //echo $query;
            $results = mysql_query($query) or die(" ". mysql_error());
            //mysql_close($dbh);
	}
?>