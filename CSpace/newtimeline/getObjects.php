<style type="text/css">
img.thumbnail_small {
	margin: 10px 10px 10px 10px;
	border: 3px solid #ccc;
}

</style>

<?php
require_once('../../connect.php');
$userID=2;

$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
$result = mysql_query($sql) or die(" ". mysql_error());

while($row = mysql_fetch_array($result))
{
	$object_type = $row['action'];
	$object_value = $row['value'];

	if($object_type == 'page') {
//		$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID."";
		$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
		$pageResult = $connection->commit($getPage);

		while($line = mysql_fetch_array($pageResult)) {
			$value = $line['pageID'];
			$thumb = $line['fileName'];

			if($value == $object_value) {
				echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/';
				echo $thumb;
				echo '" width="100" height="100" class="thumbnail_small" />';
			}
		}
	}

	if($object_type == 'query') {
		$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
		$queryResult = mysql_query($getQuery) or die(" ". mysql_error());

		while($line = mysql_fetch_array($queryResult)) {
			$value = $line['queryID'];
			$query = $line['query'];

			if($value == $object_value) {
				echo "<img src='../assets/img/query.png' class='thumbnail_small'>";
			}
		}
	}

	if($object_type == 'save-snippet') {
		$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
		$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());

		while($line = mysql_fetch_array($snippetResult)) {
			$value = $line['snippetID'];
			$snippet = $line['snippet'];

			if($value == $object_value) {
				echo "<img src='../assets/img/snippet.png' class='thumbnail_small'>";
			}
		}
	}

	if($object_type == 'add-annotation') {
		$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
		$noteResult = mysql_query($getNote) or die(" ". mysql_error());

		while($line = mysql_fetch_array($noteResult)) {
			$value = $line['noteID'];
			$note = $line['note'];

			if($value == $object_value) {
				echo "<img src='../assets/img/note.png' class='thumbnail_small'>";
			}
		}
	}
}

?>
