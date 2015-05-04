<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$base = Base::getInstance();
		$connection = Connection::getInstance();

		$userID = $base->getUserID();

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
		$session_str = '';
		$session_html_str = "<span style=\"font-weight:bold\">all</span> the sessions";
		if ($session){
			$session_str = " AND date='$session' ";
			$session_html_str = "session <span style=\"font-weight:bold\">$session</span>";
		}


		$orderBy = $_GET['orderby'];

		if (!$orderBy)
			$orderBy = 'timestamp';
?>
<table class="body" width=100%>
	<tr><td style="font-weight:bold;"><span style="color:blue;text-decoration:underline;cursor:pointer;font-weight:bold;" onClick="ajaxpage('main.php','content');">CSpace</span> > All data</td><td align="right"><img src="../img/data.jpg" height=50 style="vertical-align:middle;border:0" /> <span style="font-weight:bold;font-size:20px">Everyone's Data</span></td></tr>
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
		<form id="form1" action="log.php" method="GET">
		<table class="body" border=0>
		    <tr>
    		<td>
			<select id="projectID">
		      <option value="" selected="selected">Project:</option>
		      <?php
			  	$query = "SELECT * FROM memberships WHERE userID='$userID'";
					$results = $connection->commit("SELECT * FROM memberships WHERE userID='$userID'");
				while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
					$projID = $line['projectID'];
					$results = $connection->commit("SELECT * FROM projects WHERE projectID='$projID'");
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

					$results = $connection->commit("SELECT distinct date FROM pages WHERE userID='$userID' AND source!='coagmento' ORDER BY date desc");
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
		      <option value="" <?php if (!$objects) echo "SELECTED";?> >Objects:</option>
		      <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?> >Webpages</option>
		      <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?> >Bookmarks</option>
		      <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?> >Searches</option>
		      <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?> >Snippets</option>
		      <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?> >Annotations</option>
		    </select>
		    </td>
			<td>&nbsp;<input type="button" value="Filter" onClick="filterAllData();" />&nbsp;&nbsp; <span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('allData.php?objects=pages','content');">Show All</span></td>
			<td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php // include("pagingnav2.php");?></td>
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
 				echo "<tr><td colspan=5><input type=\"text\" size=40 id=\"searchString\" value=\"$searchString\" onKeyDown=\"if (event.keyCode == 13) document.getElementById('sButton').click();\"/> <input type=\"button\" id=\"sButton\" value=\"Search\" onclick=\"searchAll($projID, '$objects', '$sess');\"/></td></tr>";
    		?>
 		</table>
