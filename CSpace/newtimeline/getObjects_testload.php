<!DOCTYPE html > 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js" ></script>
    <script type="text/javascript" src="jquery.lazyloader.js" ></script>
    <script type="text/javascript" >
        $(document).ready( function()
        {
            $('pre.loadme').lazyLoad();
            $('pre.morestuff').lazyLoad();
        } );
    </script>
    <style>
    body {
        font-face: Tahoma;
        
    }
    ul {
        float: left;
    }
    li {
        list-style-type: none;
        float: left;
    }
    img.thumbnail_small {
        margin: 10px 10px 10px 10px;
        border: 3px solid #ccc;
    }	
    </style>
</head>

<body>

<ul>
<?php
require_once('../../connect.php');
$userID=2;

$sql="SELECT * FROM actions WHERE userID=".$userID." ORDER BY date DESC";
$result = mysql_query($sql) or die(" ". mysql_error());

while($row = mysql_fetch_array($result))
{
	$object_type = $row['action'];	
	$object_value = $row['value'];
	
	if($object_type == 'page') {
		$getPage="SELECT * FROM pages,thumbnails WHERE thumbnails.thumbnailID=pages.thumbnailID AND pages.userID=".$userID."";
		$pageResult = mysql_query($getPage) or die(" ". mysql_error());
		
		while($line = mysql_fetch_array($pageResult)) {
			$value = $line['pageID'];
			$thumb = $line['fileName'];
			
			if($value == $object_value) {
				echo '<li><pre class="loadme"><!-- <img src="http://coagmento.org/CSpace/thumbnails/small/';
				echo $thumb;
				echo '" width="100" height="100" class="thumbnail_small" /> --></pre></li>'; 
			}
		}
	}
	
	if($object_type == 'query') {
		$getQuery="SELECT * FROM actions, queries WHERE queries.queryID=actions.value AND actions.userID=".$userID."";
		$queryResult = mysql_query($getQuery) or die(" ". mysql_error());
		
		while($line = mysql_fetch_array($queryResult)) {
			$value = $line['queryID'];
			$query = $line['query'];
			
			if($value == $object_value) {
				echo "<li><pre class='loadme'><!-- <img src='query.png' class='thumbnail_small'> --></pre></li>";
			}
		}
	}
	
	if($object_type == 'save-snippet') {
		$getSnippet="SELECT * FROM actions, snippets WHERE snippets.snippetID=actions.value AND actions.userID=".$userID."";
		$snippetResult = mysql_query($getSnippet) or die(" ". mysql_error());
		
		while($line = mysql_fetch_array($snippetResult)) {
			$value = $line['snippetID'];
			$snippet = $line['snippet'];
			
			if($value == $object_value) {
				echo "<li><pre class='loadme'><!-- <img src='snippet.png' class='thumbnail_small'> --></pre></li>";
			}
		}
	}
	
	if($object_type == 'add-annotation') {
		$getNote="SELECT * FROM actions, annotations WHERE annotations.noteID=actions.value AND actions.userID=".$userID."";
		$noteResult = mysql_query($getNote) or die(" ". mysql_error());
		
		while($line = mysql_fetch_array($noteResult)) {
			$value = $line['noteID'];
			$note = $line['note'];
			
			if($value == $object_value) {
				echo "<li><pre class='loadme'><!-- <img src='note.png' class='thumbnail_small'> --></pre></li>";
			}
		}
	}
}

?>
</ul>

<pre class="morestuff" ><!--

			<div></div>
		
		--></pre>

	</body>
</html>