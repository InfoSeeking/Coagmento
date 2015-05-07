<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if ((isset($_SESSION['CSpace_userID']))) {
		$type = $_GET['type'];
		$itemID = $_GET['itemID'];
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		$value = $_GET['value'];
		require_once("../connect.php");
		$query1 = "UPDATE rating SET `active`='0' WHERE `idResource`='$itemID' AND `type`='$type' AND `active`='1' AND `userID`='$userID' AND `projectID`='$projectID'";
		$results = $connection->commit($query1);
		date_default_timezone_set('America/New_York');
		$timestamp = time();
		$datetime = getdate();
		$date = date('Y-m-d', $datetime[0]);
		$time = date('H:i:s', $datetime[0]);
		$query2 = "INSERT INTO rating (`idResource`, `type`, `value`, `userID`, `projectID`, `active`, `time`, `date`, `timestamp`) VALUES ('$itemID', '$type', '$value', '$userID', '$projectID', '1', '$time', '$date', '$timestamp')";
		$results = $connection->commit($query2);
		$webPage = $_GET['webPage'];
    $ip=$_SERVER['REMOTE_ADDR'];
		Util::getInstance()->saveAction("updateRating_$type","itemID:$itemID:$value",$base);

    $pQuery = "SELECT points FROM users WHERE userID='$userID'";
    $pResults = mysql_query($pQuery) or die(" ". mysql_error());
    $pLine = mysqli_fetch_array($pResults, MYSQL_ASSOC);
    $totalPoints = $pLine['points'];
    $newPoints = $totalPoints+10;
    $pQuery = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
    $pResults = mysql_query($pQuery) or die(" ". mysql_error());

    if ($webPage!="")
        require_once($webPage);
	}
?>
