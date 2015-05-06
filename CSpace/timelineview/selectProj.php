<?php
	session_start();
	function redirect($loc){
			echo "<script>window.document.location='".$loc."';</script>";
	}


	if (!isset($_SESSION['CSpace_userID'])) {
		echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
	}
	else {

		require_once("../core/Connection.class.php");
    require_once("../services/insertAction.php");
		$connection = Connection::getInstance();
		$projectID = $_GET['projectID'];
    $projectTitle = $_GET['projectTitle'];
		$_SESSION['CSpace_projectID'] = $projectID;
    $_SESSION['CSpace_projectTitle'] = $projectTitle;
		$query = "SELECT * FROM projects WHERE projectID='$projectID'";
		$results = $connection->commit($query);
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$title = $line['title'];
		$description = $line['description'];
		$startDate = $line['startDate'];
		$startTime = $line['startTime'];

   	// Update the selected project information for this user in the options table
		$userID = $_SESSION['CSpace_userID'];
		$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='selected-project'";
		$results = $connection->commit($query);
		if (mysql_num_rows($results)==0) {
			$query = "INSERT INTO options VALUES('','$userID','$projectID','selected-project','$projectID')";
		}
		else {
			$query = "UPDATE options SET value='$projectID' WHERE userID='$userID' AND `option`='selected-project'";
		}
		$results = $connection->commit($query);




		$query = "SELECT * FROM memberships WHERE projectID='$projectID'";
		$results = $connection->commit($query);
		while ($line = mysql_fetch_array($results, MYSQL_ASSOC)) {
			$cUserID = $line['userID'];
			$query1 = "SELECT * FROM users WHERE userID='$cUserID'";
			$results1 = $connection->commit($query1);
			$line1 = mysql_fetch_array($results1, MYSQL_ASSOC);
			$uName = $line1['firstName'] . " " . $line1['lastName'];
		}
	  insertAction("switch_project",$projectID);
	  redirect('http://'.$_SERVER['HTTP_HOST'].'/CSpace/timelineview/index2.php?project');
  }
?>
