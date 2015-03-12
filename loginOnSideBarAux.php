<?php
	session_start();
	require_once("connect.php");
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
        $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	if ($_GET['logout']=='true') {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
		$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','logout','')";
		$aResults = mysql_query($aQuery) or die(" ". mysql_error());
		session_destroy();
		setcookie("CSpace_userID");
?>

<?php
		echo "<table class=\"body\">\n";
		echo "<tr><td colspan=2><font color=\"green\">You have been successfully logged out of your CSpace.</font><br/>Thank you for using Coagmento. See you back soon!</font><br/><br/></td></tr>\n";
		echo "<tr><td colspan=2>Continue to <a href=\"http://www.coagmento.org\">Coagmento homepage</a>.</td></tr>\n";
	}
	else {
		// If the user tried to login
		if (isset($_POST['userName'])) {
			$userName = $_POST['userName'];
			$password = sha1($_POST['password']);
			$query = "SELECT * FROM users WHERE username='$userName' AND password='$password' AND active=1";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$userID = $line['userID'];
			$points = $line['points'];
			$loginCount = $line['loginCount'];
			$name = $line['firstName'] . " " . $line['lastName'];

			// If the login information was incorrect
			if (mysql_num_rows($results)==0) {
	?>
                        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<link rel="Coagmento icon" type="image/x-icon" href="img/favicon.ico">
			<title>Coagmento</title>
			<link rel="stylesheet" href="css/styles.css" type="text/css" />

			</head>
			<body>
			<center>
			<br/>
			<br/>
			<table class="body">
			<tr><td>The login information you entered does not match our records. Please <a href="http://www.coagmento.org/loginOnSideBar.php">try again</a>.</td></tr>
                        </table>
                        </center>
                        </body>
                        </html>
	<?php
			}
			else {
				$newPoints = $points+10;
				$loginCount++;
				$query = "SELECT max(timestamp) as num FROM actions WHERE userid='$userID'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				$lastActionTimestamp = $line['num'];

				$query = "UPDATE users SET points=$newPoints,lastLoginDate='$date',lastLoginTime='$time',loginCount='$loginCount',lastActionTimestamp='$lastActionTimestamp' WHERE username='$userName'";
				$results = mysql_query($query) or die(" ". mysql_error());
                                $ip=$_SERVER['REMOTE_ADDR'];
				$aQuery = "INSERT INTO actions VALUES('','$userID','0','$timestamp','$date','$time','login','','$ip')";
				$aResults = mysql_query($aQuery) or die(" ". mysql_error());

				$_SESSION['CSpace_userID'] = $userID;
                                $_SESSION['userName'] = $_POST['userName'];
                                $_SESSION['orderBySnippets'] = 'snippetID asc';
                                $_SESSION['orderByPages'] = 'pageID asc';
                                $_SESSION['orderByQueries'] = 'queryID asc';
                                $_SESSION['orderByFiles'] = 'id asc';
				setcookie("CSpace_userID", $userID);
                                header("Location: http://www.coagmento.org/CSpace/newsidebar.php?flagLogin=true");
			}
		}
		mysql_close($dbh);
	}
	?>
       