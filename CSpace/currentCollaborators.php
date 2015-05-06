<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
include('links_header.php');
?>


<script type="text/javascript">
	$(document).ready(function(){
		$(".flip").click(function(){
			$(".panel").slideToggle("slow");
		});
	});
</script>

<?php
	include('services/func.php');
?>
</head>

<body>

<?php include('header.php'); ?>

<div id="container">
<h3>Add a Collaborator</h3>

<?php
	session_start();
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	require_once("connect.php");
?>
<table>
	<?php
		$query = "SELECT * FROM memberships WHERE userID='$userID' AND projectID='$projectID' GROUP BY projectID";
		$results = mysql_query($query) or die(" ". mysql_error());
		while($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$projectID = $line['projectID'];
			$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND userID!='$userID' GROUP BY userID";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			while($line1 = mysql_fetch_array($results1, MYSQL_ASSOC)) {
				$cUserID = $line1['userID'];
				$access = $line1['access'];
				$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
				$results2 = mysql_query($query2) or die(" ". mysql_error());
				$line2 = mysql_fetch_array($results2, MYSQL_ASSOC);
				$userName = $line2['firstName'] . " " . $line2['lastName'];
				$avatar = $line2['avatar'];
				echo "<tr><td><img src=\"../img/$avatar\" width=20 height=20 /></td><td><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showCollaborator.php?userID=$cUserID','content');\">$userName</span></td><td>";
				if ($access!=1)
					echo "<span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('collaborators.php?remove=$cUserID&projID=$projectID','content');\">X</span>";
				echo "</td></tr>";
			}
		}
		echo "<tr><td colspan=4><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('collaborators.php','content');\">See all your collaborators</span></td></tr>\n";
	?>
</table>

</div>

</body>
</html>
