<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script src="assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="../assets/js/utilities.js"></script>
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<link type="text/css" href="assets/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<link type="text/css" href="assets/css/pure-release-0.6.0/forms.css" rel="stylesheet" />
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
<h3>Select a Project</h3>

<?php

	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>

<?php
	$userID = $base->getUserID();

	// If project update information was sent
	if (isset($_GET['submit'])) {
		$title = addslashes($_GET['title']);
		if ($title == "") {
			echo "<tr><td colspan=2><font color=\"red\">Error: project title cannot be empty. Please try again.</font></td></tr>";
		} // if ($title == "")
		else {
			$query = "SELECT * FROM projects,memberships WHERE projects.title='$title' AND memberships.userID='$userID' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$num = mysqli_num_rows($results);
			if ($num!=0) {
				echo "<tr><td colspan=2><font color=\"red\">Error: project <span style=\"font-weight:bold\">$title</span> already exists. Please choose a different title for your project.</font></td></tr>";
			} // if ($num!=0)
			else {
				$projectID = $_GET['projectID'];
				$description = addslashes($_GET['description']);
				$privacy = $_GET['privacy'];
				// Get the date, time, and timestamp
				$timestamp = $base->getTimestamp();
				$date = $base->getDate();
				$time = $base->getTime();

				$query = "UPDATE projects SET title='$title',description='$description',privacy='$privacy' WHERE projectID='$projectID'";
				$results = $connection->commit($query);

				$ip=$base->getIP();;
				Util::getInstance()->saveAction('edit-project',"$projectID",$base);


				echo "<tr><td colspan=2><font color=\"green\">Your changes to project <span style=\"font-weight:bold\">$title</span> have been saved. Go back to the <a href='projects.php'>project list</a>.</font></td></tr>";
			} // else with if ($num!=0)
		} // else with if ($title == "")
	} // if (isset($_GET['title']))

	$projectID = $_GET['projectID'];
	$query = "SELECT * FROM projects WHERE projectID='$projectID'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$title = stripslashes($line['title']);
	$description = stripslashes($line['description']);
	$privacy = $line['privacy'];
?>
	<tr><td><br/></td></tr>

	<tr><td>

		<!--  Form is dummy.  Used for convenience of Pure styling! :) Bad practice. -->
		<form class="pure-form pure-form-stacked" onsubmit="return false;">
    <fieldset>

        <label for="projTitle"><strong>Title</strong></label>
				<input id="projTitle" type="text" size=32 value="<?php echo $title;?>" />
				<br>

        <label for="projDesc"><strong>Description (optional)</strong></label>
				<textarea id="projDesc" cols=30 rows=4><?php echo $description;?></textarea>
				<br>

				<label for="privacy"><strong>Privacy</strong></label>
				<input type="radio" name="privacy" id="public" <?php if ($privacy==0) echo "checked"; ?> /> Public (any user can search and join this project)<br/>
				<input type="radio" name="privacy" id="private"  <?php if ($privacy==1) echo "checked"; ?> /> Private (you will have to invite others to join)<br/>
				<br>



				<input type=button class="button-submit" value="Submit" onClick="editProj();"/>
				<input type=button class="button-other" value="Cancel" onClick="location.href='projects.php';return false;"/>
				<br><br>
				<span style="color:gray;">(Remember, you will be the owner of this project, <br/>so no matter what setting you choose, you can<br/>remove any of your collaborators at any time.)</span>
	    </fieldset>
	</form>


		<!-- <table class="style1">

		<tr><td></td><td></td>
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

		</table> -->
		</td>
	</tr>


	<!-- <tr><td>
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
		<tr><td colspan=2 align=center><input type=button class="button-submit" value="Submit" onClick="editProj();"/> <input type=button class="button-other" value="Cancel" onClick="location.href='projects.php'"/></td><td></td></tr>
		</table>
		</td>
	</tr> -->
</table>
<?php
	}
?>

</body>
</html>
