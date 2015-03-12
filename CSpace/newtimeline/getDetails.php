<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$object_type = $pieces[0];
$object_id = $pieces[1];

$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);

// Page
if($object_type == 'page') {
	$page="SELECT * FROM pages WHERE pageID=".$object_id."";
	$result = mysql_query($page) or die(" ". mysql_error());
	
	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		$hasThumb = $row['thumbnailID'];
		if($hasThumb == NULL) {
			echo "<h2> Page #".$row['pageID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
		}
		else {
			$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_id."";
			$pageResult = mysql_query($getPage) or die(" ". mysql_error());
			
			while($line = mysql_fetch_array($pageResult)) {
				$value = $line['pageID'];
				$thumb = $line['fileName'];
				
				if($value == $object_id) {
					echo "<h2> Page #".$row['pageID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
					echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//';
					echo $thumb;
					echo '" width="90%" height="90%" />';
					echo '</a>';
				}
			}	
		}
	}
	echo '</div>';
}

// Query
if($object_type == 'query') {
	$query="SELECT * FROM queries WHERE queryID=".$object_id."";
	$result = mysql_query($query) or die(" ". mysql_error());
	
	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		echo "<h2> Query #".$row['queryID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
		echo "<strong>Query: </strong>".$row['query']."<br/>";
		echo "<strong>Source: </strong>".$row['source']."<br/>";
		
	}
	echo '</div>';
}

// Snippet
if($object_type == 'snippet') {
	$snippet="SELECT * FROM snippets WHERE snippetID=".$object_id."";
	$result = mysql_query($snippet) or die(" ". mysql_error());
	
	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		echo "<h2> Snippet #".$row['snippetID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
	}
	echo '</div>';
}

// Note
if($object_type == 'note') {
	$note="SELECT * FROM annotations WHERE noteID=".$object_id."";
	$result = mysql_query($note) or die(" ". mysql_error());
	
	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		echo "<h2> Note #".$row['noteID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
	}
	echo '</div>';
}










/*
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
  } */

mysql_close($con);
?>