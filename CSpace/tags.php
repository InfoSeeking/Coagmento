<?php
	session_start();
	$ip=$_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		require_once("connect.php");
		$query = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$projectName = $line['title'];
?>
<table class="body" width=100%>
	<tr><td style="font-weight:bold;"><span style="color:blue;text-decoration:underline;cursor:pointer;font-weight:bold;" onClick="ajaxpage('main.php','content');">CSpace</span> > Workspace > Tags</td><td align="right"><img src="../img/tag.jpg" height=50 style="vertical-align:middle;border:0" /> <span style="font-weight:bold;font-size:20px">Manage Tags</span></td></tr>
	<tr bgcolor="#EFEFEF">
		<td colspan=2>
			<span style="cursor:pointer;"><div onclick="switchMenu('help');">Click here to expand or collapse the help.</div></span>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="help" style="display:none;text-align:left;font-size:11px;background:#EFEFEF">
			Here you can manage the tags for your existing selected project. These tags will show up when you or your collaborators for this project are collecting bookmarks or snippets. Tagging collected information with appropriate tags could help in better organization and finding later.
			</div>
		</td>
	</tr>
</table>
<table class="body" width=100%>
	<tr><td><br/></td></tr>
	<?php
		if (isset($_GET['tag'])) {
			$tag = addslashes($_GET['tag']);
			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
		    $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);
			$query = "INSERT INTO tags VALUES('','$userID','$projectID','$tag','$timestamp','$date','$time','1')";
			$results = mysql_query($query) or die(" ". mysql_error());
		} // if (isset($_GET['tag']))
		else if (isset($_GET['remove'])) {
			$tagID = $_GET['remove'];
			$query = "UPDATE tags SET status=0 WHERE id='$tagID'";
			$results = mysql_query($query) or die(" ". mysql_error());
		}
	?>
	<tr><td>[Work in progress.]</td></tr>
	<tr><td>Enter a tag to be added to your project <span style="font-weight:bold"><?php echo $projectName;?></span>.</td></tr>
	<tr><td><br/></td></tr>
	<tr><td><input type="text" size=40 id="tag" onKeyDown="if (event.keyCode == 13) document.getElementById('aButton').click();" /> <input type="button" value="Add" id="aButton" onclick="addTag();" /></td></tr>
	<tr><td><br/></td></tr>
	<?php
		echo "<tr><td style=\"background:#EFEFEF;font-weight:bold\" colspan=2>Existing tags for your active project</td></tr>\n";
		
		$query = "SELECT * FROM tags WHERE projectID='$projectID' AND status=1";
		$results = mysql_query($query) or die(" ". mysql_error());
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$tagID = $line['id'];
			$tag = stripslashes($line['tag']);
			echo "<tr><td><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('tags.php?remove=$tagID','content');\">X</span>&nbsp;&nbsp; $tag</td></tr>\n";
		}
	?>
	
</table>
<script type="text/javascript">
	document.getElementById('tag').focus();
</script>
<?php
	}
?>