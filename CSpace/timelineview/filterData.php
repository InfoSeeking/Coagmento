<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$project_id = $pieces[0];
$object_type = $pieces[1];
$year = $pieces[2];
$month = $pieces[3];
$checked = $pieces[4];

// Connecting to database
$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);
$userID=2;

// Set project name to project ID
$sql="SELECT DISTINCT * FROM projects WHERE (title='".$project_id."')";
$result = mysql_query($sql) or die(" ". mysql_error());

while($row = mysql_fetch_array($result))
{
		$project_id = $row['projectID'];
}

// Declare projects for user
$getProjects="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult = mysql_query($getProjects) or die(" ". mysql_error());
$project_sql = '';

while($row = mysql_fetch_array($projectsResult))
{
	$project_sql .= "projectID = ".$row['projectID']." OR ";
}

$project_sql = substr($project_sql,0,-4);

// Declare projects for user for queries
$getProjects_queries="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult_queries = mysql_query($getProjects_queries) or die(" ". mysql_error());
$project_sql_queries = '';

while($row = mysql_fetch_array($projectsResult_queries))
{
	$project_sql_queries .= "queries.projectID = ".$row['projectID']." OR ";
}

$project_sql_queries = substr($project_sql_queries,0,-4);

// Declare projects for user for snippets
$getProjects_snippets="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult_snippets = mysql_query($getProjects_snippets) or die(" ". mysql_error());
$project_sql_snippets = '';

while($row = mysql_fetch_array($projectsResult_snippets))
{
	$project_sql_snippets .= "snippets.projectID = ".$row['projectID']." OR ";
}

$project_sql_snippets = substr($project_sql_snippets,0,-4);

// Declare projects for user for annotations
$getProjects_annotations="SELECT DISTINCT * FROM memberships WHERE (userID='".$userID."')";
$projectsResult_annotations = mysql_query($getProjects_annotations) or die(" ". mysql_error());
$project_sql_annotations = '';

while($row = mysql_fetch_array($projectsResult_annotations))
{
	$project_sql_annotations .= "annotations.projectID = ".$row['projectID']." OR ";
}

$project_sql_annotations = substr($project_sql_annotations,0,-4);

// Check if 'my stuff only' is checked
$userID_check = '';
$query_check = '';
$snippet_check = '';
$note_check = '';
if($checked == 'Yes') {
	$userID_check = 'userID = '.$userID.' AND';
	$query_check = 'queries.userID = '.$userID.' AND';
	$snippet_check = 'snippets.userID = '.$userID.' AND';
	$note_check = 'annotations.userID = '.$userID.' AND';
}
else {
	$userID_check = '';
	$query_check = '';
	$snippet_check = '';
	$note_check = '';
}

