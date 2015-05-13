<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="container" class="container">
<h3>Add a Collaborator</h3>

<?php

	session_start();
	require_once("../core/Connection.class.php");
	require_once("../core/Base.class.php");
	require_once("../core/Util.class.php");
	require_once("../services/utilityFunctions.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$ip=$base->getIP();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $base->getUserID();

?>

	<table class="body" width=100%>
	<?php




		if (isset($_GET['targetUserName'])) {
			$results = $connection->commit("SELECT * FROM users WHERE userID='$userID'");
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$firstName = $line['firstName'];
			$lastName = $line['lastName'];

			$targetUserName = $_GET['targetUserName'];
			$userExists = 0;

			$results = $connection->commit("SELECT count(*) as num FROM users WHERE username='$targetUserName'");
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];

			if ($num!=1) {
				// If we didn't find a match with the username, try it as an email.
				$results = $connection->commit("SELECT count(*) as num FROM users WHERE email='$targetUserName'");
				$line = mysqli_fetch_array($results, MYSQL_ASSOC);
				$num = $line['num'];
				if ($num!=1) {
					echo "<tr><td colspan=2><font color=\"red\">Error: this user does not exist in the system.</font></td></tr>\n";
				} // if the user doesn't exist
				else {
					$userExists = 1;
					$results1 = $connection->commit("SELECT * FROM users WHERE email='$targetUserName'");
					$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
				}
			}
			else {
				$userExists = 1;
				$results1 = $connection->commit("SELECT * FROM users WHERE username='$targetUserName'");
				$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
			}

			if ($userExists==1) {
				$targetUserID = $line1['userID'];
				$targetUserName = $line1['username'];
				$targetFirstName = $line1['firstName'];
				$targetLastName = $line1['lastName'];
				$projectID = $_SESSION['CSpace_projectID'];
				$results = $connection->commit("SELECT count(*) as num FROM memberships WHERE projectID='$projectID' and userID='$targetUserID'");
				$line = mysqli_fetch_array($results, MYSQL_ASSOC);
				$num = $line['num'];

				if ($num!=0) {
					echo "<tr><td colspan=2><font color=\"red\">Error: this user is already a collaborator for your currently active project.</font></td></tr>\n";
				} // if the user is already a collaborator
				else {
					$targetEmail = $line1['email'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = $connection->commit("SELECT * FROM projects WHERE projectID='$projectID'");
					$line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$query = "INSERT INTO memberships VALUES('','$projectID','$targetUserID','0')";
					$results = $connection->commit("INSERT INTO memberships VALUES('','$projectID','$targetUserID','0')");

					// Get the date, time, and timestamp
					$timestamp = $base->getTimestamp();
					$date = $base->getDate();
					$time = $base->getTime();

					// Record the action and update the points
					$aResults = $connection->commit("SELECT max(memberID) as num FROM memberships WHERE projectID='$projectID'");
					$aLine = mysqli_fetch_array($aResults, MYSQL_ASSOC);
					$rID = $aLine['num'];

					Util::getInstance()->saveAction('add-collaborator',"$rID",$base);
					addPoints($userID,100);

					// Create an email
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: Coagmento Support <support@coagmento.org>' . "\r\n";

					$subject = 'You have been added as a collaborator';
					$message = "Hello, $targetFirstName $targetLastName,<br/><br/>This is to inform you that <strong>$firstName $lastName</strong> has just added you to their project <strong>$title</strong> as a collaborator.<br/><br/>Do not reply to this email. Visit your <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace\">CSpace</a> to access your projects. Your username is <strong>$targetUserName</strong>.<br/><br/><strong>The Coagmento Team</strong><br/><font color=\"gray\">'cause two (or more) heads are better than one!</font><br/><a href=\"http://www.coagmento.org\">www.Coagmento.org</a><br/>\n";
					mail ($targetEmail, $subject, $message, $headers);
					echo "<tr><td colspan=2><font color=\"green\"><span style=\"font-weight:bold\">$targetFirstName $targetLastName</span> has been added as a collaborator for project <span style=\"font-weight:bold\">$title</span>.</font></td></tr>";
				} // If the user exists and he is not a collaborator.
				echo "<tr><td><br/></td></tr>";
			} // if ($userExists==1)
		} // if (isset($_GET['targetUserName']))
	?>
	<tr><td>Enter the <span style="font-weight:bold;">username or email</span> of the person you want to have onboard this project.</td></tr><tr><td>This person needs to be a Coagmento user.<br/><br/></td></tr>
	<tr><td><input type="text" size=40 id="inviteEmail" onKeyDown="if (event.keyCode == 13) document.getElementById('aButton').click();" /> <input type="button" class="button-submit" value="Add" id="aButton" onclick="inviteCollab();" /></td></tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<div id="sureInvite"></div>
		</td>
	</tr>
</table>
<script type="text/javascript">
	document.getElementById('inviteEmail').focus();
</script>
<?php
	}
?>

</body>
</html>
