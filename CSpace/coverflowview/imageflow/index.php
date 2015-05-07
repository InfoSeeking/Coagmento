<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>ImageFlow</title>
		<meta name="robots" content="index, follow, noarchive" />
		<link rel="stylesheet" href="style.css" type="text/css" />

		<!-- This includes the ImageFlow CSS and JavaScript -->
		<link rel="stylesheet" href="../../assets/css/imageflow_coverflow.css" type="text/css" />
		<script type="text/javascript" src="../../assets/js/imageflow_coverflow.packed.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/thickbox.js"></script>
<link rel="stylesheet" type="text/css" href="styles/thickbox.css" media="screen" />

	</head>
	<body>

    <div id="myImageFlow" class="imageflow">

    <?php
    // Connecting to database
    $con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }

    mysql_select_db("shahonli_coagmento", $con);
    $userID=2;

    $getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID." AND pages.projectID='8' LIMIT 10";
    $pageResult = $connection->commit($getPage);
    ?>

    <? while($line = mysqli_fetch_array($pageResult)) {
            $thumb = $line['fileName'];
            $title = $line['title'];
			$link = $line['url'];

            echo '<img src="../../thumbnails/small/'.$thumb.'" longdesc="javascript:tb_show( \''.$title.'\', \'../../thumbnails/small/b1cefc21ffe211b01b51d283857d67c8.png\', \'slideshow1\' )" alt="'.$title.'" />';

        }
    ?>

    </div>

	</body>
</html>