// IF ALL ALL ALL ALL
if($project_id == "all" && $object_type == "all" && $year == "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." ".$project_sql." AND projectID!=0 AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$pass_var = "page-".$value;

				if($hasThumb == NULL) {
					// If bookmarked, display star
					if($bookmarked == 1) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
								echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
									echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
								echo "</div>";
							echo "</div>";
						echo "</a>";

						$hasResult = TRUE;
					}
					// If not, display thumbnail
					else {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/page.png'>";
							echo '</div>';
						echo '</a>';

						$hasResult = TRUE;
					}
				}
				else {
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
					$pageResult = mysql_query($getPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($pageResult)) {
						$value = $line['pageID'];
						$thumb = $line['fileName'];
						$pass_var = "page-".$value;

						// Label by year, month ,day
						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

						if($value == $object_value) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo '</div>';
									echo '</div>';
								echo '</a>';

								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									echo '</div>';
								echo '</a>';

								$hasResult = TRUE;
							}
						}
					}
				}
			}
		}

		// Save page
		if($object_type2 == 'save-page') {
			$pos = strpos($object_value,'http');

			if($pos === false) {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";

							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';

							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';

									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';

									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}
		}

		// Query
		if($object_type2 == 'query') {
			$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$source = $line['source'];
				$id = $line['queryID'];
				$pass_var = "query-".$value;

				if($value == $object_value && $entered == FALSE) {
					if($line['userID'] == $userID) {
						$class = 'thumbnail_small2';
					}
					else {
						$class = 'thumbnail_small';
					}

					echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
					echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
					if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
						echo "<img src='query_".$source.".png'>";
					}
					else {
						echo "<img src='query.png'>";
					}
					echo '</div>';
					echo '</a>';

					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$pass_var = "snippet-".$value;

				if($value == $object_value && $entered == FALSE) {
					// Label by year, month ,day
					$comp_date = $row['date'];
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

							echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							echo '<div class="month"><h3>'.$le_month.'</h3></div>';
							echo '<div class="day">'.$comp_date.'</div>';

							echo '<div class="contain">';
							$contain = true;
						}
					}
					else {
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

						echo '</div>';
						$contain = false;

						if($comp_year != $compareYear) {
							echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
						}
						if($comp_month != $compareMonth) {
							echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

					if($line['userID'] == $userID) {
						$class = 'thumbnail_small2';
					}
					else {
						$class = 'thumbnail_small';
					}

					echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='../assets/img/snippet.png'>";
						echo '</div>';
					echo '</a>';

					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = mysql_query($getNote) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$pass_var = "note-".$value;

				if($value == $object_value && $entered == FALSE) {
					if($line['userID'] == $userID) {
						$class = 'thumbnail_small2';
					}
					else {
						$class = 'thumbnail_small';
					}

					echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='../assets/img/note.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF PROJ ALL ALL ALL
elseif($project_id != "all" && $object_type == "all" && $year == "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."  AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$pass_var = "page-".$value;

				if($hasThumb == NULL) {
					// If bookmarked, display star
					if($bookmarked == 1) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
								echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
									echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
								echo "</div>";
							echo "</div>";
						echo "</a>";
						$hasResult = TRUE;
					}
					// If not, display thumbnail
					else {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/page.png'>";
							echo '</div>';
						echo '</a>';
						$hasResult = TRUE;
					}
				}
				else {
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
					$pageResult = mysql_query($getPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($pageResult)) {
						$value = $line['pageID'];
						$thumb = $line['fileName'];
						$pass_var = "page-".$value;

						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

						if($value == $object_value) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo '</div>';
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
					}
				}
			}
		}

		// Save page
		if($object_type2 == 'save-page') {
			$pos = strpos($object_value,'http');

			if($pos === false) {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}
		}

		// Query
		if($object_type2 == 'query') {
			$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$source = $line['source'];
				$pass_var = "query-".$value;

				if($value == $object_value && $entered == FALSE) {
					if($line['userID'] == $userID) {
						$class = 'thumbnail_small2';
					}
					else {
						$class = 'thumbnail_small';
					}

					echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
								echo "<img src='query_".$source.".png'>";
							}
							else {
								echo "<img src='query.png'>";
							}
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$pass_var = "snippet-".$value;

				if($value == $object_value && $entered == FALSE) {
					// Label by year, month ,day
					$comp_date = $row['date'];
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

							echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							echo '<div class="month"><h3>'.$le_month.'</h3></div>';
							echo '<div class="day">'.$comp_date.'</div>';

							echo '<div class="contain">';
							$contain = true;
						}
					}
					else {
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

						echo '</div>';
						$contain = false;

						if($comp_year != $compareYear) {
							echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
						}
						if($comp_month != $compareMonth) {
							echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

					if($line['userID'] == $userID) {
						$class = 'thumbnail_small2';
					}
					else {
						$class = 'thumbnail_small';
					}

					echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='../assets/img/snippet.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = mysql_query($getNote) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$pass_var = "note-".$value;

				if($value == $object_value && $entered == FALSE) {
					// Label by year, month ,day
					$comp_date = $row['date'];
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

							echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							echo '<div class="month"><h3>'.$le_month.'</h3></div>';
							echo '<div class="day">'.$comp_date.'</div>';

							echo '<div class="contain">';
							$contain = true;
						}
					}
					else {
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

						echo '</div>';
						$contain = false;

						if($comp_year != $compareYear) {
							echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
						}
						if($comp_month != $compareMonth) {
							echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

					if($line['userID'] == $userID) {
						$class = 'thumbnail_small2';
					}
					else {
						$class = 'thumbnail_small';
					}

					echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='../assets/img/note.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF PROJ OBJ ALL ALL
elseif($project_id != "all" && $object_type != "all" && $year == "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." projectID=".$project_id." AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE pages.projectID=".$project_id." AND thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// Filter by date
								$comp_date = $row['date'];
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

										echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
										echo '<div class="month"><h3>'.$le_month.'</h3></div>';
										echo '<div class="day">'.$comp_date.'</div>';

										echo '<div class="contain">';
										$contain = true;
									}
								}
								else {
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

									echo '</div>';
									$contain = false;

									if($comp_year != $compareYear) {
										echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
									}
									if($comp_month != $compareMonth) {
										echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}

			// Bookmarks
			if($object_type == 'saved') {
				$pos = strpos($object_value,'http');

				if($pos === false) {
					$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$pass_var = "page-".$value;

						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										// Filter by date
										$comp_date = $row['date'];
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

												echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
												echo '<div class="month"><h3>'.$le_month.'</h3></div>';
												echo '<div class="day">'.$comp_date.'</div>';

												echo '<div class="contain">';
												$contain = true;
											}
										}
										else {
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

											echo '</div>';
											$contain = false;

											if($comp_year != $compareYear) {
												echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
											}
											if($comp_month != $compareMonth) {
												echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}

			// Query
			if($object_type == 'queries') {
				$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.projectID=".$project_id." AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$source = $line['source'];
					$pass_var = "query-".$value;

					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						// Filter by date
						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
									echo "<img src='query_".$source.".png'>";
								}
								else {
									echo "<img src='query.png'>";
								}
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.projectID=".$project_id." AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$pass_var = "snippet-".$value;

					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						// Filter by date
						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.projectID=".$project_id." AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = mysql_query($getNote) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$pass_var = "note-".$value;

					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						// Filter by date
						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/note.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}
	}
	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF PROJ OBJ YEAR ALL
elseif($project_id != "all" && $object_type != "all" && $year != "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." projectID=".$project_id." AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "page-".$value;

					// Check year
					if($date_year == $year) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE projectID=".$project_id." AND thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								// Filter by date
								$comp_date = $row['date'];
								$comp_month = date("m",strtotime($comp_date));
								$comp_day = date("d",strtotime($comp_date));

								if($setDate == false) {
									$compareDate = $comp_date;
									$compareMonth = $comp_month;
									$compareDay = $comp_day;
									$setDate = true;
								}

								if($comp_date == $compareDate) {
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

										echo '<div class="month"><h3>'.$le_month.'</h3></div>';
										echo '<div class="day">'.$date.'</div>';

										echo '<div class="contain">';
										$contain = true;
									}
								}
								else {
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

									echo '</div>';
									$contain = false;

									if($comp_month != $compareMonth) {
										echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									}
									if($comp_day != $compareDay) {
										echo '<div class="day">'.$date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain">';
										$contain = true;
									}

									$compareDate = $comp_date;
									$compareMonth = $comp_month;
									$compareDay = $comp_day;
								}

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}

			// Bookmarks
			if($object_type == 'saved') {
				$pos = strpos($object_value,'http');

				if($pos === false) {
					$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$date = $line['date'];
						$date_year = date("Y",strtotime($date));
						$pass_var = "page-".$value;

						// Check year
							if($date_year == $year) {
							if($hasThumb == NULL) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = mysql_query($getPage) or die(" ". mysql_error());

								while($line = mysql_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											if($line['userID'] == $userID) {
												$class = 'thumbnail_small2';
											}
											else {
												$class = 'thumbnail_small';
											}

											// Filter by date
											$comp_date = $row['date'];
											$comp_month = date("m",strtotime($comp_date));
											$comp_day = date("d",strtotime($comp_date));

											if($setDate == false) {
												$compareDate = $comp_date;
												$compareMonth = $comp_month;
												$compareDay = $comp_day;
												$setDate = true;
											}

											if($comp_date == $compareDate) {
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

													echo '<div class="month"><h3>'.$le_month.'</h3></div>';
													echo '<div class="day">'.$comp_date.'</div>';

													echo '<div class="contain">';
													$contain = true;
												}
											}
											else {
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

												echo '</div>';
												$contain = false;

												if($comp_month != $compareMonth) {
													echo '<div class="month"><h3>'.$le_month.'</h3></div>';
												}
												if($comp_day != $compareDay) {
													echo '<div class="day">'.$comp_date.'</div>';
												}

												if($contain == false) {
													echo '<div class="contain">';
													$contain = true;
												}

												$compareDate = $comp_date;
												$compareMonth = $comp_month;
												$compareDay = $comp_day;
											}

											echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
													echo '</div>';
												echo '</div>';
											echo '</a>';
											$hasResult = TRUE;
										}
									}
								}
							}
						}
					}
				}
			}

			// Query
			if($object_type == 'queries') {
				$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.projectID=".$project_id." AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$source = $line['source'];
					$pass_var = "query-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
										echo "<img src='query_".$source.".png'>";
									}
									else {
										echo "<img src='query.png'>";
									}
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.projectID=".$project_id." AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/snippet.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.projectID=".$project_id." AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = mysql_query($getNote) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "note-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/note.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}
		}
	}
	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF PROJ OBJ YEAR MONTH
