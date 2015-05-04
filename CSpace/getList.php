<?php

$cpages = $_POST["cpages"];
$operator = $_POST["operator"];
// $x is the first variable isolated for 'ranking' interface
$x= $cpages[0];

require_once("connect.php");

// ranking operator
if ($operator == "rank") {
	// if $x exists, query for thumbnails to display for rank interface
	if ($x != NULL) {
		//take out $x so that rest of array can be iterated through
		array_splice($cpages, 0, 1);

		$firstPage="SELECT * FROM pages WHERE pageID=".$x."";
		$firstResult = mysql_query($firstPage) or die(" ". mysql_error());

		while($row = mysql_fetch_array($firstResult)) {
			$hasThumb = $row['thumbnailID'];
			$projectID = $row['projectID'];
			$value = $row['pageID'];

			// Get project name
			$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
			$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

			while($line = mysql_fetch_array($projectNameResult)) {
				$projectName = $line['title'];
			}

			// if no thumbnail
			if($hasThumb == NULL || $hasThumb == -1 ) {
		  		echo "<div class='summary cf'><h4>Comparing Document:</h4><div class='wrapper'><span class='remove' onclick='removeID(".$value.")' href='javascript:void(0);'><img id='remove-icon' src='assets/img/remove_icon2.png'></span>";
			    echo "<a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' /></a></div></div>";
			}

			else {

				//get first selected doc
				$getFirstPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$x."";
				$pageFirstResult = mysql_query($getFirstPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($pageFirstResult)) {
					$value = $line['pageID'];
					$thumb = $line['fileName'];

				  	if ($value == $x) {
			    		echo "<div class='summary cf'><h4>Comparing Document:</h4><div class='wrapper'><span class='remove' onclick='removeID(".$value.")' href='javascript:void(0);'><img id='remove-icon' src='assets/img/remove_icon2.png'></span><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></a></div></div>";
					}
				}
			}
		}

		//iterating through rest of array for rest of thumbnails
		echo "<div class='summary cf'><h4>To:</h4>";
		foreach ($cpages as $checked) {
			// Page
			$page="SELECT * FROM pages WHERE pageID=".$checked."";
			$result = mysql_query($page) or die(" ". mysql_error());

			while($row = mysql_fetch_array($result)) {
				$hasThumb = $row['thumbnailID'];
				$projectID = $row['projectID'];
				$value = $row['pageID'];

				// Get project name
				$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
				$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

				while($line = mysql_fetch_array($projectNameResult)) {
					$projectName = $line['title'];
				}

				if($hasThumb == NULL || $hasThumb == -1 ) {
			  		echo "<div class='wrapper'><span class='remove' onclick='removeID(".$value.")' href='javascript:void(0);'><img id='remove-icon' src='assets/img/remove_icon2.png'></span>";
				    echo "<a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' /></a></div>";
				}

				else {
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$checked."";
					$pageResult = mysql_query($getPage) or die(" ". mysql_error());

					while($line = mysql_fetch_array($pageResult)) {
						$value = $line['pageID'];
						$thumb = $line['fileName'];

					  	if ($value == $checked) {
					  		echo "<div class='wrapper'><span class='remove' onclick='removeID(".$value.")' href='javascript:void(0);'><img id='remove-icon' src='assets/img/remove_icon2.png'></span>";
						    echo "<a class='thumbnail_small2' href='".$row['url']."' target='new'><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></a></div>";
						}
					}
				}
			}
		}
	echo "</div>";
	}
}

// summarization or clustering operators
else {
	echo "<table>";
	foreach ($cpages as $checked) {
		// Page
		$page="SELECT * FROM pages WHERE pageID=".$checked."";
		$result = mysql_query($page) or die(" ". mysql_error());

		while($row = mysql_fetch_array($result))
			{
			$hasThumb = $row['thumbnailID'];
			$projectID = $row['projectID'];
			$value = $row['pageID'];

			// Get project name
			$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
			$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());

			while($line = mysql_fetch_array($projectNameResult)) {
				$projectName = $line['title'];
			}


			if($hasThumb == NULL || $hasThumb == -1 ) {
			    echo "<tr><th><div class='wrapper'><span class='remove' onclick='removeID(".$value.")' href='javascript:void(0);'><img id='remove-icon' src='assets/img/remove_icon2.png'></span><img width='100px' height='100px' /></th>";
			    echo "<th><a href=".$row['url']." target='new'>".$row['title']."</a></th></tr>";
			}

			else {
				// Get thumbnail
				$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$checked."";
				$pageResult = mysql_query($getPage) or die(" ". mysql_error());

				while($line = mysql_fetch_array($pageResult)) {
					$value = $line['pageID'];
					$thumb = $line['fileName'];

				  	if ($value == $checked) {
					    echo "<tr><th><div class='wrapper'><span class='remove' onclick='removeID(".$value.")' href='javascript:void(0);'><img id='remove-icon' src='assets/img/remove_icon2.png'></span><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></div></th>";
					    echo "<th><a href=".$row['url']." target='new'>".$row['title']."</a></th></tr>";
					}
				}
			}
		}
	}
	echo "</table>";
}


mysql_close($con);
?>
