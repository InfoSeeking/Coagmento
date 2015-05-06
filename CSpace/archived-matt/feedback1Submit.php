<?php
	session_start();
	require_once("header.php");
	require_once("connect.php");
	$pageName = "study1/feedback1Submit.php";
	require_once("../counter.php");
?>
<?php
	echo "<br/><table class=\"body\" cellpadding=2 cellspacing=2>\n";	

	$userID = $_COOKIE['CSpace_userID'];
	$query1 = "SELECT count(*) as num FROM users WHERE userID='$userID'";
	$results1 = $connection->commit($query1);
	$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
	$num = $line1['num'];
	
	if ($num==1) {
		$datetime = getdate();
	    $date = date('Y-m-d', $datetime[0]);

		$f1q1 = $_POST['f1q1'];
		$f1q2 = $_POST['f1q2'];
		$f1q3 = $_POST['f1q3'];
		$f1q4 = $_POST['f1q4'];
		$f1q5 = $_POST['f1q5'];
		$f1q6 = $_POST['f1q6'];
		$f1q7 = $_POST['f1q7'];
		$f1q8 = $_POST['f1q8'];
		$f1q9 = $_POST['f1q9'];
		$f1q10 = $_POST['f1q10'];
		$f1q11 = $_POST['f1q11'];
		$f1q12 = $_POST['f1q12'];
		$f1q13 = $_POST['f1q13'];
		$f1q14 = $_POST['f1q14'];
		$f1q15 = $_POST['f1q15'];
		$f1q16 = $_POST['f1q16'];
		$f1q17 = $_POST['f1q17'];
		$f1like = addslashes($_POST['f1like']);
		$f1dislike = addslashes($_POST['f1dislike']);
		$query = "INSERT INTO feedback1 VALUES('','$userID','$f1q1','$f1q2','$f1q3','$f1q4','$f1q5','$f1q6','$f1q7','$f1q8','$f1q9','$f1q10','$f1q11','$f1q12','$f1q13','$f1q14','$f1q15','$f1q16','$f1q17','$f1like','$f1dislike','$date')";
		$results = $connection->commit($query);
		echo "<tr><td><br/>Thank you for your feedback. Proceed to your <a href=\"index.php\">CSpace</a>.<br/><br/></td></tr>\n";
	}
	else {
		echo "<tr><td>Something went wrong. Please <a href=\"index.php\">try again</a>.</td></tr>\n";
	}
	echo "</table>\n";
	require_once("footer.php");
?>

</body>
</html>