elseif($project_id != "all" && $object_type != "all" && $year != "all" && $month != "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." projectID=".$project_id." AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "page-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE pages.projectID=".$project_id." AND thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								// Filter by date
								$comp_date = $row['date'];
								$comp_day = date("d",strtotime($comp_date));

								if($setDate == false) {
									$compareDate = $comp_date;
									$compareDay = $comp_day;
									$setDate = true;
								}

								if($comp_date == $compareDate) {
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

										echo '<div class="day">'.$date.'</div>';

										echo '<div class="contain">';
										$contain = true;
									}
								}
								else {
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

									echo '</div>';
									$contain = false;

									if($comp_day != $compareDay) {
										echo '<div class="day">'.$date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain">';
										$contain = true;
									}

									$compareDate = $comp_date;
									$compareDay = $comp_day;
								}

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}

			// Bookmarks
			if($object_type == 'saved') {
				$pos = strpos($object_value,'http');

				if($pos === false) {
					$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value." AND result=1";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$date = $line['date'];
						$date_year = date("Y",strtotime($date));
						$date_month = date("m",strtotime($date));
						$pass_var = "page-".$value;

						// Check year and month
						if($date_year == $year && $date_month == $month) {
							// Filter by date
							$comp_date = $row['date'];
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

									echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_year != $compareYear) {
									echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								}
								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

							if($hasThumb == NULL) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = mysql_query($getPage) or die(" ". mysql_error());

								while($line = mysql_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											if($line['userID'] == $userID) {
												$class = 'thumbnail_small2';
											}
											else {
												$class = 'thumbnail_small';
											}

											echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
													echo '</div>';
												echo '</div>';
											echo '</a>';
											$hasResult = TRUE;
										}
									}
								}
							}
						}
					}
				}
			}

			// Query
			if($object_type == 'queries') {
				$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$source = $line['source'];
					$pass_var = "query-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
										echo "<img src='query_".$source.".png'>";
									}
									else {
										echo "<img src='query.png'>";
									}
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/snippet.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = mysql_query($getNote) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "note-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/note.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}
		}
	}
	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF PROJ ALL YEAR ALL
