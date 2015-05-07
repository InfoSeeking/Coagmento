<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<script type="text/javascript" src="../assets/js/utilities.js"></script>
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('../services/func.php');
?>
</head>

<body>
	<?php require("views/header.php"); ?>
<div id="container">
<h3>Inter-project Analysis</h3>

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
		$userID = $_SESSION['CSpace_userID'];
?>

<table class="body" width=100%>
	<?php


		// Find collaborators that are in multiple projects
		$query1 = "SELECT mem2.*,count(*) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' group BY mem2.userID";
		$results1 = $connection->commit($query1);
		$commonCollab = 0;
		while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
			$num = $line1['num'];
			if ($num>1)
				$commonCollab++;
		}

		// Find queries that are in multiple projects
		$query2 = "select count(*) as num from queries as q1,queries as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.query=q2.query and q1.projectID!=q2.projectID group by q1.query,q2.query";
		$results2 = $connection->commit($query2);
		$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
		$commonSearches = $line2['num'];

		// Find webpages that are in multiple projects
		$query3 = "select * from pages as q1,pages as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.url=q2.url and q1.projectID!=q2.projectID and q1.title!='Coagmento' and q1.url!='about:blank' group by q1.url,q2.url";
		$results3 = $connection->commit($query3);
		$commonPages = mysql_num_rows($results3);

		// Find bookmarks that are in multiple projects
		$query4 = "select * from pages as q1,pages as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.url=q2.url and q1.projectID!=q2.projectID and q1.title!='Coagmento' and q1.url!='about:blank' and q1.result=1 group by q1.url,q2.url";
		$results4 = $connection->commit($query4);
		$commonBookmarks = mysql_num_rows($results4);
	?>
	<tr>
		<td><span style="font-weight:bold">Common Collaborators</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cCollab');">You have <span style="font-weight:bold"><?php echo $commonCollab;?></span> collaborators in multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cCollab" style="display:none;text-align:left;font-size:11px;">
			<?php
				$results1 = $connection->commit($query1);
				while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
					$num = $line1['num'];
					if ($num>1) {
						$cUserID = $line1['userID'];
						$queryU = "SELECT * FROM users WHERE userID='$cUserID'";
						$resultsU = $connection->commit($queryU);
						$lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC);
						$userName = $lineU['firstName'] . " " . $lineU['lastName'];
						$avatar = $lineU['avatar'];
						echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"../../img/$avatar\" width=20 height=20 /> <a href='showCollaborator.php?userID=$cUserID'>$userName</a> <font color=\"gray\"> for projects</font>: ";
						$queryU = "SELECT mem2.* FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' AND mem2.userID='$cUserID'";
						$resultsU = $connection->commit($queryU);
						while ($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)) {
							$cProjectID = $lineU['projectID'];
							$queryP = "SELECT access FROM memberships WHERE projectID='$cProjectID' AND userID='$userID'";
							$resultsP = $connection->commit($queryP);
							$lineP = mysql_fetch_array($resultsP, MYSQL_ASSOC);
							$access = $lineP['access'];
							$queryQ = "SELECT title FROM projects WHERE projectID='$cProjectID'";
							$resultsQ = $connection->commit($queryQ);
							$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
							echo $lineQ['title'];
							if ($access==1)
								echo " <a href='collaborators.php?remove=$cUserID&projID=$cProjectID' style='color: #FF0000; text-decoration: none; font-weight: bold; font-size: 14px;'>X</a>";
							echo ", ";
						}
						echo "<br/>";
					}
				}
			?>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
		<tr>
		<td><span style="font-weight:bold">Common Searches</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cSearches');">You have <span style="font-weight:bold"><?php echo $commonSearches;?></span> searches that appear in multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cSearches" style="display:none;text-align:left;font-size:11px;">
			<?php
				$query2 = "select q1.query from queries as q1,queries as q2 where q1.userID='$userID' and q2.userID='$userID' and q1.userID=q2.userID and q1.query=q2.query and q1.projectID!=q2.projectID GROUP BY query";
				$results2 = $connection->commit($query2);
				while($line2 = mysql_fetch_array($results2, MYSQL_ASSOC)) {
					$queryText = $line2['query'];
					echo "$queryText: ";
					$queryU = "SELECT * FROM queries WHERE query='$queryText' AND userID='$userID' GROUP BY projectID";
					$resultsU = $connection->commit($queryU);
					while($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)){
						$pID = $lineU['projectID'];
						$source = $lineU['source'];
						$url = $lineU['url'];
						$queryQ = "SELECT title FROM projects WHERE projectID='$pID'";
						$resultsQ = $connection->commit($queryQ);
						$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
						echo $lineQ['title']. " (<a href=\"$url\" style=\"color:green;text-decoration:underline;cursor:pointer;font-size:11px;\" target=_blank>". $source."</a>), ";
					}
					echo "<br/>";
				}
			?>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
		<tr>
		<td><span style="font-weight:bold">Common Webpages</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cPages');">You have <span style="font-weight:bold"><?php echo $commonPages;?></span> webpages that you have visited for multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cPages" style="display:none;text-align:left;font-size:11px;">
			<table>
			<tr><td><span style="font-weight:bold;font-size:11px;">Webpage</span></td><td><span style="font-weight:bold;font-size:11px;">Projects</span></td></tr>
			<?php
				while($line3 = mysql_fetch_array($results3, MYSQL_ASSOC)) {
					$title = $line3['title'];
					$url = $line3['url'];
					echo "<tr><td><a style=\"font-size:11px;\" href=\"$url\" target=_blank>$title</a></td><td><span style=\"font-size:11px;\">";
					$queryU = "SELECT * FROM pages WHERE url='$url' AND userID='$userID' GROUP BY projectID";
					$resultsU = $connection->commit($queryU);
					while($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)) {
						$pID = $lineU['projectID'];
						$queryQ = "SELECT title FROM projects WHERE projectID='$pID'";
						$resultsQ = $connection->commit($queryQ);
						$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
						$pTitle = $lineQ['title'];
						echo "$pTitle, ";
					}
					echo "</span></td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
	<tr>
		<td><span style="font-weight:bold">Common Bookmarks</span>
		<span style="cursor:pointer;"><div onclick="switchMenu('cBookmarks');">You have <span style="font-weight:bold"><?php echo $commonBookmarks;?></span> bookmarks in multiple projects. Click here to expand or collapse them.</div></span>
			<div id="cBookmarks" style="display:none;text-align:left;font-size:11px;">
			<table>
			<tr><td><span style="font-weight:bold;font-size:11px;">Webpage</span></td><td><span style="font-weight:bold;font-size:11px;">Projects</span></td></tr>
			<?php
				while($line4 = mysql_fetch_array($results4, MYSQL_ASSOC)) {
					$title = $line4['title'];
					$url = $line4['url'];
					echo "<tr><td><a style=\"font-size:11px;\" href=\"$url\" target=_blank>$title</a></td><td><span style=\"font-size:11px;\">";
					$queryU = "SELECT * FROM pages WHERE url='$url' AND userID='$userID' GROUP BY projectID";
					$resultsU = $connection->commit($queryU);
					while($lineU = mysql_fetch_array($resultsU, MYSQL_ASSOC)) {
						$pID = $lineU['projectID'];
						$queryQ = "SELECT title FROM projects WHERE projectID='$pID'";
						$resultsQ = $connection->commit($queryQ);
						$lineQ = mysql_fetch_array($resultsQ, MYSQL_ASSOC);
						$pTitle = $lineQ['title'];
						echo "$pTitle, ";
					}
					echo "</span></td></tr>\n";
				}
			?>
			</table>
			</div>
		</td>
	</tr>
	<tr><td><br/></td></tr>
</table>
<?php
	}
?>

</body>
</html>
