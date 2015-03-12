<?php
	session_start();
	require_once("connect.php");
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
    $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	
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
			require_once("header.php");
?>
    	<!-- Content region -->

    	<div id="main-content">
	<div class="page-content">
The login information you entered does not match our records. Please <a href="http://www.coagmento.org/">try again</a>.
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
            setcookie("CSpace_projectID", 0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>Coagmento - Collaborative Information Seeking/Retrieval/Search/Sense-Making System</title>
<meta http-equiv="REFRESH" content="0;url=http://www.coagmento.org/CSpace/index.php?projects=all&objects=all&years=all&months=all&displayMode=timeline&formSubmit=Submit"></HEAD>
<BODY>
If you do not get redirected to your CSpace, click <a href="http://www.coagmento.org/CSpace/index.php?projects=all&objects=all&years=all&months=all&displayMode=timeline&formSubmit=Submit">here</a>.
</BODY>
</HTML>
<?php			
		}
?>
    	</div> <!-- /main-content -->
		</div>
    	<!-- End of Content region -->
<?php
	}
	
		if ($_GET['logout']=='true') {
			require_once("header.php");
			$userID = $_SESSION['CSpace_userID'];
			$projectID = $_SESSION['CSpace_projectID'];
			$ip=$_SERVER['REMOTE_ADDR'];
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','logout','','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			session_destroy();
			setcookie("CSpace_userID");
?>
	    	<div id="main-content">
    	  
    	  <div class="row-fluid wrapper">
<!--
    	    <div class="span12">
    	      <h1 class="page-title">About Coagmento</h1>
    	    </div>
-->
			You have been successfully logged out of your CSpace.</font><br/>Thank you for using Coagmento. See you back soon!<br/><br/>
			Continue to <a href="http://www.coagmento.org">Coagmento homepage</a>.
    	  </div>
    	  
    	  <div class="row-fluid wrapper">
    	  
    	  </div>
    	  </div>
<?php
	}

	require_once("footer.php");
?>
