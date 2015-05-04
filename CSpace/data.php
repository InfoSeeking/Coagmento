<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		require_once("connect.php");
		if (isset($_GET['searchString']))
			$searchString = $_GET['searchString'];
		$maxPerPage = 25;
		if (!isset($_GET['page']))
			$pageNum = 1;
		else
			$pageNum = $_GET['page'];

		$min = $pageNum*25-24;
		$max = $pageNum*25;
		$objects = $_GET['objects'];
		if (!$objects)
			$objects = 'pages';
		$projectID = $_GET['projectID'];
		$session = $_GET['session'];
		$orderBy = $_GET['orderby'];

		if (!$orderBy)
			$orderBy = 'timestamp';
?>
<table class="body" width=100%>
	<tr><td style="font-weight:bold;"><span style="color:blue;text-decoration:underline;cursor:pointer;font-weight:bold;" onClick="ajaxpage('main.php','content');">CSpace</span> > <span style="color:blue;text-decoration:underline;cursor:pointer;font-weight:bold;" onClick="ajaxpage('allData.php','content');">All data</span> > My data</td><td align="right"><img src="../img/data.jpg" height=50 style="vertical-align:middle;border:0" /> <span style="font-weight:bold;font-size:20px">My Data</span></td></tr>
	<tr bgcolor="#EFEFEF">
		<td colspan=2>
			<span style="cursor:pointer;"><div onclick="switchMenu('help');">Click here to expand or collapse the help.</div></span>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="help" style="display:none;text-align:left;font-size:11px;background:#EFEFEF">
			Once you activate Coagmento, it records all the websites that you go to and the queries you run on search engines under the currently active project. Here you can see all of that data that Coagmento captured about you, along with your own additions (snippets, annotations, etc.). Remember, Coagmento does not capture passwords or any other forms that you may fill in anywhere. Here you can delete any records or move them between different projects.
			</div>
		</td>
	</tr>
</table>
<table class="body" width=100%>
	<tr><td><div id="message"></div></td></tr>
		<form id="form1">
		<table class="body" border=0>
		    <tr>
    		<td>
			<select id="projectID">
		      <option value="" selected="selected">Project:</option>
		      <?php
			  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
				$results = mysql_query($query) or die(" ". mysql_error());
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					echo "<option value=\"$projID\" ";
					if ($projID==$projectID)
						echo "SELECTED";
					echo ">$title</option>\n";
				}
		      ?>
		    </select>
		    </td>
    		<td>
			<select id="session">
		      <option value="" selected="selected">Session:</option>
		      <?php
			  	$query = "SELECT distinct date FROM pages WHERE userID='$userID' ORDER BY date desc";
				$results = mysql_query($query) or die(" ". mysql_error());
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
					$date = $line['date'];
					echo "<option value=\"$date\" ";
					if ($date==$session)
						echo "SELECTED";
					echo ">$date</option>\n";
				}
		      ?>
		    </select>
		    </td>
    		<td>
			<select id="objects">
		      <option value="" <?php if (!$objects) echo "SELECTED";?>>Objects:</option>
		      <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
		      <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
		      <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
		      <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
		      <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
		    </select>
		    </td>
			<td>&nbsp;<input type="button" value="Filter" onClick="filterData();" />&nbsp;&nbsp; <span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('data.php?objects=pages','content');">Show All</span></td>
			<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    		</tr>
    		<?php
    			if (!$projectID)
    				$projID = 0;
    			else
    				$projID = $projectID;
    			if (!$session)
    				$sess = 0;
    			else
    				$sess = $session;
 				echo "<tr><td colspan=5><input type=\"text\" size=40 id=\"searchString\" value=\"$searchString\" onKeyDown=\"if (event.keyCode == 13) document.getElementById('sButton').click();\"/> <input type=\"button\" id=\"sButton\" value=\"Search\" onclick=\"search($projID, '$objects', '$sess');\"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";

 				if (isset($_GET['del'])) {
 					$delID = $_GET['del'];
 					$type = $_GET['type'];
					$table = $type+'s';

 					switch ($type) {
 						case 'page':
 							$query = "UPDATE pages SET status=0 WHERE pageID=$delID";
 							break;
 						case 'query':
 							$query = "UPDATE queries SET status=0 WHERE queryID=$delID";
 							break;
 						case 'snippet':
 							$query = "UPDATE snippets SET status=0 WHERE snippetID=$delID";
 							break;
 						case 'annotation':
 							$query = "UPDATE annotations SET status=0 WHERE noteID=$delID";
 							break;
 					}
 					$results = mysql_query($query) or die(" ". mysql_error());
	 				echo "<span style=\"color:red;\">Item deleted.</span> <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&undo=$delID&type=$type', 'content');\">Undo it.</span>";
 				}
 				else if (isset($_GET['undo'])) {
 					$undoID = $_GET['undo'];
 					$type = $_GET['type'];
 					switch ($type) {
 						case 'page':
 							$query = "UPDATE pages SET status=1 WHERE pageID=$undoID";
 							break;
 						case 'query':
 							$query = "UPDATE queries SET status=1 WHERE queryID=$undoID";
 							break;
 						case 'snippet':
 							$query = "UPDATE snippets SET status=1 WHERE snippetID=$undoID";
 							break;
 						case 'annotation':
 							$query = "UPDATE annotations SET status=1 WHERE noteID=$undoID";
 							break;
 					}
 					$results = mysql_query($query) or die(" ". mysql_error());
 					echo "<span style=\"color:green;\">Undone!</span>";
 				}
 				echo "</td></tr>";
    		?>
 		</table>
