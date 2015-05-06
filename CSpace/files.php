<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('links_header.php');
?>

<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('services/func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Files</h3>

<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];

		if (!(isset($_SESSION['CSpace_projectID'])) || $projectID==0) {
			$query = "select projects.projectID from projects,memberships where memberships.userID=$userID and projects.projectID=memberships.projectID and projects.title='Default' and memberships.access=1";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
?>

	<script type="text/javascript" src="assets/js/webtoolkit.aim.js"></script>
	<script type="text/javascript">
		function startCallback() {
			// make something useful before submit (onStart)
			return true;
		}

		function completeCallback(response) {
			// make something useful after (onComplete)
			document.getElementById('nr').innerHTML = parseInt(document.getElementById('nr').innerHTML) + 1;
			document.getElementById('r').innerHTML = response;
		}
	</script>

<table class="body" width=100%>
	<?php
		require_once("../connect.php");

		if (isset($_FILES['uploaded']['name'])) {
			$name = basename($_FILES['uploaded']['name']);
			$description = addslashes($_GET['description']);
			$rand = rand(1000, 9999);
			$target = "files/";
			$fileName = $rand.$userID.$projectID . '_'. $name;
			//$fileName = addslashes($fileName);
			$fileName = str_replace("'","_",$fileName);
			$fileName = str_replace("\\","_",$fileName);
			$target = $target . $fileName;
			$ok=1;
			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
			$date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);
			if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
				$query1 = "INSERT INTO files VALUES('','$userID','$projectID','$timestamp','$date','$time','$name','$fileName','1')";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
			}
		}

		if (isset($_GET['action'])) {
			$action = $_GET['action'];
			if ($action=="delete") {
				$id = $_GET['id'];
				$query3 = "SELECT * FROM files WHERE id=$id";
				$results3 = mysql_query($query3) or die(" ". mysql_error());
				$line3 = mysql_fetch_array($results3, MYSQL_ASSOC);
				$fileName = $line3['fileName'];
				$fUserID = $line3['userID'];
				if ($fUserID==$userID) {
					if (isset($_GET['confirmation'])) {
						$query4 = "UPDATE files SET status=0 WHERE id=$id";
						$results4 = mysql_query($query4) or die(" ". mysql_error());
					}
					else {
						echo "<tr><td>Are you sure you want to delete <span style=\"font-weight:bold\">$fileName</span>?</td></tr>\n";
						echo "<tr><td><a href='files.php?action=delete&id=$id&confirmation=yes'>Yes</a>&nbsp;&nbsp;&nbsp;<a href='files.php'>No</a></td></tr>\n";
					}
				}
				else {
					echo "Oops.. you don't seem to have the rights to delete this file. Sorry!";
				}
			}
		}

        echo "<tr><td><form action=\"files.php\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback}\">";
        echo "<tr><td>Upload a new file: <input name=\"uploaded\" type=\"file\"/> <input type=\"submit\" value=\"Upload\"/></form></td></tr>\n";
        echo "</table>\n";
        echo "<table class=\"body\" width=100%>\n";
		echo "<tr><td style=\"background:#EFEFEF;font-weight:bold\" colspan=5>Existing files for your active project</td></tr>\n";
		echo "<tr><td colspan=4>Remember - you are only seeing the files for the currently selected project.</td></tr>\n";
		echo "<tr><td style=\"font-weight:bold\" align=center>Delete</td><td style=\"font-weight:bold\">Name</td><td style=\"font-weight:bold\">Uploaded by</td><td style=\"font-weight:bold\" align=center>Time</td></tr>\n";
		$query = "SELECT * FROM files WHERE projectID='$projectID' AND status=1";
		$results = mysql_query($query) or die(" ". mysql_error());
		while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$id = $line['id'];
			$name = $line['name'];
			$fileName = $line['fileName'];
			$fUserID = $line['userID'];
			$query1 = "SELECT firstName,lastName FROM users WHERE userID='$fUserID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$fullName = $line1['firstName'] . " " . $line1['lastName'];
			$date = $line['date'];
			$time = $line['time'];
			echo "<tr><td align=center><a href=\"files.php?action=delete&id=$id\">X</a></td><td><a href=\"files/".$fileName."\">$name</a></td><td>$fullName</td><td align=center>$date, $time</td></tr>\n";
		}
	}
?>
</table>
</div>

</body>
</html>
