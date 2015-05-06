<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$object_type = $pieces[0];
$object_id = $pieces[1];

require_once('../core/Connection.class.php');
$connection = Connection::getInstance();
// Page
if($object_type == 'page') {
	$page="SELECT * FROM pages WHERE pageID=".$object_id."";
	$result = $connection->commit($page);

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		$hasThumb = $row['thumbnailID'];
		if($hasThumb == NULL) {
			echo "<h2> Page #".$row['pageID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
		}
		else {
			$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_id."";
			$pageResult = $connection->commit($getPage);

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
	$result = $connection->commit($query);

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
	$result = $connection->commit($snippet);

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
	$result = $connection->commit($note);

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		echo "<h2> Note #".$row['noteID']."</h2> [<a href=".$row['url']." target='new'>url</a>]<br/><br/>";
	}
	echo '</div>';
}

mysql_close($con);
?>
