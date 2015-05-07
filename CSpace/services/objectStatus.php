<?php
	session_start();
	require_once("connect.php");
	if (isset($_SESSION['CSpace_userID'])) {
		$userID = $_SESSION['CSpace_userID'];
		if (isset($_SESSION['CSpace_projectID']))
			$projectID = $_SESSION['CSpace_projectID'];
		else {
			require_once("connect.php");
			$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
			$results = $connection->commit($query);
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$projectID = $line['projectID'];
		}
		$object = $_GET['object'];
		$query = "SELECT count(*) as num FROM ".$object." WHERE projectID='$projectID'";
		
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$numNow = $line['num'];
		$query = "SELECT * FROM options WHERE userID='$userID' AND projectID='$projectID' AND `option`='$object'";
		$results = $connection->commit($query);
		
		// If there was no previous record, insert a new one now
		if (mysqli_num_rows($results)==0) {
			$value = $projectID.":".$numNow;
			$query = "INSERT INTO options VALUES('','$userID','$projectID','$object','$value')";
			$results = $connection->commit($query);
			echo "1";
		}	
		// Otherwise check the value.
		// If the previous value was same as the current one, no update required.
		else {
			$line = mysqli_fetch_array($results, MYSQL_ASSOC);
			$valueBefore = $line['value'];
			list($projBefore, $numNow) = explode(":", $valueBefore);
			if ($projBefore==$projectID) {
				if ($numNow!=$numBefore) {	
					$value = $projectID.":".$numNow;
					$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND projectID='$projectID' AND `option`='$option'";
					$results = $connection->commit($query);
					echo "1";
				}
				else
					echo "0";
			}
			else {
				$value = $projectID.":".$numNow;
				$query = "UPDATE options SET value='$value' WHERE userID='$userID' AND projectID='$projectID' AND `option`='$option'";
				$results = $connection->commit($query);
				echo "1";
			}
		}
	}
	
?>		
