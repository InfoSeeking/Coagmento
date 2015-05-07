<?php
// Db
require_once('core/Base.class.php');
require_once('core/Connection.class.php');
$connection = Connection::getInstance();
// Session info

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

	switch ($object_type)
	{

		case "all" :
		{

			// all-all-all-all & proj-all-all-all
			if($year == 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					// Page
					if($type == 'page') {
						$getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						$pageResult = $connection->commit($getPage);
						$line = mysqli_fetch_array($pageResult);

						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$pass_var = "page-".$value;
						$url = $line['url'];

						// No thumbnail
						if($hasThumb == NULL) {

							// Bookmarked
							if($value != '') {
								// Label by year, month ,day
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

										echo '<div class="contain cf">';
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

										if($comp_day == $compareDay)
											echo '<div class="day">'.$comp_date.'</div>';

									}
									if($comp_day != $compareDay) {
										echo '<div class="day">'.$comp_date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain cf">';
										$contain = true;
									}

									$compareDate = $comp_date;
									$compareYear = $comp_year;
									$compareMonth = $comp_month;
									$compareDay = $comp_day;
								}
							}
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
								echo "</a>";
							    echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								echo "</div>";

								$hasResult = TRUE;
							}
							// Not bookmarked
							else {
								if($value != '') {
									// Label by year, month ,day
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

											echo '<div class="contain cf">';
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

											if($comp_day == $compareDay)
												echo '<div class="day">'.$comp_date.'</div>';

										}
										if($comp_day != $compareDay) {
											echo '<div class="day">'.$comp_date.'</div>';
										}

										if($contain == false) {
											echo '<div class="contain cf">';
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

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									echo '<div class="wrapper">';
									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo "<img src='assets/img/page.png'>";
									echo '</a>';
									if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
									echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									}
									echo '</div>';

									$hasResult = TRUE;
								}
							}
						}
						// Has thumbnail
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							$pageResult = $connection->commit($getPage);
							$line = mysqli_fetch_array($pageResult);

							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $val) {
								// Bookmarked

								// Label by year, month ,day
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

											echo '<div class="contain cf">';
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

											if($comp_day == $compareDay)
												echo '<div class="day">'.$comp_date.'</div>';

										}
										if($comp_day != $compareDay) {
											echo '<div class="day">'.$comp_date.'</div>';
										}

										if($contain == false) {
											echo '<div class="contain cf">';
											$contain = true;
										}

										$compareDate = $comp_date;
										$compareYear = $comp_year;
										$compareMonth = $comp_month;
										$compareDay = $comp_day;
									}

								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
									echo '</a>';
							  		if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									}
									echo '</div>';

									$hasResult = TRUE;
								}
								// Not bookmarked
								else {
									if($value != '') {
										// Label by year, month ,day
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

												echo '<div class="contain cf">';
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

												if($comp_day == $compareDay)
													echo '<div class="day">'.$comp_date.'</div>';

											}
											if($comp_day != $compareDay) {
												echo '<div class="day">'.$comp_date.'</div>';
											}

											if($contain == false) {
												echo '<div class="contain cf">';
												$contain = true;
											}

											$compareDate = $comp_date;
											$compareYear = $comp_year;
											$compareMonth = $comp_month;
											$compareDay = $comp_day;
										}
									}

									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									echo '<div class="wrapper">';
										echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
										echo '</a>';
									if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
									echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									}
									echo '</div>';

									$hasResult = TRUE;
								}
							}
						}
					} // End page

					// Bookmark
					if($type == 'save-page') {
						$getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						$bookmarkResult = $connection->commit($getBookmark);
						$line = mysqli_fetch_array($bookmarkResult);

						$hasThumb = $line['thumbnailID'];
						$pass_var = "page-".$val;

						// Label by year, month ,day
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

									echo '<div class="contain cf">';
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

									if($comp_day == $compareDay)
										echo '<div class="day">'.$comp_date.'</div>';

								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain cf">';
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

						if($hasThumb == NULL) {
							echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
							echo "</a>";
							echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							echo "</div>";
						}
						else {
							$getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							$bookmarkResult = $connection->commit($getBookmark);
							$line = mysqli_fetch_array($bookmarkResult);

							$thumb = $line['fileName'];
							$pass_var = "page-".$val;

							echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo '</div>';
							echo '</a>';
							echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							echo '</div>';
						}
					} // End bookmark

					// Query
					if($type == 'query') {
						$getQuery="SELECT * FROM queries WHERE queryID=".$val."";
						$queryResult = $connection->commit($getQuery);
						$line = mysqli_fetch_array($queryResult);
						$source = $line['source'];

						$query = $line['query'];
						$source = $line['source'];
						$id = $line['queryID'];
						$pass_var = "query-".$val;

						// Label by year, month ,day
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

								echo '<div class="contain cf">';
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

								if($comp_day == $compareDay)
									echo '<div class="day">'.$comp_date.'</div>';

							}
							if($comp_day != $compareDay) {
								echo '<div class="day">'.$comp_date.'</div>';
							}

							if($contain == false) {
								echo '<div class="contain cf">';
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

						echo '<div class="wrapper">';
						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
							echo "<img src='assets/img/query_".$source.".png'>";
						}
						else {
							echo "<img src='assets/img/query.png'>";
						}

						echo '</a>';
						echo '</div>';

						$hasResult = TRUE;
					} // End query

					// Snippet
					if($type == 'save-snippet') {
						$getSnippet="SELECT * FROM snippets WHERE snippetID=".$val."";
						$snippetResult = $connection->commit($getSnippet);
						$line = mysqli_fetch_array($snippetResult);

						$pass_var = "snippet-".$val;

						// Label by year, month ,day
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

								echo '<div class="contain cf">';
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

								if($comp_day == $compareDay)
									echo '<div class="day">'.$comp_date.'</div>';

							}
							if($comp_day != $compareDay) {
								echo '<div class="day">'.$comp_date.'</div>';
							}

							if($contain == false) {
								echo '<div class="contain cf">';
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

						echo '<div class="wrapper">';
						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						echo "<img src='assets/img/snippet.png'>";

						echo '</a>';
						echo '</div>';

						$hasResult = TRUE;
					} // End snippet

					// Annotation
					if($type == 'add-annotation') {
						$getNote="SELECT * FROM annotations WHERE noteID=".$val."";
						$noteResult = $connection->commit($getNote);
						$line = mysqli_fetch_array($noteResult);

						$pass_var = "note-".$val;

						// Label by year, month ,day
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

									echo '<div class="contain cf">';
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

									if($comp_day == $compareDay)
										echo '<div class="day">'.$comp_date.'</div>';

								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain cf">';
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

						echo '<div class="wrapper">';
						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						echo "<img src='assets/img/note.png'>";

						echo '</a>';
						echo '</div>';

						$hasResult = TRUE;
					} // End annotation
				} // End while loop
			} // End all-all-all-all



			// all-all-year-all & proj-all-year-all
			if($year != 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));

					if($date_year == $year) {

					  // Page
					  if($type == 'page') {
						  $getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $pageResult = $connection->commit($getPage);
						  $line = mysqli_fetch_array($pageResult);

						  $hasThumb = $line['thumbnailID'];
						  $value = $line['pageID'];
						  $bookmarked = $line['result'];
						  $pass_var = "page-".$val;

						  // No thumbnail
						  if($hasThumb == NULL) {

							  // Bookmarked
							  if($value != '') {
								  // Label by year, month ,day
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

										  echo '<div class="contain cf">';
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

										  if($comp_day == $compareDay)
											  echo '<div class="day">'.$comp_date.'</div>';

									  }
									  if($comp_day != $compareDay) {
										  echo '<div class="day">'.$comp_date.'</div>';
									  }

									  if($contain == false) {
										  echo '<div class="contain cf">';
										  $contain = true;
									  }

									  $compareDate = $comp_date;
									  $compareYear = $comp_year;
									  $compareMonth = $comp_month;
									  $compareDay = $comp_day;
								  }
							  }
							  if($bookmarked == 1) {
								  if($line['userID'] == $userID) {
									  $class = 'thumbnail_small2';
								  }
								  else {
									  $class = 'thumbnail_small';
								  }

								  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										  echo "</div>";
								  echo "</a>";
								  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								  echo "</div>";

								  $hasResult = TRUE;
							  }
							  // Not bookmarked
							  else {
								  if($value != '') {
									  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
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

										//exclude SERP pages for checkbox
										$excludeGoogle1  = 'http://www.google';
										$excludeGoogle2   = 'https://www.google';
										$excludeYahoo1 = 'http://www.yahoo';
										$excludeYahoo2 = 'https://www.yahoo';
										$excludeBing1 = 'http://www.bing';
										$excludeBing2 = 'https://www.bing';

										$pos1 = strpos($url, $excludeGoogle1);
										$pos2 = strpos($url, $excludeGoogle2);
										$pos3 = strpos($url, $excludeYahoo1);
										$pos4 = strpos($url, $excludeYahoo2);
										$pos5 = strpos($url, $excludeBing1);
										$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='assets/img/page.png'>";
									  echo '</a>';
									  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
											echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
										}
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
						  // Has thumbnail
						  else {
							  $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $pageResult = $connection->commit($getPage);
							  $line = mysqli_fetch_array($pageResult);

							  $value = $line['pageID'];
							  $thumb = $line['fileName'];
							  $pass_var = "page-".$value;

							  if($value == $val) {
								  // Bookmarked

								  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
											  $contain = true;
										  }

										  $compareDate = $comp_date;
										  $compareYear = $comp_year;
										  $compareMonth = $comp_month;
										  $compareDay = $comp_day;
									  }

								  if($bookmarked == 1) {
									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

									  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											  echo '</div>';
									  echo '</a>';
									  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  echo '</div>';

									  $hasResult = TRUE;
								  }
								  // Not bookmarked
								  else {
									  if($value != '') {
										  // Label by year, month ,day
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

												  echo '<div class="contain cf">';
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

												  if($comp_day == $compareDay)
													  echo '<div class="day">'.$comp_date.'</div>';

											  }
											  if($comp_day != $compareDay) {
												  echo '<div class="day">'.$comp_date.'</div>';
											  }

											  if($contain == false) {
												  echo '<div class="contain cf">';
												  $contain = true;
											  }

											  $compareDate = $comp_date;
											  $compareYear = $comp_year;
											  $compareMonth = $comp_month;
											  $compareDay = $comp_day;
										  }
									  }

									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

										//exclude SERP pages for checkbox
										$excludeGoogle1  = 'http://www.google';
										$excludeGoogle2   = 'https://www.google';
										$excludeYahoo1 = 'http://www.yahoo';
										$excludeYahoo2 = 'https://www.yahoo';
										$excludeBing1 = 'http://www.bing';
										$excludeBing2 = 'https://www.bing';

										$pos1 = strpos($url, $excludeGoogle1);
										$pos2 = strpos($url, $excludeGoogle2);
										$pos3 = strpos($url, $excludeYahoo1);
										$pos4 = strpos($url, $excludeYahoo2);
										$pos5 = strpos($url, $excludeBing1);
										$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									  echo '</a>';
									  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
											echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
										}
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
					  } // End page

					  // Bookmark
					  if($type == 'save-page') {
						  $getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $bookmarkResult = $connection->commit($getBookmark);
						  $line = mysqli_fetch_array($bookmarkResult);

						  $hasThumb = $line['thumbnailID'];
						  $pass_var = "page-".$value;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  if($hasThumb == NULL) {
							  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo "</div>";
							  echo "</a>";
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo "</div>";

						  }
						  else {
							  $getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $bookmarkResult = $connection->commit($getBookmark);
							  $line = mysqli_fetch_array($bookmarkResult);

							  $thumb = $line['fileName'];
							  $pass_var = "page-".$val;
							  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo '</div>';
							  echo '</a>';
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo '</div>';
						  }
					  } // End bookmark

					  // Query
					  if($type == 'query') {
						  $getQuery="SELECT * FROM queries WHERE queryID=".$val."";
						  $queryResult = $connection->commit($getQuery);
						  $line = mysqli_fetch_array($queryResult);
						  $source = $line['source'];

						  $query = $line['query'];
						  $source = $line['source'];
						  $id = $line['queryID'];
						  $pass_var = "query-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
							  echo "<img src='assets/img/query_".$source.".png'>";
						  }
						  else {
							  echo "<img src='assets/img/query.png'>";
						  }

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End query

					  // Snippet
					  if($type == 'save-snippet') {
						  $getSnippet="SELECT * FROM snippets WHERE snippetID=".$val."";
						  $snippetResult = $connection->commit($getSnippet);
						  $line = mysqli_fetch_array($snippetResult);

						  $pass_var = "snippet-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/snippet.png'>";
						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End snippet

					  // Annotation
					  if($type == 'add-annotation') {
						  $getNote="SELECT * FROM annotations WHERE noteID=".$val."";
						  $noteResult = $connection->commit($getNote);
						  $line = mysqli_fetch_array($noteResult);

						  $pass_var = "note-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/note.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End annotation
					} // End year loop
				} // End while loop
			} // End all-all-year-all



			// all-all-year-month & proj-all-year-month
			if($year != 'all' && $month != 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page' OR action='query' OR action='add-annotation' OR action='save-snippet') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));

					if($date_year == $year && $date_month == $month) {

					  // Page
					  if($type == 'page') {
						  $getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $pageResult = $connection->commit($getPage);
						  $line = mysqli_fetch_array($pageResult);

						  $hasThumb = $line['thumbnailID'];
						  $value = $line['pageID'];
						  $bookmarked = $line['result'];
						  $pass_var = "page-".$val;

						  // No thumbnail
						  if($hasThumb == NULL) {

							  // Bookmarked
							  if($value != '') {
								  // Label by year, month ,day
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

										  echo '<div class="contain cf">';
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

										  if($comp_day == $compareDay)
											  echo '<div class="day">'.$comp_date.'</div>';

									  }
									  if($comp_day != $compareDay) {
										  echo '<div class="day">'.$comp_date.'</div>';
									  }

									  if($contain == false) {
										  echo '<div class="contain cf">';
										  $contain = true;
									  }

									  $compareDate = $comp_date;
									  $compareYear = $comp_year;
									  $compareMonth = $comp_month;
									  $compareDay = $comp_day;
								  }
							  }
							  if($bookmarked == 1) {
								  if($line['userID'] == $userID) {
									  $class = 'thumbnail_small2';
								  }
								  else {
									  $class = 'thumbnail_small';
								  }

								  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										  echo "</div>";
								  echo "</a>";
								  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								  echo "</div>";

								  $hasResult = TRUE;
							  }
							  // Not bookmarked
							  else {
								  if($value != '') {
									  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
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

										//exclude SERP pages for checkbox
										$excludeGoogle1  = 'http://www.google';
										$excludeGoogle2   = 'https://www.google';
										$excludeYahoo1 = 'http://www.yahoo';
										$excludeYahoo2 = 'https://www.yahoo';
										$excludeBing1 = 'http://www.bing';
										$excludeBing2 = 'https://www.bing';

										$pos1 = strpos($url, $excludeGoogle1);
										$pos2 = strpos($url, $excludeGoogle2);
										$pos3 = strpos($url, $excludeYahoo1);
										$pos4 = strpos($url, $excludeYahoo2);
										$pos5 = strpos($url, $excludeBing1);
										$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='assets/img/page.png'>";
									  echo '</a>';
									  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
											echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
										}
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
						  // Has thumbnail
						  else {
							  $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $pageResult = $connection->commit($getPage);
							  $line = mysqli_fetch_array($pageResult);

							  $value = $line['pageID'];
							  $thumb = $line['fileName'];
							  $pass_var = "page-".$value;

							  if($value == $val) {
								  // Bookmarked

								  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
											  $contain = true;
										  }

										  $compareDate = $comp_date;
										  $compareYear = $comp_year;
										  $compareMonth = $comp_month;
										  $compareDay = $comp_day;
									  }

								  if($bookmarked == 1) {
									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

										//exclude SERP pages for checkbox
										$excludeGoogle1  = 'http://www.google';
										$excludeGoogle2   = 'https://www.google';
										$excludeYahoo1 = 'http://www.yahoo';
										$excludeYahoo2 = 'https://www.yahoo';
										$excludeBing1 = 'http://www.bing';
										$excludeBing2 = 'https://www.bing';

										$pos1 = strpos($url, $excludeGoogle1);
										$pos2 = strpos($url, $excludeGoogle2);
										$pos3 = strpos($url, $excludeYahoo1);
										$pos4 = strpos($url, $excludeYahoo2);
										$pos5 = strpos($url, $excludeBing1);
										$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											  echo '</div>';
									  echo '</a>';
									  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
											echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
										}
									  echo '</div>';

									  $hasResult = TRUE;
								  }
								  // Not bookmarked
								  else {
									  if($value != '') {
										  // Label by year, month ,day
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

												  echo '<div class="contain cf">';
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

												  if($comp_day == $compareDay)
													  echo '<div class="day">'.$comp_date.'</div>';

											  }
											  if($comp_day != $compareDay) {
												  echo '<div class="day">'.$comp_date.'</div>';
											  }

											  if($contain == false) {
												  echo '<div class="contain cf">';
												  $contain = true;
											  }

											  $compareDate = $comp_date;
											  $compareYear = $comp_year;
											  $compareMonth = $comp_month;
											  $compareDay = $comp_day;
										  }
									  }

									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

										//exclude SERP pages for checkbox
										$excludeGoogle1  = 'http://www.google';
										$excludeGoogle2   = 'https://www.google';
										$excludeYahoo1 = 'http://www.yahoo';
										$excludeYahoo2 = 'https://www.yahoo';
										$excludeBing1 = 'http://www.bing';
										$excludeBing2 = 'https://www.bing';

										$pos1 = strpos($url, $excludeGoogle1);
										$pos2 = strpos($url, $excludeGoogle2);
										$pos3 = strpos($url, $excludeYahoo1);
										$pos4 = strpos($url, $excludeYahoo2);
										$pos5 = strpos($url, $excludeBing1);
										$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									  echo '</a>';
									  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
											echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
										}
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
					  } // End page

					  // Bookmark
					  if($type == 'save-page') {
						  $getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $bookmarkResult = $connection->commit($getBookmark);
						  $line = mysqli_fetch_array($bookmarkResult);

						  $hasThumb = $line['thumbnailID'];
						  $pass_var = "page-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  if($hasThumb == NULL) {
							  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo "</div>";
							  echo "</a>";
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo "</div>";
						  }
						  else {
							  $getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $bookmarkResult = $connection->commit($getBookmark);
							  $line = mysqli_fetch_array($bookmarkResult);

							  $thumb = $line['fileName'];
							  $pass_var = "page-".$val;
							  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo '</div>';
							  echo '</a>';
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo '</div>';
						  }
					  } // End bookmark

					  // Query
					  if($type == 'query') {
						  $getQuery="SELECT * FROM queries WHERE queryID=".$val."";
						  $queryResult = $connection->commit($getQuery);
						  $line = mysqli_fetch_array($queryResult);
						  $source = $line['source'];

						  $query = $line['query'];
						  $source = $line['source'];
						  $id = $line['queryID'];
						  $pass_var = "query-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
							  echo "<img src='assets/img/query_".$source.".png'>";
						  }
						  else {
							  echo "<img src='assets/img/query.png'>";
						  }

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End query

					  // Snippet
					  if($type == 'save-snippet') {
						  $getSnippet="SELECT * FROM snippets WHERE snippetID=".$val."";
						  $snippetResult = $connection->commit($getSnippet);
						  $line = mysqli_fetch_array($snippetResult);

						  $pass_var = "snippet-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/snippet.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End snippet

					  // Annotation
					  if($type == 'add-annotation') {
						  $getNote="SELECT * FROM annotations WHERE noteID=".$val."";
						  $noteResult = $connection->commit($getNote);
						  $line = mysqli_fetch_array($noteResult);

						  $pass_var = "note-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/note.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End annotation
					} // End year loop
				} // End while loop
			} // End all-all-year-month



			if($hasResult == FALSE) {
				echo "No results found";
			}

			break;
		} // End case "all"

		case "pages" :
		{
			// all-obj-all-all & proj-obj-all-all
			if($year == 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					// Page
					if($type == 'page') {
						$getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						$pageResult = $connection->commit($getPage);
						$line = mysqli_fetch_array($pageResult);

						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$pass_var = "page-".$val;
						$url = $line['url'];

						// No thumbnail
						if($hasThumb == NULL) {

							// Bookmarked
							if($value != '') {
								// Label by year, month ,day
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

										echo '<div class="contain cf">';
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

										if($comp_day == $compareDay)
											echo '<div class="day">'.$comp_date.'</div>';

									}
									if($comp_day != $compareDay) {
										echo '<div class="day">'.$comp_date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain cf">';
										$contain = true;
									}

									$compareDate = $comp_date;
									$compareYear = $comp_year;
									$compareMonth = $comp_month;
									$compareDay = $comp_day;
								}
							}
							if($bookmarked == 1) {
								if($line['userID'] == $userID) {
									$class = 'thumbnail_small2';
								}
								else {
									$class = 'thumbnail_small';
								}

								echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
								echo "</a>";
								echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								echo "</div>";

								$hasResult = TRUE;
							}
							// Not bookmarked
							else {
								if($value != '') {
									// Label by year, month ,day
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

											echo '<div class="contain cf">';
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

											if($comp_day == $compareDay)
												echo '<div class="day">'.$comp_date.'</div>';

										}
										if($comp_day != $compareDay) {
											echo '<div class="day">'.$comp_date.'</div>';
										}

										if($contain == false) {
											echo '<div class="contain cf">';
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

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									echo '<div class="wrapper">';
									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo "<img src='assets/img/page.png'>";
									echo '</a>';
								  	if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									}
									echo '</div>';

									$hasResult = TRUE;
								}
							}
						}
						// Has thumbnail
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							$pageResult = $connection->commit($getPage);
							$line = mysqli_fetch_array($pageResult);

							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $val) {
								// Bookmarked

								// Label by year, month ,day
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

											echo '<div class="contain cf">';
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

											if($comp_day == $compareDay)
												echo '<div class="day">'.$comp_date.'</div>';

										}
										if($comp_day != $compareDay) {
											echo '<div class="day">'.$comp_date.'</div>';
										}

										if($contain == false) {
											echo '<div class="contain cf">';
											$contain = true;
										}

										$compareDate = $comp_date;
										$compareYear = $comp_year;
										$compareMonth = $comp_month;
										$compareDay = $comp_day;
									}

								if($bookmarked == 1) {
									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
									echo '</a>';
								  	if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									}
									echo '</div>';

									$hasResult = TRUE;
								}
								// Not bookmarked
								else {
									if($value != '') {
										// Label by year, month ,day
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

												echo '<div class="contain cf">';
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

												if($comp_day == $compareDay)
													echo '<div class="day">'.$comp_date.'</div>';

											}
											if($comp_day != $compareDay) {
												echo '<div class="day">'.$comp_date.'</div>';
											}

											if($contain == false) {
												echo '<div class="contain cf">';
												$contain = true;
											}

											$compareDate = $comp_date;
											$compareYear = $comp_year;
											$compareMonth = $comp_month;
											$compareDay = $comp_day;
										}
									}

									if($line['userID'] == $userID) {
										$class = 'thumbnail_small2';
									}
									else {
										$class = 'thumbnail_small';
									}

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									echo '<div class="wrapper">';
									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									echo '</a>';
								  	if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									}
									echo '</div>';

									$hasResult = TRUE;
								}
							}
						}
					} // End page

					// Bookmark
					if($type == 'save-page') {
						$getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						$bookmarkResult = $connection->commit($getBookmark);
						$line = mysqli_fetch_array($bookmarkResult);

						$hasThumb = $line['thumbnailID'];
						$pass_var = "page-".$val;

						// Label by year, month ,day
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

									echo '<div class="contain cf">';
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

									if($comp_day == $compareDay)
										echo '<div class="day">'.$comp_date.'</div>';

								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain cf">';
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

						if($hasThumb == NULL) {
							echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
							echo "</a>";
							echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							echo "</div>";
						}
						else {
							$getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							$bookmarkResult = $connection->commit($getBookmark);
							$line = mysqli_fetch_array($bookmarkResult);

							$thumb = $line['fileName'];
							$pass_var = "page-".$val;

							echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo '</div>';
							echo '</a>';
							echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							echo '</div>';
						}
					} // End bookmark
				} // End while loop
			} // End all-all-all-all



			// all-obj-year-all & proj-obj-year-all
			if($year != 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));

					if($date_year == $year) {

					  // Page
					  if($type == 'page') {
						  $getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $pageResult = $connection->commit($getPage);
						  $line = mysqli_fetch_array($pageResult);

						  $hasThumb = $line['thumbnailID'];
						  $value = $line['pageID'];
						  $bookmarked = $line['result'];
						  $pass_var = "page-".$val;

						  // No thumbnail
						  if($hasThumb == NULL) {

							  // Bookmarked
							  if($value != '') {
								  // Label by year, month ,day
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

										  echo '<div class="contain cf">';
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

										  if($comp_day == $compareDay)
											  echo '<div class="day">'.$comp_date.'</div>';

									  }
									  if($comp_day != $compareDay) {
										  echo '<div class="day">'.$comp_date.'</div>';
									  }

									  if($contain == false) {
										  echo '<div class="contain cf">';
										  $contain = true;
									  }

									  $compareDate = $comp_date;
									  $compareYear = $comp_year;
									  $compareMonth = $comp_month;
									  $compareDay = $comp_day;
								  }
							  }
							  if($bookmarked == 1) {
								  if($line['userID'] == $userID) {
									  $class = 'thumbnail_small2';
								  }
								  else {
									  $class = 'thumbnail_small';
								  }

								  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										  echo "</div>";
								  echo "</a>";
								  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								  echo "</div>";

								  $hasResult = TRUE;
							  }
							  // Not bookmarked
							  else {
								  if($value != '') {
									  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
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

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='assets/img/page.png'>";
									  echo '</a>';
								  	  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  }
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
						  // Has thumbnail
						  else {
							  $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $pageResult = $connection->commit($getPage);
							  $line = mysqli_fetch_array($pageResult);

							  $value = $line['pageID'];
							  $thumb = $line['fileName'];
							  $pass_var = "page-".$value;

							  if($value == $val) {
								  // Bookmarked

								  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
											  $contain = true;
										  }

										  $compareDate = $comp_date;
										  $compareYear = $comp_year;
										  $compareMonth = $comp_month;
										  $compareDay = $comp_day;
									  }

								  if($bookmarked == 1) {
									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

									  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											  echo '</div>';
									  echo '</a>';
									  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  echo '</div>';

									  $hasResult = TRUE;
								  }
								  // Not bookmarked
								  else {
									  if($value != '') {
										  // Label by year, month ,day
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

												  echo '<div class="contain cf">';
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

												  if($comp_day == $compareDay)
													  echo '<div class="day">'.$comp_date.'</div>';

											  }
											  if($comp_day != $compareDay) {
												  echo '<div class="day">'.$comp_date.'</div>';
											  }

											  if($contain == false) {
												  echo '<div class="contain cf">';
												  $contain = true;
											  }

											  $compareDate = $comp_date;
											  $compareYear = $comp_year;
											  $compareMonth = $comp_month;
											  $compareDay = $comp_day;
										  }
									  }

									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									  echo '</a>';
								  	  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  }
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
					  } // End page

					  // Bookmark
					  if($type == 'save-page') {
						  $getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $bookmarkResult = $connection->commit($getBookmark);
						  $line = mysqli_fetch_array($bookmarkResult);

						  $hasThumb = $line['thumbnailID'];
						  $pass_var = "page-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  if($hasThumb == NULL) {
							  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo "</div>";
							  echo "</a>";
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo "</div>";
						  }
						  else {
							  $getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $bookmarkResult = $connection->commit($getBookmark);
							  $line = mysqli_fetch_array($bookmarkResult);

							  $thumb = $line['fileName'];
							  $pass_var = "page-".$val;

							  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo '</div>';
							  echo '</a>';
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo '</div>';
						  }
					  } // End bookmark
					} // End year loop
				} // End while loop
			} // End all-all-year-all



			// all-obj-year-month & proj-obj-year-month
			if($year != 'all' && $month != 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));

					if($date_year == $year && $date_month == $month) {

					  // Page
					  if($type == 'page') {
						  $getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $pageResult = $connection->commit($getPage);
						  $line = mysqli_fetch_array($pageResult);

						  $hasThumb = $line['thumbnailID'];
						  $value = $line['pageID'];
						  $bookmarked = $line['result'];
						  $pass_var = "page-".$val;
						  $url = $line['url'];

						  // No thumbnail
						  if($hasThumb == NULL) {

							  // Bookmarked
							  if($value != '') {
								  // Label by year, month ,day
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

										  echo '<div class="contain cf">';
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

										  if($comp_day == $compareDay)
											  echo '<div class="day">'.$comp_date.'</div>';

									  }
									  if($comp_day != $compareDay) {
										  echo '<div class="day">'.$comp_date.'</div>';
									  }

									  if($contain == false) {
										  echo '<div class="contain cf">';
										  $contain = true;
									  }

									  $compareDate = $comp_date;
									  $compareYear = $comp_year;
									  $compareMonth = $comp_month;
									  $compareDay = $comp_day;
								  }
							  }
							  if($bookmarked == 1) {
								  if($line['userID'] == $userID) {
									  $class = 'thumbnail_small2';
								  }
								  else {
									  $class = 'thumbnail_small';
								  }

									  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								  	  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										  echo "</div>";
								  	  echo "</a>";
									  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  echo "</div>";

								  $hasResult = TRUE;
							  }
							  // Not bookmarked
							  else {
								  if($value != '') {
									  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
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

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);


									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='assets/img/page.png'>";
									  echo '</a>';
								  	  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  }
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
						  // Has thumbnail
						  else {
							  $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $pageResult = $connection->commit($getPage);
							  $line = mysqli_fetch_array($pageResult);

							  $value = $line['pageID'];
							  $thumb = $line['fileName'];
							  $pass_var = "page-".$value;

							  if($value == $val) {
								  // Bookmarked

								  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
											  $contain = true;
										  }

										  $compareDate = $comp_date;
										  $compareYear = $comp_year;
										  $compareMonth = $comp_month;
										  $compareDay = $comp_day;
									  }

								  if($bookmarked == 1) {
									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

									  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											  echo '</div>';
									  echo '</a>';
									  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  echo '</div>';

									  $hasResult = TRUE;
								  }
								  // Not bookmarked
								  else {
									  if($value != '') {
										  // Label by year, month ,day
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

												  echo '<div class="contain cf">';
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

												  if($comp_day == $compareDay)
													  echo '<div class="day">'.$comp_date.'</div>';

											  }
											  if($comp_day != $compareDay) {
												  echo '<div class="day">'.$comp_date.'</div>';
											  }

											  if($contain == false) {
												  echo '<div class="contain cf">';
												  $contain = true;
											  }

											  $compareDate = $comp_date;
											  $compareYear = $comp_year;
											  $compareMonth = $comp_month;
											  $compareDay = $comp_day;
										  }
									  }

									  if($line['userID'] == $userID) {
										  $class = 'thumbnail_small2';
									  }
									  else {
										  $class = 'thumbnail_small';
									  }

									//exclude SERP pages for checkbox
									$excludeGoogle1  = 'http://www.google';
									$excludeGoogle2   = 'https://www.google';
									$excludeYahoo1 = 'http://www.yahoo';
									$excludeYahoo2 = 'https://www.yahoo';
									$excludeBing1 = 'http://www.bing';
									$excludeBing2 = 'https://www.bing';

									$pos1 = strpos($url, $excludeGoogle1);
									$pos2 = strpos($url, $excludeGoogle2);
									$pos3 = strpos($url, $excludeYahoo1);
									$pos4 = strpos($url, $excludeYahoo2);
									$pos5 = strpos($url, $excludeBing1);
									$pos6 = strpos($url, $excludeBing2);

									  echo '<div class="wrapper">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo "<img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'>";
									  echo '</a>';
								  	  if ($pos1 === false && $pos2 === false && $pos3 === false && $pos4 === false && $pos5 === false && $pos6 === false)  {
										echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  }
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
					  } // End page

					  // Bookmark
					  if($type == 'save-page') {
						  $getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $bookmarkResult = $connection->commit($getBookmark);
						  $line = mysqli_fetch_array($bookmarkResult);

						  $hasThumb = $line['thumbnailID'];
						  $pass_var = "page-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  if($hasThumb == NULL) {
							  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo "</div>";
							  echo "</a>";
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo "</div>";
						  }
						  else {
							  $getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $bookmarkResult = $connection->commit($getBookmark);
							  $line = mysqli_fetch_array($bookmarkResult);

							  $thumb = $line['fileName'];
							  $pass_var = "page-".$val;

							  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo '</div>';
							  echo '</a>';
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo '</div>';
						  }
					  } // End bookmark
					} // End year loop
				} // End while loop
			} // End all-all-year-month



			if($hasResult == FALSE) {
				echo "No results found";
			}

			break;
		} // End case "pages"

		case "queries" :
		{

			// all-obj-all-all & proj-obj-all-all
			if($year == 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='query' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					// Query
					if($type == 'query') {
						$getQuery="SELECT * FROM queries WHERE queryID=".$val."";
						$queryResult = $connection->commit($getQuery);
						$line = mysqli_fetch_array($queryResult);
						$source = $line['source'];

						$query = $line['query'];
						$source = $line['source'];
						$id = $line['queryID'];
						$pass_var = "query-".$val;

						// Label by year, month ,day
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

								echo '<div class="contain cf">';
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

								if($comp_day == $compareDay)
									echo '<div class="day">'.$comp_date.'</div>';

							}
							if($comp_day != $compareDay) {
								echo '<div class="day">'.$comp_date.'</div>';
							}

							if($contain == false) {
								echo '<div class="contain cf">';
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

						echo '<div class="wrapper">';
						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';


						if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
							echo "<img src='assets/img/query_".$source.".png'>";
						}
						else {
							echo "<img src='assets/img/query.png'>";
						}

						echo '</a>';
						echo '</div>';

						$hasResult = TRUE;
					} // End query
				} // End while loop
			} // End all-obj-all-all



			// all-obj-year-all & proj-obj-year-all
			if($year != 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='query' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));

					if($date_year == $year) {

					  // Query
					  if($type == 'query') {
						  $getQuery="SELECT * FROM queries WHERE queryID=".$val."";
						  $queryResult = $connection->commit($getQuery);
						  $line = mysqli_fetch_array($queryResult);
						  $source = $line['source'];

						  $query = $line['query'];
						  $source = $line['source'];
						  $id = $line['queryID'];
						  $pass_var = "query-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
							  echo "<img src='assets/img/query_".$source.".png'>";
						  }
						  else {
							  echo "<img src='assets/img/query.png'>";
						  }

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End query
					} // End year loop
				} // End while loop
			} // End all-obj-year-all



			// all-obj-year-month & proj-obj-year-month
			if($year != 'all' && $month != 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='query' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));

					if($date_year == $year && $date_month == $month) {

					  // Query
					  if($type == 'query') {
						  $getQuery="SELECT * FROM queries WHERE queryID=".$val."";
						  $queryResult = $connection->commit($getQuery);
						  $line = mysqli_fetch_array($queryResult);
						  $source = $line['source'];

						  $query = $line['query'];
						  $source = $line['source'];
						  $id = $line['queryID'];
						  $pass_var = "query-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  if($source == 'google' || $source == 'yahoo' || $source == 'bing') {
							  echo "<img src='assets/img/query_".$source.".png'>";
						  }
						  else {
							  echo "<img src='assets/img/query.png'>";
						  }

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End query
					} // End year loop
				} // End while loop
			} // End all-obj-year-month



			if($hasResult == FALSE) {
				echo "No results found";
			}

			break;
		} // End case "queries"

		case "snippets" :
		{

			// all-obj-all-all & proj-obj-all-all
			if($year == 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='save-snippet' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					// Snippet
					if($type == 'save-snippet') {
						$getSnippet="SELECT * FROM snippets WHERE snippetID=".$val."";
						$snippetResult = $connection->commit($getSnippet);
						$line = mysqli_fetch_array($snippetResult);

						$pass_var = "snippet-".$val;

						// Label by year, month ,day
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

								echo '<div class="contain cf">';
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

								if($comp_day == $compareDay)
									echo '<div class="day">'.$comp_date.'</div>';

							}
							if($comp_day != $compareDay) {
								echo '<div class="day">'.$comp_date.'</div>';
							}

							if($contain == false) {
								echo '<div class="contain cf">';
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

						echo '<div class="wrapper">';
						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						echo "<img src='assets/img/snippet.png'>";

						echo '</a>';
						echo '</div>';

						$hasResult = TRUE;
					} // End snippet
				} // End while loop
			} // End all-obj-all-all



			// all-obj-year-all & proj-obj-year-all
			if($year != 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='save-snippet' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));

					if($date_year == $year) {

					  // Snippet
					  if($type == 'save-snippet') {
						  $getSnippet="SELECT * FROM snippets WHERE snippetID=".$val."";
						  $snippetResult = $connection->commit($getSnippet);
						  $line = mysqli_fetch_array($snippetResult);

						  $pass_var = "snippet-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/snippet.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End snippet
					} // End year loop
				} // End while loop
			} // End all-all-year-all



			// all-obj-year-month & proj-obj-year-month
			if($year != 'all' && $month != 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='save-snippet' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));

					if($date_year == $year && $date_month == $month) {

					  // Snippet
					  if($type == 'save-snippet') {
						  $getSnippet="SELECT * FROM snippets WHERE snippetID=".$val."";
						  $snippetResult = $connection->commit($getSnippet);
						  $line = mysqli_fetch_array($snippetResult);

						  $pass_var = "snippet-".$val;

						  // Label by year, month ,day
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

								  echo '<div class="contain cf">';
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

								  if($comp_day == $compareDay)
									  echo '<div class="day">'.$comp_date.'</div>';

							  }
							  if($comp_day != $compareDay) {
								  echo '<div class="day">'.$comp_date.'</div>';
							  }

							  if($contain == false) {
								  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/snippet.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End snippet
					} // End year loop
				} // End while loop
			} // End all-obj-year-month



			if($hasResult == FALSE) {
				echo "No results found";
			}

			break;
		} // End case "snippets"

		case "annotations" :
		{

			// all-obj-all-all & proj-obj-all-all
			if($year == 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='add-annotation' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					// Annotation
					if($type == 'add-annotation') {
						$getNote="SELECT * FROM annotations WHERE noteID=".$val."";
						$noteResult = $connection->commit($getNote);
						$line = mysqli_fetch_array($noteResult);

						$pass_var = "note-".$val;

						// Label by year, month ,day
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

									echo '<div class="contain cf">';
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

									if($comp_day == $compareDay)
										echo '<div class="day">'.$comp_date.'</div>';

								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain cf">';
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

						echo '<div class="wrapper">';
						echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						echo "<img src='assets/img/note.png'>";

						echo '</a>';
						echo '</div>';

						$hasResult = TRUE;
					} // End annotation
				} // End while loop
			} // End all-all-all-all



			// all-obj-year-all & proj-obj-year-all
			if($year != 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='add-annotation' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));

					if($date_year == $year) {

					  // Annotation
					  if($type == 'add-annotation') {
						  $getNote="SELECT * FROM annotations WHERE noteID=".$val."";
						  $noteResult = $connection->commit($getNote);
						  $line = mysqli_fetch_array($noteResult);

						  $pass_var = "note-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/note.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End annotation
					} // End year loop
				} // End while loop
			} // End all-all-year-all



			// all-obj-year-month & proj-obj-year-month
			if($year != 'all' && $month != 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND action='add-annotation' AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));

					if($date_year == $year && $date_month == $month) {

					  // Annotation
					  if($type == 'add-annotation') {
						  $getNote="SELECT * FROM annotations WHERE noteID=".$val."";
						  $noteResult = $connection->commit($getNote);
						  $line = mysqli_fetch_array($noteResult);

						  $pass_var = "note-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  echo '<div class="wrapper">';
						  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';

						  echo "<img src='assets/img/note.png'>";

						  echo '</a>';
						  echo '</div>';

						  $hasResult = TRUE;
					  } // End annotation
					} // End year loop
				} // End while loop
			} // End all-all-year-month



			if($hasResult == FALSE) {
				echo "No results found";
			}

			break;
		} // End case "annotations"

		case "saved" :
		{

			// all-obj-all-all & proj-obj-all-all
			if($year == 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					// Page
					if($type == 'page') {
						$getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						$pageResult = $connection->commit($getPage);
						$line = mysqli_fetch_array($pageResult);

						$hasThumb = $line['thumbnailID'];
						$value = $line['pageID'];
						$bookmarked = $line['result'];
						$pass_var = "page-".$val;

						// No thumbnail
						if($hasThumb == NULL) {

							// Bookmarked
							if($bookmarked == 1) {
								// Label by year, month ,day
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

										echo '<div class="contain cf">';
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

										if($comp_day == $compareDay)
											echo '<div class="day">'.$comp_date.'</div>';

									}
									if($comp_day != $compareDay) {
										echo '<div class="day">'.$comp_date.'</div>';
									}

									if($contain == false) {
										echo '<div class="contain cf">';
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

								echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										echo "</div>";
								echo "</a>";
							    echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								echo "</div>";

								$hasResult = TRUE;
							}
						}
						// Has thumbnail
						else {
							$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							$pageResult = $connection->commit($getPage);
							$line = mysqli_fetch_array($pageResult);

							$value = $line['pageID'];
							$thumb = $line['fileName'];
							$pass_var = "page-".$value;

							if($value == $val) {
								// Bookmarked
								if($bookmarked == 1) {
									// Label by year, month ,day
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

											echo '<div class="contain cf">';
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

											if($comp_day == $compareDay)
												echo '<div class="day">'.$comp_date.'</div>';

										}
										if($comp_day != $compareDay) {
											echo '<div class="day">'.$comp_date.'</div>';
										}

										if($contain == false) {
											echo '<div class="contain cf">';
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

									echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											echo '</div>';
									echo '</a>';
							  		echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									echo '</div>';

									$hasResult = TRUE;
								}
							}
						}
					} // End page

					// Bookmark
					if($type == 'save-page') {
						$getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						$bookmarkResult = $connection->commit($getBookmark);
						$line = mysqli_fetch_array($bookmarkResult);

						$hasThumb = $line['thumbnailID'];
						$pass_var = "page-".$val;

						// Label by year, month ,day
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

									echo '<div class="contain cf">';
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

									if($comp_day == $compareDay)
										echo '<div class="day">'.$comp_date.'</div>';

								}
								if($comp_day != $compareDay) {
									echo '<div class="day">'.$comp_date.'</div>';
								}

								if($contain == false) {
									echo '<div class="contain cf">';
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

						if($hasThumb == NULL) {
							echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo "</div>";
							echo "</a>";
							echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							echo "</div>";
						}
						else {
							$getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							$bookmarkResult = $connection->commit($getBookmark);
							$line = mysqli_fetch_array($bookmarkResult);

							$thumb = $line['fileName'];
							$pass_var = "page-".$val;
							echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									echo '</div>';
							echo '</a>';
							echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							echo '</div>';
						}
					} // End bookmark
				} // End while loop
			} // End all-obj-all-all



			// all-obj-year-all & proj-obj-year-all
			if($year != 'all' && $month == 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));

					if($date_year == $year) {

					  // Page
					  if($type == 'page') {
						  $getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $pageResult = $connection->commit($getPage);
						  $line = mysqli_fetch_array($pageResult);

						  $hasThumb = $line['thumbnailID'];
						  $value = $line['pageID'];
						  $bookmarked = $line['result'];
						  $pass_var = "page-".$val;

						  // No thumbnail
						  if($hasThumb == NULL) {

							  // Bookmarked
							  if($bookmarked == 1) {
								  // Label by year, month ,day
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

										  echo '<div class="contain cf">';
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

										  if($comp_day == $compareDay)
											  echo '<div class="day">'.$comp_date.'</div>';

									  }
									  if($comp_day != $compareDay) {
										  echo '<div class="day">'.$comp_date.'</div>';
									  }

									  if($contain == false) {
										  echo '<div class="contain cf">';
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

								  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										  echo "</div>";
								  echo "</a>";
							  	  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								  echo "</div>";

								  $hasResult = TRUE;
							  }
						  }
						  // Has thumbnail
						  else {
							  $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $pageResult = $connection->commit($getPage);
							  $line = mysqli_fetch_array($pageResult);

							  $value = $line['pageID'];
							  $thumb = $line['fileName'];
							  $pass_var = "page-".$value;

							  if($value == $val) {
								  // Bookmarked
								  if($bookmarked == 1) {
									  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
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

									  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											  echo '</div>';
									  echo '</a>';
							  		  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
					  } // End page

					  // Bookmark
					  if($type == 'save-page') {
						  $getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $bookmarkResult = $connection->commit($getBookmark);
						  $line = mysqli_fetch_array($bookmarkResult);

						  $hasThumb = $line['thumbnailID'];
						  $pass_var = "page-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  if($hasThumb == NULL) {
							  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo "</div>";
							  echo "</a>";
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo "</div>";
						  }
						  else {
							  $getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $bookmarkResult = $connection->commit($getBookmark);
							  $line = mysqli_fetch_array($bookmarkResult);

							  $thumb = $line['fileName'];
							  $pass_var = "page-".$val;
							  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo '</div>';
							  echo '</a>';
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo '</div>';

						  }
					  } // End bookmark
					} // End year loop
				} // End while loop
			} // End all-all-year-all



			// all-obj-year-month & proj-obj-year-month
			if($year != 'all' && $month != 'all') {
				$sql="SELECT * FROM actions WHERE ".$projectFilter." ".$userFilter." AND (action='page' OR action='save-page') AND value NOT LIKE '%http%' ORDER BY date DESC, time ASC";
				$result = $connection->commit($query);
				$line = mysqli_fetch_array($result);
				$hasResult = FALSE; // Check if there are any results

				$compareDate = '';
				$compareYear = '';
				$compareMonth = '';
				$compareDay = '';
				$setDate = false;

				$entered_first = false;
				$contain = false;

				while($row = mysqli_fetch_array($result))
				{
					$type = $row['action'];
					$val = $row['value'];

					$date = $row['date'];
					$date_year = date("Y",strtotime($date));
					$date_month = date("m",strtotime($date));

					if($date_year == $year && $date_month == $month) {

					  // Page
					  if($type == 'page') {
						  $getPage="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $pageResult = $connection->commit($getPage);
						  $line = mysqli_fetch_array($pageResult);

						  $hasThumb = $line['thumbnailID'];
						  $value = $line['pageID'];
						  $bookmarked = $line['result'];
						  $pass_var = "page-".$val;

						  // No thumbnail
						  if($hasThumb == NULL) {

							  // Bookmarked
							  if($bookmarked == 1) {
								  // Label by year, month ,day
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

										  echo '<div class="contain cf">';
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

										  if($comp_day == $compareDay)
											  echo '<div class="day">'.$comp_date.'</div>';

									  }
									  if($comp_day != $compareDay) {
										  echo '<div class="day">'.$comp_date.'</div>';
									  }

									  if($contain == false) {
										  echo '<div class="contain cf">';
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
								  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
								  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
										  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
											  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
										  echo "</div>";
								  echo "</a>";
							  	  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
								  echo "</div>";

								  $hasResult = TRUE;
							  }
						  }
						  // Has thumbnail
						  else {
							  $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val."  AND NOT url = 'about:blank'  and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $pageResult = $connection->commit($getPage);
							  $line = mysqli_fetch_array($pageResult);

							  $value = $line['pageID'];
							  $thumb = $line['fileName'];
							  $pass_var = "page-".$value;

							  if($value == $val) {
								  // Bookmarked
								  if($bookmarked == 1) {
									  // Label by year, month ,day
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

											  echo '<div class="contain cf">';
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

											  if($comp_day == $compareDay)
												  echo '<div class="day">'.$comp_date.'</div>';

										  }
										  if($comp_day != $compareDay) {
											  echo '<div class="day">'.$comp_date.'</div>';
										  }

										  if($contain == false) {
											  echo '<div class="contain cf">';
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
									  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
									  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
											  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
												  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
											  echo '</div>';
									  echo '</a>';
							  		  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
									  echo '</div>';

									  $hasResult = TRUE;
								  }
							  }
						  }
					  } // End page

					  // Bookmark
					  if($type == 'save-page') {
						  $getBookmark="SELECT * FROM pages WHERE pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
						  $bookmarkResult = $connection->commit($getBookmark);
						  $line = mysqli_fetch_array($bookmarkResult);

						  $hasThumb = $line['thumbnailID'];
						  $pass_var = "page-".$val;

						  // Label by year, month ,day
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

									  echo '<div class="contain cf">';
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

									  if($comp_day == $compareDay)
										  echo '<div class="day">'.$comp_date.'</div>';

								  }
								  if($comp_day != $compareDay) {
									  echo '<div class="day">'.$comp_date.'</div>';
								  }

								  if($contain == false) {
									  echo '<div class="contain cf">';
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

						  if($hasThumb == NULL) {
							  echo '<div class="wrapper" style="background: url(assets/img/page.png);">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo "</div>";
							  echo "</a>";
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo "</div>";
						  }
						  else {
							  $getBookmark="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$val." AND NOT url = 'about:blank' and not url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
							  $bookmarkResult = $connection->commit($getBookmark);
							  $line = mysqli_fetch_array($bookmarkResult);

							  $thumb = $line['fileName'];
							  $pass_var = "page-".$val;
							  echo '<div class="wrapper" style="background: url(http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails/small/'.$thumb.');">';
							  echo '<a href="javascript:void(0);" class="'.$class.'" onClick=showDetails("'.$pass_var.'")>';
									  echo '<div class="star" style="position: relative; width: 25px !important; height: 25px !important; top: -5px; left: 65px; z-index: 99;">';
										  echo '<img src="assets/img/bookmark.png" style="width: 25px; height: 25px;" />';
									  echo '</div>';
							  echo '</a>';
							  echo '<input type="checkbox" class="checked_pages" value="'.$val.'" onClick=showList('.$val.') />';
							  echo '</div>';

						  }
					  } // End bookmark
					} // End year loop
				} // End while loop
			} // End all-all-year-month



			if($hasResult == FALSE) {
				echo "No results found";
			}

			break;
		} // End case "saved"

	} // End switch object
}

?>
