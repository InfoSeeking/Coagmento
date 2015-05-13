<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" href="assets/css/styles.css?v2" rel="stylesheet" />
<link type="text/css" href="assets/css/bootstrap-3.3.4-dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="assets/js/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="assets/css/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
<link type="text/css" href="assets/css/font-awesome-4.3.0/css/font-awesome.min.css" rel="stylesheet" />
<title>Coagmento - Collaborative Information Seeking, Synthesis, and Sense-making</title>

<?php
	session_start();
	include('../services/func.php');
?>
</head>

<body>

<?php
require("views/header.php");
?>

<div id="container" class="container">
<h3>View Collaborators</h3>

<?php

	require_once("../core/Connection.class.php");
	require_once("../core/Base.class.php");
	require_once("../core/Util.class.php");
	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {
?>
<table class="body" width=100%>
	<tr>
		<td>
		<?php
			$base = Base::getInstance();
			$connection = Connection::getInstance();
			$userID = $_GET['userID'];
			$query = "SELECT * FROM users WHERE userID='$userID'";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$userName = $line['username'];
			$avatar = $line['avatar'];
			$uName = $line['firstName'] . " " . $line['lastName'];
			$organization = $line['organization'];
			$email = $line['email'];
			$website = $line['website'];

			echo "&nbsp;&nbsp;&nbsp;&nbsp;<img src=\"../assets/img/$avatar\" style='float: left;' width=100 height=100 /><span style=\"font-weight:bold\">$uName</span><br/>\n";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Organization: $organization<br/>\n";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Email: <a href=\"mailto:$email\">$email</a><br/>\n";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Website: <a href=\"$website\">$website</a><br/>\n";
		?>
		</td>
	</tr>
</table>
<?php
	}
?>

</body>
</html>
