<?php
	session_start();
	$ip=$_SERVER['REMOTE_ADDR'];
	require_once("connect.php");
	require_once("utilityFunctions.php");

//	$fout = fopen('ctest.txt','w');

	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];

	// If no active project selected, the default project is 'Untitled'
	if ($projectID == 0) {
		$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$projectID = $line['projectID'];
	}

	$originalURL = $_GET['URL'];
	$title = $_GET['title'];
	$title = str_replace(" - Mozilla Firefox","",$title);
	$url = $originalURL;
//	$originalURL = urlencode($url);

//	fwrite($fout, $userID."\t".$projectID."\t".$originalURL."\n");

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
//	fwrite($fout, $url."\n");
	$entry = explode(".", $url);
	$i = 0;
	$isWebsite = 0;
	while (($entry[$i]) && ($isWebsite == 0))
	{
		$entry[$i] = strtolower($entry[$i]);
		if (($entry[$i] == "com") || ($entry[$i] == "edu") || ($entry[$i] == "org") || ($entry[$i] == "gov") || ($entry[$i] == "info") || ($entry[$i] == "us") || ($entry[$i] == "ca") || ($entry[$i] == "es") || ($entry[$i] == "uk") || ($entry[$i] == "net"))
		{
			$isWebsite = 1;
			$site = $entry[$i-1];
			$domain = $entry[$i];
		}
		$i++;
//		fwrite($fout, $i."\t".$site."\t".$domain."\n");
	} // while (($entry[$i]) && ($isWebsite == 0))

	// Extract the query if there is any
	$queryString = extractQuery($originalURL);
	$queryString = addslashes($queryString);

	// Get the date, time, and timestamp
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
    $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);

	// This is to avoid getting duplicates
	// If the same user has fired the same page just now,
	// chances are, this is a duplicate entry.
	$query = "SELECT max(pageID) as num FROM pages WHERE userID='$userID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$lastPageID = $line['num'];
	$query = "SELECT url FROM pages WHERE pageID='$lastPageID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$lastURL = $line['url'];

	$query = "SELECT * FROM memberships WHERE userID='$userID' AND projectID='$projectID'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
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
//		fwrite($fout, "$query\n");
		$results = mysql_query($query) or die(" ". mysql_error());
		$aQuery = "SELECT max(pageID) as num FROM pages";
		$aResults = mysql_query($aQuery) or die(" ". mysql_error());
		$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
		$pageID = $aLine['num'];
		$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','page','$pageID','$ip')";
		$aResults = mysql_query($aQuery) or die(" ". mysql_error());
//		fwrite($fout, $originalURL."\n");
		if ($queryString)
		{
			$resultsPage = urlencode($originalURL);
//			fwrite($fout, $resultsPage."\n");
			$topResults = file_get_contents($resultsPage);
//			fwrite($fout, $topResults);
			$query = "INSERT INTO queries VALUES('','$userID','$projectID','$site','$queryString','$originalURL','$title','','$topResults','$timestamp','$date','$time','1')";
//			fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$aQuery = "SELECT max(queryID) as num FROM queries";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			$aLine = mysql_fetch_array($aResults, MYSQL_ASSOC);
			$queryID = $aLine['num'];
			$contents = file_get_contents($originalURL);
			$fileName = "/home/scilsnet/chirags/projects/Coagmento/data/study2_queries_results/". $queryID . ".qr";
			$fout = fopen($fileName, 'w');
			fwrite($fout, $contents);
			fwrite($fout, "\n");
			fclose($fout);
			$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','query','$queryID','$ip')";
			$aResults = mysql_query($aQuery) or die(" ". mysql_error());
			echo $queryString;
		}

		addPoints($userID,1);
	}
//	fclose($fout);
	mysql_close($dbh);
echo "A:Finished";
?>
