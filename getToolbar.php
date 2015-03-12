<?php
        session_start();
        require_once("connect.php");
        $url = "http://www.coagmento.org/toolbar/coagmento_3_8b.xpi";
        
        $userID = $_SESSION['CSpace_userID'];
        $projectID = $_SESSION['CSpace_projectID'];

        date_default_timezone_set('America/New_York');
        $timestamp = time();
        $datetime = getdate();
        $ip=$_SERVER['REMOTE_ADDR'];
        $date = date('Y-m-d', $datetime[0]);
        $time = date('H:i:s', $datetime[0]);

        $aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','toolbar download','$url','$ip')";
        $aResults = mysql_query($aQuery) or die(" ". mysql_error());

        header("Location: $url");


?>