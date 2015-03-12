<?php
// $x is length of summary if requestType is summarization
// $x is first doc selected if requestType is rank / compare 
$x = $_POST["x"];
$xmldata = $_POST["xmldata"];
$xmldata_parsed = stripslashes($xmldata);

$xml = new SimpleXMLElement($xmldata_parsed);
$error = $xml->error->message;
$requestType = $xml->requestType;

//connect to DB
require_once("connect.php");

if ($requestType == "cluster") {
	$cluster = $xml->clusterList->cluster;
	//for each cluster, get clusterID
	for ($i=0; $i < count($cluster); $i++) { 
		$clusterID = $cluster[$i]->clusterID;
		$groupNum = $clusterID + 1;
		echo "<div class='cf'> <div class='cluster'>Group ".$groupNum." </div>";

		$resource = $cluster[$i]->resourceList->resource;
		//check for no results
		$na = $cluster[$i]->resource->id;

		//if pageID is valid
		if ($na != "-1") {
			//for each doc, get id
			for ($k = 0; $k < count($resource); $k++) {
				$id = $resource[$k]->id;

				$page="SELECT * FROM pages WHERE pageID=".$id."";
				$result = mysql_query($page) or die(" ". mysql_error());

				while($row = mysql_fetch_array($result)) {
					$hasThumb = $row['thumbnailID'];
					$projectID = $row['projectID'];
					
					// Get project name
					$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
					$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());		

					while($line = mysql_fetch_array($projectNameResult)) {
						$projectName = $line['title'];
					}

					// if thumbnail is nonexistent in database
					if($hasThumb == NULL || $hasThumb == -1 ) {
					    echo "<div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px'></a></div>";	
					}

					else {					
						// Get thumbnail
						$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$id."";
						$pageResult = mysql_query($getPage) or die(" ". mysql_error());
						
						while($line = mysql_fetch_array($pageResult)) {
							$value = $line['pageID'];
							$thumb = $line['fileName'];

						  	if ($value == $id) {  		
					    		echo "<div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></a></div>";	
							}
						}
					}
				}
			} // end for
		} // end if
		else {
			echo "<div class='error'>There are no results for this group.</div>";
		}
		echo "</div>";

	} // end for
} // end cluster

//summarize
else if ($requestType == "extract") {
	$resource = $xml->resourceList->resource;

	//for each doc, get id and summarization
	for ($k = 0; $k < count($resource); $k++) {
		$id = $resource[$k]->id;
		$summarization = $resource[$k]->content;

		//if pageID is valid
		if ($id != "-1") {

			$page="SELECT * FROM pages WHERE pageID=".$id."";
			$result = mysql_query($page) or die(" ". mysql_error());

			while($row = mysql_fetch_array($result)) {
				$hasThumb = $row['thumbnailID'];
				$projectID = $row['projectID'];
				
				// Get project name
				$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
				$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());		

				while($line = mysql_fetch_array($projectNameResult)) {
					$projectName = $line['title'];
				}

				if($hasThumb == NULL || $hasThumb == -1 ) {
				    echo "<div class='summary cf'><div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px'></a></div>";	
				}

				else {			
					// Get thumbnail
					$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$id."";
					$pageResult = mysql_query($getPage) or die(" ". mysql_error());
					
					while($line = mysql_fetch_array($pageResult)) {
						$value = $line['pageID'];
						$thumb = $line['fileName'];

					  	if ($value == $id) {  		
				    		echo "<div class='summary cf'><div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></a></div>";	
						}
					}
				}
			}

			// strip tags to avoid breaking any html
			$summarization = strip_tags($summarization);

			if (strlen($summarization) > $x) {

			    // truncate string
			    $stringCut = substr($summarization, 0, $x);

			    // make sure it ends in a word so assassinate doesn't become ass...
			    $summarization = substr($stringCut, 0, strrpos($stringCut, ' ')).''; 
			}

			echo "<h4>Summary:</h4>".$summarization."</div>";
		} // end if

		else {
			echo "<div class='error'>There is no summarization available for this page.</div>";
		}
	} // end for
} // end extract / summarization

else if ($requestType == "rank") {

	$firstPage="SELECT * FROM pages WHERE pageID=".$x."";
	$firstResult = mysql_query($firstPage) or die(" ". mysql_error());

	while($row = mysql_fetch_array($firstResult)) {
		$hasThumb = $row['thumbnailID'];
		$projectID = $row['projectID'];
		
		// Get project name
		$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
		$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());		

		while($line = mysql_fetch_array($projectNameResult)) {
			$projectName = $line['title'];
		}

		if($hasThumb == NULL || $hasThumb == -1 ) {
		    echo "<div class='summary cf'><h4>Comparing Document:</h4><div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px'></a></div></div>";	
		}

		else {	
			//get first selected doc
			$getFirstPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$x."";
			$pageFirstResult = mysql_query($getFirstPage) or die(" ". mysql_error());
			
			while($line = mysql_fetch_array($pageFirstResult)) {
				$value = $line['pageID'];
				$thumb = $line['fileName'];

			  	if ($value == $x) {  		
		    		echo "<div class='summary cf'><h4>Comparing Document:</h4><div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></a></div></div>";	
				}
			}
		}
	}

	$resource = $xml->resourceList->resource;
	echo "<div class='rank cf'><h4>To:</h4>";
	//for each doc, get id and summarization
	for ($k = 0; $k < count($resource); $k++) {
		$id = $resource[$k]->id;
		$rank = $resource[$k]->rank;

		$page="SELECT * FROM pages WHERE pageID=".$id."";
		$result = mysql_query($page) or die(" ". mysql_error());

		while($row = mysql_fetch_array($result)) {
			$hasThumb = $row['thumbnailID'];
			$projectID = $row['projectID'];
			
			// Get project name
			$getProjectName="SELECT * FROM projects WHERE projectID=".$projectID."";
			$projectNameResult = mysql_query($getProjectName) or die(" ". mysql_error());		

			while($line = mysql_fetch_array($projectNameResult)) {
				$projectName = $line['title'];
			}

			if($hasThumb == NULL || $hasThumb == -1 ) {
			    echo "<div class='summary cf'><h4>Comparing Document:</h4><div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px'></a></div></div>";	
			}

			else {	
				// Get thumbnail
				$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.pageID=".$id."";
				$pageResult = mysql_query($getPage) or die(" ". mysql_error());
				
				while($line = mysql_fetch_array($pageResult)) {
					$value = $line['pageID'];
					$thumb = $line['fileName'];

				  	if ($value == $id) {  		
			    		echo "<div class='wrapper'><a class='thumbnail_small2' href=".$row['url']." target='new'><img width='100px' height='100px' src='http://".$_SERVER['HTTP_HOST']."/CSpace/thumbnails/small/".$thumb."' /></a>";	
					}
				}
			}
		}
		$n = $k+1;
		echo "Rank " . $n. "\n";
		echo "Score: ".number_format($rank,2, '.', '')."</div>";
	} // end for
	echo "</div>";
} // end rank

// if error is received from API response
if (isset($error)) {
	echo $error;
}

?>