<table class="style3" border=1 cellpadding="2" cellspacing="0">
<?php


	$table_name = '';
	$like_str = '';
	$orderby_str = " ORDER BY timestamp LIMIT $min, $max ";
	$colspanct = 6;
	$misc_str = '1=1';
	$displaywhat_str = '';
	$w_str ='';

	$elemID_str = '';

	if($objects == 'pages'){
		$table_name = 'pages';
		$like_str = " AND title LIKE '%$searchString%' ";
		$orderby_str = " ORDER BY $orderBy LIMIT $min, $max ";
		$misc_str = " status=1 ";
		$colspanct = 6;
		$displaywhat_str = 'webpages';
		$w_str = 'width=200px';
		$elemID_str = 'pageID';
	}else if($objects == 'saved'){
		$table_name = 'pages';
		$like_str = " AND title LIKE '%$searchString%' ";
		$misc_str = " source!='coagmento' AND result='1' ";
		$colspanct = 6;
		$displaywhat_str = 'bookmarks';
		$elemID_str = 'pageID';
	}else if($objects == 'queries'){
		$table_name = 'queries';
		$like_str = " AND query LIKE '%$searchString%' ";
		$colspanct = 5;
		$displaywhat_str = 'queries';
		$elemID_str = 'queryID';
	}else if($objects == 'snippets'){

		$table_name = 'snippets';
		$like_str = " AND snippet LIKE '%$searchString%' ";
		$misc_str = " userID='$userID' ";
		$colspanct = 4;
		$displaywhat_str = 'snippets';
		$elemID_str = 'snippetID';
	}else if($objects == 'annotations'){
		$table_name = 'annotations';
		$like_str = " AND note LIKE '%$searchString%' ";
		$colspanct = 4;
		$displaywhat_str = 'annotations';
		$elemID_str = 'noteID';
	}

	$projectID_str = '';
	$title = '';
	$whatproject_str = "<span style=\"font-weight:bold\">all</span> the projects for";
	if($projectID){
		$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results1 = $connection->commit($query1);
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$title = $line1['title'];
		$projectID_str = " AND projectID='$projectID' ";
		$whatproject_str = "project <span style=\"font-weight:bold\">$title</span> for";
	}

	$select_str = "SELECT * FROM $table_name WHERE $misc_str $projectID_str $session_str $like_str";


	echo "<tr><td colspan=$colspanct><table class=\"body\" width=100%><tr><td>Displaying <span style=\"font-weight:bold\">$displaywhat_str</span> from $whatproject_str $session_html_str.</td></td><td>&nbsp;&nbsp;&nbsp;</td><td align=right $w_str>";
	require_once("pagingnav2.php");
	echo "</td></tr></table></td></tr>\n";


	// TODO: SIMPLIFY!!!!
	echo "<tr><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=source', 'content');\">Source</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=query', 'content');\">Query</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
	echo "<tr><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=source', 'content');\">Source</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=query', 'content');\">Query</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
	echo "<tr><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=source', 'content');\">Source</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=query', 'content');\">Query</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
	echo "<tr><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";
	echo "<tr><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=projectID', 'content');\">Project</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=title', 'content');\">Webpage</span></td><td align=center><span style=\"font-weight:bold;color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('allData.php?searchString=$searchString&session=$session&projectID=$projectID&objects=$objects&source=$source&qid=$qid&page=$pageNum&orderby=date', 'content');\">Time</span></td></tr>\n";

	$results = $connection->commit($query);
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$elemID = $line[$elemID_str];

		if (isset($_GET[$elemID])){

			if (isset($_GET['targetProjectID'])){
				$targetProjectID = $_GET['targetProjectID'];


				if ($targetProjectID>0) {
					$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";


					// TODO: SIMPLIFY!!!!
					$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
					$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
					$query1 = "UPDATE queries SET status=0 WHERE userID='$userID' AND queryID='$queryID'";
					$query1 = "UPDATE snippets SET projectID='$targetProjectID' WHERE userID='$userID' AND snippetID='$snippetID'";
					$query1 = "UPDATE annotations SET status=0 WHERE userID='$userID' AND noteID='$noteID'";

					$results1 = $connection->commit($query1);
				}else if (isset($_GET['del_projectID'])) {
					$targetProjectID = $_GET['del_projectID'];
					$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";

					// TODO: SIMPLIFY!!!!
					$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
					$query1 = "UPDATE pages SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
					$query1 = "UPDATE queries SET status='0' WHERE userID='$userID' AND pageID='$pageID'";
					$query1 = "UPDATE annotations SET status='0' WHERE userID='$userID' AND pageID='$pageID'";

					$results1 = $connection->commit($query1);


				}else{
					echo "<font color=red>Please make a valid selection.</font>\n";
				}
			}else{
				$query1 = "DELETE FROM $table_name WHERE $elemID_str='$elemID'";
				$results1 = $connection->commit($query1);
			}

		}else{

			$userID = $line['userID'];

			$query1 = "SELECT * FROM users WHERE userID='$userID'";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$userName = $line1['userName'];
			$projID = $line['projectID'];
			$query1 = "SELECT * FROM projects WHERE projectID='$projID'";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);

			$title = $line1['title'];
			$url = $line['url'];
			$date = $line['date'];
			$time = $line['time'];




			$source = '';
			$snippet = '';
			$note = '';
			$queryText = '';
			$source_str = '';
			$external_str = '';

			if($objects=='pages' || $objects=='saved' || $objects=='queries'){
				$source = $line['source'];
				$queryText = $line['query'];
				$source_str = "<td>$source</td>";
			}
			else if($objects=='snippets'){
				$snippet = stripslashes($line['snippet']);
			}else if($objects=='annotations'){
				$note = stripslashes($line['note']);
			}



			$pageTitle = '';
			if($objects=='pages' || $objects == 'saved'){
				if ($line['title'])
					$pageTitle = $line['title'];
				else
					$pageTitle = $url;

				$external_str = "$pageTitle";
			}else if($objects=='queries'){
				$external_str = "$queryText";
			}else{
				$external_str = "$url";
			}
			$snippet = stripslashes($line['snippet']);
			$note = stripslashes($line['note']);

			if($objects=='pages' || $objects=='saved'){
				$saved = $line['result'];
				if ($saved)
					echo " <img src=\"../img/star.jpg\" height=18/>";
				echo "</td><td>$source</td><td>$queryText</td><td>$date<br/>$time</td></tr>\n";
			}else if($objects=='queries'){
				echo "</td><td>$date<br/>$time</td></tr>\n";
			}else if($objects=='snippets'){
				echo "<br/><span style=\"color:gray;\">$snippet</span></td><td>$date<br/>$time</td></tr>\n";
			}else if($objects=='annotations'){
				echo "<br/><span style=\"color:gray;\">$note</span></td><td>$date<br/>$time</td></tr>\n";
			}
		}
	}
?>
</table>
<br/><br/>
</form>
<?php
	}
?>
