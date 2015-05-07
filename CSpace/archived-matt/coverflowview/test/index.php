<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>Coagmento CSpace Coverflow View</title>
		<link rel="stylesheet" href="betterflow.css" type="text/css" media="screen" title="betterflow style" charset="utf-8">
		<style type="text/css" media="screen">
			body {
				font-family: sans-serif;
				background-color: #fff;
			}

			#example {
				height: 310px;
			}

			#example li {
				width: 300px;
				height: 300px;
				border: 1px solid #eee;
			}

			#example li div.betterflow-front img {
				width: 100%;
				height: 100%;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="jquery.mousewheel.js" type="text/javascript" charset="utf-8"></script>
		<script src="betterflow.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" charset="utf-8">
			$(function() {
				$("#example").betterflow();

				$("#example li.betterflow-selected").live("click", function() {
					$("#example").trigger("betterflow-flip");
				});
			});
		</script>
	</head>
	<body>

<?php
// Connecting to database
$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);
$userID=2;

$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND pages.projectID='8'";
$pageResult = $connection->commit($getPage);
?>

		<h1>Coverflow View</h1>
		<!--<p><em>Tips:</em> click on non-selected album covers to scroll to them; click on the selected one to flip between back/front covers</p>-->
		<ul class="betterflow" id="example">


        <? while($line = mysqli_fetch_array($pageResult)) {
		$thumb = $line['fileName'];
		$title = $line['title'];

		echo "<li>";
		echo "<div class='betterflow-front'><img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."'></div>";
		echo "<div class='betterflow-back'><p>".$title."</p></div>";
		echo "</li>";
		}
		?>

			<!-- <li>
				<div class="betterflow-front"><img src="http://userserve-ak.last.fm/serve/300x300/40625357.png"></div>
				<div class="betterflow-back">Wish You Were Here</div>
			</li>
			<li>
				<div class="betterflow-front"><img src="http://userserve-ak.last.fm/serve/300x300/47226415.png"></div>
				<div class="betterflow-back">Dark Side of the Moon</div>
			</li>
			<li>
				<div class="betterflow-front"><img src="http://userserve-ak.last.fm/serve/300x300/57124781.png"></div>
				<div class="betterflow-back">The Wall</div>
			</li>
			<li>
				<div class="betterflow-front"><img src="http://userserve-ak.last.fm/serve/300x300/40549903.png"></div>
				<div class="betterflow-back">Meddle</div>
			</li> -->
		</ul>
	</body>
</html>
