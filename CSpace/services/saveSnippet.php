<?php
	session_start();
	require_once("utilityFunctions.php");
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();
?>
<html>
<head>
	<title>Snippet</title>
	<link href="../assets/css/styles.css" rel="stylesheet" type="text/css" />
</head>
<?php
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];

	if ($userID) {
		$url = $_GET['URL'];
		$title = htmlspecialchars($_GET['title']);
		$snippet = addslashes($_GET['snippet']);
		$title = $_GET['title'];
		$title = str_replace(" - Mozilla Firefox","",$title);
		$url = $_GET['URL'];

		if (isset($_GET['annotation'])) {
			// Get the date, time, and timestamp
			date_default_timezone_set('America/New_York');
			$timestamp = time();
			$datetime = getdate();
                        $date = date('Y-m-d', $datetime[0]);
			$time = date('H:i:s', $datetime[0]);
			$annotation = addslashes($_GET['annotation']);
			$rating = $_GET['rating'];
			$snippet = stripslashes($snippet);
			$snippet = str_replace("&quote;", "\"", $snippet);

			$query = "INSERT INTO snippets VALUES('','$url','$title','$userID','$projectID','$timestamp','$date','$time','$snippet','$annotation','text','1')";

			echo "<body class=\"body\" onload=\"window.close();\">\n";
			echo "<center>\n";
			echo "<table class=\"body\" width=90%>";
			$results = $connection->commit($query);
                        $snippetID = $connection->getLastID();


                        $ip=$_SERVER['REMOTE_ADDR'];

			Util::getInstance()->saveAction('save-snippet',"$snippetID",$base);
			if ($rating != "")
			{
				$queryRating = "INSERT INTO rating (`idResource`, `type`, `value`, `userID`, `projectID`, `active`,`time`,`date`,`timestamp`) VALUES ('$snippetID', 'snippets', '$rating', '$userID', '$projectID', '1','$time','$date','$timestamp')";
				$queryRatingResults = $connection->commit($queryRating);
			}

			addPoints($userID,10);
			echo "<tr><td>The snippet was saved. This window will close now.</td></tr>";
		}
		else {
			echo "<body class=\"body\" onload=\"document.f.annotation.focus();\">\n";
			echo "<br/><center>\n";
			echo "<form name=\"f\" action=\"saveSnippet.php\" method=GET>\n";
			echo "<table class=\"body\" width=90%>";
			echo "<tr><th>Collecting a snippet from page: <a href=\"$url\">$title</a><br/><br/></th></tr>\n";
			$snippet = stripslashes($snippet);
			$snippet = stripslashes($snippet);
			$snippetValue = str_replace("\"","&quote;",$snippet);
			echo "<tr bgcolor=#EEEEEE><td>$snippet</td></tr>\n";
			echo "<tr><td align=center><em>Add notes to this snippet (optional)</em><br/><textarea cols=35 rows=6 name=\"annotation\"></textarea><input type=\"hidden\" name=\"userID\" value=\"$userID\"/><input type=\"hidden\" name=\"projectID\" value=\"$projectID\"/><input type=\"hidden\" name=\"URL\" value=\"$url\"/><input type=\"hidden\" name=\"title\" value=\"$title\"/><input type=\"hidden\" name=\"snippet\" value=\"$snippetValue\"/><input type=\"hidden\" name=\"title\" value=\"$title\"/></td></tr>\n";
			echo "<tr><td align=center><br>How good is this snippet? Rate it:</td></tr></table>";
 			echo "<table><tr><td><input type=\"radio\" name=\"rating\" value=\"1\"></td>";
 			echo "<td><input type=\"radio\" name=\"rating\" value=\"2\"></td>";
 			echo "<td><input type=\"radio\" name=\"rating\" value=\"3\"></td>";
			echo "<td><input type=\"radio\" name=\"rating\" value=\"4\"></td>";
			echo "<td><input type=\"radio\" name=\"rating\" value=\"5\"></td></tr>";
			echo "<tr align=center><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr></table>";
			echo "<table><tr><td align=center><br><input type=\"submit\" value=\"Save\" /> <input type=\"button\" value=\"Cancel\" onclick=\"window.close();\" /></td></tr>\n";
			echo "</table>\n";
			echo "</form>\n";
		}
	}
	else {
		echo "<tr><td>Something went wrong. Click 'Home' on your Coagmento toolbar.</td></tr>\n";
	}
?>
</body>
</html>
