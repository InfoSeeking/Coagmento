<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="assets/css/style.css" TYPE="text/css" MEDIA=screen>
<LINK REL=StyleSheet HREF="assets/css/style2.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Create a Project</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>

<table class="body" width=100%>

<?php
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");

	$base = Base::getInstance();
	$connection = Connection::getInstance();
	$userID = $base->getUserID();

	// If new project information was sent
	if (isset($_GET['title'])) {
		$title = addslashes($_GET['title']);
		if ($title == "") {
			echo "<tr><td colspan=2><font color=\"red\">Error: project title cannot be empty. Please try again.</font></td></tr>";
		} // if ($title == "")
		else {
			$query = "SELECT * FROM projects,memberships WHERE projects.title='$title' AND memberships.userID='$userID' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$num = mysql_num_rows($results);
			if ($num!=0) {
				echo "<tr><td colspan=2><font color=\"red\">Error: project <span style=\"font-weight:bold\">$title</span> already exists. Please choose a different title for your project.</font></td></tr>";
			} // if ($num!=0)
			else {
				$description = addslashes($_GET['description']);
				$privacy = $_GET['privacy'];
				// Get the date, time, and timestamp
				date_default_timezone_set('America/New_York');
				$timestamp = $base->getTimestamp();
				$startDate = $base->getDate();
				$startTime = $base->getTime();

				$query = "INSERT INTO projects VALUES('','$title','$description','$startDate','$startTime','1','$privacy')";
				$results = $connection->commit($query);

				$projectID = $connection->getLastID();
				$query = "INSERT INTO memberships VALUES('','$projectID','$userID','1')";
				$results = $connection->commit($query);

				// Record the action and update the points
				$ip=$base->getIP();
				Util::getInstance()->saveAction('create-project',"$projectID",$base);

				require_once("utilityFunctions.php");
				addPoints($userID,100);

				echo "<tr><td colspan=2><font color=\"green\">Your new project <span style=\"font-weight:bold\">$title</span> has been created.</font></td></tr>";
			} // else with if ($num!=0)
		} // else with if ($title == "")
	} // if (isset($_GET['title']))
?>
	<tr><td>
		<table class="style1">
		<tr><td><strong>Title</strong></td><td><input id="projTitle" type="text" size=41 /></td><td></td></tr>
		<tr><td><strong>Description<br/>(optional)</strong></td><td><textarea id="projDesc" cols=30 rows=4></textarea></td>
			<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;
				<div id="sureCreate"></div>
			</td>
		</tr>
		<tr><td><strong>Privacy</strong></td>
			<td>
				<input type="radio" name="privacy" id="public" /> Public (any user can search and join this project)<br/>
				<input type="radio" name="privacy" id="private" checked /> Private (you will have to invite others to join)<br/>
			</td>
			<td></td>
		</tr>
		<tr><td></td><td><span style="color:gray;">Remember, you will be the owner of this project, so no matter what setting<br/> you choose, you can remove any of your collaborators at any time.</span></td><td></td></tr>
		<tr><td><input type=button value="Create" onClick="createProj();"/></td><td></td></tr>
		</table>
		</td>
	</tr>

<?php
	echo "<tr><td><table class=\"style1\"><tr><td>&nbsp;</td></tr>";
	echo "<tr><td><span style=\"font-size: 16px; font-weight:bold\">Existing projects</span></td></tr><tr>\n";
	$query = "SELECT * FROM memberships WHERE userID='$userID'";
	$results = $connection->commit($query);
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$projectID = $line['projectID'];
		$access = $line['access'];
		$query1 = "SELECT * FROM projects WHERE projectID='$projectID' AND status=1";
		$results1 = $connection->commit($query1);
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$projectID = $line1['projectID'];
		$title = $line1['title'];

		// If the current user didn't create this project, find out who did
		if ($access!=1) {
			$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND access=1";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$uID = $line1['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$uID'";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$uName = $line1['username'];
			$title = $title . " ($uName)";
		}

		$startDate = $line1['startDate'];
		echo "<tr><td><a href='projectInfo.php?projectID=$projectID' class='existing_projects'>$title</a></td></tr>";
	}
	echo "</table></td></tr>\n";
	echo "</table></td></tr>\n";
	echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
?>
</table>
<?php
	}
?>

</body>
</html>