<table class="style3" border=1 cellpadding="2" cellspacing="0">
<?php
	switch ($objects) {
		case 'pages':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND source!='coagmento' AND status=1 AND date='$session' AND title LIKE '%$searchString%' ORDER BY $orderBy LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND source!='coagmento' AND status=1 AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\" width=100%><tr><td>Displaying <span style=\"font-weight:bold\">webpages</span> from project <span style=\"font-weight:bold\">$title</span> for session <span style=\"font-weight:bold\">$session</span>.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right width=200px>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND source!='coagmento' AND status=1 AND title LIKE '%$searchString%' ORDER BY $orderBy LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND source!='coagmento' AND status=1 AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\" width=100%><tr><td>Displaying <span style=\"font-weight:bold\">webpages</span> from project <span style=\"font-weight:bold\">$title</span> for <span style=\"font-weight:bold\">all</span> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right width=200px>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND source!='coagmento' AND status=1 AND title LIKE '%$searchString%' ORDER BY $orderBy LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND source!='coagmento' AND status=1 AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\" width=100%><tr><td>Displaying <span style=\"font-weight:bold\">webpages</span> from <span style=\"font-weight:bold\">all</span> the projects for session <span style=\"font-weight:bold\">$session</span>.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right width=200px>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND status=1 AND source!='coagmento' AND title LIKE '%$searchString%' ORDER BY $orderBy LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND status=1 AND source!='coagmento' AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\" width=100%><tr><td>Displaying <span style=\"font-weight:bold\">webpages</span> from <span style=\"font-weight:bold\">all</span> the projects for <span style=\"font-weight:bold\">all</span> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right width=200px>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><td align=center><span style=\"font-weight:bold\">Delete</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=source', 'content');\">Source</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=query', 'content');\">Query</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";

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
					}
					else {
						$query1 = "DELETE FROM pages WHERE pageID='$pageID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
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
					echo "<tr><td align=center><span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=$orderBy&del=$pageID&type=page', 'content');\">X</span></td><td>$title</td><td><a href=\"$url\" target=\"_external\">$pageTitle</a>";
					if ($saved)
						echo " <img src=\"../img/star.jpg\" height=18/>";
					echo "</td><td>$source</td><td>$queryText</td><td>$date<br/>$time</td></tr>\n";
				}
			}
			break;

		case 'saved':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND source!='coagmento' AND status=1 AND result='1' AND title LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND source!='coagmento' AND status=1 AND result='1' AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">bookmarks</span> from project <span style=\"font-weight:bold\">$title</span> for session <span style=\"font-weight:bold\">$session</span>.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND status=1 AND source!='coagmento' AND result='1' AND title LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND projectID='$projectID' AND status=1 AND source!='coagmento' AND result='1' AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">bookmarks</span> from project <span style=\"font-weight:bold\">$title</span> for <span style=\"font-weight:bold\">all</span> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND status=1 AND source!='coagmento' AND result='1' AND title LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM pages WHERE userID='$userID' AND date='$session' AND status=1 AND source!='coagmento' AND result='1' AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">bookmarks</span> from <span style=\"font-weight:bold\">all</span> the projects for session <span style=\"font-weight:bold\">$session</span>.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM pages WHERE userID='$userID' AND status=1 AND result='1' AND source!='coagmento' AND title LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query = "SELECT * FROM pages WHERE userID='$userID' AND status=1 AND result='1' AND source!='coagmento' AND title LIKE '%$searchString%'";
					echo "<tr><td colspan=6><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">bookmarks</span> from <span style=\"font-weight:bold\">all</span> the projects for <span style=\"font-weight:bold\">all</span> the sessions.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><td align=center><span style=\"font-weight:bold\">Delete</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=source', 'content');\">Source</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=query', 'content');\">Query</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
