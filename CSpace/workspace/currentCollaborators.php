<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<script src="assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="../assets/js/utilities.js"></script>
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<link type="text/css" href="assets/css/pure-release-0.6.0/tables.css" rel="stylesheet" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	include('../services/func.php');
?>
</head>

<body>

<?php include('views/header.php'); ?>

<div id="content">
<div id="container" class="container">
<h3>Add a Collaborator</h3>
<br>

<?php
	session_start();
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");

	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$userID = $base->getUserID();
	$projectID = $base->getProjectID();

?>
<table class="pure-table pure-table-bordered">

	<thead >
	<tr>
			<th class="th-text">Collaborator</th>
			<th class="th-text">Remove from Project?</th>
	</tr>

</thead>
	<tbody>
	<?php
		$query = "SELECT * FROM memberships WHERE userID='$userID' AND projectID='$projectID' GROUP BY projectID";
		$results = $connection->commit($query);
		while($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
			$projectID = $line['projectID'];
			$query1 = "SELECT * FROM memberships WHERE projectID='$projectID' AND userID!='$userID' GROUP BY userID";
			$results1 = $connection->commit($query1);
			while($line1 = mysqli_fetch_array($results1, MYSQL_ASSOC)) {
				$cUserID = $line1['userID'];
				$access = $line1['access'];
				$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
				$results2 = $connection->commit($query2);
				$line2 = mysqli_fetch_array($results2, MYSQL_ASSOC);
				$userName = $line2['firstName'] . " " . $line2['lastName'];
				$avatar = $line2['avatar'];
				echo "<tr><td><img src=\"../assets/img/$avatar\" width=20 height=20 /><span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('showCollaborator.php?userID=$cUserID','content');\">$userName</span></td>";
				if ($access!=1)
					echo "<td><span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('collaborators.php?remove=$cUserID&projID=$projectID','content');\">X</span>";
				echo "</td></tr>";
			}
		}
	?>
</tbody>
</table>
<span style=\"color:blue;text-decoration:underline;cursor:pointer;\" onClick=\"ajaxpage('collaborators.php','content');\">See all your collaborators</span>

</div>
</div>

</body>
</html>