elseif($project_id != "all" && $object_type == "all" && $year != "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "page-".$value;

				if($date_year == $year) {
					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}
		}

		// Save page
		if($object_type2 == 'save-page') {
			$pos = strpos($object_value,'http');

			if($pos === false) {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "page-".$value;

					if($date_year == $year) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}
		}

		// Query
		if($object_type2 == 'query') {
			$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$source = $line['source'];
				$pass_var = "query-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
									echo "<img src='query_".$source.".png'>";
								}
								else {
									echo "<img src='query.png'>";
								}
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = mysql_query($getNote) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/note.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF PROJ ALL YEAR MONTH
elseif($project_id != "all" && $object_type == "all" && $year != "all" && $month != "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "page-".$value;

				if($date_year == $year && $date_month == $month) {
					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							// Filter by date
							$comp_date = $row['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}
		}

		// Save page
		if($object_type2 == 'save-page') {
			$pos = strpos($object_value,'http');

			if($pos === false) {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "page-".$value;

					if($date_year == $year && $date_month == $month) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}
		}

		// Query
		if($object_type2 == 'query') {
			$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$source = $line['source'];
				$pass_var = "query-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
									echo "<img src='query_".$source.".png'>";
								}
								else {
									echo "<img src='query.png'>";
								}
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = mysql_query($getNote) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/note.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// IF ALL OBJ ALL ALL
elseif($project_id == "all" && $object_type != "all" && $year == "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." (".$project_sql.") AND projectID != 0 AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." (".$project_sql.") AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							// Filter by date
							$comp_date = $row['date'];
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

									echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_year != $compareYear) {
									echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								}
								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}

			// Bookmarks
			if($object_type == 'saved') {
				$pos = strpos($object_value,'http');

				if($pos === false) {
					$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$date = $line['date'];
						$date_year = date("Y",strtotime($date));
						$pass_var = "page-".$value;

						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										// Filter by date
										$comp_date = $row['date'];
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

												echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
												echo '<div class="month"><h3>'.$le_month.'</h3></div>';
												echo '<div class="day">'.$comp_date.'</div>';

												echo '<div class="contain">';
												$contain = true;
											}
										}
										else {
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

											echo '</div>';
											$contain = false;

											if($comp_year != $compareYear) {
												echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
											}
											if($comp_month != $compareMonth) {
												echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}

			// Query
			if($object_type == 'queries') {
				$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." (".$project_sql_queries.") AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$source = $line['source'];
					$pass_var = "query-".$value;

					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						// Filter by date
						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
									echo "<img src='query_".$source.".png'>";
								}
								else {
									echo "<img src='query.png'>";
								}
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." (".$project_sql_snippets.") AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "snippet-".$value;

					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						// Filter by date
						$comp_date = $row['date'];
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." (".$project_sql_annotations.") AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = mysql_query($getNote) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "note-".$value;

					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						// Filter by date
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

						if($comp_date == $compareDate) {
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

								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								echo '<div class="day">'.$comp_date.'</div>';

								echo '<div class="contain">';
								$contain = true;
							}
						}
						else {
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

							echo '</div>';
							$contain = false;

							if($comp_year != $compareYear) {
								echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
							}
							if($comp_month != $compareMonth) {
								echo '<div class="month"><h3>'.$le_month.'</h3></div>';
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
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/note.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}
	}
	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// ALL OBJ YEAR ALL
elseif($project_id == "all" && $object_type != "all" && $year != "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." (".$project_sql.") AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." (".$project_sql.") AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "page-".$value;

					// Check year
					if($date_year == $year) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								// Filter by date
								$comp_date = $line['date'];
								$comp_month = date("m",strtotime($comp_date));
								$comp_day = date("d",strtotime($comp_date));

								if($setDate == false) {
									$compareDate = $comp_date;
									$compareMonth = $comp_month;
									$compareDay = $comp_day;
									$setDate = true;
								}

								if($comp_date == $compareDate) {
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

										echo '<div class="month"><h3>'.$le_month.'</h3></div>';
										echo '<div class="day">'.$comp_date.'</div>';

										echo '<div class="contain">';
										$contain = true;
									}
								}
								else {
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

									echo '</div>';
									$contain = false;

									if($comp_month != $compareMonth) {
										echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									}
									if($comp_day != $compareDay) {
										echo '<div class="day">'.$comp_date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain">';
										$contain = true;
									}

									$compareDate = $comp_date;
									$compareMonth = $comp_month;
									$compareDay = $comp_day;
								}

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}

			// Bookmarks
			if($object_type == 'saved') {
				$pos = strpos($object_value,'http');

				if($pos === false) {
					$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$date = $line['date'];
						$date_year = date("Y",strtotime($date));
						$pass_var = "page-".$value;

						// Check year
						if($date_year == $year) {
							if($hasThumb == NULL) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = mysql_query($getPage) or die(" ". mysql_error());

								while($line = mysql_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											if($line['userID'] == $userID) {
												$class = 'thumbnail_small2';
											}
											else {
												$class = 'thumbnail_small';
											}

											// Filter by date
											$comp_date = $row['date'];
											$comp_month = date("m",strtotime($comp_date));
											$comp_day = date("d",strtotime($comp_date));

											if($setDate == false) {
												$compareDate = $comp_date;
												$compareMonth = $comp_month;
												$compareDay = $comp_day;
												$setDate = true;
											}

											if($comp_date == $compareDate) {
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

													echo '<div class="month"><h3>'.$le_month.'</h3></div>';
													echo '<div class="day">'.$comp_date.'</div>';

													echo '<div class="contain">';
													$contain = true;
												}
											}
											else {
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

												echo '</div>';
												$contain = false;

												if($comp_month != $compareMonth) {
													echo '<div class="month"><h3>'.$le_month.'</h3></div>';
												}
												if($comp_day != $compareDay) {
													echo '<div class="day">'.$comp_date.'</div>';
												}

												if($contain == false) {
													echo '<div class="contain">';
													$contain = true;
												}

												$compareDate = $comp_date;
												$compareMonth = $comp_month;
												$compareDay = $comp_day;
											}

											echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
													echo '</div>';
												echo '</div>';
											echo '</a>';
											$hasResult = TRUE;
										}
									}
								}
							}
						}
					}
				}
			}

			// Query
			if($object_type == 'queries') {
				$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." (".$project_sql_queries.") AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$source = $line['source'];
					$pass_var = "query-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
										echo "<img src='query_".$source.".png'>";
									}
									else {
										echo "<img src='query.png'>";
									}
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." (".$project_sql_snippets.") AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/snippet.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." (".$project_sql_annotations.") AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = mysql_query($getNote) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "note-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/note.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}
		}
	}
	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// ALL OBJ YEAR MONTH