//			echo "<tr><td align=center><span style=\"font-weight:bold\"><input type=\"checkbox\" name=\"pageID\" value=\"all\" onclick=\"checkUncheckAll(this);\"></span></td><td align=center><span style=\"font-weight:bold\">Project</span></td><td align=center><span style=\"font-weight:bold\">Webpage</span></td><td align=center><span style=\"font-weight:bold\">Source</span></td><td align=center><span style=\"font-weight:bold\">Query</span></td><td align=center><span style=\"font-weight:bold\">Date</span></td><td align=center><span style=\"font-weight:bold\">Time</span></td></tr>\n";

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
					}
					else {
						$query1 = "DELETE FROM pages WHERE pageID='$pageID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
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
					echo "<tr><td align=center><span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=$orderBy&del=$pageID&type=page', 'content');\">X</span></td><td>$title</td><td><a href=\"$url\" target=\"_external\">$pageTitle</a>";
					if ($saved)
						echo " <img src=\"../img/star.jpg\" height=18/>";
					echo "</td><td>$source</td><td>$queryText</td><td>$date<br/>$time</td></tr>\n";
				}
			}
			break;

		case 'queries':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND status=1 AND query LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM queries WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND status=1 AND query LIKE '%$searchString%'";
					echo "<tr><td colspan=5><table class=\"body\">Displaying <span style=\"font-weight:bold\">searches</span> from project <span style=\"font-weight:bold\">$title</span> for session <span style=\"font-weight:bold\">$session</span>.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND projectID='$projectID' AND status=1 AND query LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM queries WHERE userID='$userID' AND projectID='$projectID' AND status=1 AND query LIKE '%$searchString%'";
					echo "<tr><td colspan=5><table class=\"body\">Displaying <span style=\"font-weight:bold\">searches</span> from project <span style=\"font-weight:bold\">$title</span> for <span style=\"font-weight:bold\">all</span> the sessions.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND date='$session' AND status=1 AND query LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM queries WHERE userID='$userID' AND date='$session' AND status=1 AND query LIKE '%$searchString%'";
					echo "<tr><td colspan=5><table class=\"body\">Displaying <span style=\"font-weight:bold\">searches</span> from <span style=\"font-weight:bold\">all</span> the projects for session <span style=\"font-weight:bold\">$session</span>.</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM queries WHERE userID='$userID' AND status=1 AND query LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM queries WHERE userID='$userID' AND status=1 AND query LIKE '%$searchString%'";
					echo "<tr><td colspan=5><table class=\"body\">Displaying <span style=\"font-weight:bold\">searches</span> from <span style=\"font-weight:bold\">all</span> the projects for <span style=\"font-weight:bold\">all</span> the sessions.</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><td align=center><span style=\"font-weight:bold\">Delete</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=source', 'content');\">Source</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=query', 'content');\">Query</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
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
					}
					else {
						$query1 = "DELETE FROM queries WHERE queryID='$queryID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$source = $line['source'];
					$queryText = $line['query'];
					$url = $line['url'];
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=$orderBy&del=$queryID&type=query', 'content');\">X</span></td><td>$title</td><td>$source</td><td><a href=\"$url\" target=\"_external\" >$queryText</a></td><td>$date<br/>$time</td></tr>\n";
				}
			}
			break;
		case 'snippets':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND status=1 AND snippet LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM snippets WHERE userID='$userID' AND projectID='$projectID' AND date='$session' AND status=1 AND snippet LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">snippets</span> from project <span style=\"font-weight:bold\">$title</span> for session <span style=\"font-weight:bold\">$session</span>.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND status=1 AND projectID='$projectID' AND snippet LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM snippets WHERE userID='$userID' AND status=1 AND projectID='$projectID' AND snippet LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">snippets</span> from project <span style=\"font-weight:bold\">$title</span> for <span style=\"font-weight:bold\">all</span> the sessions.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND date='$session' AND status=1 AND snippet LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM snippets WHERE userID='$userID' AND date='$session' AND status=1 AND snippet LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">snippets</span> from <span style=\"font-weight:bold\">all</span> the projects for session <span style=\"font-weight:bold\">$session</span>.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM snippets WHERE userID='$userID' AND status=1 AND snippet LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM snippets WHERE userID='$userID' AND status=1 AND snippet LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">snippets</span> from <span style=\"font-weight:bold\">all</span> the projects for <span style=\"font-weight:bold\">all</span> the sessions.</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><td align=center><span style=\"font-weight:bold\">Delete</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
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
					}
					else {
						$query1 = "DELETE FROM snippets WHERE snippetID='$snippetID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$url = $line['url'];
					$snippet = stripslashes($line['snippet']);
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=$orderBy&del=$snippetID&type=snippet', 'content');\">X</span></td><td>$title</td><td><a href=\"$url\" target=\"_external\">$url</a><br/><span style=\"color:gray;\">$snippet</span></td><td>$date<br/>$time</td></tr>\n";
				}
			}
			break;
		case 'annotations':
			if ($projectID) {
				$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				$title = $line1['title'];
				if ($session) {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND status=1 AND projectID='$projectID' AND date='$session' AND note LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM annotations WHERE userID='$userID' AND status=1 AND projectID='$projectID' AND date='$session' AND note LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">annotations</span> from project <span style=\"font-weight:bold\">$title</span> for session <span style=\"font-weight:bold\">$session</span>.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND status=1 AND projectID='$projectID' AND note LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM annotations WHERE userID='$userID' AND status=1 AND projectID='$projectID' AND note LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">annotations</span> from project <span style=\"font-weight:bold\">$title</span> for <span style=\"font-weight:bold\">all</span> the sessions.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			else {
				if ($session) {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND date='$session' AND status=1 AND note LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM annotations WHERE userID='$userID' AND date='$session' AND status=1 AND note LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">annotations</span> from <span style=\"font-weight:bold\">all</span> the projects for session <span style=\"font-weight:bold\">$session</span>.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
				else {
					$query = "SELECT * FROM annotations WHERE userID='$userID' AND status=1 AND note LIKE '%$searchString%' ORDER BY timestamp LIMIT $min, $max";
					$query1 = "SELECT * FROM annotations WHERE userID='$userID' AND status=1 AND note LIKE '%$searchString%'";
					echo "<tr><td colspan=4><table class=\"body\"><tr><td>Displaying <span style=\"font-weight:bold\">annotations</span> from <span style=\"font-weight:bold\">all</span> the projects for <span style=\"font-weight:bold\">all</span> the sessions.</td><td>&nbsp;&nbsp;&nbsp;</td><td align=right>";
					require_once("pagingnav.php");
					echo "</td></tr></table></td></tr>\n";
				}
			}
			echo "<tr><td align=center><span style=\"font-weight:bold\">Delete</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
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
					}
					else {
						$query1 = "DELETE FROM annotations WHERE noteID='$noteID'";
						$results1 = mysql_query($query1) or die(" ". mysql_error());
					}
				}
				else {
					$userID = $line['userID'];
					$query1 = "SELECT * FROM users WHERE userID='$userID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$userName = $line1['userName'];
					$projID = $line['projectID'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$url = $line['url'];
					$note = stripslashes($line['note']);
					$date = $line['date'];
					$time = $line['time'];
					echo "<tr><td align=center><span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('data.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=$orderBy&del=$noteID&type=annotation', 'content');\">X</span></td><td>$title</td><td><a href=\"$url\" target=\"_external\">$url</a><br/><span style=\"color:gray;\">$note</span></td><td>$date<br/>$time</td></tr>\n";
				}
			}
			break;
		}
?>
</table>
<br/><br/>
</form>
<?php
	}
?>
