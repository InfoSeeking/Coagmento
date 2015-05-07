<?php
	session_start();
	require_once("connect.php");
	echo "<table class=\"style3\" width=100%>\n";
	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$query = "SELECT * FROM actions WHERE projectID='$projectID' AND userID!='$userID' AND value!='' AND (action='save' OR action='save' OR action='save-snippet' OR action='query') ORDER BY timestamp desc LIMIT 4";
		$results = $connection->commit($query);
		if (mysql_num_rows($results)==0)
			echo "No notifications available.";
		else {
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$aUserID = $line['userID'];
				$query1 = "SELECT * FROM users WHERE userID='$aUserID'";
				$results1 = $connection->commit($query1);
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$userName = $line1['username'];
				$action = $line['action'];
				$value = $line['value'];
				$date = $line['date'];
				$time = $line['time'];
				switch ($action) {
					case 'page':
						$query2 = "SELECT * FROM pages WHERE pageID='$value'";
						$results2 = $connection->commit($query2);
						$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
						$originalTitle = stripslashes($line2['title']);
						$url = $line2['url'];
						$dispTitle = substr($originalTitle, 0, 45);
						if (strlen($originalTitle)>45)
							$dispTitle = $dispTitle . '..';
						$dispAction = "<span style=\"font-size:8px;color:green\">($date, $time):</span> <span style=\"font-size:10px\">viewed</span> <a href=\"$url\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$dispTitle</a>";
						break;
					case 'query':
					case 'sidebar-query':
					case 'sidebar-query-snapshot':
						$query2 = "SELECT * FROM queries WHERE queryID='$value'";
						$results2 = $connection->commit($query2);
						$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
						$originalTitle = stripslashes($line2['title']);
						$url = $line2['url'];
						$qText = $line2['query'];
						$dispAction = "<span style=\"font-size:8px;color:green\">($date, $time):</span> <span style=\"font-size:10px\">searched:</span> <a href=\"$url\" onclick=\"javascript:ajaxpage('sidebarComponents/insertAction.php?action=sidebar-query-notification&value='+$value,null)\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$qText</a>";
						break;
					case 'save-snippet':
						$query2 = "SELECT * FROM snippets WHERE snippetID='$value'";
						$results2 = $connection->commit($query2);
						$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
						//$originalTitle = stripslashes($line2['snippet']);
                                                $originalTitle = stripslashes($line2['note']);
                                                if ($originalTitle=="")
                                                    $originalTitle = stripslashes($line2['snippet']);
						$url = $line2['url'];
						$dispTitle = substr($originalTitle, 0, 11);
						if (strlen($originalTitle)>8)
							$dispTitle = $dispTitle . '..';
						$dispTitle = "a snippet (".$dispTitle.")";
                                                $viewSnipetOnWindow = "window.open('sidebarComponents/viewSnippet.php?value=$value&action=show_snippet_notification','Snippet View','statusbar=0,menubar=0,resizable=yes,scrollbars=yes,width=600,height=550,left=600')";
						$dispAction = "<span style=\"font-size:8px;color:green\">($date, $time):</span> <span style=\"font-size:10px\">saved </span> <a class=\"cursorType\" onclick=\"javascript:$viewSnipetOnWindow\" style=\"font-size:10px;color:blue;text-decoration:underline;\">$dispTitle</a>";
//				//		$dispAction = "<span style=\"font-size:8px;color:green\">($date, $time):</span> <span style=\"font-size:10px\">saved $dispTitle</a>";
						break;
					case 'save':
						$query2 = "SELECT * FROM pages WHERE url='$value'";
//						echo "$query2";
						$results2 = $connection->commit($query2);
						$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
						$originalTitle = stripslashes($line2['title']);
						$url = $line2['url'];
						$dispTitle = substr($originalTitle, 0, 14);
						if (strlen($originalTitle)>14)
							$dispTitle = $dispTitle . '...';
						//$dispTitle = "a page";
                                                $dispAction = "<span style=\"font-size:8px;color:green\">($date, $time):</span> <span style=\"font-size:10px\">bookmarked </span> <a href=\"$url\" onclick=\"javascript:ajaxpage('sidebarComponents/insertAction.php?action=sidebar-page-notification&value='+$value,null)\" target=_content style=\"font-size:10px;color:blue;text-decoration:underline;\">$dispTitle</a>";
						//$dispAction = "<span style=\"font-size:8px;color:green\">($date, $time):</span> <span style=\"font-size:10px\">bookmarked $dispTitle</a>";

				}
				if ($dispAction)
					echo "<tr><td><span style=\"font-size:10px;color:#4096EE\">$userName</span></td><td>$dispAction</span></td></tr>\n";
			}
		}
	}
	else
		echo "<tr><td>No notifications available.</td></tr>\n";
	echo "</table>\n";
?>
