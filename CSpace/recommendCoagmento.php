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
<h3>Recommend Coagmento</h3>

<?php
	session_start();
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("utilityFunctions.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();


	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
	$userID = $base->getUserID();
	$projectID = $base->getProjectID();
?>

<table class="body" width=100%>
	<?php
		if (isset($_GET['inviteEmail'])) {

			$code = get_rand_id(10);
			$inviteEmail = $_GET['inviteEmail'];

			if ($inviteEmail!="") {
				// First see if this user is already in the system
				$query = "SELECT count(*) as num FROM users WHERE email='$inviteEmail'";
				$results = $connection->commit($query);
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				$num = $line['num'];

				if ($num!=0) {
					echo "<tr><td colspan=2><font color=\"red\">Error: this email is already associated with a user in the system.</font></td></tr>";
				} // if ($num!=0)
				else {
					$timestamp = $base->getTimestamp();
					$date = $base->getDate();
					$time = $base->getTime();
					$pastTimestamp = $timestamp - 518400;

					// Now see if this email has already been used for an invitation
					$query = "SELECT count(*) as num FROM invitations WHERE email='$inviteEmail' AND timestamp>$pastTimestamp";
					$results = $connection->commit($query);
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$num = $line['num'];
					if ($num!=0) {
						echo "<tr><td colspan=2><font color=\"red\">Error: this person has already been sent an invitation in the past one week.</font></td></tr>";
					} // if ($num!=0)
					else {
						$query = "SELECT * FROM users WHERE userID='$userID'";
						$results = $connection->commit($query);
						$line = mysql_fetch_array($results, MYSQL_ASSOC);
						$firstName = $line['firstName'];
						$lastName = $line['lastName'];
						$rEmail = $line['email'];
						$userMessage = urldecode($_GET['message']);

						// Create an email
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
						$headers .= 'From: Coagmento <support@coagmento.org>' . "\r\n";

						$subject = 'Somebody has invited you to try Coagmento!';
						$message = "Hello, <br/><br/><strong>$firstName $lastName</strong> ($rEmail) has invited you to try out Coagmento - a plug-in for Firefox browser that allows you to collect online information (text snippets, pictures, etc.) effectively, keep track of your search and browse history, and connect with your friends and co-workers without leaving your browser!";
						if ($userMessage!='')
							$message = $message . "<br/><br/>His/her personal message to you: $userMessage<br/><br/>";
						$message = $message . " Coagmento also provides you with an online collaborative space (called CSpace) with which you can easily and effectively organize personal and group information, get recommendations based on your browsing, and produce reports. You can even get access to your projects on supported mobile devices. So why wait? Try it out now!<br/><br/>Please click on <a href=\"http://www.coagmento.org/index.php?code=$code\">this link</a> to open a FREE account. If you cannot click on this link, please copy and paste the following in your browser: http://www.coagmento.org/index.php?code=$code<br/><br/>See you there!<br/><strong>The Coagmento Team</strong><br/><font color=\"gray\">'cause two (or more) heads are better than one!</font><br/><a href=\"http://www.coagmento.org\">www.Coagmento.org</a><br/>\n";
						mail ($inviteEmail, $subject, $message, $headers);

						$userMessage = addslashes($userMessage);
						$query = "INSERT INTO invitations VALUES('','$userID','$inviteEmail','$userMessage','$timestamp','$date','$time','$code')";
						$results = $connection->commit($query);

						// Record the action and update the points
						$aQuery = "SELECT max(id) as num FROM invitations WHERE userID='$userID'";
						$aResults = $connection->commit($aQuery);
						$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
						$rID = $aLine['num'];

						Util::getInstance()->saveAction("recommend-coagmento","$rID",$base);
						addPoints($userID,100);

						echo "<tr><td colspan=2>Thank you for recommending Coagmento! An email has been sent to <span style=\"color:green;font-weight:bold\">$inviteEmail</span> with a unique link to open a free Coagmento account. You have been awarded 100 points for this. If the receiver accepts this invitation and opens an account, you will get additional 200 points!</font></td></tr>";
					} // else with if ($num!=0)
				} // else with if ($num!=0)
			} // if ($inviteEmail)
			else {
				echo "<tr><td colspan=2><font color=\"red\">Error: email cannot be left blank. Please try again.</font><br/></td></tr>";
			} // else with if ($inviteEmail)
		} // if (isset($_GET['inviteEmail']))
	?>
	<tr><td>Enter an email of the person you want to invite for using Coagmento.</td></tr>
	<tr><td><input type="text" size=80 id="inviteEmail" /></td></tr>
	<tr><td>Your message (optional)</td></tr>
	<tr><td><textarea id="message" rows=4 cols=78></textarea></td></tr>
	<tr><td><input type="button" value="Recommend Coagmento" onclick="recommendCoagmento();" /></td></tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<div id="sureInvite"></div>
		</td>
	</tr>
</table>
<?php
	}
?>

</body>
</html>
