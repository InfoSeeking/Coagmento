<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('links_header.php');
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('services/func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Updates</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "<br/><br/>Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>
<script type="text/javascript" src="assets/js/utilities.js"></script>

<?php
	require_once("connect.php");
	$userID = $_SESSION['CSpace_userID'];
	$query = "SELECT * FROM users WHERE userid='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$lastActionTimestamp = $line['lastActionTimestamp'];
?>
<table class="body" width=100%>
	<tr><td><span style="font-weight:bold;">Actions by your collaborators since your last login:</span></td></tr>
	<tr><td><ul>
	<?php
		$query1 = "SELECT lastActionTimestamp FROM users WHERE userID='$userID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$lastActionTimestamp = $line1['lastActionTimestamp'];

		$query4 = "SELECT * FROM memberships,actions WHERE actions.projectID=memberships.projectID AND actions.userID!='$userID' and memberships.userID='$userID' AND actions.timestamp>='$lastActionTimestamp'";
		$results4 = mysql_query($query4) or die(" ". mysql_error());
		while ($line4 = mysql_fetch_array($results4, MYSQL_ASSOC)) {
			$cUserID = $line4['userID'];
			$cProjID = $line4['projectID'];
			$cDate = $line4['date'];
			$cTime = $line4['time'];
			$cAction = $line4['action'];
			$cValue = $line4['value'];
			$query3 = "SELECT * FROM users WHERE userID='$cUserID'";
			$results3 = mysql_query($query3) or die(" ". mysql_error());
			$line3 = mysql_fetch_array($results3, MYSQL_ASSOC);
			$cUserName = $line3['firstName'] . " " . $line3['lastName'];
			$query3 = "SELECT title FROM projects WHERE projectID='$cProjID'";
			$results3 = mysql_query($query3) or die(" ". mysql_error());
			$line3 = mysql_fetch_array($results3, MYSQL_ASSOC);
			$projTitle = $line3['title'];

			switch ($cAction) {
				case 'page':
					$query2 = "SELECT * FROM pages WHERE pageID='$cValue'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
					$originalTitle = stripslashes($line2['title']);
					$url = $line2['url'];
					$dispAction = "viewed <a href=\"$url\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$originalTitle</a>";
					break;
				case 'query':
					$query2 = "SELECT * FROM queries WHERE queryID='$cValue'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
					$originalTitle = stripslashes($line2['title']);
					$url = $line2['url'];
					$dispAction = "searched: <a href=\"$url\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$originalTitle</a>";
					break;
				case 'save-snippet':
					$query2 = "SELECT * FROM snippets WHERE snippetID='$cValue'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
					$originalTitle = stripslashes($line2['snippet']);
					$url = $line2['url'];
					$dispAction = "saved: <a href=\"$url\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$originalTitle</a>";
					break;
				case 'save':
					$query2 = "SELECT * FROM pages WHERE url='$cValue'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
					$originalTitle = stripslashes($line2['title']);
					$url = $line2['url'];
					$dispAction = "bookmarked: <a href=\"$url\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$originalTitle</a>";
			}

			echo "<li>$cDate, $cTime: <span style=\"font-weight:bold;\">$cUserName</span> $dispAction ($projTitle).</li>\n";
		}
	?>
	</ul></td></tr>
</table>
<?php
	}
?>

</body>
</html>
