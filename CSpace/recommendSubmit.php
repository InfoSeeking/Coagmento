<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		require_once("connect.php");
		$query = "SELECT * FROM users WHERE userID='$userID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$senderName = $line['firstName'] . " " . $line['lastName'];
		$senderEmail = $line['email'];
		$title = $_GET['title'];
		$url = $_GET['url'];
		$message = addslashes($_GET['message']);
		date_default_timezone_set('America/New_York');
		$timestamp = time();
		$datetime = getdate();
	    $date = date('Y-m-d', $datetime[0]);
		$time = date('H:i:s', $datetime[0]);
		
		// Create an email
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: $senderName <$senderEmail>" . "\r\n";

		$subject = 'You have a recommendation from a Coagmento user';
		$messageToSend = "Hello,<br/><br/><strong>$senderName</strong> has recommended a webpage to you: <a href=\"$url\">$title</a>.";
		if ($message) {
			$message = stripslashes($message);
			$messageToSend = $messageToSend . "<br/>His/her message to you:<br/>$message";
		}
		$messageToSend = $messageToSend . "<br/><br/><strong>The Coagmento Team</strong><br/><font color=\"gray\">'cause two (or more) heads are better than one!</font><br/><a href=\"http://www.coagmento.org\">www.Coagmento.org</a><br/>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="Coagmento icon" type="image/x-icon" href="../img/favicon.ico">
<title>Coagmento</title>
<link rel="stylesheet" href="css/styles.css" type="text/css" />
</head>
<body class="body">
<table class="body" width=90%>
	<tr><td align="right"><img src="../img/recommend.jpg" height=40 style="vertical-align:middle;border:0" /> <span style="font-weight:bold;font-size:15px">Recommend a Webpage</span></td></tr>
	<tr><td><br/></td></tr>
	<tr><td>Your recommendation of webpage <a href="<?php echo $url;?>"><?php echo $title;?></a> was sent to the following collaborators:</td></tr>
	<tr><td><br/></td></tr>	
	<tr>
		<td>
			<table class="body">
		<?php
			$query = "SELECT * FROM memberships WHERE userID='$userID' AND projectID='$projectID' GROUP BY projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
				$projectID = $line['projectID'];
				$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND userID!='$userID' GROUP BY userID";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
					$cUserID = $line1['userID'];
					if (isset($_GET[$cUserID])) {
						$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
						$results2 = mysql_query($query2) or die(" ". mysql_error());
						$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
						$receiverEmail = $line2['email'];
						mail ($receiverEmail, $subject, $messageToSend, $headers);
						
						$query2 = "INSERT INTO recommendations VALUES('','$userID','$projectID','$cUserID','$title','$url','$message','$timestamp','$date','$time')";
						$results2 = mysql_query($query2) or die(" ". mysql_error());

						// Record the action and update the points
						$aQuery = "SELECT max(id) as num FROM recommendations WHERE userID='$userID'";
						$aResults = mysql_query($aQuery) or die(" ". mysql_error());
						$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
						$rID = $aLine['num'];
						
						$ip=$_SERVER['REMOTE_ADDR'];
						$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','recommend','$rID','$ip')";
						$aResults = mysql_query($aQuery) or die(" ". mysql_error());
						
						$pQuery = "SELECT points FROM users WHERE userID='$userID'";
						$pResults = mysql_query($pQuery) or die(" ". mysql_error());
						$pLine = mysql_fetch_array($pResults, MYSQL_ASSOC);
						$totalPoints = $pLine['points'];
						$newPoints = $totalPoints+10;
						$pQuery = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
						$pResults = mysql_query($pQuery) or die(" ". mysql_error());
						
						$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
						$results2 = mysql_query($query2) or die(" ". mysql_error());
						$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
						$userName = $line2['firstName'] . " " . $line2['lastName'];
						$avatar = $line2['avatar'];
						echo "<tr><td> <img src=\"../img/$avatar\" width=30 height=30 /> </td><td> $userName</td></tr>";
					}
				}
			}
		?>
			</table>
		</td>
	</tr>
	<tr><td><br/></td></tr>	
	<tr><td>Email recommendation sent to:</td></tr>
	<?php
		$emails = $_GET['emails'];
		if ($emails) {
			$email = explode(",",$emails);
			$i = 0;
			$message = addslashes($message);
			while ($email[$i]) {
				$query2 = "INSERT INTO recommendations VALUES('','$userID','$projectID','$email[$i]','$title','$url','$message','$timestamp','$date','$time')";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				mail ($email[$i], $subject, $messageToSend, $headers);
				$i++;
			}
		}
	?>
	<tr><td><?php echo $emails;?></td></tr>
	<tr><td>Message sent:</td></tr>	
	<tr><td><?php echo stripslashes($message);?></td></tr>		
	<tr><td><br/></td></tr>	
	<tr><td align="center"><input type="button" value="Close" onclick="window.close();"/></td></tr>
</table>
</body>
</html>
<?php
	}
?>