<?php
	session_start();
	require_once("connect.php");
	$version = $_GET['version'];
        $currentVersion = 307;
        $newToolbarURL = "http://www.coagmento.org/getToolbar.php";
        if ($version<$currentVersion)
            echo "0;";
        else
            echo "1;";
	$userID = $_SESSION['CSpace_userID'];
	$projectID = $_SESSION['CSpace_projectID'];
	if ($projectID == 0) {
		$query = "SELECT projects.projectID FROM projects,memberships WHERE memberships.userID='$userID' AND (projects.description LIKE '%Untitled project%' OR projects.description LIKE '%Default project%') AND projects.projectID=memberships.projectID";
//		fwrite($fout, "$query\n");
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$projectID = $line['projectID'];
	}

	$originalURL = $_GET['URL'];
	$title = $_GET['title'];
	$url = $originalURL;

	// Update the entry for the current page by this user
//	if (!preg_match("/coagmento/",$url)) {
		$pageToRecord = $url.";:;".$title; // Demarker is ;:;
		$pageToRecord = addslashes($pageToRecord);
		$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='current-page'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		if (mysql_num_rows($results)==0) {
			$query = "INSERT INTO options VALUES('','$userID','$projectID','current-page','$pageToRecord')";
			$results = mysql_query($query) or die(" ". mysql_error());
		}
		else {
			$query = "UPDATE options SET projectID='$projectID',value='$pageToRecord' WHERE userID='$userID' AND `option`='current-page'";
			$results = mysql_query($query) or die(" ". mysql_error());
		}
//	}

	if ($userID>0) {
		$query = "SELECT * FROM options WHERE userID='$userID' AND `option`='page-status'";
		$results = mysql_query($query) or die(" ". mysql_error());
		$line = mysql_fetch_array($results, MYSQL_ASSOC);
		$pageStatus = $line['value'];
		if ($pageStatus=='on') {
                        $query = "SELECT (SELECT count(*) as num FROM pages WHERE projectID = '$projectID' AND url='$url' AND result=1) as bookmarked, (SELECT count(*) as num FROM pages WHERE projectID = '$projectID' AND url='$url') as views, (SELECT count(*) as num FROM annotations WHERE projectID = '$projectID' AND url='$url') as annotations,(SELECT count(*) as num FROM snippets WHERE projectID = '$projectID' AND url='$url') as snippets,(SELECT title FROM projects WHERE projectID='$projectID') as title";
                        $results = mysql_query($query) or die(" ". mysql_error());
                        $line = mysql_fetch_array($results, MYSQL_ASSOC);
                        $title = $line['title'];

                        if ($line['bookmarked'] == 0)
				$saved = 0;
			else
				$saved = 1;
			echo "$saved;";
                        echo "Views: ".$line['views'];
                        echo ";Annotations: ".$line['annotations'];
                        echo ";Snippets: ".$line['snippets'];
                        if ($title =="")
                            $title = "N/A";
			echo ";Project: $title";

			/*$query = "SELECT count(*) as num FROM pages WHERE projectID='$projectID' AND url='$url' AND result=1";
		//	fwrite($fout, $query."\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];
			if ($num == 0)
				$saved = 0;
			else
				$saved = 1;
			echo "$saved;";
			$query = "SELECT count(*) as num FROM pages WHERE projectID='$projectID' AND url='$url'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = 0;
			$num = $line['num'];
			echo "Views: $num;";

			$query = "SELECT count(*) as num FROM annotations WHERE projectID='$projectID' AND url='$url'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = 0;
			$num = $line['num'];
			echo "Annotations: $num;";

			$query = "SELECT count(*) as num FROM snippets WHERE projectID='$projectID' AND url='$url'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = 0;
			$num = $line['num'];
			echo "Snippets: $num;";

			$query = "SELECT title FROM projects WHERE projectID='$projectID'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$title = $line['title'];
                        if ($title =="")
                            $title = "N/A";
			echo "Project: $title";
		*/
	/*
			$query = "SELECT count(distinct url) as num FROM pages WHERE projectID='$projectID'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];
			echo "Viewed: $num, ";

			$query = "SELECT count(distinct url) as num FROM pages WHERE projectID='$projectID' AND result=1";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];
			echo "Saved: $num, ";

			$query = "SELECT count(distinct url) as num FROM queries WHERE projectID='$projectID'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];
			echo "Queries: $num, ";

			$query = "SELECT count(*) as num FROM snippets WHERE projectID='$projectID'";
		//	fwrite($fout, "$query\n\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$num = $line['num'];
			echo "Snippets: $num";
	*/
		} // if ($pageStatus=='on')
		else {
			$query = "SELECT (SELECT count(*) as num FROM pages WHERE userID='$userID' AND projectID='$projectID' AND url='$url' AND result=1) as bookmarked, (SELECT title FROM projects WHERE projectID='$projectID') as projectTitle";
		//	fwrite($fout, $query."\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
                        $title = $line['title'];

			if ($line['bookmarked'] == 0)
				$saved = 0;
			else
				$saved = 1;
			echo "$saved";
                        if ($title =="")
                            $title = "N/A";
			echo ";0;;;Project: $title";

                        /*$num = $line['num'];
			if ($num == 0)
				$saved = 0;
			else
				$saved = 1;
			echo "$saved;";
			$query = "SELECT title FROM projects WHERE projectID='$projectID'";
		//	fwrite($fout, "$query\n");
			$results = mysql_query($query) or die(" ". mysql_error());
			$line = mysql_fetch_array($results, MYSQL_ASSOC);
			$title = $line['title'];
			echo ";;; Project: $title";*/
		} // else with if ($pageStatus=='on')
	} // if ($userID>0)
	else
		echo "0; Views: N/A; Annotations: N/A; Snippets: N/A; Project: N/A";
	mysql_close($dbh);
         if ($version<$currentVersion)
            echo ";$newToolbarURL";
//	fclose($fout);
?>
