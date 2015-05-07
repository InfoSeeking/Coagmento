<?php
$q=$_GET["q"];
$pieces = explode("-", $q);
$object_type = $pieces[0];
$object_id = $pieces[1];

require_once("../connect.php");

// Page
if($object_type == 'page') {

	$page="SELECT * FROM pages WHERE thumbnailID=".$object_id."";
	$result = $connection->commit($page);

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

		// Get thumbnail
		$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND thumbnails.thumbnailID=".$object_id."";
		$pageResult = $connection->commit($getPage);

		while($line = mysqli_fetch_array($pageResult)) {
			//$value = $line['pageID'];
			$thumb = $line['fileName'];
			$source = $line['source'];
			$date = $line['date'];

		  	if ($hasThumb == $object_id) {
				echo "<img src='../links.png'><h3><a href=".$row['url']." target='new'>".$row['title']."</a></h3>";
				echo "<p><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</p>";
				echo "<p><strong>Project:</strong> ".$projectName."</p>";
				echo "<p><strong>User:</strong> ".$userName."</p></div>";
				echo "<div><a href=".$row['url']." target='new'><img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/".$thumb."' width='100%'/></a>";
			}
		}
	}
}


?>
