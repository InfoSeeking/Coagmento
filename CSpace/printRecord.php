<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Coagmento</title>
<?php
	include('links_header.php');
?>
<body class="body">
<table class="table">
<?php
	require_once("connect.php");
	$pageName = "CSpace/printRecord.php";
	require_once("../counter.php");

	$userID = $_GET['userID'];
	$projectID = $_GET['projectID'];
	$pageID = $_GET['pageID'];

	$query = "SELECT * FROM pages WHERE pageID='$pageID'";
	$results = $connection->commit($query);
	$line = mysql_fetch_array($results, MYSQL_ASSOC);

	$query1 = "SELECT * FROM users WHERE userID='$userID'";
	$results1 = $connection->commit($query1);
	$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	$userName = $line1['username'];
	$avatar = $line1['avatar'];
	echo "<tr><td align=center><img src=\"../img/$avatar\" height=60 width=60 /><br/>$userName</td>";
	$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
	$results1 = $connection->commit($query1);
	$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	$title = $line1['title'];
	echo "<td>Project: <em>$title</em></td></tr>\n";

	if ($line['title'])
		$pageTitle = $line['title'];
	else
		$pageTitle = $line['url'];
	$url = $line['url'];
	$source = $line['source'];
	$queryText = $line['query'];
	$date = $line['date'];
	$time = $line['time'];
	$subText = "";
	$query1 = "SELECT * FROM snippets WHERE url='$url' AND projectID='$projectID'";
	$results1 = $connection->commit($query1);
	if (mysql_num_rows($results1)!=0) {
		$subText = "<em>Snippets:</em><font color=\"gray\"><ul>";
		while ($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
			$subText = $subText . "<li>" . $line1['snippet'];
			if ($line1['note'])
				$subText = $subText . " - <em>" . $line1['note']. "</em>";
			$subText = $subText . "</li>";
		}
		$subText = $subText . "</ul></font>";
	}
	$query1 = "SELECT note FROM annotations WHERE url='$url' AND projectID='$projectID'";
	$results1 = $connection->commit($query1);
	if (mysql_num_rows($results1)!=0) {
		$subText = "<em>Annotations:</em><font color=\"gray\"><ul>";
		while ($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
			$subText = $subText . "<li>" . $line1['note'] . "</li>";
		}
		$subText = $subText . "</ul></font>";
	}
	echo "<tr><td colspan=2><a href=\"$url\">$pageTitle</a></td></tr>\n";
	if ($queryText)
		echo "<tr><td colspan=2>Result of query \"<em>$queryText</em>\" on $source</td></tr>\n";
	echo "<tr><td><em>Viewing history:</em></td>";
	if ($line['result'])
		echo "<td align=right>Saved <img src=\"../img/check.gif\" height=20/></td></tr>\n";
	else
		echo "<td align=right>Unsaved</td></tr>\n";
	echo "<tr><td align=center><strong>User</strong></td><td align=center><strong>Time</strong></td></tr>\n";
	$query1 = "SELECT * FROM pages WHERE url='$url' AND projectID='$projectID'";
	$results1 = $connection->commit($query1);
	while ($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
		$userID = $line1['userID'];
		$date = $line1['date'];
		$time = $line1['time'];
		$query2 = "SELECT * FROM users WHERE userID='$userID'";
		$results2 = $connection->commit($query2);
		$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
		$userName = $line2['username'];
		echo "<tr><td align=center>$userName</td><td align=center>$date, $time</td></tr>\n";
	}
	if ($subText)
		echo "<tr><td colspan=2>$subText</td></tr>";

//	echo "<tr><td colspan=2>Status: first viewed on $date, $time";
//	echo "</td></tr>\n";
?>
</table>
</body>
</html>
