<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
<h3>Files</h3>
<?php
	session_start();
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $_SESSION['CSpace_userID'];
		$projectID = $_SESSION['CSpace_projectID'];
?>
	<script type="text/javascript" src="js/webtoolkit.aim.js"></script>
	<script type="text/javascript">
		function startCallback() {
			// make something useful before submit (onStart)
			return true;
		}

		function completeCallback(response) {
			// make something useful after (onComplete)
			document.getElementById('nr').innerHTML = parseInt(document.getElementById('nr').innerHTML) + 1;
			document.getElementById('r').innerHTML = response;
		}
	</script>

<table class="body" width=100%>
	<?php
		require_once("../connect.php");

        echo "<tr><td><form action=\"index.php?file\" enctype=\"multipart/form-data\" method=\"post\" onsubmit=\"return AIM.submit(this, {'onStart' : startCallback, 'onComplete' : completeCallback}\">";
        echo "Upload a new file: <input name=\"uploaded\" type=\"file\"/> <input type=\"submit\" value=\"Upload\"/></form></td></tr>\n";

        echo "<tr><td><br/></td></tr>\n";
        echo "</table>\n";
        echo "<table class=\"body\" width=100%>\n";

		echo "<tr><td style=\"background:#EFEFEF;font-weight:bold\" colspan=4>Existing files for your active project</td></tr>\n";
		echo "<tr><td style=\"font-weight:bold\" align=center>Delete</td><td style=\"font-weight:bold\">Name</td><td style=\"font-weight:bold\">Uploaded by</td><td style=\"font-weight:bold\" align=center>Time</td></tr>\n";
		$query = "SELECT * FROM files WHERE userID='$userID' AND projectID='$projectID' AND status=1";
		$results = $connection->commit($query);
		while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$name = $line['name'];
			$fileName = $line['fileName'];

			$fUserID = $line['userID'];
			$query1 = "SELECT firstName,lastName FROM users WHERE userID='$fUserID'";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$fullName = $line1['firstName'] . " " . $line1['lastName'];
			$date = $line['date'];
			$time = $line['time'];
			echo "<tr><td align=center>X</td><td><a href=\"files/".$fileName."\">$name</a></td><td>$fullName</td><td align=center>$date, $time</td></tr>\n";

		}
	}
?>
</table>
</div>

</body>
</html>