elseif($project_id == "all" && $object_type != "all" && $year != "all" && $month != "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." (".$project_sql.") AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." (".$project_sql.") AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "page-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								// Filter by date
								$comp_date = $line['date'];
								$comp_day = date("d",strtotime($comp_date));

								if($setDate == false) {
									$compareDate = $comp_date;
									$compareDay = $comp_day;
									$setDate = true;
								}

								if($comp_date == $compareDate) {
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

										echo '<div class="day">'.$comp_date.'</div>';

										echo '<div class="contain">';
										$contain = true;
									}
								}
								else {
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

									echo '</div>';
									$contain = false;

									if($comp_day != $compareDay) {
										echo '<div class="day">'.$comp_date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain">';
										$contain = true;
									}

									$compareDate = $comp_date;
									$compareDay = $comp_day;
								}

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}

			// Bookmarks
			if($object_type == 'saved') {
				$pos = strpos($object_value,'http');

				if($pos === false) {
					$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$date = $line['date'];
						$date_year = date("Y",strtotime($date));
						$date_month = date("m",strtotime($date));
						$pass_var = "page-".$value;

						// Check year and month
						if($date_year == $year && $date_month == $month) {
							if($hasThumb == NULL) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = mysql_query($getPage) or die(" ". mysql_error());

								while($line = mysql_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											if($line['userID'] == $userID) {
												$class = 'thumbnail_small2';
											}
											else {
												$class = 'thumbnail_small';
											}

											// Filter by date
											$comp_date = $line['date'];
											$comp_day = date("d",strtotime($comp_date));

											if($setDate == false) {
												$compareDate = $comp_date;
												$compareDay = $comp_day;
												$setDate = true;
											}

											if($comp_date == $compareDate) {
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

													echo '<div class="day">'.$comp_date.'</div>';

													echo '<div class="contain">';
													$contain = true;
												}
											}
											else {
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

												echo '</div>';
												$contain = false;

												if($comp_day != $compareDay) {
													echo '<div class="day">'.$comp_date.'</div>';
												}

												if($contain == false) {
													echo '<div class="contain">';
													$contain = true;
												}

												$compareDate = $comp_date;
												$compareDay = $comp_day;
											}

											echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
													echo '</div>';
												echo '</div>';
											echo '</a>';
											$hasResult = TRUE;
										}
									}
								}
							}
						}
					}
				}
			}

			// Query
			if($object_type == 'queries') {
				$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." (".$project_sql_queries.") AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$source = $line['source'];
					$pass_var = "query-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $line['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
										echo "<img src='query_".$source.".png'>";
									}
									else {
										echo "<img src='query.png'>";
									}
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." (".$project_sql_snippets.") AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $line['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/snippet.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." (".$project_sql_annotations.") AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = mysql_query($getNote) or die(" ". mysql_error());
				$entered = FALSE;

				while($line = mysql_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "note-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							// Filter by date
							$comp_date = $line['date'];
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareDay = $comp_day;
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/note.png'>";
								echo '</div>';
							echo '</a>';
							$entered = TRUE;
							$hasResult = TRUE;
						}
					}
				}
			}
		}
	}
	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// ALL ALL YEAR ALL
