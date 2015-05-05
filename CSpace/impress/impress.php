<?php
  	// Connecting to database
		require_once("../connect.php");
		session_start();

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
		$result = mysql_query($sql) or die(" ". mysql_error());
		$line = mysql_fetch_array($result);
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
		// echo $fullQuery;
		$pageResult = mysql_query($fullQuery) or die(" ". mysql_error());

		// $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' ORDER BY date DESC";
		// $pageResult = mysql_query($getPage) or die(" ". mysql_error());

		$hasResult = FALSE; // Check if there are any results

		$compareDate = '';
		$compareYear = '';
		$compareMonth = '';
		$compareDay = '';
		$setDate = false;
		$yval = 0;
		$zval = 0;
		$xval = 300;

		$entered_first = false;
		$contain = false;
		}

	  	while($line = mysql_fetch_array($pageResult)) {
			// $thumb = $line['fileName'];
			// $title = $line['title'];

			// $hasThumb = $line['thumbnailID'];
			// $pass_var = "page-".$hasThumb;

			$type = $line['type'];
			$thumb = $line['thumbnailID'];
			$title = $line['title'];
			$source = $line['source'];
			$link = $line['url'];
			$pageID = $line['pageID'];
			$comp_date = $line['date'];
			$bookmarked = $line['result'];

			if($value == $val) {
			// Bookmarked

			// Label by year, month ,day
			$comp_year = date("Y",strtotime($comp_date));
			$comp_month = date("m",strtotime($comp_date));
			$comp_day = date("d",strtotime($comp_date));

			if($setDate == false) {
				$compareDate = $comp_date;
				$compareYear = $comp_year;
				$compareMonth = $comp_month;
				$compareDay = $comp_day;
				$setDate = true;
			}

			if($comp_date == $compareDate) {
				//if same date
				//$xval = $xval + 100;
				if($entered_first == false) {
					$entered_first = true;

					// Converting months to word format
					switch ($comp_month) {
						case 01:
							$le_month = "Jan";
							break;
						case 02:
							$le_month = "Feb";
							break;
						case 03:
							$le_month = "Mar";
							break;
						case 04:
							$le_month = "Apr";
							break;
						case 05:
							$le_month = "May";
							break;
						case 06:
							$le_month = "Jun";
							break;
						case 07:
							$le_month = "Jul";
							break;
						case 08:
							$le_month = "Aug";
							break;
						case 09:
							$le_month = "Sep";
							break;
						case 10:
							$le_month = "Oct";
							break;
						case 11:
							$le_month = "Nov";
							break;
						case 12:
							$le_month = "Dec";
							break;
					  }

					// echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
					// echo '<div class="month"><h3>'.$le_month.'</h3></div>';
					// echo '<div class="day">'.$comp_date.'</div>';

					// echo '<div class="contain cf">';
					echo '<div class="step cf" data-x='.$xval.' data-y='.$yval.' data-z='.$zval.' data-rotate="0" data-scale="1">';
					echo '<div class="day">'.$comp_date.'</div>';

					$contain = true;
				}
			}
			//when date is different
			else {

				echo '</div>';
				$contain = false;

				if($comp_month != $compareMonth) {
					// echo '<div class="month"><h3>'.$le_month.'</h3></div>';
					$zval = $zval - 500;
					$yval = $yval - 200;
					$xval = $xval - 300;

					// if($comp_day == $compareDay)
					// 	// echo '<div class="day">'.$comp_date.'</div>';
					// 	$xval = $xval + 100;
				}

				if($comp_day != $compareDay) {
					// echo '<div class="day">'.$comp_date.'</div>';
					$zval = $zval - 500;
					$yval = $yval - 200;
					$xval = $xval - 300;
				}

				if($contain == false) {
					// echo '<div class="contain cf">';
					echo '<div class="step cf" data-x='.$xval.' data-y='.$yval.' data-z='.$zval.' data-rotate="0" data-scale="1">';
					echo '<div class="day">'.$comp_date.'</div>';

					$contain = true;
				}

				$compareDate = $comp_date;
				$compareYear = $comp_year;
				$compareMonth = $comp_month;
				$compareDay = $comp_day;
			}

				// echo '<div class="wrapper">';
				// echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
				// echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/'.$thumb.'">';
				// echo '</a></div>';

		if ($type == "query") {
		    // $queryID = $line['queryID'];

		    $pass_var = "query-".$pageID;

		    if ($source == "bing") {
				echo '<div class="wrapper">';
				echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
		        echo "<img src='../coverflow/query_bing2.png'/>";
		    	echo '</a></div>';
		    }

		    else if ($source == "google") {
				echo '<div class="wrapper">';
				echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
		        echo "<img src='../coverflow/query_google2.png'/>";
				echo '</a></div>';

		    }

		}
		else if ($type == "page") {
		    $pass_var = "page-".$pageID;

		    if ($thumb !== NULL) {
				echo '<div class="wrapper">';
				echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
		        echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' />";
				echo '</a></div>';
		    }



		}
		else if ($type == "snippet") {

		    $pass_var = "snippet-".$pageID;

			echo '<div class="wrapper">';
			echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
		    echo "<img src='../snippet.png' width='100' height='100' />";
			echo '</a></div>';
		}

		else if ($type == "annotation") {
		    
		    $pass_var = "note-".$pageID;
			echo '<div class="wrapper">';
			echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
		    echo "<img src='../note.png' width='100' height='100' />";
			echo '</a></div>';
		}

			$hasResult = TRUE;

		}

	}

	?>

	<script src="js/impress.js"></script>
	<script>impress().init();</script>
