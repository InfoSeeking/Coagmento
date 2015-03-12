<?php
	session_start();
?>
<div id="docsBox" style="height:200px;overflow:auto;">
<?php
	require_once("connect.php");
	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		echo "<span style=\"font-size:10px\">Sort by:</span> <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(1,'title');\">Title</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(1,'source');\">Source</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(1,'date');\">Date</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(1,'author');\">Author</span><hr/>\n";
		
		// Find out the preferences set by this user for this project.
		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='docs-order'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$orderBy = $line['value'];
		if (!$orderBy)
			$orderBy = 'timestamp';
						
		echo "<table width=100%>\n";
		$query = "SELECT * FROM pages WHERE projectID='$projectID' AND result=1 AND status=1 GROUP BY url ORDER BY $orderBy";
		$results = mysql_query($query) or die(" ". mysql_error());
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$qUserID = $line['userID'];
			if ($userID==$qUserID)
				$color = '#FF7400';
			else
				$color = '#008C00';
			$query1 = "SELECT * FROM users WHERE userID='$qUserID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$userName = $line1['username'];
			$title = $line['title'];
			$url = $line['url'];
			if (!$title)
				$title = $url;
			$originalTitle = $title;
			$title = substr($title, 0, 25);
			if (strlen($originalTitle)>25)
				$title = $title . '..';
			echo "<tr><td><span style=\"font-size:10px;color:$color\">$userName:</span></td><td><span style=\"font-size:10px\"><font color=blue><a href=\"$url\" target=_content style=\"font-size:10px\" onclick=\"addAction('sidebar-page','$url');\">$title</a></font></span></td></tr>\n";
		}
		echo "</table>\n";
	}
	else {
		echo "Your session has expired. Please <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
	mysql_close($dbh);
?>
</div>
