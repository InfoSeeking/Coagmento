<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="style.css" TYPE="text/css" MEDIA=screen>

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
<h3>Select a Project</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>

<?php
	require_once("../connect.php");
	$userID = $_SESSION['CSpace_userID'];

	// If project update information was sent
	if (isset($_GET['submit'])) {
		$title = addslashes($_GET['title']);
		if ($title == "") {
			echo "<tr><td colspan=2><font color=\"red\">Error: project title cannot be empty. Please try again.</font></td></tr>";
		} // if ($title == "")
		else {
			$query = "SELECT * FROM projects,memberships WHERE projects.title='$title' AND memberships.userID='$userID' AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$num = mysql_num_rows($results);
			if ($num!=0) {
				echo "<tr><td colspan=2><font color=\"red\">Error: project <span style=\"font-weight:bold\">$title</span> already exists. Please choose a different title for your project.</font></td></tr>";
			} // if ($num!=0)
			else {
				$projectID = $_GET['projectID'];
				$description = addslashes($_GET['description']);
				$privacy = $_GET['privacy'];
				// Get the date, time, and timestamp
				date_default_timezone_set('America/New_York');
				$timestamp = time();
				$datetime = getdate();
			    $startDate = date('Y-m-d', $datetime[0]);
				$startTime = date('H:i:s', $datetime[0]);
				
				$query = "UPDATE projects SET title='$title',description='$description',privacy='$privacy' WHERE projectID='$projectID'";
				$results = mysql_query($query) or die(" ". mysql_error());
				
				
				$ip=$_SERVER['REMOTE_ADDR'];
				$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$startDate','$startTime','edit-project','$projectID','$ip')";
				$aResults = mysql_query($aQuery) or die(" ". mysql_error());
				
				echo "<tr><td colspan=2><font color=\"green\">Your changes to project <span style=\"font-weight:bold\">$title</span> have been saved. Go back to the <a href='projects.php'>project list</a>.</font></td></tr>";
			} // else with if ($num!=0)
		} // else with if ($title == "")
	} // if (isset($_GET['title']))
	
	$projectID = $_GET['projectID'];
	$query = "SELECT * FROM projects WHERE projectID='$projectID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$title = stripslashes($line['title']);
	$description = stripslashes($line['description']);
	$privacy = $line['privacy'];
?>
	<tr><td><br/></td></tr>
	<tr><td>
		<table class="style1">
		<tr><td>Title</td><td><input id="projTitle" type="text" size=32 value="<?php echo $title;?>" /></td><td></td></tr>
		<tr><td>Description<br/>(optional)</td><td><textarea id="projDesc" cols=30 rows=4><?php echo $description;?></textarea></td>
			<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;
			<div id="sureCreate"></div>
			</td>
		</tr>
		<tr><td>Privacy</td>
			<td>
				<input type="radio" name="privacy" id="public" <?php if ($privacy==0) echo "checked"; ?> /> Public (any user can search and join this project)<br/>
				<input type="radio" name="privacy" id="private"  <?php if ($privacy==1) echo "checked"; ?> /> Private (you will have to invite others to join)<br/>
			</td>
			<td><input type="hidden" name="projectID" id="projectID" value="<?php echo $projectID;?>" /></td>
		</tr>
		<tr><td></td><td><span style="color:gray;">Remember, you will be the owner of this project, so no matter what setting<br/> you choose, you can remove any of your collaborators at any time.</span></td><td></td></tr>
		<tr><td colspan=2><br/></td></tr>
		<tr><td colspan=2 align=center><input type=button value="Submit" onClick="editProj();"/> <a href="projects.php">Cancel</a></td><td></td></tr>
		</table>
		</td>
	</tr>
</table>
<?php
	}
?>

</body>
</html>