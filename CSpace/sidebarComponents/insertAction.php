<?php
	require_once("../connect.php");
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();
	session_start();
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
  $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	$projectID = $_SESSION['CSpace_projectID'];
	$userID = $_SESSION['CSpace_userID'];
  $ip=$_SERVER['REMOTE_ADDR'];
	$action = $_GET['action'];
 	$value = $_GET['value'];
	Util::getInstance()->saveAction("$action","$value",$base);
  addPoints($userID,5);
?>
