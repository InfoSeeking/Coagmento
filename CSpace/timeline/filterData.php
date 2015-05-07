<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$project_id = $pieces[0];
$object_type = $pieces[1];
$year = $pieces[2];
$month = $pieces[3];

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
$result = $connection->commit($query);

while($row = mysqli_fetch_array($result))
{
		$project_id = $row['projectID'];
}

// IF ALL ALL ALL ALL
if($project_id == "all" && $object_type == "all" && $year == "all" && $month == "all") {
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	$compareDate = '';
	$compareYear = '';
	$compareMonth = '';
	$compareDay = '';
	$setDate = false;

	$comp_diff = false;
	$comp_count = 0;
	$entered_first = false;

	while($row = mysqli_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

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
			$comp_count = 0;
		}
		else {
			$compareDate = $comp_date;
			$compareYear = $comp_year;
			$compareMonth = $comp_month;
			$compareDay = $comp_day;
			$comp_count++;

			if($comp_diff == false) {
				$comp_diff = true;
				echo '<div class="year">'.$comp_year.'</div>';
				echo '<div class="month">'.$comp_month.'</div>';
				echo '<div class="day">'.$comp_day.'</div>';
			}
			else {
				echo '</div>';
			}

			if($comp_year != $compareYear) {
				echo '<div class="year">'.$comp_year.'</div>';
			}
			if($comp_month != $compareMonth) {
				echo '<div class="month">'.$comp_month.'</div>';
			}
			if($comp_day != $compareDay) {
				echo '<div class="day">'.$comp_day.'</div>';
			}

			if($entered_first == false) {
				$entered_first = true;
				echo '<div class="container">';
			}

			if($comp_count == 1) {
				echo '<div class="container">';
			}
		}

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysqli_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$pass_var = "page-".$value;

				if($hasThumb == NULL) {
					// If bookmarked, display star
					if($bookmarked == 1) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
								echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
									echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
								echo "</div>";
							echo "</div>";
						echo "</a>";
						$hasResult = TRUE;
					}
					// If not, display thumbnail
					else {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='page.png'>";
							echo '</div>';
						echo '</a>';
						$hasResult = TRUE;
					}
				}
				else {
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
					$pageResult = $connection->commit($getPage);

					while($line = mysqli_fetch_array($pageResult)) {
						$value = $line['pageID'];
						$thumb = $line['fileName'];
						$pass_var = "page-".$value;

						if($value == $object_value) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo '</div>';
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
			$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = $connection->commit($getQuery);
			$entered = FALSE;

			while($line = mysqli_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$pass_var = "query-".$value;

				if($value == $object_value && $entered == FALSE) {
					echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='query.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = $connection->commit($getSnippet);
			$entered = FALSE;

			while($line = mysqli_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$pass_var = "snippet-".$value;

				if($value == $object_value && $entered == FALSE) {
					echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='snippet.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = $connection->commit($getNote);
			$entered = FALSE;

			while($line = mysqli_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$pass_var = "note-".$value;

				if($value == $object_value && $entered == FALSE) {
					echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysqli_fetch_array($allPageResult)) {
				$hasThumb = $line['thumbnailID'];
				$value = $line['pageID'];
				$bookmarked = $line['result'];
				$pass_var = "page-".$value;

				if($hasThumb == NULL) {
					// If bookmarked, display star
					if($bookmarked == 1) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
								echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
									echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
								echo "</div>";
							echo "</div>";
						echo "</a>";
						$hasResult = TRUE;
					}
					// If not, display thumbnail
					else {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='page.png'>";
							echo '</div>';
						echo '</a>';
						$hasResult = TRUE;
					}
				}
				else {
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
					$pageResult = $connection->commit($getPage);

					while($line = mysqli_fetch_array($pageResult)) {
						$value = $line['pageID'];
						$thumb = $line['fileName'];
						$pass_var = "page-".$value;

						if($value == $object_value) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo '</div>';
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
			$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = $connection->commit($getQuery);
			$entered = FALSE;

			while($line = mysqli_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$pass_var = "query-".$value;

				if($value == $object_value && $entered == FALSE) {
					echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='query.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Snippet
		if($object_type2 == 'save-snippet') {
			$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = $connection->commit($getSnippet);
			$entered = FALSE;

			while($line = mysqli_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$pass_var = "snippet-".$value;

				if($value == $object_value && $entered == FALSE) {
					echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='snippet.png'>";
						echo '</div>';
					echo '</a>';
					$entered = TRUE;
					$hasResult = TRUE;
				}
			}
		}

		// Annotation
		if($object_type2 == 'add-annotation') {
			$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = $connection->commit($getNote);
			$entered = FALSE;

			while($line = mysqli_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$pass_var = "note-".$value;

				if($value == $object_value && $entered == FALSE) {
					echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
							echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
					$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysqli_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$pass_var = "page-".$value;

						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
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
				$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = $connection->commit($getQuery);
				$entered = FALSE;

				while($line = mysqli_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$pass_var = "query-".$value;

					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='query.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = $connection->commit($getSnippet);
				$entered = FALSE;

				while($line = mysqli_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$pass_var = "snippet-".$value;

					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = $connection->commit($getNote);
				$entered = FALSE;

				while($line = mysqli_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$pass_var = "note-".$value;

					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
					$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysqli_fetch_array($allPageResult)) {
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
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = $connection->commit($getPage);

								while($line = mysqli_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
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
				$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = $connection->commit($getQuery);
				$entered = FALSE;

				while($line = mysqli_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "query-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='query.png'>";
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
				$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = $connection->commit($getSnippet);
				$entered = FALSE;

				while($line = mysqli_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='snippet.png'>";
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
				$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = $connection->commit($getNote);
				$entered = FALSE;

				while($line = mysqli_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "note-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
					$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysqli_fetch_array($allPageResult)) {
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
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = $connection->commit($getPage);

								while($line = mysqli_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
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
				$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = $connection->commit($getQuery);
				$entered = FALSE;

				while($line = mysqli_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "query-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='query.png'>";
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
				$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = $connection->commit($getSnippet);
				$entered = FALSE;

				while($line = mysqli_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='snippet.png'>";
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
				$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = $connection->commit($getNote);
				$entered = FALSE;

				while($line = mysqli_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "note-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysqli_fetch_array($allPageResult)) {
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
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
			$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = $connection->commit($getQuery);
			$entered = FALSE;

			while($line = mysqli_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "query-".$value;

				if($date_year == $date) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='query.png'>";
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
			$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = $connection->commit($getSnippet);
			$entered = FALSE;

			while($line = mysqli_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='snippet.png'>";
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
			$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = $connection->commit($getNote);
			$entered = FALSE;

			while($line = mysqli_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND projectID=".$project_id." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysqli_fetch_array($allPageResult)) {
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
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
			$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = $connection->commit($getQuery);
			$entered = FALSE;

			while($line = mysqli_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "query-".$value;

				if($date_year == $date && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='query.png'>";
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
			$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = $connection->commit($getSnippet);
			$entered = FALSE;

			while($line = mysqli_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='snippet.png'>";
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
			$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = $connection->commit($getNote);
			$entered = FALSE;

			while($line = mysqli_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
					$hasThumb = $line['thumbnailID'];
					$value = $line['pageID'];
					$bookmarked = $line['result'];
					$pass_var = "page-".$value;

					if($hasThumb == NULL) {
						// If bookmarked, display star
						if($bookmarked == 1) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
					$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysqli_fetch_array($allPageResult)) {
						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$date = $line['date'];
						$date_year = date("Y",strtotime($date));
						$pass_var = "page-".$value;

						if($hasThumb == NULL) {
							// If bookmarked, display star
							if($bookmarked == 1) {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
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
				$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = $connection->commit($getQuery);
				$entered = FALSE;

				while($line = mysqli_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "query-".$value;

					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='query.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Snippet
			if($object_type == 'snippets') {
				$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = $connection->commit($getSnippet);
				$entered = FALSE;

				while($line = mysqli_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "snippet-".$value;

					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='snippet.png'>";
							echo '</div>';
						echo '</a>';
						$entered = TRUE;
						$hasResult = TRUE;
					}
				}
			}

			// Annotation
			if($object_type == 'annotations') {
				$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = $connection->commit($getNote);
				$entered = FALSE;

				while($line = mysqli_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "note-".$value;

					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
					$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysqli_fetch_array($allPageResult)) {
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
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = $connection->commit($getPage);

								while($line = mysqli_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
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
				$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = $connection->commit($getQuery);
				$entered = FALSE;

				while($line = mysqli_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "query-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='query.png'>";
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
				$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = $connection->commit($getSnippet);
				$entered = FALSE;

				while($line = mysqli_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='snippet.png'>";
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
				$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = $connection->commit($getNote);
				$entered = FALSE;

				while($line = mysqli_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$pass_var = "note-".$value;

					// Check year
					if($date_year == $year) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_value = $row['value'];
		$pos = strpos($object_value,'http');
		if($pos === false) {
			// Page
			if($object_type == 'pages') {
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
					$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
					$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

					while($line = mysqli_fetch_array($allPageResult)) {
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
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo "</div>";
										echo "</div>";
									echo "</a>";
									$hasResult = TRUE;
								}
							}
							else {
								$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
								$pageResult = $connection->commit($getPage);

								while($line = mysqli_fetch_array($pageResult)) {
									$value = $line['pageID'];
									$thumb = $line['fileName'];
									$pass_var = "page-".$value;

									if($value == $object_value) {
										// If bookmarked, display star
										if($bookmarked == 1) {
											echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
												echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
													echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
														echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
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
				$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
				$queryResult = $connection->commit($getQuery);
				$entered = FALSE;

				while($line = mysqli_fetch_array($queryResult)) {
					$value = $line['queryID'];
					$query = $line['query'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "query-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='query.png'>";
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
				$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
				$snippetResult = $connection->commit($getSnippet);
				$entered = FALSE;

				while($line = mysqli_fetch_array($snippetResult)) {
					$value = $line['snippetID'];
					$snippet = $line['snippet'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "snippet-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='snippet.png'>";
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
				$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
				$noteResult = $connection->commit($getNote);
				$entered = FALSE;

				while($line = mysqli_fetch_array($noteResult)) {
					$value = $line['noteID'];
					$note = $line['note'];
					$date = $line['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));
					$pass_var = "note-".$value;

					// Check year and month
					if($date_year == $year && $date_month == $month) {
						if($value == $object_value && $entered == FALSE) {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysqli_fetch_array($allPageResult)) {
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
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
			$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = $connection->commit($getQuery);
			$entered = FALSE;

			while($line = mysqli_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "query-".$value;

				if($date_year == $date) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='query.png'>";
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
			$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = $connection->commit($getSnippet);
			$entered = FALSE;

			while($line = mysqli_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='snippet.png'>";
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
			$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = $connection->commit($getNote);
			$entered = FALSE;

			while($line = mysqli_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='note.png'>";
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
	$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
	$result = $connection->commit($query);
	$hasResult = FALSE; // Check if there are any results

	while($row = mysqli_fetch_array($result))
	{
		$object_type2 = $row['action'];
		$object_value = $row['value'];

		// Page
		if($object_type2 == 'page') {
			$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
			$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

			while($line = mysqli_fetch_array($allPageResult)) {
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
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
								echo "</div>";
							echo "</a>";
							$hasResult = TRUE;
						}
						// If not, display thumbnail
						else {
							echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
								echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
									echo "<img src='page.png'>";
								echo '</div>';
							echo '</a>';
							$hasResult = TRUE;
						}
					}
					else {
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
						$pageResult = $connection->commit($getPage);

						while($line = mysqli_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $object_value) {
								// If bookmarked, display star
								if($bookmarked == 1) {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
										echo '</div>';
									echo '</a>';
									$hasResult = TRUE;
								}
								// If not, display thumbnail
								else {
									echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
				$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
				$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

				while($line = mysqli_fetch_array($allPageResult)) {
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
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(page.png);">';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
									echo "</div>";
								echo "</a>";
								$hasResult = TRUE;
							}
							// If not, display thumbnail
							else {
								echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
										echo "<img src='page.png'>";
									echo '</div>';
								echo '</a>';
								$hasResult = TRUE;
							}
						}
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
							$pageResult = $connection->commit($getPage);

							while($line = mysqli_fetch_array($pageResult)) {
								$value = $line['pageID'];
								$thumb = $line['fileName'];
								$pass_var = "page-".$value;

								if($value == $object_value) {
									// If bookmarked, display star
									if($bookmarked == 1) {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="wrapper" style="width: 100px; height: 100px; float: left; background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
												echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
													echo '<img src="bookmark.png" style="width: 25px; height: 25px;" />';
												echo '</div>';
											echo '</div>';
										echo '</a>';
										$hasResult = TRUE;
									}
									// If not, display thumbnail
									else {
										echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
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
			$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
			$queryResult = $connection->commit($getQuery);
			$entered = FALSE;

			while($line = mysqli_fetch_array($queryResult)) {
				$value = $line['queryID'];
				$query = $line['query'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "query-".$value;

				if($date_year == $date && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='query.png'>";
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
			$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
			$snippetResult = $connection->commit($getSnippet);
			$entered = FALSE;

			while($line = mysqli_fetch_array($snippetResult)) {
				$value = $line['snippetID'];
				$snippet = $line['snippet'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "snippet-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='snippet.png'>";
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
			$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
			$noteResult = $connection->commit($getNote);
			$entered = FALSE;

			while($line = mysqli_fetch_array($noteResult)) {
				$value = $line['noteID'];
				$note = $line['note'];
				$date = $line['date'];
				$date_year = date("Y",strtotime($date));
				$date_month = date("m",strtotime($date));
				$pass_var = "note-".$value;

				if($date_year == $year && $date_month == $month) {
					if($value == $object_value && $entered == FALSE) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
							echo '<div class="wrapper" style="width: 100px; height: 100px; float: left;">';
								echo "<img src='note.png'>";
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


?>
