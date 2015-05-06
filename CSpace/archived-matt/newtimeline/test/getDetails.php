<?php
$q=$_GET["q"];

$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);

// Thumbnail Query
$sql_thumb="SELECT * FROM pages, thumbnails WHERE pages.thumbnailID = thumbnails.thumbnailID AND userID=2 AND pageID=".$q." order by date desc";
$result_thumb = mysql_query($sql_thumb) or die(" ". mysql_error());

while($row = mysql_fetch_array($result_thumb))
  {
	    echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	    echo $row['title']; echo '<br/>';
		echo "<a href='".$row['url']."'>".$row['url']."</a>";
		echo '</div>';
		
	    echo '<div style="width:350px; position: relative; margin: 0 auto; padding-top: 20px;">';
		echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//';
		echo $row['fileName'];
		echo '" width="350" height="350" style="margin: 0 auto;" />';
		echo '</div>';
		
		echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
		echo '<table border="0"><tr><td width="90"><b>Source:</b></td>';
		echo '<td>'.$row['source'].'</td></tr>';
		echo '<tr><td><b>Query:</b></td>';
		if($row['query'] == '') { echo '<td>null</td></tr>'; }
		else { echo '<td>'.$row['query'].'</td></tr>'; }
		echo '<tr><td><b>Time:</b></td>';
		echo '<td>'.$row['time'].'</td></tr></table>';
  }

mysql_close($con);
?>