<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="assets/css/style.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../assets/js/utilities.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Select a Project</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		require_once("../connect.php");
		$projectID = $_GET['projectID'];
		$query = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$title = $line['title'];
		$description = $line['description'];
		$startDate = $line['startDate'];
		$startTime = $line['startTime'];
		$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND access=1";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$uID = $line1['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$uID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$uName = $line1['username'];
?>
<table class="body" width=100%>
<?php
		echo "<tr><td colspan=2><table class=\"style1\">";
		//echo "<tr><td><span style=\"font-weight:bold\">$title</span></td><td align=right>&nbsp;&nbsp;&nbsp;&nbsp;<span style=\"color:green;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('selectProj.php?projectID=$projectID','content');loadElems();\">Select this project</span></td></tr>\n";
                if ($title=='')
                    $title = 'N/A';
                //echo "<tr><td><span style=\"font-weight:bold\">Project: </span><span>$title</span></td><td><span style=\"color:green;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('selectProj.php?projectID=$projectID','content');loadElems();\">Work on this Project</span></td></tr>\n";
                echo "<tr><td><span style=\"font-weight:bold\">Project: </span><span>$title</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href='editProject.php?projectID=$projectID'><img src=\"../../img/edit.jpg\" style=\"vertical-align:middle;width:15px;height:15px;border:0\" alt=\"Edit\" title=\"Edit\" /></a></td><td><span style=\"color:green;text-decoration:underline;cursor:pointer;\" onClick=\"javascript:window.document.location = 'selectProj.php?projectID=$projectID&projectTitle=$title'\">Work on this Project</span></td></tr>\n";
		echo "<tr><td colspan=2><font color=\"gray\">$description</font></td></tr>\n";
		echo "<tr><td colspan=2>Started on: $startDate, $startTime, Created by: $uName</td></tr>\n";
		echo "<tr><td colspan=2>Collaborators: ";
		$query = "SELECT * FROM memberships WHERE projectID='$projectID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$cUserID = $line['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$uName = $line1['firstName'] . " " . $line1['lastName'];
			echo "<a href='showCollaborator.php?userID=$cUserID'>$uName</a>, ";
		}
		echo "</td></tr><tr><td colspan=2><br/></td></tr>\n";
                if ($projectID==$_SESSION['CSpace_projectID'])
                    echo "<tr><td colspan=2><a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/etherpad.php\" style=\"font-weight:bold; color=brown\">Start editing this project's document</a><hr/></td></tr><tr><td colspan=2><br/></td></tr>\n";

		$query1 = "SELECT count(distinct url) as num FROM pages WHERE projectID='$projectID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$num1 = $line1['num'];
		$query1 = "SELECT count(distinct url) as num FROM pages WHERE projectID='$projectID' AND result=1";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$num2 = $line1['num'];
		$query1 = "SELECT count(*) as num FROM snippets WHERE projectID='$projectID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$num3 = $line1['num'];
		$query1 = "SELECT count(distinct url) as num FROM queries WHERE projectID='$projectID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$num4 = $line1['num'];

		echo "<tr><td>Webpages: $num1 viewed, $num2 bookmarked.</td></tr>\n";
		echo "<tr><td>Snippets collected: $num3.</td></tr>\n";
		echo "<tr><td>Searches done: $num4.</td></tr>\n";

		echo "</table>\n";
	}
?>

</body>
</html>
