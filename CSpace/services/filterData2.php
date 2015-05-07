<?php

// Db


// Session info

session_start();
require_once('./core/Base.class.php');
require_once("./core/Connection.class.php");
$base = Base::getInstance();
$connection = Connection::getInstance();

function monthNumToAbbr($comp_month){
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

	return $le_month;
}

if (!isset($_SESSION['CSpace_userID'])) {
	echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
}
else {
	$userID = $base->getUserID();

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

	if($checked == 'Yes')
		$userFilter = "and userID = ".$_SESSION['CSpace_userID'];


		// Within first case
	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;
	$entered_first = false;
	$contain = false;



	$action_str = '';
	$value_str = " AND value NOT LIKE '%http%' ORDER BY date DESC ";
	if($object_type == 'all'){
		$action_str = " AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ";
	}else if($object_type == 'pages'){
		$action_str = " AND (action='page' OR action='save-page') ";
		$not_str = "AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
	}else if($object_type == 'saved'){
		$action_str = " AND (action='page' OR action='save-page') ";
	}else if($object_type == 'queries'){
		$action_str = " AND action='query' ";
	}else if($object_type == 'snippets'){
		$action_str = " action='save-snippet' ";
	}else if($object_type == 'annotations'){
		$action_str = " AND action='add-annotation' ";
	}
	$query = "SELECT * FROM actions WHERE $projectFilter $userFilter $action_str $value_str";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results


	while($row = mysqli_fetch_array($result)){
		$type = $row['action'];
		$val = $row['value'];

		$id_str = '';
		$not_str = "";
		if ($type=="page"){
			$id_str = " WHERE pageID=".$val." ";
			$not_str = " AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' ";
		}
		else if ($type=="save-page"){
			$id_str = " WHERE pageID=$val ";
		}
		else if ($type=="save-snippet"){
			$id_str = " WHERE snippetID=$val ";
		}
		else if ($type=="add-annotation"){
			$id_str = " noteID=$val ";
		}



		$matchDate = TRUE;
		if($year != 'all'){
			$date = $row['date'];
			$date_year = date("Y",strtotime($date));
			$matchDate = ($matchDate && ($date_year == $year));
			if($year != 'all' && $month != 'all'){
				$date_month = date("m",strtotime($date));
				$matchDate = ($matchDate && ($date_month == $month));
			}
		}

		if($matchDate){

			$pass_var = "$type-".$value;
			if($type=="save-page")
			{
				$pass_var = "page-".$value;
			}

			$query = "SELECT * FROM $table_name $id_str $not_str";
			$result  = $connection->commit($getPage);
			$line = mysqli_fetch_array($result);

			$comp_date = $line['date'];
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


			// THUMBNAILS ONLY!
			if($line['userID'] == $userID) {
				$class = 'thumbnail_small2';
			}
			else {
				$class = 'thumbnail_small';
			}


			if($comp_date == $compareDate) {
				if($entered_first == false) {
					$entered_first = true;

					// Converting months to word format
					$le_month = monthNumToAbbr($comp_month);

					echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
					echo '<div class="month"><h3>'.$le_month.'</h3></div>';
					echo '<div class="day">'.$comp_date.'</div>';

					echo '<div class="contain">';
					$contain = true;
				}
			}
			else if ($type != 'add-annotation'){
				// Converting months to word format
				$le_month = monthNumToAbbr($comp_month);

				echo '</div>';
				$contain = false;

				if($comp_year != $compareYear) {
					echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
				}
				if($comp_month != $compareMonth) {
					echo '<div class="month"><h3>'.$le_month.'</h3></div>';

					if($comp_day == $compareDay)
						echo '<div class="day">'.$comp_date.'</div>';

				}
				if($comp_day != $compareDay) {
					echo '<div class="day">'.$comp_date.'</div>';
				}

				if($contain == false) {
					echo '<div class="contain">';
					$contain = true;
				}

				$compareDate = $comp_date;
				$compareYear = $comp_year;
				$compareMonth = $comp_month;
				$compareDay = $comp_day;
			}








			echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

			$background = '';
			$bookmarked = FALSE;
			$thumb='';
			if($type=='page'){
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];

				if($hasThumb && $bookmarked){
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
					$pageResult = $connection->commit($getPage);
					$line = mysqli_fetch_array($pageResult);
					$value = $line['pageID'];
					$thumb = $line['fileName'];
					$background= 'url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.')';
				}else if($bookmarked){
					$background="url(assets/img/page.png);";
				}
			}else if($type=='save-page'){
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];

				if($hasThumb){
					$getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."";
					$bookmarkResult = $connection->commit($getBookmark);
					$line = mysqli_fetch_array($bookmarkResult);
					$thumb = $line['fileName'];
					$background= 'url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.')';
				}else{
					$background="url(assets/img/page.png);";
				}





			}
			echo "<div class=\"wrapper\" style=\"width: 100px; height: 100px; float: left; $background\">";




			if($type=='page' || ($type=='save-page' && !$bookmarked)){
				echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
			}


			// IMAGE
			if($bookmarked){
					echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
			}else if($type=='page'){
					echo "<img src='assets/img/page.png'>";
			}else if($type=='save-page'){
				echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
			}
			else if($type=='query'){
				$source = $line['source'];
				$query = $line['query'];
				$source = $line['source'];
				$id = $line['queryID'];

				if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
					echo "<img src='assets/img/query_".$source.".png'>";
				}
				else {
					echo "<img src='assets/img/query.png'>";
				}
			}else if($type=='save-page'){
				echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
			}else if($type=='save-snippet'){
				echo '<img src="assets/img/snippet.png">';
			}else if($type=='add-annotation'){
				echo '<img src="assets/img/note.png">';
			}


			if($type=='page' || ($type=='save-page' && !$bookmarked)){
				echo "</div>";
			}


			// Thumbnail, not bookmarked


			// END

			echo "</div>";
			echo "</a>";

			$hasResult = TRUE;

		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}


}

?>
