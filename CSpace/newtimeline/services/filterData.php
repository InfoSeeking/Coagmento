<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$object_type = $pieces[0];
$project_id = $pieces[1];
$year = $pieces[2];
$month = $pieces[3];

$con = mysql_connect('localhost', 'shahonli_ic', 'collab2010!');
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("shahonli_coagmento", $con);
$userID=2;

if($object_type == "all" && $project_id == "all" && $year == "all" && $month == "all") {
$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
$result = $connection->commit($query);

while($row = mysqli_fetch_array($result))
{
	$object_type = $row['action'];
	$object_value = $row['value'];

	// Page
	if($object_type == 'page') {
		$getAllPage="SELECT * FROM pages WHERE pageID=".$object_value."";
		$allPageResult = mysql_query($getAllPage) or die(" ". mysql_error());

		while($line = mysqli_fetch_array($allPageResult)) {
			$hasThumb = $line['thumbnailID'];
			$value = $line['pageID'];
			$pass_var = "page-".$value;

			if($hasThumb == NULL) {
				echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
				echo "<img src='../assets/img/page_newtimeline.png'>";
				echo '</a>';
			}
			else {
				$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_value."";
				$pageResult = $connection->commit($getPage);

				while($line = mysqli_fetch_array($pageResult)) {
					$value = $line['pageID'];
					$thumb = $line['fileName'];
					$pass_var = "page-".$value;

					if($value == $object_value) {
						echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
						echo '<img src="http://'.$_SERVER['HTTP_HOST'].'/CSpace/thumbnails//small/';
						echo $thumb;
						echo '" />';
						echo '</a>';
					}
				}
			}
		}
	}

	// Query
	if($object_type == 'query') {
		$getQuery="SELECT * FROM actions,queries WHERE queries.queryID=actions.value AND queries.queryID=".$object_value."";
		$queryResult = $connection->commit($getQuery);
		$entered = FALSE;

		while($line = mysqli_fetch_array($queryResult)) {
			$value = $line['queryID'];
			$query = $line['query'];
			$pass_var = "query-".$value;

			if($value == $object_value && $entered == FALSE) {
				echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
				echo "<img src='../assets/img/query.png'>";
				echo '</a>';
				$entered = TRUE;
			}
		}
	}

	// Snippet
	if($object_type == 'save-snippet') {
		$getSnippet="SELECT * FROM actions,snippets WHERE snippets.snippetID=actions.value AND snippets.snippetID=".$object_value."";
		$snippetResult = $connection->commit($getSnippet);
		$entered = FALSE;

		while($line = mysqli_fetch_array($snippetResult)) {
			$value = $line['snippetID'];
			$snippet = $line['snippet'];
			$pass_var = "snippet-".$value;

			if($value == $object_value && $entered == FALSE) {
				echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
				echo "<img src='../assets/img/snippet.png'>";
				echo '</a>';
				$entered = TRUE;
			}
		}
	}

	// Annotation
	if($object_type == 'add-annotation') {
		$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND annotations.noteID=".$object_value."";
		$noteResult = $connection->commit($getNote);
		$entered = FALSE;

		while($line = mysqli_fetch_array($noteResult)) {
			$value = $line['noteID'];
			$note = $line['note'];
			$pass_var = "note-".$value;

			if($value == $object_value && $entered == FALSE) {
				echo '<a href="javascript:void(0);" class="thumbnail_small" onClick=showDetails("'.$pass_var.'")>';
				echo "<img src='../assets/img/note.png'>";
				echo '</a>';
				$entered = TRUE;
			}
		}
	}
}
}
else {
	echo $q;
}


?>
