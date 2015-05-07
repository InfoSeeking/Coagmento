<!doctype html>
<html>
<head>
<meta charset="utf-8" />
<title>Coagmento Coverflow</title>
  <link rel="stylesheet" href="../assets/css/style_coverflow.css" type="text/css" />
  <link rel="stylesheet" href="../assets/css/jquery.fancybox.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="../assets/css/imageflow.css" type="text/css" />
  <script type="text/javascript" src="../assets/js/jquery_1.6.1.js"></script>
  <script type="text/javascript" src="../assets/js/jquery.fancybox.pack.js"></script>
  <script type="text/javascript" src="../assets/js/imageflow.js"></script>
  <script type="text/javascript" src="../assets/js/main.js"></script>

<!-- This includes the ImageFlow CSS and JavaScript -->


<?php
    session_start();
    include('../services/func.php');
    require_once('../core/Connection.class.php');
    require_once('../core/Base.class.php');
    if (!isset($_SESSION['CSpace_userID'])) {
        echo "<div id='login'>Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.</div>";
    }
    else {
        $base = Base::getInstance();
        $connection = Connection::getInstance();
        $userID = $base->getUserID();
        $projectID = $base->getUserID();
        $query = "SELECT * FROM users WHERE userID='$userID'";
        $results = $connection->commit($query);
        $line = mysqli_fetch_array($results, MYSQL_ASSOC);
        $userName = $line['firstName'] . " " . $line['lastName'];
        $avatar = $line['avatar'];
        $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
        $points = $line['points'];
        $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
        $results = $connection->commit($query);
        $line = mysqli_fetch_array($results, MYSQL_ASSOC);
        $projectNums = $line['num'];
        $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
        $results = $connection->commit($query);
        $line = mysqli_fetch_array($results, MYSQL_ASSOC);
        $collabNums = $line['num'];
    }
?>

</head>
<body>

  <?php

    $displayMode='coverflow';
    include('../header.php');

  ?>

<div id="content"><?php require_once("extern.php");?></div>
</body>
</html>
