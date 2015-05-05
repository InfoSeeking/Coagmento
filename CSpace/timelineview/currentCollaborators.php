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
<h3>View Collaborators</h3>
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
?>
<!-- <table class="body" width=100%>
	<tr>
		<td colspan=2>
			<div id="help" style="display:none;text-align:left;font-size:11px;background:#EFEFEF">
			Collaborators are other people that you may want to have involved in your project. When someone becomes your collaborator/teammate for a project, he/she can <span style="font-weight:bold">see all the things that you do under that project</span>. This involves the websites you visited and the searches you did (given that Coagmento was activated during that time), and the objects you saved. Your collaborators <span style="font-weight:bold">cannot delete</span> any of your records. They can read and comment on them. Using Coagmento, you can chat with your collaborators, share information, recommend websites to them, and create a common product (e.g., a report or homework assignment) using workspace.
			</div>
		</td>
	</tr>
</table> -->
<table class="body" width=100%>
		<?php
			require_once("../connect.php");
			// If there was a request to remove a collaborator
			if (isset($_GET['remove'])) {
				$removeID = $_GET['remove'];
				$projID = $_GET['projID'];
				$query3 = "SELECT title FROM projects WHERE projectID='$projID'";
				$results3 = mysql_query($query3) or die(" ". mysql_error());
				$line3 = mysql_fetch_array($results3, MYSQL_ASSOC);
				$title = $line3['title'];
				$query2 = "SELECT * FROM users WHERE userID='$removeID'";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
				$userName = $line2['firstName'] . " " . $line2['lastName'];
				echo "<tr><td>Are you sure you want to remove <span style=\"font-weight:bold\">$userName</span> from project <span style=\"font-weight:bold\">$title</span>?</td></tr>\n";
				echo "<tr><td><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('collaborators.php?uID=$removeID&projID=$projID','content');\">Yes</span>&nbsp;&nbsp;&nbsp;<span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('collaborators.php','content');\">No</span></td></tr>\n";
			}
			else {
?>
	<tr><td>Your existing collaborators are shown below. Click on 'X' to remove them from the corresponding project.</td></tr>
	<tr><td>If you don't see a 'X' next to a project name, that project is owned by your collaborator. You can only leave from such a project.</td></tr>
	<tr><td>To leave a project, see the list of <a href="projects.php">your projects</a>.<br/><br/></td></tr>
	<tr>
		<td>
			<?php
				// If the request to remove a collaborator was confirmed
				if (isset($_GET['uID'])) {
					$removeID = $_GET['uID'];
					$projID = $_GET['projID'];
					$query1 = "DELETE FROM memberships WHERE userID='$removeID' AND projectID='$projID'";
					$results1 = mysql_query($query1) or die(" ". mysql_error());
					echo "<tr><td><span style=\"color:green;\">A collaborator successfully removed.</span></td></tr>\n";
					echo "<tr><td><br/></td></tr>\n";
				}
				$query = "SELECT mem2.* FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' GROUP BY mem2.userID";
				$results = mysql_query($query) or die(" ". mysql_error());
				while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
					$projectID = $line['projectID'];
					$cUserID = $line['userID'];
					$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
					$userName = $line2['firstName'] . " " . $line2['lastName'];
					$avatar = $line2['avatar'];
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"../../img/$avatar\" width=20 height=20 /> <a href='showCollaborator.php?userID=$cUserID'>$userName</a> <font color=\"gray\"> for projects</font>: ";
					$query2 = "SELECT mem2.* FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' AND mem2.userID='$cUserID'";
					$results2 = mysql_query($query2) or die(" ". mysql_error());
					while ($line2 = mysql_fetch_array($results2, MYSQL_ASSOC)) {
						$cProjectID = $line2['projectID'];
						$query4 = "SELECT access FROM memberships WHERE projectID='$cProjectID' AND userID='$userID'";
						$results4 = mysql_query($query4) or die(" ". mysql_error());
						$line4 = mysql_fetch_array($results4, MYSQL_ASSOC);
						$access = $line4['access'];
						$query3 = "SELECT title FROM projects WHERE projectID='$cProjectID'";
						$results3 = mysql_query($query3) or die(" ". mysql_error());
						$line3 = mysql_fetch_array($results3, MYSQL_ASSOC);
						echo $line3['title'];
						if ($access==1)
							echo " (<a href='collaborators.php?remove=$cUserID&projID=$cProjectID' style='color: #ff0000; text-decoration: none;'>X</a>)";
						echo ", ";
					}
					echo "<br/>";
				}
			}
		?>
		</td>
	</tr>
</table>
<?php
	}
?>
</div>

</body>
</html>
