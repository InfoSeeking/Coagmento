<?php
	session_start();
	require_once("connect.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="Coagmento icon" type="image/x-icon" href="../img/favicon.ico">
	<title>Coagmento</title>
</head>

<body>
<?php
	echo "<table>";
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	$qID = $_GET['qID'];
	$query = "SELECT * FROM queries WHERE queryID='$qID' AND projectID='$projectID'";
	$results = $connection->commit($query);
	if (mysqli_num_rows($results)==0) {
		echo "<tr><td>Error: you are not authorized to access this query snapshot.</td></tr>\n";
	}
	else {
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$qText = $line['query'];
		$source = $line['source'];
		$date = $line['date'];
		$time = $line['time'];
		echo "<tr><td>Query <strong>$qText</strong> executed on <strong>$source</strong> on $date at $time</td></tr>\n";
		echo "<tr><td><hr/></td></tr>\n";
		echo "<tr><td>";
//		$qID = 327;
		$qFileName = "/home1/shahonli/projects/Coagmento/data/study2_queries_results/".$qID.".qr";
		if (file_exists($qFileName))
			require_once($qFileName);
		echo "</td></tr>\n";
	}
	echo "</table>\n";
?>
</body>
</html>
