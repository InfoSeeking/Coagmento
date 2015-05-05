<?php /*
include('user_agent.php'); // Redirecting http://mobile.site.info
// site.com data */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<LINK REL=StyleSheet HREF="../assets/css/style_timelineview.css" TYPE="text/css" MEDIA=screen>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
<script type="text/javascript" src="../js/utilities.js"></script>

<?php
  include('func.php');
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

<div id="topbar">
	<div class="left" style="float: left; "> <!-- min-width: 790px; width: 60%; -->
        <h2><a href="index.php">Coagmento CSpace</a></h2><br/>
    </div>

        	<div style="float: left;">
    				<?php
					session_start();
					require_once('../connect.php');
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
					/* <td><img src=\"../../img/$avatar\" width=45 height=45 style=\"vertical-align:middle;border:0\" /></td> */
					echo "<div class='top_links' style='border-left: 1px solid #ccc; padding-left: 15px;'><table style='font-size: 12px;'><tr><td valign=\"middle\">&nbsp;&nbsp;Welcome, <span style=\"font-weight:bold\">$userName</span> to your <a href='main.php'>CSpace</a>.<br/>&nbsp;&nbsp;Current login: $lastLogin<br/>&nbsp;&nbsp;Points earned: <a href='points.php'>$points</a></td><td valign=\"middle\">&nbsp;&nbsp;</td><td valign=\"middle\">&nbsp;&nbsp;You have <a href='projects.php?userID=$userID'>$projectNums projects</a> and <a href='collaborators.php?userID=1'>$collabNums collaborators</a>.<br/>&nbsp;&nbsp;<span id=\"currProj\"></span><br/>&nbsp;&nbsp;<a href='projects.php?userID=$userID'>Select a different project.</a></td></tr></table></div>";
				?>
                </div>

    <div class="right" style="position: fixed; top: 25px; right: 20px;">

    	<p class="flip" style="float: right;"><!-- <img src="../assets/img/menu_dark.png" /> --> <?php echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/img/'.$avatar.'" width=45 height=45 style="vertical-align:middle;border:3px solid #000;">'; ?><br/><img src="../assets/img/arrow.png"/></p>
        <div style="clear:both;"></div>
        <div class="panel">
        	<table>
            	<tr>
                	<td valign="top" width="150">
                    	<b>Collaborators</b><br/>
                        <a href="../addCollaborator.php">Add</a>
                        <a href="../currentCollaborators.php">View</a><br/>

                        <b>Projects</b>
                        <a href="../createProject.php">Create</a>
                        <a href="../projects.php">Select</a>
                        <a href="../showPublicProjs.php">Join</a>
                    </td>
                	<td valign="top" width="150">
                    	<b>Sharing</b>
                        <a href="../showRecommendations.php">Recommendations</a>
                        <a href="../interProject.php">Inter-project</a><br/>

                   		<b>Workspace</b>
                        <a href="../etherpad.php">Editor</a>
                        <a href="../files.php">Files</a>
                        <a href="../printreport.php">Print reports</a>
                    </td>
                    <td valign="top" width="150">
                    	<b>Settings</b>
                        <a href="../profile.php">Profile</a>
                        <a href="../settings.php">Options</a>
                    </td>
                </tr>
                <!-- <tr height="10">
                	<td></td>
                </tr> -->
                <!-- <tr>
                	<td colspan=3 valign="top">
                    	<a href="" style="color: #95ba23 !important; border: 0 !important; display: inline-block !important;">CSpace</a>&nbsp;&nbsp;
                		<a href="" style="color: #95ba23 !important; border: 0 !important; display: inline-block !important;">Log out</a>
                    </td>
				</tr> -->
            </table>
        </div>
    </div>

</div>

<div id="container">
<h3>Add a Collaborator</h3>

<?php
	session_start();
	$ip=$_SERVER['REMOTE_ADDR'];
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
?>
	<!-- <table class="body" width=100%>
	<tr bgcolor="#EFEFEF">
		<td colspan=2>
			<span style="cursor:pointer;"><div onclick="switchMenu('help');">Click here to expand or collapse the help.</div></span>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="help" style="display:none;text-align:left;font-size:11px;background:#EFEFEF">
			You can invite someone to collaborate with you on your current project. Enter his/her username here. They will receive an email announcing this collaboration. You will have the right to remove them from the project later.
			</div>
		</td>
	</tr>
</table> -->
<table class="body" width=100%>
	<?php
		if (isset($_GET['targetUserName'])) {
			require_once("../connect.php");
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$firstName = $line['firstName'];
			$lastName = $line['lastName'];

			$targetUserName = $_GET['targetUserName'];
			$userExists = 0;

			$query = "SELECT count(*) as num FROM users WHERE username='$targetUserName'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];

			if ($num!=1) {
				// If we didn't find a match with the username, try it as an email.
				$query = "SELECT count(*) as num FROM users WHERE email='$targetUserName'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				$num = $line['num'];
				if ($num!=1) {
					echo "<tr><td colspan=2><font color=\"red\">Error: this user does not exist in the system.</font></td></tr>\n";
				} // if the user doesn't exist
				else {
					$userExists = 1;
					$query1 = "SELECT * FROM users WHERE email='$targetUserName'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
				}
			}
			else {
				$userExists = 1;
				$query1 = "SELECT * FROM users WHERE username='$targetUserName'";
				$results1 = mysql_query($query1) or die(" ". mysql_error());
				$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			}

			if ($userExists==1) {
				$targetUserID = $line1['userID'];
				$targetUserName = $line1['username'];
				$targetFirstName = $line1['firstName'];
				$targetLastName = $line1['lastName'];
				$projectID = $_SESSION['CSpace_projectID'];
				$query = "SELECT count(*) as num FROM memberships WHERE projectID='$projectID' and userID='$targetUserID'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				$num = $line['num'];

				if ($num!=0) {
					echo "<tr><td colspan=2><font color=\"red\">Error: this user is already a collaborator for your currently active project.</font></td></tr>\n";
				} // if the user is already a collaborator
				else {
					$targetEmail = $line1['email'];
					$query1 = "SELECT * FROM projects WHERE projectID='$projectID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
					$title = $line1['title'];
					$query = "INSERT INTO memberships VALUES('','$projectID','$targetUserID','0')";
					$results = mysql_query($query) or die(" ". mysql_error());

					// Get the date, time, and timestamp
					date_default_timezone_set('America/New_York');
					$timestamp = time();
					$datetime = getdate();
					$date = date('Y-m-d', $datetime[0]);
					$time = date('H:i:s', $datetime[0]);

					// Record the action and update the points
					$aQuery = "SELECT max(memberID) as num FROM memberships WHERE projectID='$projectID'";
					$aResults = mysql_query($aQuery) or die(" ". mysql_error());
					$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
					$rID = $aLine['num'];

					$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','add-collaborator','$rID','$ip')";
					$aResults = mysql_query($aQuery) or die(" ". mysql_error());

					$pQuery = "SELECT points FROM users WHERE userID='$userID'";
					$pResults = mysql_query($pQuery) or die(" ". mysql_error());
					$pLine = mysql_fetch_array($pResults, MYSQL_ASSOC);
					$totalPoints = $pLine['points'];
					$newPoints = $totalPoints+100;
					$pQuery = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
					$pResults = mysql_query($pQuery) or die(" ". mysql_error());

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
	<tr><td><input type="text" size=40 id="inviteEmail" onKeyDown="if (event.keyCode == 13) document.getElementById('aButton').click();" /> <input type="button" value="Add" id="aButton" onclick="inviteCollab();" /></td></tr>
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
</div>

</body>
</html>
