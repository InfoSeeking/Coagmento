<?php
	session_start();
?>
<style type="text/css">
/*---------- bubble tooltip -----------*/
a.tt{
    position:relative;
    z-index:24;
    color:blue;
    text-decoration:none;
}
a.tt span{ display: none; }

/*background:; ie hack, something must be changed in a for ie to execute it*/
a.tt:hover{ z-index:25; color: #aaaaff; background:;}
a.tt:hover span.tooltip{
    display:block;
    position:absolute;
    top:0px; left:0;
	padding: 0px 0 0 0;
	width:200px;
	color: black;
	font-size: 8px;
    text-align: left;
	filter: alpha(opacity:90);
	KHTMLOpacity: 0.90;
	MozOpacity: 0.90;
	opacity: 0.90;
}
a.tt:hover span.top{
	display: block;
	padding: 30px 8px 0;
    background: url(bubble.gif) no-repeat top;
}
a.tt:hover span.middle{ /* different middle bg for stretch */
	display: block;
	padding: 0 8px; 
	background: url(bubble_filler.gif) repeat bottom; 
}
a.tt:hover span.bottom{
	display: block;
	padding:3px 8px 10px;
	color: #548912;
    background: url(bubble.gif) no-repeat bottom;
}
</style>

<div id="snippetsBox" style="height:200px;overflow:auto;">
<?php
	require_once("connect.php");
	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		echo "<span style=\"font-size:10px\">Sort by:</span> <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(2,'title');\">Title</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(2,'source');\">Source</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(2,'date');\">Date</span> | <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:10px;\" onClick=\"tabsReload(2,'author');\">Author</span><hr/>\n";

		echo "<table width=100%><tr style=\"background:#CDE;\"><td style=\"font-size:12px;color:green;font-weight:bold\" ondragenter=\"handleDragDropEvent(event);\" align=center>DropZone</td></tr></table>";
		// Find out the preferences set by this user for this project.
		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='snippets-order'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$orderBy = $line['value'];
		if (!$orderBy)
			$orderBy = 'timestamp';
		if ($orderBy=='source')
			$orderBy = 'url';
		if ($orderBy=='title')
			$orderBy = 'title';
			
		echo "<table width=100%>\n";		
		$query = "SELECT * FROM snippets WHERE projectID='$projectID' AND status=1 ORDER BY $orderBy desc";
		$results = mysql_query($query) or die(" ". mysql_error());
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$snippetID = $line['snippetID'];
			$qUserID = $line['userID'];
			if ($userID==$qUserID)
				$color = '#FF7400';
			else
				$color = '#008C00';
			$query1 = "SELECT * FROM users WHERE userID='$qUserID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$userName = $line1['username'];
			$snippet = stripslashes($line['snippet']);
/*
			$snippet = substr($line['snippet'], 0, 25);
			$snippet = $snippet . '..';
*/
			$url = $line['url'];
			$title = stripslashes($line['title']);
			$type = $line['type'];
			if (!$title)
				$title = $url;
			if (strlen($title)>25) {
				$title = substr($title, 0, 25);
				$title = $title . '..';
			}
				
			echo "<tr><td><span style=\"font-size:10px;color:$color\">$userName:</span> </td><td><span style=\"font-size:10px\">";
			if ($url) {
				echo "<font color=blue><a href=\"$url\" class=\"tt\" target=_content style=\"font-size:10px\" onclick=\"addAction('sidebar-snippet','$snippetID');\">$title<span class=\"tooltip\"><span class=\"top\"></span><span class=\"middle\">";
				if ($type=="image")
					echo "<img src=\"$snippet\" width=120 />";
				else
					echo "$snippet";				
				echo "</span><span class=\"bottom\"></span></span></a></font>";
			}
			else
				echo "$snippet</span></td></tr>\n";
		}
		echo "</table>\n";
	}
	else {
		echo "Your session has expired. Please <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
	mysql_close($dbh);
?>
</div>
