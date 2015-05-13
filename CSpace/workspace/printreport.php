<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="assets/js/jquery-2.1.3.min.js"></script>
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<link type="text/css" href="assets/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	session_start();
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="container" class="container">
<h3>Print Reports</h3><br>

<?php

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
?>
<table class="body" width=100%>
	<?php

		$query = "SELECT title FROM projects WHERE projectID='$projectID'";
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$title = $line['title'];
		$query1 = "SELECT * FROM queries WHERE projectID='$projectID' AND status=1 ORDER BY timestamp";
		$results1 = $connection->commit($query1);
		$numSearches = mysqli_num_rows($results1);
		$query2 = "SELECT distinct url FROM pages WHERE projectID='$projectID' AND status=1";
		$results2 = $connection->commit($query2);
		$numPages = mysqli_num_rows($results2);
		$query3 = "SELECT distinct url FROM pages WHERE projectID='$projectID' AND result=1 AND status=1";
		$results3 = $connection->commit($query3);
		$numBookmarks = mysqli_num_rows($results3);
		$query4 = "SELECT * FROM snippets WHERE projectID='$projectID' AND status=1 ORDER BY timestamp";
		$results4 = $connection->commit($query4);
		$numSnippets = mysqli_num_rows($results4);
		$query5 = "SELECT * FROM annotations WHERE projectID='$projectID' AND status=1 ORDER BY timestamp";
		$results5 = $connection->commit($query5);
		$numAnnotations = mysqli_num_rows($results5);
	?>
	<tr><td>Displaying objects for project <span style="font-weight:bold"><?php echo $title?></span></td><td align=right><a href="javascript:void(0);" onClick="window.open('printObjects.php?objects=all', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print All</a></td></tr>
	<tr><td colspan=2><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wSearches');">Show/hide <span style="font-weight:bold"> <?php echo $numSearches;?> searches</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><a href="javascript:void(0);" onClick="window.open('printObjects.php?objects=queries', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Searches</a></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wSearches" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
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
					echo "<tr><td style=\"font-size:10px;\"> $userName &nbsp;&nbsp;</td><td style=\"font-size:10px;color:gray;\"> $date &nbsp;&nbsp;</td><td style=\"font-size:10px;\"> <a href=\"$url\" style=\"font-size:10px;\" target=_blank>$queryText</a> (<span style=\"color:green;font-size:10px;\">$source</span>) </td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wPages');">Show/hide <span style="font-weight:bold"> <?php echo $numPages;?> webpages</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><a href="javascript:void(0);" onClick="window.open('printObjects.php?objects=pages', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Webpages</a></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wPages" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				$query2 = "SELECT * FROM pages WHERE projectID='$projectID' AND status=1 GROUP BY url";
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
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wBookmarks');">Show/hide <span style="font-weight:bold"> <?php echo $numBookmarks;?> bookmarks</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><a href="javascript:void(0);" onClick="window.open('printObjects.php?objects=bookmarks', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Bookmarks</a></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wBookmarks" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
				$query3 = "SELECT * FROM pages WHERE projectID='$projectID' AND result=1 AND status=1 AND status=1 GROUP BY url";
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
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wSnippets');">Show/hide <span style="font-weight:bold"> <?php echo $numSnippets;?> snippets</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><a href="javascript:void(0);" onClick="window.open('printObjects.php?objects=snippets', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Snippets</a></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wSnippets" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
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
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<span style="cursor:pointer;"><div onclick="switchMenu('wAnnotations');">Show/hide <span style="font-weight:bold"> <?php echo $numAnnotations;?> annotations</span> for project <span style="font-weight:bold"><?php echo $title?></span></div></span>
		</td>
		<td align=right><a href="javascript:void(0);" onClick="window.open('printObjects.php?objects=annotations', 'Coagmento - Print Searches', 'width=450,height=450,scrollbars=yes');
">Print Annotations</a></td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="wAnnotations" style="display:none;text-align:left;font-size:11px;">
			<table>
			<?php
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
			?>
			</table>
			</div>
		</td>
	</tr>
</table>
<?php
	}
?>
</div>

</body>
</html>
