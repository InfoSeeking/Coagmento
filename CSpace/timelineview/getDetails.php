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
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = mysql_query($getUserName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		if($hasThumb == NULL) {
			echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
			echo "<div style='clear:both;'></div>";
			echo "<table><tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
			echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
			echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
		}
		else {
			$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$object_id."";
			$pageResult = mysql_query($getPage) or die(" ". mysql_error());

			while($line = mysql_fetch_array($pageResult)) {
				$value = $line['pageID'];
				$thumb = $line['fileName'];
				$source = $line['source'];
				$date = $line['date'];

				if($value == $object_id) {
					echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
					echo "<div style='clear:both;'></div>";
					echo "<table><tr><td class='thumb' align='center'><img src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/".$thumb."' width='100%' /></td></tr>";
					echo "<tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
					echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
					echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
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
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = mysql_query($getUserName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['query']."</a></h2>";
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
	$result = mysql_query($snippet) or die(" ". mysql_error());

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = mysql_query($getUserName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		if($row['title'] == "") { echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>Snippet #".$row['snippetID']."</a></h2>"; }
		echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
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
	$result = mysql_query($note) or die(" ". mysql_error());

	echo '<div class="thumbnail_info" style="padding-left: 20px; padding-top: 20px;">';
	while($row = mysql_fetch_array($result))
  	{
		$projectID = $row['projectID'];
		$userID = $row['userID'];

		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		// Get user name
		$getUserName="SELECT * FROM users WHERE userID=".$userID."";
		$userNameResult = mysql_query($getUserName) or die(" ". mysql_error());

		while($line = mysql_fetch_array($userNameResult)) {
			$userName = $line['username'];
		}

		if($row['title'] == "") { echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>Snippet #".$row['noteID']."</a></h2>"; }
		echo "<img src='../assets/img/links.png' style='float:left; margin-top: 4px;'/><h2><a href=".$row['url']." target='new'>".$row['title']."</a></h2>";
		echo "<div style='clear:both;'></div>";
		echo "<table><tr><td><strong>Note:</strong> ".$row['note']."</td></tr>";
		echo "<tr><td><strong>Viewed on:</strong> ".$row['date']." ".$row['time']."</td></tr>";
		echo "<tr><td><strong>Project:</strong> ".$projectName."</td></tr>";
		echo "<tr><td><strong>User:</strong> ".$userName."</td></tr></table>";
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
