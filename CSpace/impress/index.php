<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1024" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>Coagmento 3D</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:regular,semibold,italic,italicsemibold|PT+Sans:400,700,400italic,700italic|PT+Serif:400,700,400italic,700italic" rel="stylesheet" />
    <link rel="stylesheet" href="css/jquery_impress.fancybox.css" type="text/css" media="screen" />
    <link href="../assets/css/impress-demo.css" rel="stylesheet" />
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	  <script type="text/javascript" src="js/jquery_impress.fancybox.pack.js"></script>
    <script type="text/javascript" src="../assets/js/main_imageflow.js"></script>

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
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $userName = $line['firstName'] . " " . $line['lastName'];
        $avatar = $line['avatar'];
        $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
        $points = $line['points'];
        $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
        $results = $connection->commit($query);
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $projectNums = $line['num'];
        $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
        $results = $connection->commit($query);
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $collabNums = $line['num'];
    }
?>

</head>
<body class="impress-not-supported">

<?php

  $displayMode='3D';
  include('../header.php');

?>

<div class="fallback-message">
    <p>Your browser <b>doesn't support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>
    <p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>
</div>

<button id="prev"></button><button id="next"></button>


<div id="impress"></div>




<script type="text/javascript">

$("#next").click(function () {
	impress().next();
});

$("#prev").click(function () {
	impress().prev();
});

</script>

</body>
</html>
