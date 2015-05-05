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
<h3>Projects</h3>
<?php
	session_start();
	ob_start();
	require_once("../connect.php");
	$pageName = "CSpace/timelineview/selectProject.php";

	if (isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$userID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$firstName = $line1['firstName'];
		$lastName = $line1['lastName'];
		if (isset($_GET['projectID'])) {
			$projectID = $_GET['projectID'];
			$_SESSION['projectID'] = $projectID;
			setcookie("CSpace_projectID", $projectID);
			echo "<br/><br/><center>\n<table class=\"body\">\n";
			echo "<tr bgcolor=#DDDDDD><td>Hello, <strong>$firstName $lastName</strong>.</td></tr>\n";
			$query = "SELECT * FROM projects WHERE projectID='$projectID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$title = $line['title'];
			echo "<tr><td>You have selected <strong>$title</strong> as your active project.<br/></td></tr>\n";
			echo "<tr><td>Once you activate <em>Coagmento</em> from your toolbar, the following things will be recorded:\n";
			echo "<ul><li>URLs you enter</li>\n<li>Queries you execute on search sites</li>\n<li>Any link you click on a webpage</li>\n";
			echo "</td></tr>\n";
			echo "<tr><td>You can also save a page, collect snippets, and make annotations to a page or snippets.<br/>These will all be saved under the project you have selected.</td></tr>\n";
			echo "<tr><td>You can access all these records by clicking on '<a href=\"log.php\">My logs</a>' on your <a href=\"index.php\">CSpace</a>.</td></tr>\n";
			echo "<tr><td>You can change your active project by visiting your <a href=\"index.php\">CSpace</a>.</td></tr>\n";
			echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
		}
		else {
			echo "<br/><br/><center>\n<table class=\"body\">\n";
			echo "<tr><td>Sorry. Looks like we had trouble knowing what project you want to work on!<br/>Please try <a href=\"index.php\">selecting this</a> again.</td></tr>\n";
			echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
		}
	}
	else {
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr><td>Sorry. Looks like we had trouble knowing who you are!<br/>Please try <a href=\"index.php\">logging in</a> again.</td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	}
?>
</div>

</body>
</html>
