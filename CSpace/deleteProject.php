<?php
	session_start();
	ob_start();
	require_once("header.php");
	require_once("connect.php");
	$pageName = "CSpace/projects.php";
	require_once("../counter.php");
		
	if (isset($_SESSION['userID'])) {
		$userID = $_SESSION['userID'];
		$query1 = "SELECT * FROM users WHERE userID='$userID'";
		$results1 = mysql_query($query1) or die(" ". mysql_error());
		$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
		$firstName = $line1['firstName'];
		$lastName = $line1['lastName'];
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr bgcolor=#DDDDDD><td colspan=2>Hello, <strong>$firstName $lastName</strong>.";
		
		// If new project information was sent
		if (isset($_GET['projectID'])) {
			$projectID = $_GET['projectID'];
			$query = "SELECT * FROM projects,memberships WHERE projects.projectID='$projectID' AND memberships.userID='$userID'";
			$results = mysql_query($query) or die(" ". mysql_error());
			$num = mysql_num_rows($results);
			if ($num==0) {
				echo "<tr><td colspan=2><font color=\"red\">Sorry, you do not have permission to delete this project.</font></td></tr>";
			} // if ($num!=0)
			else {
				if ($_GET['confirm']=="true") {
					$query = "UPDATE projects SET status=0 WHERE projectID=$projectID";
					$results = mysql_query($query) or die(" ". mysql_error());
					$query = "DELETE FROM memberships WHERE projectID=$projectID AND userID=$userID";
					$results = mysql_query($query) or die(" ". mysql_error());					
					echo "<tr><td colspan=2><font color=\"green\">The selected project was deleted.</font></td></tr>";
				}
				else {
					$query = "SELECT title FROM projects WHERE projectID=$projectID";
					$results = mysql_query($query) or die(" ". mysql_error());
					$line = mysql_fetch_array($results, MYSQL_ASSOC);
					$title = $line['title'];
					if ($title == "Untitled")
						echo "<tr><td colspan=2><font color=\"red\">Sorry, Untitled project cannot be deleted.</font></td></tr>\n";
					else
						echo "<tr><td colspan=2><font color=\"green\">Are you sure you want to delete project <strong>$title</strong>? </font>&nbsp;&nbsp;&nbsp;&nbsp; <a href=\"deleteProject.php?projectID=$projectID&confirm=true\">Yes</a>&nbsp;&nbsp; <a href=\"projects.php\">No</a></td></tr>\n";
				}
			} // else with if ($num!=0)
		} // if (isset($_GET['title']))
		echo "<tr><td valign=top><form action=\"projects.php\" method=GET>";
		echo "<table class=\"table\"><tr><td colspan=2>You can create a new project:</td></tr>";
		echo "<tr><td>Title</td><td><input name=\"title\" type=\"text\" size=32\></td></tr>\n";
		echo "<tr><td>Description<br/>(optional)</td><td><textarea name=\"description\" cols=28 rows=4></textarea></td></tr>\n";
		echo "<tr><td colspan=2 align=center><input type=submit value=\"Create\"/></td></tr>\n";
		echo "</table>\n</form></td><td valign=top>";
		echo "<table class=\"table\"><tr><td>Or select from an existing project:</td></tr>";
		echo "<tr><td><table class=\"style1\">";
		echo "<tr><th>Title</th><th>Started on</th><th>Edit</th></tr>\n";
		$query = "SELECT projectID FROM memberships WHERE userID='$userID'";
		$results = mysql_query($query) or die(" ". mysql_error());
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$projectID = $line['projectID'];
			$query1 = "SELECT * FROM projects WHERE projectID='$projectID' AND status=1";
			$results1 = mysql_query($query1) or die(" ". mysql_error());
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$projectID = $line1['projectID'];
			$title = $line1['title'];
//			$description = $line1['description'];
			$startDate = $line1['startDate'];
			echo "<tr><td><a href=\"selectProject.php?projectID=$projectID\">$title</a></td><td>$startDate</td><td><a href=\"deleteProject.php?projectID=$projectID\">Delete</a></td></tr>";
		}
		echo "</table></td></tr>\n";
		echo "</table></td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	}
	else {
		echo "<br/><br/><center>\n<table class=\"body\">\n";
		echo "<tr><td>Sorry. Looks like we had trouble knowing who you are!<br/>Please try <a href=\"index.php\">logging in</a> again.</td></tr>\n";
		echo "</table>\n</center>\n<br/><br/><br/><br/>\n";
	}
	require_once("footer.php");
?>
  <!-- end #footer - > </div>
<!-- end #container - > </div>

</body>
</html>
