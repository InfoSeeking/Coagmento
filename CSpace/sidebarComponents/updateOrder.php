<?php
	session_start();
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

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
    $ip=$_SERVER['REMOTE_ADDR'];
		Util::getInstance()->saveAction("updateOrder_$table","$orderBy",$base);
		require_once($webPage);
	}
?>
