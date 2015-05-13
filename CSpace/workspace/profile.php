<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<script type="text/javascript" src="../assets/js/utilities.js"></script>
<script src="assets/js/jquery-2.1.3.min.js"></script>
<link type="text/css" href="assets/css/pure-release-0.6.0/forms.css" rel="stylesheet" />
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<link type="text/css" href="assets/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="container" class="container">
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
<br>
<div class="form-container">
<table class="body" width=100%>
	<?php

		$userID = $base->getUserID();

		if (isset($_FILES['uploaded']['name'])) {
			$target = "../assets/img/";
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

		echo "<tr><td style=\"padding:0;display:inline-block;\"><table><tr><td>";
			echo "<form class=\"pure-form pure-form-aligned\" style=\"border-spacing:0;margin:0 0 0 0\">";
				echo "<fieldset style=\"margin:0 0 0 0\">";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"username\"><strong>Username</strong></label>";
				echo "$userName<input type=\"hidden\" name=\"username\" value=\"$userName\"/>";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"password\"><strong>Password*</strong></label>";
				echo "<input id=\"password\" type=\"password\" size=30 />";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"cpassword\"><strong>Confirm Password*</strong></label>";
				echo "<input id=\"cpassword\" type=\"password\" size=30 />";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"fname\"><strong>First Name</strong></label>";
				echo "<input type=\"text\" id=\"fname\" size=30 value=\"$firstName\"/>";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"lname\"><strong>Last Name</strong></label>";
				echo "<input type=\"text\" id=\"lname\"  size=30 value=\"$lastName\"/>";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"organization\"><strong>Organization</strong></label>";
				echo "<input type=\"text\" id=\"organization\" size=30 value=\"$organization\"/>";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"email\"><strong>Email</strong></label>";
				echo "<input type=\"text\" size=30 id=\"email\" value=\"$email\" disabled />";
				echo "</div>";
				echo "<br>";

				echo "<div class=\"pure-control-group\" style=\"margin:0 0 0 0\">";
				echo "<label for=\"website\"><strong>Website</strong></label>";
				echo "<input type=\"text\" size=30 id=\"website\" value=\"$website\"/>";
				echo "</div>";
				echo "<br>";

				echo "<input type=\"hidden\"   name=\"update\" value=\"true\"/>";



				echo "<center><input type=\"button\" class=\"button-submit\" value=\"Update\" style=\"align:center\" onClick=\"updateProfile();\"></center>";
				echo "<br><center><span class=\"paren-text\">*Only if you want to change your existing password.</span></center>";
				echo "</fieldset>";
				echo "</form>";

			echo "</table></td>\n";

			echo "<td valign=top halign=left style=\"padding:0;display:inline-block\">";
			echo "<table>";
			echo "<tr><td>";



				echo "<form class=\"pure-form\" action=\"profile.php\"  enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback}\">";
					echo "<center><img src=\"../assets/img/$avatar\" height=100 width=100><center>";
					echo "<center><div class=\"fileUpload button-other\" ><label for=\"inputphoto\">Browse for photos<input type=\"file\" id=\"inputphoto\" class=\"upload custom-file-input\"></label></div></center>";
					echo "<center><input type=\"submit\" class=\"button-submit\"  style=\"width:90%\" value=\"Upload\"/></center>";
					echo "</form>";




				echo "</td></tr>\n";
		    echo "</table></td></tr>\n\n";




		//echo "<table class=\"body\">\n";
    //echo "<input type=\"hidden\"   name=\"update\" value=\"true\"/></td></tr>\n";
    //echo "</table>\n";
    //echo "</td>";
		//echo "<td valign=top>";
		//echo "<table>";
    //echo "<tr><td>";
		//echo "<p><img src=\"../assets/img/$avatar\" height=100 width=100></p>";
    //echo "<form action=\"profile.php\"  enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback}\">";
    //echo "<div class=\"fileUpload button-other\" style=\"width:100%\"><span>Browse for photos...</span><input type=\"file\" class=\"upload\" /></div>";
		//echo "<input type=\"submit\" class=\"button-submit\" style=\"width:100%\" value=\"Upload\"/></form>";
		//echo "</td></tr>\n";
    //echo "</table></td></tr></table>\n\n";

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