elseif($project_id == "all" && $object_type == "all" && $year != "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." (".$project_sql.") AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "page-".$value;

				if($date_year == $year) {
					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							// Filter by date
							$comp_date = $row['date'];
							$comp_month = date("m",strtotime($comp_date));
							$comp_day = date("d",strtotime($comp_date));

							if($setDate == false) {
								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
								$setDate = true;
							}

							if($comp_date == $compareDate) {
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

									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
									echo '<div class="day">'.$comp_date.'</div>';

									echo '<div class="contain">';
									$contain = true;
								}
							}
							else {
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

								echo '</div>';
								$contain = false;

								if($comp_month != $compareMonth) {
									echo '<div class="month"><h3>'.$le_month.'</h3></div>';
								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain">';
									$contain = true;
								}

								$compareDate = $comp_date;
								$compareMonth = $comp_month;
								$compareDay = $comp_day;
							}

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}
		}

		// Save page
		if($object_type2 == 'save-page') {
			$pos = strpos($object_value,'http');

			if($pos === false) {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "page-".$value;

					if($date_year == $year) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}
		}

		// Query
		if($object_type2 == 'query') {
			$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." (".$project_sql_queries.") AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$source = $line['source'];
				$pass_var = "query-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
									echo "<img src='query_".$source.".png'>";
								}
								else {
									echo "<img src='query.png'>";
								}
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." (".$project_sql_snippets.") AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." (".$project_sql_annotations.") AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = mysql_query($getNote) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/note.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}
}

