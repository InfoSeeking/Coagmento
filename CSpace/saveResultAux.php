<?php
	session_start();
        $ip=$_SERVER['REMOTE_ADDR'];
	require_once("connect.php");
	require_once("utiliFunctions.php");

	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	if ($userID) {
		$title = $_GET['title'];
                $source = $_GET['source'];
		$site = $_GET['site'];
		$queryString = $_GET['queryString'];
		$originalURL = $_GET['originalURL'];
		$rating = $_GET['rating'];
		$note = $_GET['annotation'];
		echo "<script>window.close()</script>";
		date_default_timezone_set('America/New_York');
		$timestamp = time();
		$datetime = getdate();
		$date = date('Y-m-d', $datetime[0]);
		$time = date('H:i:s', $datetime[0]);
		$query = "INSERT INTO pages VALUES('','$userID','$projectID','$originalURL','$title','$site','$queryString','$timestamp','$date','$time','1','1','$note',NULL)";
		$results = mysql_query($query) or die(" ". mysql_error());
                $lastID = mysql_insert_id();
		if ($rating != "")
		{
			//$aQuery = "SELECT max(pageID) as num FROM pages";
			//$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			//$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
			//$pageID = $aLine['num'];
			$queryRating = "INSERT INTO rating (`idResource`, `type`, `value`, `userID`, `projectID`, `active`,`time`,`date`,`timestamp`) VALUES ('$lastID', 'pages', '$rating', '$userID', '$projectID', '1','$time','$date','$timestamp')";
			$queryRatingResults = mysql_query($queryRating) or die(" ". mysql_error());
		}
                $aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','save-page','$lastID','$ip')";
                $aResults = mysql_query($aQuery) or die(" ". mysql_error());
								addPoints($userID,10);
                $pResults = mysql_query($pQuery) or die(" ". mysql_error());
	}
?>
