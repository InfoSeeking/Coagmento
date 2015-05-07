<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=1024" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <title>Coagmento Impress Experiment</title>
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:regular,semibold,italic,italicsemibold|PT+Sans:400,700,400italic,700italic|PT+Serif:400,700,400italic,700italic" rel="stylesheet" />
    <link href="../assets/css/impress-demo.css" rel="stylesheet" />
	<link type="text/css" rel="stylesheet" href="../assets/css/jquery.qtip.min.css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/jquery.qtip.min.js"></script>

	<script>

	$(document).ready(function()
	{
		$('span').qtip(
		{
			content: { attr: 'data-ot' },
			position: {
				target: 'mouse'
			},
			style: {
				classes: 'qtip-light'
			}
		});
	});

	</script>


</head>
<body class="impress-not-supported">
<div class="fallback-message">
    <p>Your browser <b>doesn't support the features required</b> by impress.js, so you are presented with a simplified version of this presentation.</p>
    <p>For the best experience please use the latest <b>Chrome</b>, <b>Safari</b> or <b>Firefox</b> browser.</p>
</div>
<!-- main part -->
<div id="impress">

<div id="overview" class="step" data-x="0" data-y="0" data-z="500" data-scale="1"><h1>Coagmento 3D</h1></div>

<!-- set base y and z value (increment per step), set x value (increment if month or year differs until max),
	if year differs from previous, reset z and y to base values -->

<!-- add css class to no opacity, add class if (day) slice is in focus -->

<?php
  	// Connecting to database
		require_once("../connect.php");
		session_start();

		if (!isset($_SESSION['CSpace_userID'])) {
			echo "Sorry. Your session has expired. Please <a href=\"http://www.coagmento.org\">login again</a>.";
		}
		else {

		$userID = $_SESSION['CSpace_userID'];

		$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND NOT url = 'about:blank' AND NOT url like '%coagmento.org%' AND NOT url like '%coagmentopad.rutgers.edu%'";
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

	  	while($line = mysqli_fetch_array($pageResult)) {
			$thumb = $line['fileName'];
			$title = $line['title'];

			$hasThumb = $line['thumbnailID'];
			$val = $row['value'];

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
					echo '<div class="step cf" data-x='.$xval.' data-y='.$yval.' data-z='.$zval.' data-rotate="0" data-scale="0.2">';
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

					// if($comp_day == $compareDay)
					// 	// echo '<div class="day">'.$comp_date.'</div>';
					// 	$xval = $xval + 100;
				}

				// if($comp_day != $compareDay) {
				// 	echo '<div class="day">'.$comp_date.'</div>';
				// 	// $xval = $xval + 100;
				// }

				if($contain == false) {
					// echo '<div class="contain cf">';
					echo '<div class="step cf" data-x='.$xval.' data-y='.$yval.' data-z='.$zval.' data-rotate="0" data-scale="0.2">';
					echo '<div class="day">'.$comp_date.'</div>';

					$contain = true;
				}

				$compareDate = $comp_date;
				$compareYear = $comp_year;
				$compareMonth = $comp_month;
				$compareDay = $comp_day;
			}

				echo '<div class="wrapper">';
				echo '<span class="thumbnail" data-ot="<strong>'.$title.'</strong></br>Viewed on: '.$comp_date.'">';
				// echo '<a href="'.$row['url'].'" target="_blank">';
				echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/'.$thumb.'">';
				echo '</span></div>';

			$hasResult = TRUE;
		}
	}

	?>

</div>


<script src="../assets/js/impress.js"></script>
<script>impress().init();</script>

</body>
</html>
