<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$object_type = $pieces[0];
$object_id = $pieces[1];

require_once('../core/Base.class.php');
require_once("../core/Connection.class.php");
require_once("../core/Util.class.php");

$base = Base::getInstance();
$connection = Connection::getInstance();

// Page
if($object_type == 'page') {
	$page="SELECT * FROM pages WHERE pageID=".$object_id."";
	$result = $connection->commit($page);

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysqli_fetch_array($result))
  	{
		$hasThumb = $row['thumbnailID'];
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = $connection->commit($getProjectName);

		while($line = mysqli_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = $connection->commit($snippet);

		while($line = mysqli_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		if($hasThumb === NULL || $hasThumb == -1 ) {
			echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
			echo "<div style='clear:both;'></div>";
			echo "<table><tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
			echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
			echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
		}
		else {
			$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_id."";
			$pageResult = $connection->commit($getPage);

			while($line = mysqli_fetch_array($pageResult)) {
				$value = $line['pageID'];
				$thumb = $line['fileName'];
				$source = $line['source'];
				$date = $line['date'];

				if($value == $object_id) {
					echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
					echo "<div style='clear:both;'></div>";
					echo "<table>";
					echo "<tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
					echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
					echo "<tr><td><strong>User:</strong> ".$userName."</td></tr>";
					echo "<tr><td class='thumb' align='center'><img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/".$thumb."' width='100%' /></td></tr></table>";
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
	while($row = mysqli_fetch_array($result))
  	{
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = $connection->commit($getProjectName);

		while($line = mysqli_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = $connection->commit($snippet);

		while($line = mysqli_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		echo "<img src='assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['query']."</a></h2>";
		echo "<div style='clear:both;'></div>";
		echo "<table><tr><td><strong>Source:</strong> ".$row['source']."</td></tr>";
		echo "<tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
		echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
		echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
	}
	echo '</div>';
}

// Snippet
if($object_type == 'snippet') {
	$snippet="SELECT * FROM snippets WHERE snippetID=".$object_id."";
	$result = $connection->commit($snippet);

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysqli_fetch_array($result))
  	{
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = $connection->commit($getProjectName);

		while($line = mysqli_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = $connection->commit($snippet);

		while($line = mysqli_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		if($row['title'] == "") { echo "<img src='assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>Snippet #".$row['snippetID']."</a></h2>"; }
		echo "<img src='assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
		echo "<div style='clear:both;'></div>";
		echo "<table><tr><td><strong>Snippet:</strong> ".$row['snippet']."</td></tr>";
		echo "<tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
		echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
		echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
	}
	echo '</div>';
}

// Note
if($object_type == 'note') {
	$note="SELECT * FROM annotations WHERE noteID=".$object_id."";
	$result = $connection->commit($note);

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysqli_fetch_array($result))
  	{
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = $connection->commit($getProjectName);

		while($line = mysqli_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = $connection->commit($snippet);

		while($line = mysqli_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		if($row['title'] == "") { echo "<img src='assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>Snippet #".$row['noteID']."</a></h2>"; }
		echo "<img src='assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
		echo "<div style='clear:both;'></div>";
		echo "<table><tr><td><strong>Note:</strong> ".$row['note']."</td></tr>";
		echo "<tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
		echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
		echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
	}
	echo '</div>';
}
?>
