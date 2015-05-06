<?php
	session_start();
	require_once("connect.php");
	require_once("services/utilityFunctions.php");
	require_once('./core/Base.class.php');
	require_once("./core/Connection.class.php");
	require_once("./core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	if ((isset($_SESSION['CSpace_userID']))) {
		$userID = $base->getUserID();
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $base->getProjectID();
		else {
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND projects.description LIKE '%Untitled project%' AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		if (isset($_SESSION['CSpace_noteShared']))
			$shared = $_SESSION['CSpace_noteShared'];
		else
			$shared = 0;
		if (isset($_GET['note'])) {
			$note = $_GET['note'];
			if (isset($_GET['noteID'])) {
				$noteID = $_GET['noteID'];
				$query = "UPDATE notes SET note='$note' WHERE noteID='$noteID'";
				$results = $connection->commit($query);
			}
			else {
				$timestamp = $base->getTimestamp();
				$date = $base->getDate();
				$time = $base->getTime();
				$query = "INSERT INTO notes VALUES('','$userID','$projectID','$note','$timestamp','$date','$time','$shared','1')";
				$results = $connection->commit($query);

				$rID = $connection->getLastID();

				$ip=$base->getIP();
				Util::getInstance()->saveAction('create-note',"$rID",$base);
				addPoints($userID,20);
			}
		}
		if (isset($_GET['delete'])) {
			$noteID = $_GET['noteID'];
			$query = "DELETE FROM notes WHERE noteID='$noteID'";
			echo "$query<br/>\n";
			$results = $connection->commit($query);
		}

		$maxPerPage = 3;
		$pageToGo = 'noteList.php';
		$container = 'noteList';
		if (!isset($_GET['page']))
			$pageNum = 1;
		else
			$pageNum = $_GET['page'];

		$min = $pageNum*3-3;

		$query1 = "SELECT * FROM notes WHERE projectID='$projectID' AND shared='$shared'";
		$results = $connection->commit($query1);
		echo "<table>\n";
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$noteID = $line['noteID'];
			$note = stripslashes($line['note']);
			$noteSnippet = substr($line['note'], 0, 20);
			$date = $line['date'];
			$dispNote = addslashes($note);
			echo "<tr><td><span style=\"color:blue;text-decoration:underline;cursor:pointer;font-size:11px;\" onClick=\"showNote($shared, $noteID,'$dispNote');\">$noteSnippet..</span></td><td align=right><span style=\"color:green;font-size:9px;\">$date</span> <span style=\"color:red;text-decoration:underline;cursor:pointer;\" onClick=\"deleteNote($shared, $noteID);\">X</span></font></td></tr>\n";
		}
		echo "<tr><td colspan=2><hr/></td></tr>\n";
		echo "<tr><td colspan=2 align=center>";
		require_once("sidebarPaging.php");
		echo "</td></tr>\n";
		echo "</table>\n";
	}
	else {
		echo "Your session has expired. Please <a href=\"http://".$_SERVER['HTTP_HOST']."/CSpace/\" target=_content><span style=\"color:blue;text-decoration:underline;cursor:pointer;\">login</span> again.\n";
	}
?>
