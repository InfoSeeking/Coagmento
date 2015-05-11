<?php
	session_start();
	require_once('../core/Connection.class.php');
	require_once('../core/Base.class.php');
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $base->getUserID();
		$projectID = $base->getProjectID();
		$query = "SELECT title FROM projects WHERE projectID='$projectID'";
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$title = $line['title'];
		$objects = $_GET['objects'];
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="Coagmento icon" type="image/x-icon" href="../assets/img/favicon.ico">
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<body class="body">
<table class="body">
		<tr><td style="font-size:12px;"><b>Objects from project <span style="font-weight:bold;font-size:12px;"><?php echo $title?></span></b></td><td align=right><a href="javascript:void(0);" onClick="services/addAction('print','<?php echo $objects;?>');window.print();">Print</a> <a href="javascript:void(0);" onClick="window.close();">Close</a></td></tr>
		<tr><td colspan=2><hr/></td></tr>
<?
	switch ($objects) {
		case 'queries':
			$query1 = "SELECT * FROM queries WHERE projectID='$projectID' ORDER BY timestamp";
			$results1 = $connection->commit($query1);
			$numSearches = mysqli_num_rows($results1);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numSearches</span> searches for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			while ($line1 = mysqli_fetch_array($results1, MYSQL_ASSOC)) {
				$queryText = $line1['query'];
				$source = $line1['source'];
				$url = $line1['url'];
				$date = $line1['date'];
				$cUserID = $line1['userID'];
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:11px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:11px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:11px;\"> <a href=\"$url\" style=\"font-size:11px;\" target=_blank>$queryText</a> (<span style=\"color:green;font-size:11px;\">$source</span>) </td></tr>\n";
			}
			echo "</table></td></tr>\n";
			break;

		case 'pages':
			$query2 = "SELECT distinct url FROM pages WHERE projectID='$projectID'";
			$results2 = $connection->commit($query2);
			$numPages = mysqli_num_rows($results2);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numPages</span> webpages visited for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			$query2 = "SELECT * FROM pages WHERE projectID='$projectID' GROUP BY url";
			$results2 = $connection->commit($query2);
			while ($line2 = mysqli_fetch_array($results2, MYSQL_ASSOC)) {
				$url = $line2['url'];
				$pTitle = $line2['title'];
				$source = $line2['source'];
				$date = $line2['date'];
				$cUserID = $line2['userID'];
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$pTitle</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
			}
			echo "</table></td></tr>\n";
			break;

		case 'bookmarks':
			$query3 = "SELECT distinct url FROM pages WHERE projectID='$projectID' AND result=1";
			$results3 = $connection->commit($query3);
			$numBookmarks = mysqli_num_rows($results3);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numBookmarks</span> webpages bookmarked for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			$query3 = "SELECT * FROM pages WHERE projectID='$projectID' AND result=1 GROUP BY url";
			$results3 = $connection->commit($query3);
			while ($line3 = mysqli_fetch_array($results3, MYSQL_ASSOC)) {
				$url = $line3['url'];
				$pTitle = $line3['title'];
				$source = $line3['source'];
				$date = $line3['date'];
				$cUserID = $line3['userID'];
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$pTitle</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
			}
			echo "</table></td></tr>\n";
			break;

		case 'snippets':
			$query4 = "SELECT * FROM snippets WHERE projectID='$projectID' ORDER BY timestamp";
			$results4 = $connection->commit($query4);
			$numSnippets = mysqli_num_rows($results4);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numSnippets</span> snippets collected for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			while ($line4 = mysqli_fetch_array($results4, MYSQL_ASSOC)) {
				$url = $line4['url'];
				$cUserID = $line4['userID'];
				$date = $line4['date'];
				$snippet = stripslashes($line4['snippet']);
				$note = stripslashes($line4['note']);
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\">$userName</td><td>&nbsp;&nbsp;</td><td><a href=\"$url\"  style=\"font-size:10px;\" target=_blank>$url</a></td></tr>\n";
				echo "<tr><td style=\"font-size:10px;color:gray;\">$date</td><td>&nbsp;&nbsp;</td><td style=\"font-size:10px;\">$snippet<br/><span style=\"color:gray;\">$note</span></td></tr>\n";
			}
			echo "</table></td></tr>\n";
			break;

		case 'annotations':
			$query5 = "SELECT * FROM annotations WHERE projectID='$projectID' ORDER BY timestamp";
			$results5 = $connection->commit($query5);
			$numAnnotations = mysqli_num_rows($results5);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numAnnotations</span> annotations done for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			while ($line5 = mysqli_fetch_array($results5, MYSQL_ASSOC)) {
				$url = $line5['url'];
				$cUserID = $line5['userID'];
				$date = $line5['date'];
				$note = stripslashes($line5['note']);
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\">$userName</td><td>&nbsp;&nbsp;</td><td><a href=\"$url\"  style=\"font-size:10px;\" target=_blank>$url</a></td></tr>\n";
				echo "<tr><td style=\"font-size:10px;color:gray;\">$date</td><td>&nbsp;&nbsp;</td><td style=\"font-size:10px;\">$note</td></tr>\n";
			}
			echo "</table></td></tr>\n";
			break;

		default:
			$query1 = "SELECT * FROM queries WHERE projectID='$projectID' ORDER BY timestamp";
			$results1 = $connection->commit($query1);
			$numSearches = mysqli_num_rows($results1);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numSearches</span> searches for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			while ($line1 = mysqli_fetch_array($results1, MYSQL_ASSOC)) {
				$queryText = $line1['query'];
				$source = $line1['source'];
				$url = $line1['url'];
				$date = $line1['date'];
				$cUserID = $line1['userID'];
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:11px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:11px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:11px;\"> <a href=\"$url\" style=\"font-size:11px;\" target=_blank>$queryText</a> (<span style=\"color:green;font-size:11px;\">$source</span>) </td></tr>\n";
			}
			echo "</table></td></tr>\n";

			echo "<tr><td><br/></td></tr>\n";

			$query2 = "SELECT distinct url FROM pages WHERE projectID='$projectID'";
			$results2 = $connection->commit($query2);
			$numPages = mysqli_num_rows($results2);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numPages</span> webpages visited for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			$query2 = "SELECT * FROM pages WHERE projectID='$projectID' GROUP BY url";
			$results2 = $connection->commit($query2);
			while ($line2 = mysqli_fetch_array($results2, MYSQL_ASSOC)) {
				$url = $line2['url'];
				$pTitle = $line2['title'];
				$source = $line2['source'];
				$date = $line2['date'];
				$cUserID = $line2['userID'];
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$pTitle</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
			}
			echo "</table></td></tr>\n";

			echo "<tr><td><br/></td></tr>\n";

			$query3 = "SELECT distinct url FROM pages WHERE projectID='$projectID' AND result=1";
			$results3 = $connection->commit($query3);
			$numBookmarks = mysqli_num_rows($results3);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numBookmarks</span> webpages bookmarked for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			$query3 = "SELECT * FROM pages WHERE projectID='$projectID' AND result=1 GROUP BY url";
			$results3 = $connection->commit($query3);
			while ($line3 = mysqli_fetch_array($results3, MYSQL_ASSOC)) {
				$url = $line3['url'];
				$pTitle = $line3['title'];
				$source = $line3['source'];
				$date = $line3['date'];
				$cUserID = $line3['userID'];
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$pTitle</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
			}
			echo "</table></td></tr>\n";

			echo "<tr><td><br/></td></tr>\n";

			$query4 = "SELECT * FROM snippets WHERE projectID='$projectID' ORDER BY timestamp";
			$results4 = $connection->commit($query4);
			$numSnippets = mysqli_num_rows($results4);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numSnippets</span> snippets collected for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			while ($line4 = mysqli_fetch_array($results4, MYSQL_ASSOC)) {
				$url = $line4['url'];
				$cUserID = $line4['userID'];
				$date = $line4['date'];
				$snippet = stripslashes($line4['snippet']);
				$note = stripslashes($line4['note']);
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\">$userName</td><td>&nbsp;&nbsp;</td><td><a href=\"$url\"  style=\"font-size:10px;\" target=_blank>$url</a></td></tr>\n";
				echo "<tr><td style=\"font-size:10px;color:gray;\">$date</td><td>&nbsp;&nbsp;</td><td style=\"font-size:10px;\">$snippet<br/><span style=\"color:gray;\">$note</span></td></tr>\n";
			}
			echo "</table></td></tr>\n";

			echo "<tr><td><br/></td></tr>\n";

			$query5 = "SELECT * FROM annotations WHERE projectID='$projectID' ORDER BY timestamp";
			$results5 = $connection->commit($query5);
			$numAnnotations = mysqli_num_rows($results5);
			echo "<tr><td colspan=2 style=\"font-size:12px;color:gray;\">There were total <span style=\"font-weight:bold;\">$numAnnotations</span> annotations done for this project.</td></tr>\n";
			echo "<tr><td colspan=2><table>\n";
			while ($line5 = mysqli_fetch_array($results5, MYSQL_ASSOC)) {
				$url = $line5['url'];
				$cUserID = $line5['userID'];
				$date = $line5['date'];
				$note = stripslashes($line5['note']);
				$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
				$resultsU = $connection->commit($queryU);
				$lineU = mysqli_fetch_array($resultsU, MYSQL_ASSOC);
				$userName = $lineU['username'];
				echo "<tr><td style=\"font-size:10px;\">$userName</td><td>&nbsp;&nbsp;</td><td><a href=\"$url\"  style=\"font-size:10px;\" target=_blank>$url</a></td></tr>\n";
				echo "<tr><td style=\"font-size:10px;color:gray;\">$date</td><td>&nbsp;&nbsp;</td><td style=\"font-size:10px;\">$note</td></tr>\n";
			}
			echo "</table></td></tr>\n";

			break;
	}
?>
</table>
</body>
</html>
<?php
	}
?>
