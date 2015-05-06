<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="../assets/css/style_timelineview.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<?php
  include('../func.php');
?>

<script type="text/javascript">
$(document).ready(function(){
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
});
</script>
</head>

<body>

<div id="container">
<h3>Projects</h3>
<?php
	session_start();
	ob_start();
	require_once("../connect.php");
	$pageName = "CSpace/timelineview/selectProject.php";

	if (isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$userID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$firstName = $line1['firstName'];
		$lastName = $line1['lastName'];
		if (isset($_GET['projectID'])) {
			$projectID = $_GET['projectID'];
			$_SESSION['projectID'] = $projectID;
			setcookie("CSpace_projectID", $projectID);
			echo "<br/><br/><center>\n<table class=\"body\">\n";
			echo "<tr bgcolor=#DDDDDD><td>Hello, <strong>$firstName $lastName</strong>.</td></tr>\n";
			$query = "SELECT * FROM projects WHERE projectID='$projectID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$title = $line['title'];
			echo "<tr><td>You have selected <strong>$title</strong> as your active project.<br/></td></tr>\n";
			echo "<tr><td>Once you activate <em>Coagmento</em> from your toolbar, the following things will be recorded:\n";
			echo "<ul><li>URLs you enter</li>\n<li>Queries you execute on search sites</li>\n<li>Any link you click on a webpage</li>\n";
			echo "</td></tr>\n";
			echo "<tr><td>You can also save a page, collect snippets, and make annotations to a page or snippets.<br/>These will all be saved under the project you have selected.</td></tr>\n";
			echo "<tr><td>You can access all these records by clicking on '<a href=\"log.php\">My logs</a>' on your <a href=\"index.php\">CSpace</a>.</td></tr>\n";
			echo "<tr><td>You can change your active project by visiting your <a href=\"index.php\">CSpace</a>.</td></tr>\n";
			echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
		}
		else {
			echo "<br/><br/><center>\n<table class=\"body\">\n";
			echo "<tr><td>Sorry. Looks like we had trouble knowing what project you want to work on!<br/>Please try <a href=\"index.php\">selecting this</a> again.</td></tr>\n";
			echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
		}
	}
	else {
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr><td>Sorry. Looks like we had trouble knowing who you are!<br/>Please try <a href=\"index.php\">logging in</a> again.</td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	}
?>
</div>

</body>
</html>
