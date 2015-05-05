<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Connect with Information, Collaborate with People</title>

<LINK REL=StyleSheet HREF="assets/css/style2.css" TYPE="text/css" MEDIA=screen>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="assets/js/loadXMLDoc.js"></script>
<link rel="stylesheet" href="assets/css/ladda.min.css" type="text/css" />

<script type="text/javascript" src="assets/js/jquery.cookie.js"></script>
<script type="text/javascript" src="assets/js/main2.js"></script>

<script>
  document.write('<script src=assets/js/' +
  ('__proto__' in {} ? 'zepto' : 'jquery') +
  '.js><\/script>')
  </script>

  <script src="assets/js/foundation.min.js"></script>
  <script src="assets/js/foundation.reveal.js"></script>

  <script>
    $(document).foundation();
  </script>

<?php
  include('func.php');
  require_once('connect.php');
  $userID=2;
?>

<?php
	session_start();
  require_once('core/Connection.class.php');
  require_once('core/Base.class.php');
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
    $base = Base::getInstance();
		$userID = $base->getUserID();
    $connection = Connection::getInstance();

    $projectID = $base->getProjectID();
    $query = "SELECT * FROM users WHERE userID='$userID'";
    $results = $connection->commit($query);
    $line = mysql_fetch_array($results, MYSQL_ASSOC);
    $userName = $line['firstName'] . " " . $line['lastName'];
    $avatar = $line['avatar'];
    $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
    $points = $line['points'];
    $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
    $results = mysql_query($query) or die(" ". mysql_error());
    $line = mysql_fetch_array($results, MYSQL_ASSOC);
    $projectNums = $line['num'];
    $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
    $results = mysql_query($query) or die(" ". mysql_error());
    $line = mysql_fetch_array($results, MYSQL_ASSOC);
    $collabNums = $line['num'];
	}
?>

</head>

<body>

  <?php

    $displayMode='timeline';
    include('header.php');

  ?>

    <!-- Modal Content -->
    <div id="myModal" class="reveal-modal">
         <h1>Quickstart</h1>
         <div class="flex-video">
            <iframe width="640" height="480" src="//www.youtube.com/embed/JqNY7Xu46BY?rel=0" frameborder="0" allowfullscreen></iframe>
        </div>
         <a class="close-reveal-modal">&#215;</a>
         <input type="checkbox" onclick="validate()" id="dismiss" name="dismiss" /> Don't show this again.
         <button id="close-box" class="ladda-button blue expand-right">Close</button>
    </div>

<div id="container">
    <div id="box_left"></div>

    <div id="box_right">

    <div id="intro">
        <?php
        session_start();
        require_once('../connect.php');
        $userID = $_SESSION['CSpace_userID'];
        $projectID = $_SESSION['CSpace_projectID'];
        $query = "SELECT * FROM users WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $userName = $line['firstName'] . " " . $line['lastName'];
        $avatar = $line['avatar'];
        $lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
        $points = $line['points'];
        $query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $projectNums = $line['num'];
        $query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
        $results = mysql_query($query) or die(" ". mysql_error());
        $line = mysql_fetch_array($results, MYSQL_ASSOC);
        $collabNums = $line['num'];
        echo "<div id='speech'>Welcome, <span style=\"font-weight:bold\">$userName</span> to your <a href='index.php?projects=all&objects=all&years=all&months=all&formSubmit=Submit'>CSpace</a><br>&nbsp;&nbsp;Current login: $lastLogin<br>&nbsp;&nbsp;Points earned: <a href='points.php'>$points</a><br>&nbsp;&nbsp;You have <a href='projects.php?userID=$userID'>$projectNums projects</a> and <a href='collaborators.php?userID=1'>$collabNums collaborators</a></div>";
        //}
        //}
        ?>
        <div id="clearthis">
            <h3>Instructions</h3>
            <ul>
            <li>Click a thumbnail for details.</li>
            <li>Click on your profile picture in the top-right corner for the menu.</li>
            <li>Click on the checkbox below any thumbnail to access <a target="_blank" href="http://iris.infoseeking.org" target="_blank">IRIS</a> operators.</li>
            </ul>
        </div>
    </div>

    <div id="list">
        <h3>Search Analysis <a class="powered" href='http://iris.infoseeking.org/' target="_blank"><img src='assets/img/poweredbyIRIS_clear.png' width='70' height='40' alt='Powered by IRIS'></a></h3>
        <div id="options">

        <div id="method">
        <p>Operators:</p>
        <select id="opt">
            <option value=""></option>
            <option value="cluster">Grouping</option>
            <option value="summarize">Summarization</option>
            <option value="rank">Compare</option>
        </select>
        </div>

        <div id="group_opt">
        <p># of Groups:</p>
        <select id="groups">
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            <option value="6">6</option>
            <option value="7">7</option>
            <option value="8">8</option>
            <option value="9">9</option>
            <option value="10">10</option>
        </select>
        </div>

        <div id="summary_opt">
        <p>Length:</p>
        <select id="summary_length">
            <option value="50">Short</option>
            <option value="150">Medium</option>
            <option value="250">Long</option>
        </select>
        </div>

        <button class="ladda-button" data-style="expand-right" data-size="s" data-color="blue" id="go"><span class="ladda-label">Go</span></button>
        <button class="ladda-button" data-size="s" id="clear">Clear</button>
        </div>

        <div id="xml_response"></div>

        <div id="results"></div>
    </div>

    <div id="details"></div>

    </div>
</div>
<script type="text/javascript" src="assets/js/spin.min.js"></script>
<script type="text/javascript" src="assets/js/ladda.min.js"></script>
</body>
</html>
