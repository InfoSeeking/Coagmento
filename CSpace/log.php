<?php
	session_start();
	ob_start();
	require_once("header1.php");
	require_once("connect.php");
	$pageName = "CSpace/log.php";
	require_once("../counter.php");
	$maxPerPage = 25;
	if (!isset($_GET['page']))
		$pageNum = 1;
	else
		$pageNum = $_GET['page'];

	$min = $pageNum*25-24;
	$max = $pageNum*25;
			
	if (isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$userID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$firstName = $line1['firstName'];
		$lastName = $line1['lastName'];
?>
		<form id="form1" action="log.php" method="GET">
		<table class="table" border=0>
		    <tr>
		    <td>
		    <select name="filter" id="selection" onchange="makeSelection();">
		      <option value="" selected="selected">Select:</option>
		      <option value="all">All</option>
		      <option value="none">None</option>
		      <option value="invert">Invert</option>
		    </select>
		    </td>
    		<td>
			<select name="projectID">
		      <option value="" selected="selected">Project:</option>
		      <?php
			  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
				$results = mysql_query($query) or die(" ". mysql_error());
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
					$projectID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					echo "<option value=\"$projectID\">$title</option>\n";
				}
		      ?>
		    </select>
		    </td>
    		<td>
			<select name="session">
		      <option value="" selected="selected">Session:</option>
		      <?php
			  	$query = "SELECT distinct date FROM pages WHERE userID='$userID' ORDER BY date desc";
				$results = mysql_query($query) or die(" ". mysql_error());
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
					$date = $line['date'];
					echo "<option value=\"$date\">$date</option>\n";
				}
		      ?>
		    </select>
		    </td>
    		<td>
			<select name="objects">
		      <option value="" selected="selected">Objects:</option>
		      <option value="pages">Pages</option>
		      <option value="saved">Results</option>
		      <option value="queries">Queries</option>
		      <option value="snippets">Snippets</option>
		      <option value="annotations">Annotations</option>
		    </select>
		    </td>
			<td><input type="submit" value="Filter" /></td>
			<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php // include("pagingnav.php");?></td>
    		</tr>
		</table>
