
<?php

// Connecting to database
    $con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
    if (!$con)
      {
      die('Could not connect: ' . mysql_error());
      }
    
    mysql_select_db("shahonli_coagmento", $con);
    $userID=2;


$q=$_GET["q"];


while($line = mysql_fetch_array($pageResult)) {
            $thumb = $line['fileName'];
            $title = $line['title'];
			$link = $line['url'];
            
			echo "<div class='item'>";
            echo "<img class='content' src='../../thumbnails/small/".$thumb."' />";  
			echo "<div class='caption'>".$title."</div>";
			echo "</div>";
}

mysql_close($con); 
?>