// ALL ALL YEAR MONTH
elseif($project_id == "all" && $object_type == "all" && $year != "all" && $month != "all") {
	$sql="SELECT * FROM actions WHERE ".$userID_check." (".$project_sql.") AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY timestamp ASC";
	$result = mysql_query($sql) or die(" ". mysql_error());
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareDay = '';
	$setDate = false;

	$entered_first = false;
	$contain = false;

	while($row = mysql_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE ".$userID_check." (".$project_sql.") AND pageID=".$object_value." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "page-".$value;

				if($date_year == $year && $date_month == $month) {
					// Filter by date
					$comp_date = $row['date'];
					$comp_day = date("d",strtotime($comp_date));

					if($setDate == false) {
						$compareDate = $comp_date;
						$compareDay = $comp_day;
						$setDate = true;
					}

					if($comp_date == $compareDate) {
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

							echo '<div class="day">'.$comp_date.'</div>';

							echo '<div class="contain">';
							$contain = true;
						}
					}
					else {
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

						echo '</div>';
						$contain = false;

						if($comp_day != $compareDay) {
							echo '<div class="day">'.$comp_date.'</div>';
						}

						if($contain == false) {
							echo '<div class="contain">';
							$contain = true;
						}

						$compareDate = $comp_date;
						$compareDay = $comp_day;
					}

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							if($line['userID'] == $userID) {
								$class = 'thumbnail_small2';
							}
							else {
								$class = 'thumbnail_small';
							}

							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='../assets/img/page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());

						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
							}
						}
					}
				}
			}
		}

		// Save page
		if($object_type2 == 'save-page') {
			$pos = strpos($object_value,'http');

			if($pos === false) {
				$getAllPage="SELECT * FROM pages WHERE ".$userID_check." pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "page-".$value;

					if($date_year == $year && $date_month == $month) {
						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(../assets/img/page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='../assets/img/page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = mysql_query($getPage) or die(" ". mysql_error());

							while($line = mysql_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="../assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										if($line['userID'] == $userID) {
											$class = 'thumbnail_small2';
										}
										else {
											$class = 'thumbnail_small';
										}

										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
												echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
								}
							}
						}
					}
				}
			}
		}

		// Query
		if($object_type2 == 'query') {
			$getQuery="SELECT * FROM actions,queries WHERE ".$query_check." (".$project_sql_queries.") AND queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$source = $line['source'];
				$pass_var = "query-".$value;

				if($date_year == $date && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
									echo "<img src='query_".$source.".png'>";
								}
								else {
									echo "<img src='query.png'>";
								}
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE ".$snippet_check." (".$project_sql_snippets.") AND snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE ".$note_check." (".$project_sql_annotations.") AND annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = mysql_query($getNote) or die(" ". mysql_error());
			$entered = FALSE;

			while($line = mysql_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						if($line['userID'] == $userID) {
							$class = 'thumbnail_small2';
						}
						else {
							$class = 'thumbnail_small';
						}

						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='../assets/img/note.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}
		}
	}

	if($hasResult == FALSE) {
		echo "No results found";
	}
}

else {
	echo "Please input a valid combination.";
}

echo '</div>';

mysql_close($con);
?>
