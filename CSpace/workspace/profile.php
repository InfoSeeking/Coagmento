<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="container">
<h3>Profile</h3>

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
<div class="form-container">
<table class="body" width=100%>
	<?php

		$userID = $base->getUserID();

		if (isset($_FILES['uploaded']['name'])) {
			$target = "../img/";
			$fileName = $userID . '_'. basename($_FILES['uploaded']['name']);
			$target = $target . $fileName;
			$ok=1;
			if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
				$query1 = "UPDATE users SET avatar='$fileName' WHERE userID='$userID'";
				$results1 = $connection->commit($query1);
			}
		}

		// If update profile info was sent
		if (isset($_GET['fname'])) {
			$fname = $_GET['fname'];
			$lname = $_GET['lname'];
			$organization = $_GET['organization'];
			$email = $_GET['email'];
			$website = $_GET['website'];
			if (isset($_GET['password'])) {
				$password = sha1($_GET['password']);
				$query = "UPDATE users SET password='$password', firstName='$fname',lastName='$lname',organization='$organization',email='$email',website='$website' WHERE userID='$userID'";
			}
			else
				$query = "UPDATE users SET firstName='$fname',lastName='$lname',organization='$organization',email='$email',website='$website' WHERE userID='$userID'";
			$results = $connection->commit($query);
			echo "<tr><td><font color=\"green\">Your profile has been updated.</font></td></tr>";

		}
		$query = "SELECT * FROM users WHERE userID='$userID'";
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$userName = $line['username'];
		$firstName = $line['firstName'];
		$lastName = $line['lastName'];
		$organization = $line['organization'];
		$email = $line['email'];
		$website = $line['website'];
		$avatar = $line['avatar'];
		echo "<table class=\"body\"><tr><td>";
		echo "<table class=\"body\">\n";
	    echo "<tr><td>Username</td><td>$userName<input type=\"hidden\" name=\"username\" value=\"$userName\"/></td></tr>\n";
        echo "<tr><td>Password*</td><td><input id=\"password\" type=\"password\" size=30 /></td></tr>\n";
        echo "<tr><td>Confirm Password*</td><td><input id=\"cpassword\" type=\"password\" size=30 /></td></tr>\n";
        echo "<tr><td>First Name</td><td><input type=\"text\" id=\"fname\" size=30 value=\"$firstName\"/></td></tr>\n";
        echo "<tr><td>Last Name</td><td><input type=\"text\" id=\"lname\"  size=30 value=\"$lastName\"/></td></tr>\n";
        echo "<tr><td>Organization</td><td><input type=\"text\" id=\"organization\" size=30 value=\"$organization\"/></td></tr>\n";
        echo "<tr><td>Email</td><td><input type=\"text\" size=30 id=\"email\" value=\"$email\" disabled /></td></tr>\n";
        echo "<tr><td>Website</td><td><input type=\"text\" size=30 id=\"website\" value=\"$website\"/></td></tr>\n";
        echo "<tr><td><input type=\"button\" class=\"button-submit\" value=\"Update\" onClick=\"updateProfile();\">";
				echo "<tr><td colspan=2><span class=\"paren-text\">*Only if you want to change your existing password.</span></td></tr>\n";
        echo "<input type=\"hidden\"   name=\"update\" value=\"true\"/></td></tr>\n";
        echo "</table>\n";
        echo "</td><td valign=top><table>";
        echo "<tr><td>";
				echo "<p><img src=\"../../img/$avatar\" height=100 width=100></p>";

        echo "<form action=\"profile.php\"  enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback}\">";
        echo "<div class=\"fileUpload button-other\" style=\"width:100%\"><span>Browse for photos...</span><input type=\"file\" class=\"upload\" /></div>";
				echo "<input type=\"submit\" class=\"button-submit\" style=\"width:100%\" value=\"Upload\"/></form>";
				echo "</td></tr>\n";
        echo "</table></td></tr></table>\n\n";

	?>
</table>
</div>

<?php
echo "<br/><br/><br/><br/>";
	}
?>
</div>

</body>
</html>
