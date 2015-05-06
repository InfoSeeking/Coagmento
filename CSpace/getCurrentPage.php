<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");

	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$userID = $_SESSION['CSpace_userID'];
	$query2 = "SELECT * FROM options WHERE userID='$userID' AND `option`='current-page'";
	$results2 = $connection->commit($query2);
	$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
	$value = $line2['value'];
	echo $value;
?>
