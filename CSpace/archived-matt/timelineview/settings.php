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

<?php include('header.php'); ?>

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
		echo "<tr><td>Page status (views, annotations, snippets) on the toolbar is currently on. <a href='settings.php?option=page-status&value=off'>Turn it off</a>.<br/><span style=\"color:gray;\">You may have to switch to a different tab or reload a page afterward to see its effect.</span></td></tr>\n";
	echo "</td></tr>\n";
	echo "<tr><td><br/></tr>\n";

	echo "<tr><td><span style=\"font-weight:bold\">Default Project</span></td></tr>\n";
	$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='default-project'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$value = $line['value'];
	if (!$value || $value=='default')
		echo "<tr><td>The default selected project when you login to Coagmento is 'Default'. <a href='settings.php?option=default-project&value=last'>Make the last selected project as the default</a>.<br/><span style=\"color:gray;\">This will come into effect the next time you login.</span></td></tr>\n";
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
