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
		
        
        <link rel="stylesheet" href="betterflow.css" type="text/css" media="screen" title="betterflow style" charset="utf-8">
		<style type="text/css" media="screen">
			
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
    
    	<ul class="betterflow" id="example">
        
        <? while($line = mysql_fetch_array($pageResult)) {
            $thumb = $line['fileName'];
            $title = $line['title'];
			$link = $line['url'];
            
			echo "<li>";
            echo "<div class='betterflow-front'><img src='../../thumbnails/small/".$thumb."' longdesc='".$link."' alt='".$title."' /></div>";
			echo "<div class='betterflow-back'><p>".$title."</p></div>";
			echo "</li>";  
        }
        ?>
        
        </ul>

    </div>
    
    <?
	mysql_close($con);
	?>

	</body>
</html>