<table class="table" border=1>
<?php	
	// First see if there was a valid project selection.
	// If not, see if we came here from a delete action.
	// If not, then get the projectID from the session environment.
	if ($_GET['projectID'])
		$projectID = $_GET['projectID'];
	else if (isset($_GET['del_projectID']))
		$projectID = $_GET['del_projectID'];
	else if (isset($_SESSION['projectID']))
		if ($_SESSION['projectID']>0)
			$projectID = $_SESSION['projectID'];
	if ($_GET['objects'])
		$objects = $_GET['objects'];
	else
		$objects = $_GET['del_objects'];
	if ($_GET['session'])
		$session = $_GET['session'];
	else
		$session = $_GET['del_session'];
	if (!$objects)
		$objects = 'pages';

	// Get the date, time, and timestamp
	$timestamp = time();
	$datetime = getdate();
	$date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
		
	$ip=$_SERVER['REMOTE_ADDR'];
	$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','view-log','','$ip')";
	$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			
	switch ($objects) {
		case 'pages':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND status=1 AND date='$session' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND status=1";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from project <strong>$title</strong> for session <strong>$session</strong>.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND status=1 ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND status=1";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from project <strong>$title</strong> for <strong>all</strong> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND status=1 ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND status=1";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from <strong>all</strong> the projects for session <strong>$session</strong>.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND status=1 ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND status=1";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from <strong>all</strong> the projects for <strong>all</strong> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><th><input type=\"checkbox\" name=\"pageID\" value=\"all\" onclick=\"checkUncheckAll(this);\"></th><th>Project</th><th>Document</th><th>Source</th><th>Query</th><th>Date</th><th>Time</th></tr>\n";
				
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$pageID = $line['pageID'];
				if(isset($_GET[$pageID])) {
					if (isset($_GET['targetProjectID'])) {
						$targetProjectID = $_GET['targetProjectID'];
						if ($targetProjectID>0) {
							$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else if (isset($_GET['del_projectID'])) {
							$targetProjectID = $_GET['del_projectID'];
							$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else	
							echo "<font color=red>Please make a valid selection.</font>\n";
//						echo "<font color=red>Selected records moved.</font>\n";					
					}
					else {
						$query1 = "DELETE FROM pages WHERE pageID='$pageID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
//						echo "<font color=red>Selected records deleted.</font>\n";
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projectID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$url = $line['url'];
					if ($line['title'])
						$pageTitle = $line['title'];
					else
						$pageTitle = $url;
					$saved = $line['result'];
					$source = $line['source'];
					$queryText = $line['query'];
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><input type=\"checkbox\" name=\"$pageID\"></td><td>$title</td><td><a href=\"$url\">$pageTitle</a>";
					if ($saved)
						echo " <img src=\"../img/check.gif\" height=20/>";
					echo "</td><td>$source</td><td>$queryText</td><td>$date</td><td>$time</td></tr>\n";
				}
			}
			echo "<tr><td colspan=7><input type=\"submit\" value=\"Delete Selected\" /> ";
			echo " <input type=\"submit\" value=\"Move Selected Objects To ...\" /> ";
			echo "<select name=\"targetProjectID\">\n";
	      	echo "<option value=\"\" selected=\"selected\">Project:</option>\n";
		  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$targetProjectID = $line['projectID'];
				$query1 = "SELECT * FROM projects WHERE projectID='$targetProjectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				echo "<option value=\"$targetProjectID\">$title</option>\n";
			}
	    	echo "</select>\n";
			echo "</td></tr>";
			break;
			
		case 'saved':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND result='1' ORDER BY timestamp";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from project <strong>$title</strong> for session <strong>$session</strong>.</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND result='1' ORDER BY timestamp";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from project <strong>$title</strong> for <strong>all</strong> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND result='1' ORDER BY timestamp";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from <strong>all</strong> the projects for session <strong>$session</strong>.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND result='1' ORDER BY timestamp";
					echo "<tr><td colspan=7><table class=\"body\"><tr><td>Displaying <strong>pages</strong> from <strong>all</strong> the projects for <strong>all</strong> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><th><input type=\"checkbox\" name=\"pageID\" value=\"all\" onclick=\"checkUncheckAll(this);\"></th><th>Project</th><th>Document</th><th>Source</th><th>Query</th><th>Date</th><th>Time</th></tr>\n";
			
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$pageID = $line['pageID'];
				if(isset($_GET[$pageID])) {
					if (isset($_GET['targetProjectID'])) {
						$targetProjectID = $_GET['targetProjectID'];
						if ($targetProjectID>0) {
							$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else if (isset($_GET['del_projectID'])) {
							$targetProjectID = $_GET['del_projectID'];
							$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else
							echo "<font color=red>Please make a valid selection.</font>\n";
//						echo "<font color=red>Selected records moved.</font>\n";					
					}
					else {
						$query1 = "DELETE FROM pages WHERE pageID='$pageID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
//						echo "<font color=red>Selected records deleted.</font>\n";
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projectID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$url = $line['url'];
					if ($line['title'])
						$pageTitle = $line['title'];
					else
						$pageTitle = $url;
					$saved = $line['result'];
					$source = $line['source'];
					$queryText = $line['query'];
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><input type=\"checkbox\" name=\"$pageID\"></td><td>$title</td><td><a href=\"$url\">$pageTitle</a>";
					if ($saved)
						echo " <img src=\"../img/check.gif\" height=20/>";
					echo "</td><td>$source</td><td>$queryText</td><td>$date</td><td>$time</td></tr>\n";
				}
			}
			echo "<tr><td colspan=7><input type=\"submit\" value=\"Delete Selected\" /> ";
			echo " <input type=\"submit\" value=\"Move Selected Objects To ...\" /> ";
			echo "<select name=\"targetProjectID\">\n";
	      	echo "<option value=\"\" selected=\"selected\">Project:</option>\n";
		  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$targetProjectID = $line['projectID'];
				$query1 = "SELECT * FROM projects WHERE projectID='$targetProjectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				echo "<option value=\"$targetProjectID\">$title</option>\n";
			}
	    	echo "</select>\n";
			echo "</td></tr>\n";
			break;
		
		case 'queries':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND projectID='$projectID' AND date='$session' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>queries</strong> from project <strong>$title</strong> for session <strong>$session</strong>.</td></tr>\n";
				}
				else {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND projectID='$projectID' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>queries</strong> from project <strong>$title</strong> for <strong>all</strong> the sessions.</td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND date='$session' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>queries</strong> from <strong>all</strong> the projects for session <strong>$session</strong>.</td></tr>\n";
				}
				else {
					$query = "SELECT * FROM queries WHERE userID='$userID' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>queries</strong> from <strong>all</strong> the projects for <strong>all</strong> the sessions.</td></tr>\n";
				}
			}
			echo "<tr><th><input type=\"checkbox\" name=\"pageID\" value=\"all\" onclick=\"checkUncheckAll(this);\"></th><th>Project</th><th>Source</th><th>Query</th><th>Date</th><th>Time</th></tr>\n";

			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$queryID = $line['queryID'];
				if(isset($_GET[$queryID])) {
					if (isset($_GET['targetProjectID'])) {
						$targetProjectID = $_GET['targetProjectID'];
						if ($targetProjectID>0) {
							$query1 = "UPDATE queries SET status=0 WHERE userID='$userID' AND queryID='$queryID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else if (isset($_GET['del_projectID'])) {
							$targetProjectID = $_GET['del_projectID'];
							$query1 = "UPDATE queries SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else
							echo "<font color=red>Please make a valid selection.</font>\n";
//						echo "<font color=red>Selected records moved.</font>\n";					
					}
					else {
						$query1 = "DELETE FROM queries WHERE queryID='$queryID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
//						echo "<font color=red>Selected records deleted.</font>\n";
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projectID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$source = $line['source'];
					$queryText = $line['query'];
					$url = $line['url'];
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><input type=\"checkbox\" name=\"$queryID\"></td><td>$title</td><td>$source</td><td><a href=\"$url\">$queryText</a></td><td>$date</td><td>$time</td></tr>\n";
				}
			}
			echo "<tr><td colspan=6><input type=\"submit\" value=\"Delete Selected\" /> ";
			echo " <input type=\"submit\" value=\"Move Selected Objects To ...\" /> ";
			echo "<select name=\"targetProjectID\">\n";
	      	echo "<option value=\"\" selected=\"selected\">Project:</option>\n";
		  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$targetProjectID = $line['projectID'];
				$query1 = "SELECT * FROM projects WHERE projectID='$targetProjectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				echo "<option value=\"$targetProjectID\">$title</option>\n";
			}
	    	echo "</select>\n";
			echo "</td></tr>\n";
			break;
		case 'snippets':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND projectID='$projectID' AND date='$session' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>snippets</strong> from project <strong>$title</strong> for session <strong>$session</strong>.</td></tr>\n";
				}
				else {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND projectID='$projectID' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>snippets</strong> from project <strong>$title</strong> for <strong>all</strong> the sessions.</td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND date='$session' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>snippets</strong> from <strong>all</strong> the projects for session <strong>$session</strong>.</td></tr>\n";
				}
				else {
					$query = "SELECT * FROM snippets WHERE userID='$userID' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>snippets</strong> from <strong>all</strong> the projects for <strong>all</strong> the sessions.</td></tr>\n";
				}
			}
			echo "<tr><th><input type=\"checkbox\" name=\"pageID\" value=\"all\" onclick=\"checkUncheckAll(this);\"></th><th>Project</th><th>Document</th><th>Snippet</th><th>Date</th><th>Time</th></tr>\n";
			
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$snippetID = $line['snippetID'];
				if(isset($_GET[$snippetID])) {
					if (isset($_GET['targetProjectID'])) {
						$targetProjectID = $_GET['targetProjectID'];
						if ($targetProjectID>0) {
							$query1 = "UPDATE snippets SET projectID='$targetProjectID' WHERE userID='$userID' AND snippetID='$snippetID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						
						else
							echo "<font color=red>Please make a valid selection.</font>\n";
//						echo "<font color=red>Selected records moved.</font>\n";					
					}
					else {
						$query1 = "DELETE FROM snippets WHERE snippetID='$snippetID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
//						echo "<font color=red>Selected records deleted.</font>\n";
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projectID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$url = $line['url'];
					$snippet = stripslashes($line['snippet']);
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><input type=\"checkbox\" name=\"$snippetID\"></td><td>$title</td><td><a href=\"$url\">$url</a></td><td>$snippet</td><td>$date</td><td>$time</td></tr>\n";
				}
			}	
			echo "<tr><td colspan=6><input type=\"submit\" value=\"Delete Selected\" /> ";
			echo " <input type=\"submit\" value=\"Move Selected Objects To ...\" /> ";
			echo "<select name=\"targetProjectID\">\n";
	      	echo "<option value=\"\" selected=\"selected\">Project:</option>\n";
		  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$targetProjectID = $line['projectID'];
				$query1 = "SELECT * FROM projects WHERE projectID='$targetProjectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				echo "<option value=\"$targetProjectID\">$title</option>\n";
			}
	    	echo "</select>\n";
			echo "</td></tr>\n";
			break;
		case 'annotations':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND projectID='$projectID' AND date='$session' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>annotations</strong> from project <strong>$title</strong> for session <strong>$session</strong>.</td></tr>\n";
				}
				else {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND projectID='$projectID' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>annotations</strong> from project <strong>$title</strong> for <strong>all</strong> the sessions.</td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND date='$session' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>annotations</strong> from <strong>all</strong> the projects for session <strong>$session</strong>.</td></tr>\n";
				}
				else {
					$query = "SELECT * FROM annotations WHERE userID='$userID' ORDER BY timestamp";
					echo "<tr><td colspan=6>Displaying <strong>annotations</strong> from <strong>all</strong> the projects for <strong>all</strong> the sessions.</td></tr>\n";
				}
			}
			echo "<tr><th><input type=\"checkbox\" name=\"pageID\" value=\"all\" onclick=\"checkUncheckAll(this);\"></th><th>Project</th><th>Document</th><th>Snippet</th><th>Date</th><th>Time</th></tr>\n";
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$noteID = $line['noteID'];
				if(isset($_GET[$noteID])) {
					if (isset($_GET['targetProjectID'])) {
						$targetProjectID = $_GET['targetProjectID'];
						if ($targetProjectID>0) {
							$query1 = "UPDATE annotations SET status=0 WHERE userID='$userID' AND noteID='$noteID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else if (isset($_GET['del_projectID'])) {
							$targetProjectID = $_GET['del_projectID'];
							$query1 = "UPDATE annotations SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
							$results1 = mysql_query($query1) or die(" ". mysql_error());
						}
						else
							echo "<font color=red>Please make a valid selection.</font>\n";
//						echo "<font color=red>Selected records moved.</font>\n";					
					}
					else {
						$query1 = "DELETE FROM annotations WHERE noteID='$noteID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
//						echo "<font color=red>Selected records deleted.</font>\n";
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projectID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$url = $line['url'];
					$note = stripslashes($line['note']);
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><input type=\"checkbox\" name=\"$noteID\"></td><td>$title</td><td><a href=\"$url\">$url</a></td><td>$note</td><td>$date</td><td>$time</td></tr>\n";
				}
			}		
			echo "<tr><td colspan=6><input type=\"submit\" value=\"Delete Selected\" /> ";
			echo " <input type=\"submit\" value=\"Move Selected Objects To ...\" /> ";
			echo "<select name=\"targetProjectID\">\n";
	      	echo "<option value=\"\" selected=\"selected\">Project:</option>\n";
		  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {	
				$targetProjectID = $line['projectID'];
				$query1 = "SELECT * FROM projects WHERE projectID='$targetProjectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				echo "<option value=\"$targetProjectID\">$title</option>\n";
			}
	    	echo "</select>\n";
			echo "</td></tr>\n";		
			break;
		}
?>
</table>
<input type="hidden" name="del_projectID" value="<?php echo $projectID;?>" />
<input type="hidden" name="del_session" value="<?php echo $session;?>" />
<input type="hidden" name="del_objects" value="<?php echo $objects;?>" />
</form>
<?php
	}
	else {
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr><td>Sorry. Looks like we had trouble knowing who you are!<br/>Please try <a href=\"index.php\">logging in</a> again.</td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	} 		
	require_once("footer.php");
?>
  <!-- end #footer --></div>
<!-- end #container --></div>


</body>
</html>
