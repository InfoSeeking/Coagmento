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
  include('../func.php');
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
<h3>Join a Project</h3>
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
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
			Here is a list of available open projects on Coagmento. You can freely join any project. However, the owner of the project has the right to reject your membership. You can create your public projects by choosing 'Public' option for privacy while creating a project.
			</div>
		</td>
	</tr>
</table>-->
<table class="body" width=100%>
	<td><div id="sureJoin"></div><div id="sureDelete"></div></td>
<?php
	require_once("../connect.php");
	$userID = $_SESSION['CSpace_userID'];
	echo "<tr><td><table class=\"style1\">";
	if (isset($_GET['projectID'])) {
		$projectID = $_GET['projectID'];
		$query = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$title = stripslashes($line['title']);
		$query = "INSERT INTO memberships VALUES('','$projectID','$userID','0')";
		$results = mysql_query($query) or die(" ". mysql_error());
		echo "<tr><td colspan=3><font color=\"green\">You have just joined project <span style=\"font-weight:bold\">$title</span>.</font></td></tr>";
		echo "<tr><td colspan=3><br/></td></tr>\n";

		$query = "SELECT * FROM users WHERE userID='$userID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$firstName = $line['firstName'];
		$lastName = $line['lastName'];

		$query = "SELECT * FROM memberships WHERE projectID='$projectID' AND access=1";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$uID = $line['userID'];

		$query = "SELECT * FROM users WHERE userID='$uID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$targetUserName = $line['username'];
		$targetFirstName = $line['firstName'];
		$targetLastName = $line['lastName'];
		$targetEmail = $line['email'];

		$title = addslashes($title);
		// Create an email
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: Coagmento Support <support@coagmento.org>' . "\r\n";

		$subject = 'You have a new collaborator!';
		$message = "Hello, $targetFirstName $targetLastName,<br/><br/>This is to inform you that <strong>$firstName $lastName</strong> has just joined your  project <strong>$title</strong> as a collaborator.<br/><br/>Do not reply to this email. Visit your <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace\">CSpace</a> to access your projects. Your username is <strong>$targetUserName</strong>.<br/><br/><strong>The Coagmento Team</strong><br/><font color=\"gray\">'cause two (or more) heads are better than one!</font><br/><a href=\"http://www.coagmento.org\">www.Coagmento.org</a><br/>\n";
		mail ($targetEmail, $subject, $message, $headers);
/*
		echo "$targetEmail, $subject<br/>\n";
		echo "$message<br/>\n";
*/
		mail ('chirags@rutgers.edu', $subject, $message, $headers);
	}
	echo "<tr><th><span style=\"font-weight:bold\">Title</span></th><th>&nbsp;&nbsp;</th><th><span style=\"font-weight:bold\">Started on</span></th><th>&nbsp;&nbsp;</th><th><span style=\"font-weight:bold\">Membership</span></th></tr>\n";
	$query = "SELECT * FROM projects WHERE privacy=0";
	$results = mysql_query($query) or die(" ". mysql_error());
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$projectID = $line['projectID'];
		$startDate = $line['startDate'];
		$title = stripslashes($line['title']);
		$description = stripslashes($line['description']);
		$query1 = "SELECT * FROM memberships WHERE projectID='$projectID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$members = "";
		$belongsTo = 0;
		while ($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
			$uID = $line1['userID'];
			$access = $line1['access'];
			$query2 = "SELECT * FROM users WHERE userID='$uID'";
			$results2 = mysql_query($query2) or die(" ". mysql_error());
			$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
			$uName = $line2['username'];
			$firstName = $line2['firstName'];
			$lastName = $line2['lastName'];
			if ($userID==$uID)
				$belongsTo = 1;
			if ($access==0)
				$members = $members . $firstName . " " . $lastName . "($uName), ";
			else {
				$ownerID = $uID;
				$owner = $firstName . " " . $lastName . "($uName)";
			}
		}

		if ($belongsTo)
			echo "<tr><td><a href='projectInfo.php?projectID=$projectID'>$title</a><br/><span style=\"color:gray;\">$description</span></td><td>&nbsp;&nbsp;</td><td>$startDate</td><td>&nbsp;&nbsp;</td><td align=center><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"deleteProj('$projectID','$title');\">Leave</span>";
		else
			echo "<tr><td style=\"font-weight:bold\">$title<br/><span style=\"color:gray;\">$description</span></td><td>&nbsp;&nbsp;</td><td>$startDate</td><td>&nbsp;&nbsp;</td><td align=center><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"joinProj('$projectID','$title');\">Join</span>";
		echo "</td></tr>\n";
		echo "<tr><td colspan=5>Created by: $owner; Other members: $members<br/><hr/></td></tr>\n";
	}
	echo "</table></td></tr>\n";
	echo "</table></td></tr>\n";
	echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	}
?>
</div>

</body>
</html>
