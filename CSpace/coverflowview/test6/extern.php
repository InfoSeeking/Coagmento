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

        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.js" type="text/javascript"></script>
		<script type="text/javascript">
         
        $(document).ready(function(){
         
                $(".slidingDiv").hide();
                $(".show_hide").show();
         
            $('.show_hide').click(function(){
            $(".slidingDiv").slideToggle();
            });
         
        });
         
        </script>
        <style type="text/css" media="screen">
			.slidingDiv {
				height:300px;
				background-color: #99CCFF;
				padding:20px;
				margin-top:10px;
				border-bottom:5px solid #3399FF;
			}
			 
			.show_hide {
				display:none;
			}
			
			a.show_hide {
				color: #00F !important;
			}
			
			a.test {
				color: #F00 !important;
			}
		</style>
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
    $pageResult = mysql_query($getPage) or die(" ". mysql_error());
    ?>

    <!-- This is all the XHTML ImageFlow needs -->
    <div id="myImageFlow" class="imageflow">
        <? while($line = mysql_fetch_array($pageResult)) {
            $thumb = $line['fileName'];
            $title = $line['title'];
			$link = $line['url'];
            
            echo "<img src='../../thumbnails/small/".$thumb."' longdesc='&lt;a href=&quot;javascript:void(0);&quot; class=&quot;how-to&quot;&gt;toggle in jQuery&lt;/a&gt;' alt='&lt;a href=&quot;javascript:void(0);&quot; class=&quot;how-to&quot;&gt;toggle in jQuery&lt;/a&gt;' />";  
        }
        ?>
        
        <div style="display: none;" id="toggle-div">
		You should not write inline style, but I used it to make example of using slide toggle with jQuery shorter.
		</div>
        
        <a href="javascript:void(0);" id="how-to">toggle in jQuery</a>
        
        <script>
		jQuery("how-to").click(function () {
			jQuery("#toggle-div").slideToggle("slow");
		});
		</script>

    </div>
    
    <?
	mysql_close($con);
	?>

	</body>
</html>