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
          require_once('./core/Base.class.php');
        	require_once("./core/Connection.class.php");
        	require_once("./core/Util.class.php");
        	$base = Base::getInstance();
        	$connection = Connection::getInstance();

					$userID = $base->getUserID();
					$projectID = $base->getProjectID();
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
<h3>Create a Project</h3>
<!-- <table class="body" width=100%>
	<tr>
		<td colspan=2>
			<span style="cursor:pointer;"><div onclick="switchMenu('help');">Click here to expand or collapse the help.</div></span>
		</td>
	</tr>
	<tr>
		<td colspan=2>
			<div id="help" style="display:none;text-align:left;font-size:11px;">
			Here you can create a new project. All you need is a name (title) for it. Once you create a project, you can invite others to join!
			</div>
		</td>
	</tr>
</table> -->
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>

<table class="body" width=100%>

<?php
	require_once("../connect.php");
	$userID = $_SESSION['CSpace_userID'];

	// If new project information was sent
	if (isset($_GET['title'])) {
		$title = addslashes($_GET['title']);
		if ($title == "") {
			echo "<tr><td colspan=2><font color=\"red\">Error: project title cannot be empty. Please try again.</font></td></tr>";
		} // if ($title == "")
		else {
			$query = "SELECT * FROM projects,memberships WHERE projects.title='$title' AND memberships.userID='$userID' AND projects.projectID=memberships.projectID";
			$results = mysql_query($query) or die(" ". mysql_error());
			$num = mysql_num_rows($results);
			if ($num!=0) {
				echo "<tr><td colspan=2><font color=\"red\">Error: project <span style=\"font-weight:bold\">$title</span> already exists. Please choose a different title for your project.</font></td></tr>";
			} // if ($num!=0)
			else {
				$description = addslashes($_GET['description']);
				$privacy = $_GET['privacy'];
				// Get the date, time, and timestamp
				date_default_timezone_set('America/New_York');
				$timestamp = time();
				$datetime = getdate();
			    $startDate = date('Y-m-d', $datetime[0]);
				$startTime = date('H:i:s', $datetime[0]);
				$query = "INSERT INTO projects VALUES('','$title','$description','$startDate','$startTime','1','$privacy')";
				$results = mysql_query($query) or die(" ". mysql_error());
				$query = "SELECT max(projectID) as num FROM projects";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				$projectID = $line['num'];
				$query = "INSERT INTO memberships VALUES('','$projectID','$userID','1')";
				$results = mysql_query($query) or die(" ". mysql_error());

				// Record the action and update the points
				$aQuery = "SELECT max(projectID) as num FROM projects";
				$aResults = mysql_query($aQuery) or die(" ". mysql_error());
				$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
				$pID = $aLine['num'];

				$ip=$base->getIP();
        Util::getInstance()->saveAction('create-project',"$pID",$base);


				$pQuery = "SELECT points FROM users WHERE userID='$userID'";
				$pResults = mysql_query($pQuery) or die(" ". mysql_error());
				$pLine = mysql_fetch_array($pResults, MYSQL_ASSOC);
				$totalPoints = $pLine['points'];
				$newPoints = $totalPoints+100;
				$pQuery = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
				$pResults = mysql_query($pQuery) or die(" ". mysql_error());

				echo "<tr><td colspan=2><font color=\"green\">Your new project <span style=\"font-weight:bold\">$title</span> has been created.</font></td></tr>";
			} // else with if ($num!=0)
		} // else with if ($title == "")
	} // if (isset($_GET['title']))
?>
	<tr><td>
		<table class="style1">
		<tr><td><strong>Title</strong></td><td><input id="projTitle" type="text" size=41 /></td><td></td></tr>
		<tr><td><strong>Description<br/>(optional)</strong></td><td><textarea id="projDesc" cols=30 rows=4></textarea></td>
			<td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;
				<div id="sureCreate" style="width: 500px; background: #fff !important;"></div>
			</td>
		</tr>
		<tr><td><strong>Privacy</strong></td>
			<td>
				<input type="radio" name="privacy" id="public" /> Public (any user can search and join this project)<br/>
				<input type="radio" name="privacy" id="private" checked /> Private (you will have to invite others to join)<br/>
			</td>
			<td></td>
		</tr>
		<tr><td></td><td><span style="color:gray;">Remember, you will be the owner of this project, so no matter what setting<br/> you choose, you can remove any of your collaborators at any time.</span></td><td></td></tr>
		<tr><td><input type=button value="Create" onClick="createProj();"/></td><td></td></tr>
		</table>
		</td>
	</tr>

<?php
	echo "<tr><td><table class=\"style1\"><tr><td>&nbsp;</td></tr>";
	echo "<tr><td><span style=\"font-size: 16px; font-weight:bold\">Existing projects</span></td></tr><tr>\n";
	$query = "SELECT * FROM memberships WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
		$projectID = $line['projectID'];
		$access = $line['access'];
		$query1 = "SELECT * FROM projects WHERE projectID='$projectID' AND status=1";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$projectID = $line1['projectID'];
		$title = $line1['title'];

		// If the current user didn't create this project, find out who did
		if ($access!=1) {
			$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND access=1";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$uID = $line1['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$uID'";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$uName = $line1['username'];
			$title = $title . " ($uName)";
		}
//			$description = $line1['description'];
		$startDate = $line1['startDate'];
		echo "<td><a href='projectInfo.php?projectID=$projectID' class='existing_projects'>$title</a></td>";
	}
	echo "</tr></table></td></tr>\n";
	echo "</table></td></tr>\n";
	echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
?>
</table>
<?php
	}
?>
</div>

</body>
</html>
