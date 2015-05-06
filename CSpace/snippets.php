<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	require_once("connect.php");
	$pageName = "CSpace/snippets.php";
	require_once("../counter.php");
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	if ($projectID == 0) {
		$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$projectID = $line['projectID'];
	}
?>
<html>
<head>
	<title>Snippets</title>
	<link href="assets/css/styles.css" rel="stylesheet" type="text/css" />
</head>
<body class="body">
<center>
<?php
	$url = $_GET['page'];
	$title = $_GET['title'];
	echo "Snippets from page: <a href=\"$url\">$title</a><br/><br/>\n";
?>
<table border=1 cellpadding=2 cellspacing=0 class="style3">
<?php
	if ($userID>0) {
		echo "<tr bgcolor=#DDDDDD><th>#</th><th>User</th><th>Time</th><th>Snippet</th></tr>\n";
		// Get the date, time, and timestamp
		$timestamp = time();
		$datetime = getdate();
	    $date = date('Y-m-d', $datetime[0]);
		$time = date('H:i:s', $datetime[0]);

		// Get the results for the given user and the project
		$query = "SELECT * FROM snippets WHERE projectID='$projectID' AND url='$url' ORDER BY timestamp";
		$results = $connection->commit($query);
		$ip=$_SERVER['REMOTE_ADDR'];
		Util::getInstance()->saveAction('view-snippets',"$url",$base);

		$count = 1;
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$sUserID = $line['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$sUserID'";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$userName = $line1['username'];
			$avatar = $line1['avatar'];

			$displayDate = $line['date'];
			$displayTime = $line['time'];
			$snippet = stripslashes($line['snippet']);
			$annotation = stripslashes($line['note']);
			echo "<tr><td>$count</td><td align=center><img src=\"../img/$avatar\" height=60 width=60 /><br/>$userName</td><td align=center>$displayDate<br/>$displayTime</td><td>$snippet<br/><font color=\"gray\">$annotation</font></td></tr>\n";
			$count++;
		}
	}
	else {
		echo "<tr><td>Your session is expired. Please login again.</td></tr>\n";
	}
?>
</table>
</center>
</body>
</html>
