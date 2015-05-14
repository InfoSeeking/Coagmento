<?php
session_start();
require_once('../core/Base.class.php');
require_once("../core/Connection.class.php");
require_once("../core/Util.class.php");
$base = Base::getInstance();
$connection = Connection::getInstance();

if (isset($_SESSION['CSpace_userID'])) {
  $userID = $base->getUserID();
  $projectID = $base->getProjectID();
  $ip=$base->getIP();
  $timestamp = $base->getTimestamp();
  $date = $base->getDate();
  $time = $base->getTime();

  $value = $_GET['value'];

  $query = "INSERT INTO mood (userID, projectID, value, date, time, timestamp) VALUES('$userID','$projectID','$value','$date','$time','$timestamp')";
  $results = $connection->commit($query);
  Util::getInstance->saveAction('change_mood',"$value",$base);
}

?>
