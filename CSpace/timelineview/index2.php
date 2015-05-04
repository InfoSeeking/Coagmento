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
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$value = $line['value'];
			if (!$value || $value=='default') {
				$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.title='Default' AND projects.projectID=memberships.projectID";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				$projectID = $line['projectID'];
			}
			else {
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='selected-project'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
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
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+100;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());

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
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+100;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());

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
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+200;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());

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
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+200;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());

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
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$points = $line['points'];
			$newPoints = $points+500;
			$query = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());

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
			$results = mysql_query($query) or die(" ". mysql_error());

			$content = "studyTerms.php?submit=true";
		}
		else if (isset($_GET['search'])) {
			$searchString = $_GET['search'];
			$content = "data.php?searchString=$searchString";
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
					$results1 = mysql_query($query1) or die(" ". mysql_error());
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
//					$message = "Your profile photo ". basename( $_FILES['uploadedfile']['name']). " has been updated.";
					$query1 = "INSERT INTO files VALUES('','$userID','$projectID','$timestamp','$date','$time','$name','$fileName','1')";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
				}
				else {
//					echo "<br/><br/><font color=\"red\">Sorry, there was a problem uploading your file. Please try again.</font>\n";
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
<!-- <script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script><script type="text/javascript">FB.init("cb95c84269498d94eff10edd6223b863");</script>
<table class="body">
	<tr>
		<td class="menu" align="center"><img src="../img/coagmento4.jpg" style="vertical-align:bottom;border:0;cursor:pointer;" height=44  onClick="ajaxpage('showProgress.php','content');ajaxpage('main.php','content');" /></td>
		<td align="left">
			<table class="body">
				<?php
					$userID = $_SESSION['CSpace_userID'];
					$projectID = $_SESSION['CSpace_projectID'];
					$query = "SELECT * FROM users WHERE userID='$userID'";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$userName = $line['firstName'] . " " . $line['lastName'];
					$avatar = $line['avatar'];
					$lastLogin = $line['lastLoginDate'] . ", " . $line['lastLoginTime'];
					$points = $line['points'];
					$query = "SELECT count(*) as num FROM memberships WHERE userID='$userID'";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$projectNums = $line['num'];
					$query = "SELECT count(distinct mem2.userID) as num FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID'";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$collabNums = $line['num'];
					echo "<tr><td><img src=\"../img/$avatar\" width=45 height=45 style=\"vertical-align:middle;border:0\" /></td><td valign=\"middle\">&nbsp;&nbsp;Welcome, <span style=\"font-weight:bold\">$userName</span> to your <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('main.php','content');\">CSpace</span>.<br/>&nbsp;&nbsp;Current login: $lastLogin<br/>&nbsp;&nbsp;Points earned: <span style=\"color:blue;text-decoration:underline;cursor:pointer;font-weight:bold\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('points.php','content');\">$points</span></td><td valign=\"middle\">&nbsp;&nbsp;</td><td valign=\"middle\">&nbsp;&nbsp;You have <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('projects.php?userID=$userID','content');\">$projectNums projects</span> and <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('collaborators.php?userID=1','content');\">$collabNums collaborators</span>.<br/>&nbsp;&nbsp;<span id=\"currProj\"></span><br/>&nbsp;&nbsp;<span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showProgress.php','content');ajaxpage('projects.php?userID=$userID','content');\">Select a different project.</td></tr>";
				?>
			</table>
		</td>
		<td align="right"><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('help.php', 'content');">Help</a><br/><a href="../login.php?logout=true">Logout</a></td>
	</tr>
	<tr>
		<td valign="top">
			<ul class="acc" id="acc">
				<li>
					<h3><img src="../img/collab.jpg" width=40 style="vertical-align:middle;border:0" /> Collaborators<br/><font color="gray">Add or remove collaborators for your projects, or become a collaborator.</font></h3>
					<div class="acc-section">
						<div class="acc-content">
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('addCollaborator.php', 'content');"><img src="../img/add.jpg" width=18 style="vertical-align:middle;border:0" />Add someone</span> as a collaborator.<br/><span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('showPublicProjs.php', 'content');"><img src="../img/updates.jpg" width=18 style="vertical-align:middle;border:0" />Join an open project</span><br/>
							Collaborators for the current project:<br/>
							<div id="currentCollaborators"></div>
						</div>
					</div>
				</li>
				<li>
					<h3><img src="../img/projects.jpg" width=40 style="vertical-align:middle;border:0" /> Projects<br/><font color="gray">Add and manipulate your projects.</font></h3>
					<div class="acc-section">
						<div class="acc-content">
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('createProject.php?userID=<?php echo $userID;?>','content');"><img src="../img/add.jpg" width=18 style="vertical-align:middle;border:0" />Create</span> a new project.<br/>
							<span id="currProj2"/></span><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('projects.php?userID=<?php echo $userID;?>','content');">Select a different project</span> to work with.
						</div>
					</div>
				</li>
				<li>
					<h3><img src="../img/data.jpg" width=40 style="vertical-align:middle;border:0" /> Data & Information<br/><font color="gray">Explore data and information about you and your collaborators.</font></h3>
					<div class="acc-section">
						<div class="acc-content">
							See the <span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('data.php?objects=pages','content');">data</span> collected about/by you.<br/>
							See <span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('allData.php?objects=pages','content');">everyone's data</span>.<br/>
						</div>
					</div>
				</li>
				<li>
					<h3><img src="../img/workspace.jpg" width=40 style="vertical-align:middle;border:0" /> Workspace<br/><font color="gray">Explore your collected information and produce results using the workspace.</font></h3>
					<div class="acc-section">
						<div class="acc-content">
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('printreport.php','content');">Print reports</span><br/>
							<a href="www.coagmento.org/CSpace/etherpad.php">Editor</a><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('files.php','content');">Files</span><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('tags.php','content');">Tags</span>
						</div>
					</div>
				</li>
				<li>
					<h3><img src="../img/connect.jpg" width=40 style="vertical-align:middle;border:0" /> Connect<br/><font color="gray">Connect with your collaborators, and let the system connect your projects.</font></h3>
					<div class="acc-section">
						<div class="acc-content">
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('showRecommendations.php','content');">Recommended by your collaborators</span><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('interProject.php', 'content');">Inter-project analysis</span>
						</div>
					</div>
				</li>

				<li>
					<h3><img src="../img/settings2.jpg" width=40 style="vertical-align:middle;border:0" /> Tools & Settings<br/><font color="gray">Update your profile, change settings for CSpace and Coagmento plug-in.</font></h3>
					<div class="acc-section">
						<div class="acc-content">
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('profile.php?userID=1', 'content');">My profile</span><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('settings.php','content');">Settings</span><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('recommendCoagmento.php','content');">Recommend Coagmento</span><br/>
							<span style="color:blue;text-decoration:underline;cursor:pointer;" onClick="ajaxpage('showProgress.php','content');ajaxpage('terms.php','content');">Terms & Conditions</span>
						</div>
					</div>
				</li>
			</ul>
			<br/>
			<!-- AddThis Button BEGIN -->
<!-- <a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;pub=chirag"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pub=chirag"></script>
<!-- AddThis Button END -->
	<!--	</td>
		<td colspan=2 valign="top">
			<div id="content" style="vertical-align:top"></div>
		</td>
	</tr>
</table>

<script type="text/javascript" src="script.js"></script> -->

</body>
</html>
<?php
	}
	else {
		header('Location: http://www.coagmento.org');
	}
?>
