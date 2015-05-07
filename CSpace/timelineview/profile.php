<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<LINK REL=StyleSheet HREF="../assets/css/style_timelineview.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<?php
  session_start();
  include('../services/func.php');
?>

<script type="text/javascript">
$(document).ready(function(){
$(".flip").click(function(){
    $(".panel").slideToggle("slow");
  });
});
</script>
</head>

<body>

  <?php include('../header.php'); ?>

<div id="container">
<h3>Profile</h3>

<?php

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>
	<script type="text/javascript" src="js/webtoolkit.aim.js"></script>
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
		$userID = $_SESSION['CSpace_userID'];

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
		echo "<table><tr><td>";
		echo "<table class=\"body\">\n";
	    echo "<tr><td>Username</td><td>$userName<input type=\"hidden\" name=\"username\" value=\"$userName\"/></td></tr>\n";
        echo "<tr><td>Password*</td><td><input id=\"password\" type=\"password\" size=30 /></td></tr>\n";
        echo "<tr><td>Confirm Password*</td><td><input id=\"cpassword\" type=\"password\" size=30 /></td></tr>\n";
        echo "<tr><td>First Name</td><td><input type=\"text\" id=\"fname\" size=30 value=\"$firstName\"/></td></tr>\n";
        echo "<tr><td>Last Name</td><td><input type=\"text\" id=\"lname\"  size=30 value=\"$lastName\"/></td></tr>\n";
        echo "<tr><td>Organization</td><td><input type=\"text\" id=\"organization\" size=30 value=\"$organization\"/></td></tr>\n";
        echo "<tr><td>Email</td><td><input type=\"text\" size=30 id=\"email\" value=\"$email\" disabled /></td></tr>\n";
        echo "<tr><td>Website</td><td><input type=\"text\" size=30 id=\"website\" value=\"$website\"/></td></tr>\n";
        echo "<tr><td colspan=2>*Only if you want to change your existing password.</td></tr>\n";
        echo "<tr><td><input type=\"button\" value=\"Update\" onClick=\"updateProfile();\" />";
        echo "<input type=\"hidden\" name=\"update\" value=\"true\"/></td></tr>\n";
        echo "</table>\n";
        echo "</td><td valign=top><table>";
        echo "<tr><td><img src=\"../../img/$avatar\" height=100 width=100><br/><br/></td></tr>\n";
        echo "<tr><td>";
        echo "<form action=\"index.php?profile\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback}\">";
        echo "Upload a new picture: <input name=\"uploaded\" type=\"file\"/><br/><input type=\"submit\" value=\"Upload\"/></form></td></tr>\n";
/*         echo "<tr><td align=center><form action=\"index.php?profile\" enctype=\"multipart/form-data\" method=\"POST\">Upload a new picture: <input name=\"uploaded\" type=\"file\"/><br/><td align=center><input type=\"submit\" value=\"Upload\"/></form></td></tr>\n"; */
        echo "</table></td></tr></table>\n<br/><br/><br/><br/>\n";
	?>
</table>
<?php
	}
?>
</div>

</body>
</html>
