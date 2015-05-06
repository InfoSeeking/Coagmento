<?php
  	// Connecting to database
		require_once("../connect.php");
		session_start();

		if (!isset($_SESSION['CSpace_userID'])) {
			echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
		}
		else {

		$userID = $_SESSION['CSpace_userID'];

		$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%' ORDER BY date DESC";
		$pageResult = $connection->commit($getPage);

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
			$thumb = $line['fileName'];
			$title = $line['title'];

			$hasThumb = $line['thumbnailID'];
			$pass_var = "page-".$hasThumb;

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
					// echo '<div class="year"><h2>'.$comp_year.'</h2></div>';
				}
				if($comp_month != $compareMonth) {
					// echo '<div class="month"><h3>'.$le_month.'</h3></div>';
					$zval = $zval - 500;
					$yval = $yval - 200;
					$xval = $xval - 300;


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

				echo '<div class="wrapper">';
				echo '<a class="thumbnail_small various fancybox.ajax" href="getDetails.php?q='.$pass_var.'">';
				echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/'.$thumb.'">';
				echo '</a></div>';

			$hasResult = TRUE;

		}
	}
	?>

	<script src="../assets/js/impress.js"></script>
	<script>impress().init();</script>
