<?php
	session_start();
        
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	require_once("connect.php");
	$url = $_GET['page'];
	$title = $_GET['title'];
	$title = str_replace(" - Mozilla Firefox","",$title);

	$originalURL = $_GET['page'];
	$title = $_GET['title'];
	$url = $originalURL;
	$save = $_GET['save'];
	// Get the date, time, and timestamp
	date_default_timezone_set('America/New_York');
	$timestamp = time();
	$datetime = getdate();
    $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);

/*
	$query = "SELECT max(pageID) as id FROM pages WHERE userID='1' AND projectID='1' AND url='$url'";
	$results = mysql_query($query) or die(" ". mysql_error());
	$line = mysql_fetch_array($results, MYSQL_ASSOC);
	$pageID = $line['id'];
*/
	
	if ($save) {
?>
<html>
<head>
	<title>Bookmark</title>
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
</head>
<?php
		$query = "SELECT * FROM pages WHERE projectID='$projectID' AND url='$originalURL' AND result=2";
		$results = mysql_query($query) or die(" ". mysql_error());
		if (mysql_num_rows($results)==0) {	
			require_once("utilityFunctions.php");

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
			while (($entry[$i]) && ($isWebsite == 0)) {
				$entry[$i] = strtolower($entry[$i]);
				if (($entry[$i] == "com") || ($entry[$i] == "edu") || ($entry[$i] == "org") || ($entry[$i] == "gov") || ($entry[$i] == "info") || ($entry[$i] == "us") || ($entry[$i] == "ca") || ($entry[$i] == "es") || ($entry[$i] == "uk") || ($entry[$i] == "net")) {
					$isWebsite = 1;
					$site = $entry[$i-1];
					$domain = $entry[$i];
				}
				$i++;
			} // while (($entry[$i]) && ($isWebsite == 0))
		
			// Extract the query if there is any
			$queryString = extractQuery($originalURL);

                        echo "<body class=\"body\" onload=\"document.f.annotation.focus();\">\n";
                        echo "<br/><center>\n";
                        echo "<form name=\"f\" action=\"saveResultAux.php\" method=GET>\n";
                        echo "<table class=\"body\" width=90%>";
                        echo "<tr><th>Bookmark the following page: <a href=\"$originalURL\">$title</a><br/><br/></th></tr>\n";
                        echo "<tr><td align=center><em>Add notes to this page (optional)</em><br/><textarea cols=35 rows=6 name=\"annotation\"></textarea><input type=\"hidden\" name=\"originalURL\" value=\"$originalURL\"/><input type=\"hidden\" name=\"source\" value=\"$url\"/><input type=\"hidden\" name=\"title\" value=\"$title\"/><input type=\"hidden\" name=\"site\" value=\"$site\"/><input type=\"hidden\" name=\"queryString\" value=\"$queryString\"/>'</td></tr>\n";
                        echo "<tr><td align=center><br>How good is this page? Rate it:</td></tr></table>";
                        echo "<table><tr><td><input type=\"radio\" name=\"rating\" value=\"1\"></td>";
                        echo "<td><input type=\"radio\" name=\"rating\" value=\"2\"></td>";
                        echo "<td><input type=\"radio\" name=\"rating\" value=\"3\"></td>";
                        echo "<td><input type=\"radio\" name=\"rating\" value=\"4\"></td>";
                        echo "<td><input type=\"radio\" name=\"rating\" value=\"5\"></td></tr>";
                        echo "<tr align=center><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr></table>";
                        echo "<table><tr><td align=center><br><input type=\"submit\" value=\"Save\" /> <input type=\"button\" value=\"Cancel\" onclick=\"window.close();\" /></td></tr></table>\n";
                        echo "</table>";
                        echo "</form>\n";

			//$query = "INSERT INTO pages VALUES('','$userID','$projectID','$originalURL','$title','$site','$queryString','$timestamp','$date','$time','1','1')";
		} // if (mysql_num_rows($results)==0)
		else
                {
                        $query = "UPDATE pages SET result=1 WHERE projectID='$projectID' AND url='$originalURL' AND result=2";
                        $results = mysql_query($query) or die(" ". mysql_error());
                        echo "<script>window.close()</script>";
                        $aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','save-page','$url','$ip')";
                        $aResults = mysql_query($aQuery) or die(" ". mysql_error());
                }
               // $aQuery = "INSERT INTO actions VALUES('','$userID','$teamID','$timestamp','$date','$time','save','$originalURL')";



//			$query = "UPDATE pages SET result=1 WHERE projectID='$projectID' AND url='$originalURL'";
?>
</body>
</html>
<?php
	} // if ($save)
	else {
		$query = "UPDATE pages SET result=2 WHERE projectID='$projectID' AND url='$originalURL' AND NOT result=0";
        $results = mysql_query($query) or die(" ". mysql_error());
		$aQuery = "INSERT INTO actions VALUES('','$userID','$projectID','$timestamp','$date','$time','remove','$url','$ip')";
		$aResults = mysql_query($aQuery) or die(" ". mysql_error());
	}
	
	$pQuery = "SELECT points FROM users WHERE userID='$userID'";
	$pResults = mysql_query($pQuery) or die(" ". mysql_error());
	$pLine = mysql_fetch_array($pResults, MYSQL_ASSOC);
	$totalPoints = $pLine['points'];
	$newPoints = $totalPoints+10;
	$pQuery = "UPDATE users SET points=$newPoints WHERE userID='$userID'";
	$pResults = mysql_query($pQuery) or die(" ". mysql_error());

	
