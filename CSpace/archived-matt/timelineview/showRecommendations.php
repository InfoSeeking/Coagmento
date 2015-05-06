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

<?php include('../header.php'); ?>

<div id="container">
<h3>Recommend</h3>
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>
<table class="body" width=100%>
<?php
	require_once("../connect.php");

	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$query = "SELECT * FROM recommendations WHERE rUserID='$userID' ORDER BY timestamp";
		$results = mysql_query($query) or die(" ". mysql_error());
		while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$cUserID = $line['userID'];
			$cProjectID = $line['projectID'];
			$cTitle = $line['title'];
			$cURL = $line['url'];
			$cDate = $line['date'];
			$cMessage = stripslashes($line['message']);
			$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$cName = $line1['firstName'] . " " . $line1['lastName'];
			$cAvatar = $line1['avatar'];
			$query1 = "SELECT * FROM projects WHERE projectID='$cProjectID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$projTitle = $line1['title'];
			echo "<tr><td><a href=\"$cURL\" target=_external>$cTitle</a> on $cDate</td></tr>\n";
			echo "<tr><td>Recommended by <img src=\"../../img/$cAvatar\" height=30 width=30 style=\"vertical-align:middle;border:0\" /> <span style=\"font-weight:bold;\">$cName</span> for project <span style=\"font-weight:bold;\">$projTitle</span></td></tr>\n";
			echo "<tr><td><span style=\"color:gray;\">$cMessage</span></td></tr>\n";
		}
	}
}
?>
</div>

</body>
</html>
