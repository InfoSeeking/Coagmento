<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Chained Select Boxes using PHP, MySQL and jQuery</title>

<style type="text/css">
	#topbar {
	color: #000;
	background: #fff;
	width: 100%;
	height: 40px;
	position: fixed;
	top: 0; left: 0;
	padding-top: 20px;
	padding-left: 20px;
	z-index: 100;
	border-bottom: 3px solid #000;
	}
	#container {
	position: relative;
	margin-top: 100px;
	padding-left: 20px;
	}
	.header {
	font-family: Arial;
	font-weight: bold;
	}
	#box_left {
	width: 60%;
	float: left;
	}
	#box_right {
	width: 35%;
	background: #fff;
	height: 600px;
	float: left;
	position: fixed;
	top: 110px; left: 60%;
	}
	h2 {
	display: inline;
	margin: 0;
	}
	a img {
	display: inline-block;
	width: 100px;
	height: 100px;
	}
	a img:hover {
	border-color: #F00;
	}
	.thumbnail_small {
	margin: 10px 10px 10px 10px;
	border: 2px solid #ccc;
	display: inline-block;
	width: 100px;
	height: 100px;
	}
	.thumbnail_info {
	font-family: arial;
	}
	.thumbnail_info a {
	color: #06F;
	font-family: arial;
	text-decoration: none;
	}
	img.thumbnail_small {
	margin: 10px 10px 10px 10px;
	border: 3px solid #ccc;
	}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
function showDetails(str)
{
if (str=="")
  {
  document.getElementById("box_right").innerHTML="Click a thumbnail to see details.";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("box_right").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("box_right").innerHTML = '<div style="padding-left: 20px; padding-top: 20px; font-family: arial;">Loading..</a>'; }
  }
xmlhttp.open("GET","getDetails.php?q="+str,true);
xmlhttp.send();
}
</script>

<?php
  include('func.php');
?>

</head>

<body>

<script type="text/javascript">
$(document).ready(function () {
	$('.thumbnail_small').click(function(){
		$(this)
			.css('border-color','#ff0000')
			.siblings()
			.css('border-color','#ccc');
	});
});
</script>

<div id="topbar">

  	<span class="header">Timeline</span>

</div>

<div id="container">

<div id="box_left">
<?php
require_once('../../connect.php');
$userID=2;

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
				echo "<img src='../../assets/img/page_newtimeline.png'>";
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
				echo "<img src='../../assets/img/query.png'>";
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
				echo "<img src='../../assets/img/note.png'>";
				echo '</a>';
				$entered = TRUE;
			}
		}
	}
}

echo '</div>'; // Close box_left

echo '<div id="box_right"></div>';

echo '</div>'; // Close container

?>

</body>
</html>
