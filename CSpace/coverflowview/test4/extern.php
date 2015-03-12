<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Coagmento CSpace Coverflow View</title>
		<meta name="robots" content="index, follow, noarchive" />
		<link rel="stylesheet" href="style.css" type="text/css" />

		<!-- This includes the ImageFlow CSS and JavaScript -->
		<link rel="stylesheet" href="imageflow.packed.css" type="text/css" />
		<script type="text/javascript" src="imageflow.packed.js"></script>

        
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

	</head>
	<body>
    
    Hello test 
    
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
    $pageResult = mysql_query($getPage) or die(" ". mysql_error());
    ?>

    <!-- This is all the XHTML ImageFlow needs -->
    <div id="myImageFlow" class="imageflow">
        <? while($line = mysql_fetch_array($pageResult)) {
            $thumb = $line['fileName'];
            $title = $line['title'];
			$link = $line['url'];
            
            echo "<img src='../../thumbnails/small/".$thumb."' longdesc='".$link."' alt='".$title."' />";  
        }
        ?>

    </div>
    
    <?
	mysql_close($con);
	?>

	</body>
</html>