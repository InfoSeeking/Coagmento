<?php
	session_start();
?>
<?php
	require_once("../connect.php");

	if ((isset($_SESSION['CSpace_userID']))) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="Coagmento icon" type="image/x-icon" href="../img/favicon.ico">
<?php
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='default-project'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$value = $line['value'];
			if (!$value || $value=='default') {
				$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.title='Default' AND projects.projectID=memberships.projectID";
				$results = $connection->commit($query);
				$line = mysqli_fetch_array($results, MYSQL_ASSOC);
				$projectID = $line['projectID'];
			}
			else {
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='selected-project'";
				$results = $connection->commit($query);
				$line = mysqli_fetch_array($results, MYSQL_ASSOC);
				$projectID = $line['value'];
			}
			$_SESSION['CSpace_projectID'] = $projectID;
		}
		if (isset($_POST['demographic'])) {
			$age = addslashes($_POST['age']);
			$gender = $_POST['gender'];
			$os = $_POST['os'];
			$browser = $_POST['browser'];
			$experience = $_POST['experience'];
			$often = $_POST['often'];
			$text = $_POST['text'];
			$project = $_POST['project'];
			$collabNum = $_POST['collabnum'];
			$enjoy = $_POST['enjoy'];
			$success = $_POST['success'];
			$engine = $_POST['engine'];
			if ($_POST['aim'])
				$aim = 1;
			else
				$aim = 0;
			if ($_POST['yahoo'])
				$yahoo = 1;
			else
				$yahoo = 0;
			if ($_POST['msn'])
				$msn = 1;
			else
				$msn = 0;
			if ($_POST['google'])
				$google = 1;
			else
				$google = 0;
			if ($_POST['facebook'])
				$facebook = 1;
			else
				$facebook = 0;
			$other = addslashes($_POST['other']);
			$smartphone = addslashes($_POST['smartphone']);
			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
		    $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);

			$query = "INSERT INTO demographicHS VALUES('','$userID','$age','$gender','$os','$browser','$experience','$often','$text','$project','$collabNum','$enjoy','$success','$engine','$aim','$yahoo','$msn','$google','$facebook','$other','$smartphone','$timestamp','$date','$time')";
			$result = mysql_query($query) or mysql_error();
			$ip=$_SERVER['REMOTE_ADDR'];
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','demographic','','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+100;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = $connection->commit($query);

			$content = "demographic.php?submit=true";
		}
		else if (isset($_POST['prestudy'])) {
			$tools = addslashes($_POST['tools']);
			$obs_search = addslashes($_POST['obs_search']);
			$obs_share = addslashes($_POST['obs_share']);
			$often = addslashes($_POST['often']);
			$resume = addslashes($_POST['resume']);
			$sense = addslashes($_POST['sense']);
			$notes = addslashes($_POST['notes']);
			$printout = addslashes($_POST['printout']);
			$project = addslashes($_POST['project']);
			$familiar = $_POST['familiar'];
			$experience = $_POST['experience'];
			$difficult = $_POST['difficult'];

			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
		    $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);

			$query = "INSERT INTO prestudyHS VALUES('','$userID','$timestamp','$date','$time','$tools','$obs_search','$obs_share','$often','$resume','$sense','$notes','$printout','$project','$familiar','$experience','$difficult')";
			$result = mysql_query($query) or mysql_error();
			$ip=$_SERVER['REMOTE_ADDR'];
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','pre-study','','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+100;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = $connection->commit($query);

			$content = "preStudy.php?submit=true";
		}
		else if (isset($_POST['midstudy1'])) {
			$e1q1 = $_POST['e1q1'];
			$e1q2 = $_POST['e1q2'];
			$e1q3 = $_POST['e1q3'];
			$e1q4 = $_POST['e1q4'];
			$e1q5 = $_POST['e1q5'];
			$e1q6 = $_POST['e1q6'];
			$e1q7 = $_POST['e1q7'];
			$e1q8 = $_POST['e1q8'];
			$e1q9 = $_POST['e1q9'];
			$e1q10 = $_POST['e1q10'];
			$e1q11 = $_POST['e1q11'];
			$e1q12 = $_POST['e1q12'];
			$e1q13 = $_POST['e1q13'];
			$e1q14 = $_POST['e1q14'];
			$e1q15 = $_POST['e1q15'];
			$e1q16 = $_POST['e1q16'];
			$e1q17 = $_POST['e1q17'];
			$e1q18 = $_POST['e1q18'];
			$like = addslashes($_POST['e1like']);
			$dislike = addslashes($_POST['e1dislike']);
			$e1q21 = $_POST['e1q21'];
			$e1q22 = $_POST['e1q22'];
			$e1q23 = $_POST['e1q23'];
			$e1q24 = $_POST['e1q24'];
			$e1q25 = $_POST['e1q25'];
			$e1q26 = $_POST['e1q26'];
			$e1q27 = $_POST['e1q27'];
			$e1q28 = $_POST['e1q28'];

			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
		    $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);

			$query = "INSERT INTO midstudy VALUES('','$userID','1','$e1q1','$e1q2','$e1q3','$e1q4','$e1q5','$e1q6','$e1q7','$e1q8','$e1q9','$e1q10','$e1q11','$e1q12','$e1q13','$e1q14','$e1q15','$e1q16','$e1q17','$e1q18','$like','$dislike','$e1q21','$e1q22','$e1q23','$e1q24','$e1q25','$e1q26','$e1q27','$e1q28','$date')";
			$result = mysql_query($query) or mysql_error();
			$ip=$_SERVER['REMOTE_ADDR'];
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','mid-study','1','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+200;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = $connection->commit($query);

			$content = "main.php";
		}
		else if (isset($_POST['midstudy2'])) {
			$e1q1 = $_POST['e1q1'];
			$e1q2 = $_POST['e1q2'];
			$e1q3 = $_POST['e1q3'];
			$e1q4 = $_POST['e1q4'];
			$e1q5 = $_POST['e1q5'];
			$e1q6 = $_POST['e1q6'];
			$e1q7 = $_POST['e1q7'];
			$e1q8 = $_POST['e1q8'];
			$e1q9 = $_POST['e1q9'];
			$e1q10 = $_POST['e1q10'];
			$e1q11 = $_POST['e1q11'];
			$e1q12 = $_POST['e1q12'];
			$e1q13 = $_POST['e1q13'];
			$e1q14 = $_POST['e1q14'];
			$e1q15 = $_POST['e1q15'];
			$e1q16 = $_POST['e1q16'];
			$e1q17 = $_POST['e1q17'];
			$e1q18 = $_POST['e1q18'];
			$like = addslashes($_POST['e1like']);
			$dislike = addslashes($_POST['e1dislike']);
			$e1q21 = $_POST['e1q21'];
			$e1q22 = $_POST['e1q22'];
			$e1q23 = $_POST['e1q23'];
			$e1q24 = $_POST['e1q24'];
			$e1q25 = $_POST['e1q25'];
			$e1q26 = $_POST['e1q26'];
			$e1q27 = $_POST['e1q27'];
			$e1q28 = $_POST['e1q28'];

			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
		    $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);

			$query = "INSERT INTO midstudy VALUES('','$userID','2','$e1q1','$e1q2','$e1q3','$e1q4','$e1q5','$e1q6','$e1q7','$e1q8','$e1q9','$e1q10','$e1q11','$e1q12','$e1q13','$e1q14','$e1q15','$e1q16','$e1q17','$e1q18','$like','$dislike','$e1q21','$e1q22','$e1q23','$e1q24','$e1q25','$e1q26','$e1q27','$e1q28','$date')";
			$result = mysql_query($query) or mysql_error();
			$ip=$_SERVER['REMOTE_ADDR'];
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','mid-study','2','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+200;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = $connection->commit($query);

			$content = "main.php";
		}
		else if (isset($_POST['endstudy'])) {
			$e1q1 = $_POST['e1q1'];
			$e1q2 = $_POST['e1q2'];
			$e1q3 = $_POST['e1q3'];
			$e1q4 = $_POST['e1q4'];
			$e1q5 = $_POST['e1q5'];
			$e1q6 = $_POST['e1q6'];
			$e1q7 = $_POST['e1q7'];
			$e1q8 = $_POST['e1q8'];
			$e1q9 = $_POST['e1q9'];
			$e1q10 = $_POST['e1q10'];
			$e1q11 = $_POST['e1q11'];
			$e1q12 = $_POST['e1q12'];
			$e1q13 = $_POST['e1q13'];
			$e1q14 = $_POST['e1q14'];
			$e1q15 = $_POST['e1q15'];
			$e1q16 = $_POST['e1q16'];
			$e1q17 = $_POST['e1q17'];
			$e1q18 = $_POST['e1q18'];
			$like = addslashes($_POST['e1like']);
			$dislike = addslashes($_POST['e1dislike']);
			$e1q21 = $_POST['e1q21'];
			$e1q22 = $_POST['e1q22'];
			$e1q23 = $_POST['e1q23'];
			$e1q24 = $_POST['e1q24'];
			$e1q25 = $_POST['e1q25'];
			$e1q26 = $_POST['e1q26'];
			$e1q27 = $_POST['e1q27'];
			$e1q28 = $_POST['e1q28'];
			$familiar = $_POST['familiar'];
			$motivation = $_POST['motivation'];
			$easy = $_POST['easy'];
			$comments = addslashes($_POST['comments']);

			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
		    $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);

			$query = "INSERT INTO endstudyHS VALUES('','$userID','$e1q1','$e1q2','$e1q3','$e1q4','$e1q5','$e1q6','$e1q7','$e1q8','$e1q9','$e1q10','$e1q11','$e1q12','$e1q13','$e1q14','$e1q15','$e1q16','$e1q17','$e1q18','$like','$dislike','$e1q21','$e1q22','$e1q23','$e1q24','$e1q25','$e1q26','$e1q27','$e1q28','$comments','$familiar','$motivation','$easy','$date')";
			$result = mysql_query($query) or mysql_error();
			$ip=$_SERVER['REMOTE_ADDR'];
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','end-study','','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+500;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = $connection->commit($query);

			$content = "main.php";
		}
		else if (isset($_POST['consent'])) {
			// Create an email
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: Coagmento <noreply@Coagmento.org>' . "\r\n";

			$subject = 'You have been enrolled as an official Coagmento Beta Tester';
			$message = "Hello,<br/><br/>We are glad you decided to take part in this study and officially become Coagmento Beta Tester. Frankly, it makes sense if you were planning on using Coagmento anyway since all you have to do for this study is to use Coagmento regularly! Alright, well, we expect you to do a few more things, such as filling in demographic information and some other questionnaires from time to time. Don't worry, it's really easy, you don't have to do it at a specific time or place, and it won't take more than a few moments of your time. Specifically, in order to qualify for the monthly drawing of the prizes, you need to (1) use Coagmento at least once a week and earn at least 500 points per week, (2) earn at least 5000 points by the end of the month, and (3) work on at least one collaborative project (involving at least one collaborator besides yourself) in the given month. The greater the usage you have beyond that requirement, the more chances you have for getting your name picked for a prize (iPod Nano)!<br/><br/>Now when you login to Coagmento, you will see some messages on your CSpace letting you know what information you need to submit. For starters, you should submit your demographic information and a pre-study questionnaire. Together, they should take about 10 minutes. After that, we won't bother you for a while and you can simply use Coagmento as you would have anyway!<br/><br/>Everything that you do with Coagmento earns you points and you need to meet certain requirements in terms of your usage to qualify for the prizes every months. Details can be found on the points page in your CSpace (click on your points on the top).<br/><br/>Coagmento is constantly evolving and you will keep finding more features and enhancements as we keep developing. We love to hear from you, so do drop us a line (or more) telling how you are using Coagmento and what more you would like to see!<br/><br/><strong>The Coagmento Team</strong><br/><font color=\"gray\">'cause two (or more) heads are better than one!</font><br/><a href=\"http://www.coagmento.org\">www.Coagmento.org</a><br/>p.s. Don't forget to invite your friends to use Coagmento!<br/>\n";
			mail ($email, $subject, $message, $headers);
			mail ('chirag@unc.edu', $subject, $message, $headers);
			$query = "UPDATE users SET type='Beta 2 subject' WHERE userID='$userID'";
			$results = $connection->commit($query);

			$content = "studyTerms.php?submit=true";
		}
		else if (isset($_GET['search'])) {
			$searchString = $_GET['search'];
			$content = "../services/data.php?searchString=$searchString";
		}
		else if (isset($_GET['project']))
			$content = "projectInfo.php?projectID=$projectID";
		else if (isset($_GET['profile'])) {
			if (isset($_FILES['uploaded']['name'])) {
				$target = "../img/";
				$fileName = $userID . '_'. basename($_FILES['uploaded']['name']);
				$target = $target . $fileName;
				$ok=1;
				if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) {
//					$message = "Your profile photo ". basename( $_FILES['uploadedfile']['name']). " has been updated.";
					$query1 = "UPDATE users SET avatar='$fileName' WHERE userID='$userID'";
					$results1 = $connection->commit($query1);
				}
				else {
//					echo "<br/><br/><font color=\"red\">Sorry, there was a problem uploading your file. Please try again.</font>\n";
				}
			}
			$content = "profile.php";
		}
		else if (isset($_GET['file'])) {
			if (isset($_FILES['uploaded']['name'])) {
				$name = basename($_FILES['uploaded']['name']);
				$description = addslashes($_GET['description']);
				$rand = rand(1000, 9999);
				$target = "files/";
				$fileName = $rand.$userID.$projectID . '_'. $name;
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
					$results1 = $connection->commit($query1);
				}

			}
			$content = "files.php";
		}
		else if (isset($_GET['files']))
			$content = 'files.php';
		else
			$content = 'main.php';
?>
<script type="text/javascript" src="../js/utilities.js"></script>
<script type="text/javascript">
	function loadElems() {

		var currProjID = document.getElementById('currProj');
		var requestURL = 'http://www.coagmento.org/CSpace/currentProj.php';
		req = new phpRequest(requestURL);
		var projTitle = req.execute();
		document.write(projTitle);
		var currProjID2 = document.getElementById('currProj2');
	}
	function loadContent(content) {
		location.href = content;
	}
</script>
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>
</head>
<? echo $content; ?>
<body onload="loadElems();loadContent('<?php echo $content;?>');">
</body>
</html>
<?php
	}
	else {
		header('Location: http://www.coagmento.org');
	}
?>
