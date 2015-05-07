<?php
	session_start();
        $ip=$_SERVER['REMOTE_ADDR'];
	require_once("connect.php");
	require_once("utiliFunctions.php");
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

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
		$results = $connection->commit($query);
    $lastID = mysql_insert_id();
		if ($rating != "")
		{
			$queryRating = "INSERT INTO rating (`idResource`, `type`, `value`, `userID`, `projectID`, `active`,`time`,`date`,`timestamp`) VALUES ('$lastID', 'pages', '$rating', '$userID', '$projectID', '1','$time','$date','$timestamp')";
			$queryRatingResults = $connection->commit($queryRating);
		}

								Util::getInstance()->saveAction('save-page',"$lastID",$base);
								addPoints($userID,10);
	}
?>
