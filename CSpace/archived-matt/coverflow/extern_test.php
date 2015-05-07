<script type="text/javascript">
domReady(function()
{
    var instanceOne = new ImageFlow();
    // override default options
    instanceOne.init({ ImageFlowID:'myImageFlow', reflections: false, reflectionP: 0.0, opacity: true, imagesM: 1.5, scrollbarP: 0.5, imageCursor: 'pointer', buttons: true, onClick: $.noop });
});
</script>

<?php

session_start();

// Connecting to database
require_once('connect.php');


if (!isset($_SESSION['CSpace_userID'])) {
    echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
}

else {
    $userID = $_SESSION['CSpace_userID'];

    $q=$_GET["q"];
    if ($q != "")
    {
        $pieces = explode("-", $q);
        $projects = $pieces[0];
        $object_type = $pieces[1];
        $year = $pieces[2];
        $month = $pieces[3];
        $checked = $pieces[4];
    }

	$projectID = "";
	$userID = $_SESSION['CSpace_userID'];

	// Set project name to project ID
	$sql="SELECT DISTINCT * FROM projects WHERE (title='".$projects."')";
	$result = $connection->commit($query);
	$line = mysqli_fetch_array($result);
	$projectID = $line['projectID'];

	// Project filter
    if ($projects == "all")
        $projectFilter = "projectID in (SELECT projectID from memberships where userID = $userID and access = 1)";
    else
        $projectFilter = "projectID = ".$projectID."";

    // "My stuff only" filter
    if($checked == 'Yes') {
        $userFilter = "and userID = ".$_SESSION['CSpace_userID'];
    }

    // Date filter
    if ($year !== 'all') {
        $yearFilter = "and DATE_FORMAT(date, '%Y') = ".$year."";
    }
    else {
        $yearFilter = NULL;
    }

    if ($month !== 'all') {
        $monthFilter = "and DATE_FORMAT(date, '%m') = ".$month."";
    }
    else {
        $monthFilter = NULL;
    }

    $queryPages = "SELECT pageID, 'page' as `type`, userID, projectID, url, source, title, query, result, date, time, timestamp,
                   (select fileName from thumbnails b where b.thumbnailID = a.thumbnailID LIMIT 1) thumbnailID
                   FROM pages a WHERE ".$projectFilter." ".$userFilter." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' ".$yearFilter." ".$monthFilter."";
                   //fileName in thumbnails is renamed and moved to

    $queryBookmarks = "SELECT pageID, 'page' as `type`, userID, projectID, url, source, title, query, result, date, time, timestamp,
                   (select fileName from thumbnails b where b.thumbnailID = a.thumbnailID LIMIT 1) thumbnailID
                   FROM pages a WHERE result = 1 and ".$projectFilter." ".$userFilter." AND result = 1 AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' ".$yearFilter." ".$monthFilter."";
                   //fileName in thumbnails is renamed and moved to

	$queryQueries = "SELECT queryID as pageID, 'query' as `type`, userID, projectID, url, source, title, query, '', date, time, timestamp, NULL as thumbnailID FROM queries WHERE ".$projectFilter." ".$userFilter." ".$yearFilter." ".$monthFilter."";

	$querySnippets = "SELECT snippetID as pageID, 'snippet' as `type`, userID, projectID, url, snippet, title, '', '',date, time, timestamp, NULL as thumbnailID FROM snippets WHERE ".$projectFilter." ".$userFilter." ".$yearFilter." ".$monthFilter."";

    $queryAnnotations = "SELECT noteID as pageID, 'annotation' as `type`, userID, projectID, url, note, title, '', '',date, time, timestamp, NULL as thumbnailID FROM annotations WHERE ".$projectFilter." ".$userFilter." ".$yearFilter." ".$monthFilter."";

	$fullQuery = "SELECT * from ($queryPages UNION $queryBookmarks UNION $queryQueries UNION $querySnippets UNION $queryAnnotations) tmp order by date desc, time asc";

	if ($object_type != "all") {
		switch ($object_type) {

		case "pages":
			$fullQuery = $queryPages;
			break;
        //bookmarked
        case "saved":
            $fullQuery = $queryBookmarks;
            break;
		case "queries":
			$fullQuery = $queryQueries;
			break;
		case "snippets":
			$fullQuery = $querySnippets;
			break;
        case "annotations":
            $fullQuery = $queryAnnotations;
            break;
		}
	}

    //echo $fullQuery;
	$pageResult = mysql_query($fullQuery) or die(" ". mysql_error());

    //This is all the XHTML ImageFlow needs
    echo "<div id='myImageFlow' class='imageflow'>";

    while($line = mysqli_fetch_array($pageResult)) {
        $type = $line['type'];
        $thumb = $line['thumbnailID'];
        $title = $line['title'];
        $source = $line['source'];
    	$link = $line['url'];
        $pageID = $line['pageID'];
        $comp_date = $line['date'];
        $bookmarked = $line['result'];

        if ($type == "query") {
            // $queryID = $line['queryID'];

            $pass_var = "query-".$pageID;

            if ($source == "bing") {
                echo "<img src='../assets/img/query_bing2.png' width='320' height='240' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";
            }

            else if ($source == "google") {
                echo "<img src='../assets/img/query_google2.png' width='320' height='240' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";
            }

        }
        else if ($type == "page") {
            $pass_var = "page-".$pageID;

            if ($thumb !== NULL) {
                echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' width='320' height='240' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";
            }

        }
        else if ($type == "snippet") {
            $pass_var = "snippet-".$pageID;
            echo "<img src='../assets/img/snippet.png' width='100' height='100' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";
        }

        else if ($type == "annotation") {
            $pass_var = "note-".$pageID;
            echo "<img src='../assets/img/note.png' width='100' height='100' class='various fancybox.ajax' href='getDetails.php?q=".$pass_var."' alt='".$title." (".$comp_date.")' />";
        }
    }

    echo "</div>";
}


?>
