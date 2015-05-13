<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<script src="assets/js/jquery-2.1.3.min.js"></script>
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<link type="text/css" href="assets/css/pure-release-0.6.0/tables.css" rel="stylesheet" />

<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	session_start();
	include('../services/func.php');
?>
</head>

<body>


<?php
include('views/header.php');
?>
<div id="container" class="container">
<h3>View Collaborators</h3>

<?php
	require_once("../core/Connection.class.php");
	require_once("../core/Base.class.php");
	require_once("../core/Util.class.php");
	require_once("../services/utilityFunctions.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
		$userID = $base->getUserID();
?>

<table class="body" width=100%>
		<?php

			// If there was a request to remove a collaborator
			if (isset($_GET['remove'])) {
				$removeID = $_GET['remove'];
				$projID = $_GET['projID'];
				$query3 = "SELECT title FROM projects WHERE projectID='$projID'";
				$results3 = $connection->commit($query3);
				$line3 = mysqli_fetch_array($results3, MYSQL_ASSOC);
				$title = $line3['title'];
				$query2 = "SELECT * FROM users WHERE userID='$removeID'";
				$results2 = $connection->commit($query2);
				$line2 = mysqli_fetch_array($results2, MYSQL_ASSOC);
				$userName = $line2['firstName'] . " " . $line2['lastName'];
				echo "<tr><td>Are you sure you want to remove <span style=\"font-weight:bold\">$userName</span> from project <span style=\"font-weight:bold\">$title</span>?</td></tr>\n";
				echo "<tr><td><a href='collaborators.php?uID=$removeID&projID=$projID'>Yes</a>&nbsp;&nbsp;&nbsp;<a href='collaborators.php'>No</a></td></tr>\n";
			}
			else {
?>
	<tr><td>Your existing collaborators are shown below. Click on 'X' to remove them from the corresponding project.</td></tr>
	<tr><td>If you don't see a 'X' next to a project name, that project is owned by your collaborator. You can only leave from such a project.</td></tr>
	<tr><td>To leave a project, see the list of <a href="projects.php">your projects</a>.</td></tr>
	<tr><td><br/></td></tr>
	<tr>
		<td>
			<table class="pure-table pure-table-bordered">

				<thead >
        <tr>
            <th class="th-text">Collaborator</th>
            <th class="th-text">Projects</th>
        </tr>

    	</thead>
			<tbody>
			<?php
				$query = "SELECT mem2.* FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' GROUP BY mem2.userID";
				$results = $connection->commit($query);
				while($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
					$projectID = $line['projectID'];
					$cUserID = $line['userID'];
					$query2 = "SELECT * FROM users WHERE userID='$cUserID'";
					$results2 = $connection->commit($query2);
					$line2 = mysqli_fetch_array($results2, MYSQL_ASSOC);
					$userName = $line2['firstName'] . " " . $line2['lastName'];
					$avatar = $line2['avatar'];
					echo "&nbsp;&nbsp;&nbsp;&nbsp;<tr><td><img src=\"../assets/img/$avatar\" width=20 height=20 /> <a href='showCollaborator.php?userID=$cUserID'>$userName</a> <font color=\"gray\"> for projects</font>: </td>";
					$query2 = "SELECT mem2.* FROM memberships as mem1,memberships as mem2 WHERE mem1.userID!=mem2.userID AND mem1.projectID=mem2.projectID AND mem1.userID='$userID' AND mem2.userID='$cUserID'";
					$results2 = $connection->commit($query2);
					echo "<td><ul>";
					while ($line2 = mysqli_fetch_array($results2, MYSQL_ASSOC)) {
						echo "<li>";
						$cProjectID = $line2['projectID'];
						$query4 = "SELECT access FROM memberships WHERE projectID='$cProjectID' AND userID='$userID'";
						$results4 = $connection->commit($query4);
						$line4 = mysqli_fetch_array($results4, MYSQL_ASSOC);
						$access = $line4['access'];
						$query3 = "SELECT title FROM projects WHERE projectID='$cProjectID'";
						$results3 = $connection->commit($query3);
						$line3 = mysqli_fetch_array($results3, MYSQL_ASSOC);
						echo $line3['title'];
						if ($access==1)
							echo " <a href='collaborators.php?remove=$cUserID&projID=$cProjectID' style='color: #FF0000; text-decoration: none; font-weight: bold; font-size: 14px;'>X</a>";
						echo "</li>";
					}
					echo "</ul></td></tr>";

				}


				// If the request to remove a collaborator was confirmed
				if (isset($_GET['uID'])) {
					$removeID = $_GET['uID'];
					$projID = $_GET['projID'];
					$query1 = "DELETE FROM memberships WHERE userID='$removeID' AND projectID='$projID'";
					$results1 = $connection->commit($query1);
					echo "<tr><td><span style=\"color:green;\">A collaborator successfully removed. Refresh to see the updated list.</span></td></tr>\n";
				}
			}
		?></tbody>
		</table>
		</td>
	</tr>
</table>
<?php
	}
?>

</body>
</html>
