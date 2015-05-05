<?php
	session_start();
?>
<div id="queriesBox" style="height:200px;overflow:auto;">
<?php
require_once('./core/Base.class.php');
require_once("./core/Connection.class.php");
$base = Base::getInstance();
$connection = Connection::getInstance();
	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $base->getUserID();
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $base->getProjectID();
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		echo "<span style=\"font-size:10px\">Sort by:</span> <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(0,'title');\">Title</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(0,'source');\">Source</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(0,'date');\">Date</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(0,'author');\">Author</span><hr/>\n";

		// Find out the preferences set by this user for this project.
		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='query-order'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$orderBy = $line['value'];
		if (!$orderBy)
			$orderBy = 'timestamp';
		if ($orderBy=='title')
			$orderBy = 'query';
		$maxPerPage = 6;
		$pageToGo = 'sidebarHistory.php?selectTab=0';
		$container = 'history';
		if (!isset($_GET['page']))
			$pageNum = 1;
		else
			$pageNum = $_GET['page'];

		$min = $pageNum*$maxPerPage-5;
		$max = $pageNum*$maxPerPage;

		if ($pageNum==1)
			$query = "SELECT * FROM queries WHERE projectID='$projectID' AND status=1 GROUP BY query,source ORDER BY $orderBy desc";
		else {
			$prevMin = ($pageNum-1)*$maxPerPage-5;
			$prevMax = ($pageNum-1)*$maxPerPage;
			$query = "SELECT queryID FROM queries WHERE projectID='$projectID' GROUP BY query,source ORDER BY $orderBy desc LIMIT $prevMin,$prevMax";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$minID = $line['noteID'];
			$query = "SELECT * FROM queries WHERE projectID='$projectID' ORDER BY $orderBy desc LIMIT $maxPerPage";
		}

		$query1 = "SELECT * FROM queries WHERE projectID='$projectID' AND status=1 GROUP BY query,source";

		echo "<table width=100%>\n";
		$results = $connection->commit($query);
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$queryID = $line['queryID'];
			$qUserID = $line['userID'];
			if ($userID==$qUserID)
				$color = '#FF7400';
			else
				$color = '#008C00';
			$query2 = "SELECT * FROM users WHERE userID='$qUserID'";
			$results2 = $connection->commit($query2);
			$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
			$userName = $line2['username'];
			$source = $line['source'];
			$queryText = $line['query'];
			$url = $line['url'];
			echo "<tr><td><span style=\"font-size:11px;color:$color\">$userName:</span></td><td><span style=\"font-size:10px\"> <font color=blue><a href=\"$url\" target=_content style=\"font-size:10px\" onclick=\"addAction('sidebar-query','$queryID');\">$queryText</a></font> (<font style=\"font-size:10px;color:green\">$source</font>)</span></td><td><a href=\"showQuerySnapshot.php?qID=$queryID\" target=_content><img src=\"../img/snapshot2.jpg\" style=\"vertical-align:bottom;border:0\" height=12 onclick=\"addAction('sidebar-query-snapshot','$queryID');\"/></a></td></tr>\n";
		}


		echo "</table>\n";
		echo "</div>\n";
	}
	else {
		echo "Your session has expired. Please <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
	mysql_close($dbh);
?>
