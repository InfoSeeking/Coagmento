<?php /*
include('user_agent.php'); // Redirecting http://mobile.site.info
// site.com data */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
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
<h3>Options</h3>
<?php
	session_start();
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
			Here you can configure various options for your Coagmento toolbar, sidebar, and the CSpace.
			</div>
		</td>
	</tr>
</table> -->
<table class="body" width=100%>
<?php
	require_once("../connect.php");
	if (isset($_GET['option'])) {
		$option = $_GET['option'];
		$value = $_GET['value'];
		switch ($option) {
			case 'page-status':
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='page-status'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				if (mysql_num_rows($results)==0) {
					$query = "INSERT INTO options VALUES('','$userID','0','$option','$value')";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				else {
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND `option`='page-status'";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				break;

			case 'default-project':
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='default-project'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				if (mysql_num_rows($results)==0) {
					$query = "INSERT INTO options VALUES('','$userID','0','$option','$value')";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				else {
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND `option`='default-project'";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				break;

			case 'sidebar-chat':
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-chat'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				if (mysql_num_rows($results)==0) {
					$query = "INSERT INTO options VALUES('','$userID','0','$option','$value')";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				else {
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND `option`='sidebar-chat'";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				break;

			case 'sidebar-history':
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-history'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				if (mysql_num_rows($results)==0) {
					$query = "INSERT INTO options VALUES('','$userID','0','$option','$value')";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				else {
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND `option`='sidebar-history'";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				break;

			case 'sidebar-notepad':
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-notepad'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				if (mysql_num_rows($results)==0) {
					$query = "INSERT INTO options VALUES('','$userID','0','$option','$value')";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				else {
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND `option`='sidebar-notepad'";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				break;

			case 'sidebar-notifications':
				$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-notifications'";
				$results = mysql_query($query) or die(" ". mysql_error());
				$line = mysql_fetch_array($results, MYSQL_ASSOC);
				if (mysql_num_rows($results)==0) {
					$query = "INSERT INTO options VALUES('','$userID','0','$option','$value')";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				else {
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND `option`='sidebar-notifications'";
					$results = mysql_query($query) or die(" ". mysql_error());
				}
				break;
		}
	}
	echo "<tr><td><table class=\"style1\">";
	echo "<tr><td><span style=\"font-weight:bold\">Page Status</span></td></tr>\n";
	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='page-status'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='off')
		echo "<tr><td>Page status (views, annotations, snippets) on the toolbar is currently off. <a href='settings.php?option=page-status&value=on'>Turn it on</a>.<br/><span style=\"color:gray;\">You may have to switch to a different tab or reload a page afterward to see its effect.</span></td></tr>\n";
	else
		echo "<tr><td>Page status (views, annotations, snippets) on the toolbar is currently on. <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('settings.php?option=page-status&value=off', 'content');\">Turn it off</span>.<br/><span style=\"color:gray;\">You may have to switch to a different tab or reload a page afterward to see its effect.</span></td></tr>\n";
	echo "</td></tr>\n";
	echo "<tr><td><br/></tr>\n";

	echo "<tr><td><span style=\"font-weight:bold\">Default Project</span></td></tr>\n";
	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='default-project'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='default')
		echo "<tr><td>The default selected project when you login to Coagmento is 'Default'. <span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('settings.php?option=default-project&value=last', 'content');\">Make the last selected project as the default</span>.<br/><span style=\"color:gray;\">This will come into effect the next time you login.</span></td></tr>\n";
	else
		echo "<tr><td>The default selected project when you login to Coagmento is the last selected project. <a href='settings.php?option=default-project&value=default'>Make 'Default' as the default</a>.<br/><span style=\"color:gray;\">This will come into effect the next time you login.</span></td></tr>\n";
	echo "</td></tr>\n";
	echo "<tr><td><br/></tr>\n";

	echo "<tr><td><span style=\"font-weight:bold\">Sidebar Modules</span></td></tr>\n";
	echo "<tr><td>Select the modules you want to see in your Coagmento sidebar.<br/><span style=\"color:gray;\">You will have to re-open the sidebar after making your selections.</span></td></tr>\n";
	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-chat'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='on')
		echo "<tr><td><input type=checkbox checked onclick=\"settings.php?option=sidebar-chat&value=off\"/> Chat</td></tr>\n";
	else
		echo "<tr><td><input type=checkbox onclick=\"settings.php?option=sidebar-chat&value=on\"/> Chat</td></tr>\n";

	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-history'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='on')
		echo "<tr><td><input type=checkbox checked onclick=\"settings.php?option=sidebar-history&value=off\"/> History</td></tr>\n";
	else
		echo "<tr><td><input type=checkbox onclick=\"settings.php?option=sidebar-history&value=on\"/> History</td></tr>\n";

	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-notepad'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='on')
		echo "<tr><td><input type=checkbox checked onclick=\"settings.php?option=sidebar-notepad&value=off\"/> Notepad</td></tr>\n";
	else
		echo "<tr><td><input type=checkbox onclick=\"settings.php?option=sidebar-notepad&value=on\"/> Notepad</td></tr>\n";

	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='sidebar-notifications'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='on')
		echo "<tr><td><input type=checkbox checked onclick=\"settings.php?option=sidebar-notifications&value=off\"/> Notifications</td></tr>\n";
	else
		echo "<tr><td><input type=checkbox onclick=\"settings.php?option=sidebar-notifications&value=on\"/> Notifications</td></tr>\n";
	echo "</table>\n";
	}
?>
</div>

</body>
</html>