//	fwrite($fout, $userID."\t".$projectID."\t".$originalURL."\n");
	
/*	// Get the date, time, and timestamp
	$timestamp = time();
	$datetime = getdate();
    $date = date('Y-m-d', $datetime[0]);
	$time = date('H:i:s', $datetime[0]);
	
	if ($save == 1) {
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
		} // while (($entry[$i]) && ($isWebsite == 0))
		
		// Fetch the webpage
		$page = file_get_contents($originalURL);	
		$fout = fopen("temp",'w');
		fwrite($fout, $page);
		fclose($fout);
		

		// Calculate the file size (in bytes) and word count
		$fileSize = filesize("temp");
		if ($fileSize == 0)
		{
			$entry = explode("/", $originalURL);
			$i = 0;
			$fetchURL = "";
			while($entry[$i+1])
			{
				$fetchURL = $fetchURL . $entry[$i];
				$i++;
			}
			// Fetch the webpage
			$page = file_get_contents($fetchURL);	
			$fout = fopen("temp",'w');
			fwrite($fout, $page);
			fclose($fout);
	
			// Calculate the file size (in bytes) and word count
			$fileSize = filesize("temp");
		}
		$wordCount = str_word_count($page);

		
		// Extract the title of the webpage
		system("grep \"<title>\" temp > ttemp");
		$fTemp = fopen("ttemp", 'r');
		$tLine = fgets($fTemp);
		trim($tLine);
		if ($tLine=="") {
			system("grep \"<TITLE>\" temp > ttemp");
			$fTemp = fopen("ttemp", 'r');
			$tLine = fgets($fTemp);
			trim($tLine);
		}
		str_replace("TITLE>", "title>", $tLine);
		fclose($fTemp);
		list($temp1, $temp2) = explode("<title>", $tLine);
		list($title, $temp3) = explode("</title>", $temp2);
					
		$query = "INSERT INTO results1 VALUES('','$userID','$projectID','$originalURL','$site','$title','$fetchURL','','$timestamp','$date','$time','1','','$fileSize','$wordCount')";
		$results = mysql_query($query) or die(" ". mysql_error());
	}
	else {
		$query = "REMOVE FROM results WHERE userID='$userID' AND projectID='$projectID' AND url='$originalURL'";
		$results = mysql_query($query) or die(" ". mysql_error());
	}	
//	fclose($fout);
*/
?>
