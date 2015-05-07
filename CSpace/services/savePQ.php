<?php
	session_start();
	$ip=$_SERVER['REMOTE_ADDR'];
	require_once("utilityFunctions.php");
	require_once('../core/Base.class.php');
	require_once("../core/Connection.class.php");
	require_once("../core/Util.class.php");
	$base = Base::getInstance();
	$connection = Connection::getInstance();

	$userID = $base->getUserID();
	$projectID = $base->getProjectID();

	// If no active project selected, the default project is 'Untitled'
	if ($projectID == 0) {
		$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
		$results = $connection->commit($query);
		$line = mysqli_fetch_array($results, MYSQL_ASSOC);
		$projectID = $line['projectID'];
	}

	$originalURL = $_GET['URL'];
	$title = $_GET['title'];
	$title = str_replace(" - Mozilla Firefox","",$title);
	$url = $originalURL;
	$site = '';

	// Parse the URL to extract the source
	$url = str_replace("http://", "", $url); // Remove 'http://' from the reference
	$url = str_replace("com/", "com.", $url);
	$url = str_replace("org/", "org.", $url);
	$url = str_replace("edu/", "edu.", $url);
	$url = str_replace("gov/", "gov.", $url);
	$url = str_replace("us/", "us.", $url);
	$url = str_replace("ca/", "ca.", $url);
	$url = str_replace("uk/", "uk", $url);
	$url = str_replace("es/", "es.", $url);
	$url = str_replace("net/", "net.", $url);

	$entry = explode(".", $url);
	$i = 0;
	$isWebsite = 0;
	while (isset($entry[$i]) && ($isWebsite == 0))
	{
		$entry[$i] = strtolower($entry[$i]);
		if (($entry[$i] == "com") || ($entry[$i] == "edu") || ($entry[$i] == "org") || ($entry[$i] == "gov") || ($entry[$i] == "info") || ($entry[$i] == "us") || ($entry[$i] == "ca") || ($entry[$i] == "es") || ($entry[$i] == "uk") || ($entry[$i] == "net"))
		{
			$isWebsite = 1;
			$site = $entry[$i-1];
			$domain = $entry[$i];
		}
		$i++;
	} // while (($entry[$i]) && ($isWebsite == 0))

	// Extract the query if there is any
	$queryString = extractQuery($originalURL);
	$queryString = addslashes($queryString);

	// Get the date, time, and timestamp
	$timestamp = $base->getTimestamp();
	$date = $base->getDate();
	$time = $base->getTime();

	// This is to avoid getting duplicates
	// If the same user has fired the same page just now,
	// chances are, this is a duplicate entry.
	$lastPageID = $connection->getLastID();
	$query = "SELECT url FROM pages WHERE pageID='$lastPageID'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	$lastURL = $line['url'];

	$query = "SELECT * FROM memberships WHERE userID='$userID' AND projectID='$projectID'";
	$results = $connection->commit($query);
	$line = mysqli_fetch_array($results, MYSQL_ASSOC);
	if ($line['access']==-1)
		$validMembership = FALSE;
	else
		$validMembership = TRUE;

//	fwrite($fout, $lastURL."\n");
echo "A:Trying";
	if (($lastURL != $originalURL) && ($validMembership))
	{
echo "A:$projectID";
		$query = "INSERT INTO pages VALUES('','$userID','$projectID','$originalURL','$title','$site','$queryString','$timestamp','$date','$time','0','1',NULL,NULL)";
		$results = $connection->commit($query);
		$pageID = $connection->getLastID();
		Util::getInstance()->saveAction('page',"$pageID",$base);
		if ($queryString)
		{
			$resultsPage = urlencode($originalURL);
			$topResults = file_get_contents($resultsPage);
			$query = "INSERT INTO queries VALUES('','$userID','$projectID','$site','$queryString','$originalURL','$title','','$topResults','$timestamp','$date','$time','1')";
			$results = $connection->commit($query);
			$queryID = $connection->getLastID();
			$contents = file_get_contents($originalURL);
			$fileName = "/home/scilsnet/chirags/projects/Coagmento/data/study2_queries_results/". $queryID . ".qr";
			$fout = fopen($fileName, 'w');
			fwrite($fout, $contents);
			fwrite($fout, "\n");
			fclose($fout);
			Util::getInstance()->saveAction('query',"$queryID",$base);
			echo $queryString;
		}

		addPoints($userID,1);
	}

echo "A:Finished";
?>
