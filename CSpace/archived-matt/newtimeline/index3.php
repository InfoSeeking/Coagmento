<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coagmento CSpace Plain View</title>

<style type="text/css">
	body {
	font-family: arial;
	}
	#topbar {
	color: #000;
	background: #fff;
	width: 100%;
	height: 50px;
	position: fixed;
	top: 0; left: 0;
	padding-top: 20px;
	padding-left: 20px;
	z-index: 100;
	border-bottom: 3px #000 solid;
	}
	#container {
	position: relative;
	padding-left: 20px;
	margin-top: 100px;
	}
	#box_left {
	width: 55%;
	float: left;
	}
	#box_right {
	width: 35%;
	background: #fff;
	height: 600px;
	float: left;
	position: fixed;
	top: 110px; left: 60%;
	overflow: auto;
	}
	h2 {
	font-family: arial;
	display: inline;
	margin: 0;
	float: left;
	}
	a img {
	display: inline-block;
	width: 100px;
	height: 100px;
	}
	.thumbnail_small {
	margin: 10px 10px 10px 10px;
	border: 1px solid #ccc;
	display: inline-block;
	width: 100px;
	height: 100px;
	}
	a:hover {
	border: 1px solid #000 !important;
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
	.form {
	float: left;
	padding-left: 20px;
	padding-top: 3px;
	}
	.details {
	float: left;
	padding-left: 20px;
	padding-top: 6px;
	}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>

<script type="text/javascript">
function filterData(str)
{
if (str=="")
  {
  document.getElementById("box_left").innerHTML="";
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
    document.getElementById("box_left").innerHTML=xmlhttp.responseText;
    }
	else { document.getElementById("box_left").innerHTML = '<img src="../assets/img/loading.gif"/>'; }
  }
xmlhttp.open("GET","filterData.php?q="+str,true);
xmlhttp.send();
}

function showDetails(str)
{
if (str=="")
  {
  document.getElementById("box_right").innerHTML="";
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
	else { document.getElementById("box_right").innerHTML = '<div style="padding-left: 20px; padding-top: 20px; font-family: arial;"><img src="../assets/img/loading.gif"/></div>'; }
  }
xmlhttp.open("GET","getDetails.php?q="+str,true);
xmlhttp.send();
}
</script>

<?php
  include('func.php');
  require_once('../../connect.php');
  $userID=2;
?>

<script type="text/javascript">
$(document).ready(function () {
	$('.thumbnail_small').live('click', function(){
    $(this)
        .css('border-color','#000')
        .siblings()
        .css('border-color','#ccc');
	});
});
</script>

</head>

<body>

<div id="topbar">

  	<h2>Plain View</h2>

	<?php
        if(isset($_POST['formSubmit']))
        {
            $varProjects = $_POST['projects'];
            $varObjects = $_POST['objects'];
            $varYears = $_POST['years'];
            $varMonths = $_POST['months'];

            $str = $varProjects.'-'.$varObjects.'-'.$varYears.'-'.$varMonths;

            echo '<div class="details">';
            echo 'Viewing <b>';
            echo $varObjects;
            echo '</b> from project <b>';
            echo $varProjects;
            echo '</b> from <b>';
            switch ($varMonths) {
                case "all":
                    echo "all";
                    break;
                case 01:
                    echo "Jan";
                    break;
                case 02:
                    echo "Feb";
                    break;
                case 03:
                    echo "Mar";
                    break;
                case 04:
                    echo "Apr";
                    break;
                case 05:
                    echo "May";
                    break;
                case 06:
                    echo "Jun";
                    break;
                case 07:
                    echo "Jul";
                    break;
                case 08:
                    echo "Aug";
                    break;
                case 09:
                    echo "Sept";
                    break;
                case 10:
                    echo "Oct";
                    break;
                case 11:
                    echo "Nov";
                    break;
                case 12:
                    echo "Dec";
                    break;
            }
            echo ' ';
            echo $varYears;
            echo '</b>';

            echo '</div>';

            echo '<script type="text/javascript">';
            echo 'filterData("'.$str.'")';
            echo '</script>';
        }
    ?>

    <div class="form">
    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">

	<select name="projects">
		<?php
            echo '<option value="all">All Projects</option>';
            $query = "SELECT * FROM memberships WHERE userID='$userID'";
            $results = $connection->commit($query);
            while ($line = mysqli_fetch_array($results, MYSQL_ASSOC)) {
                $projID = $line['projectID'];
                $query1 = "SELECT * FROM projects WHERE projectID='$projID'";
                $results1 = $connection->commit($query1);
                $line1 = mysqli_fetch_array($results1, MYSQL_ASSOC);
                $title = $line1['title'];
                echo "<option value=\"$title\" ";
                if ($projID==$projectID)
                    echo "SELECTED";
                    echo ">$title</option>\n";
            }
        ?>
    </select>

    <select id="objects" name="objects">
      <option value="all">All Objects</option>
      <option value="pages" <?php if ($objects=="pages") echo "SELECTED";?>>Webpages</option>
      <option value="saved" <?php if ($objects=="saved") echo "SELECTED";?>>Bookmarks</option>
      <option value="queries" <?php if ($objects=="queries") echo "SELECTED";?>>Searches</option>
      <option value="snippets" <?php if ($objects=="snippets") echo "SELECTED";?>>Snippets</option>
      <option value="annotations" <?php if ($objects=="annotations") echo "SELECTED";?>>Annotations</option>
    </select>

    <select id="years" name="years">
      <option value="all">All Years</option>
	  <?
      $sql_year="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
      $result_year=mysql_query($sql_year);

      $options="";
      $y=array();

      while ($row=mysqli_fetch_array($result_year)) {
          $date=$row["date"];
          $year = date("Y",strtotime($date));

          if (!in_array($year, $y)){
              $y[] = $year;
              $options.="<OPTION VALUE=".$year.">".$year; echo'</OPTION>';
          }

      }
      echo $options;
      ?>
    </select>

    <select id="months" name="months">
      <option value="all">All Months</option>
      <?
	  $sql_month="SELECT DISTINCT date FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet')";
	  $result_month=mysql_query($sql_month);

	  $m=array();

	  while ($row2=mysqli_fetch_array($result_month)) {
		  $date2=$row2["date"];
		  $month = date("m",strtotime($date2));

		  if (!in_array($month, $m)){
		  	  if($month == 01 || $month == 02 || $month == 03 || $month == 04 || $month == 05 || $month == 06 || $month == 07 || $month == 08 || $month == 09 || $month == 10 || $month == 11 || $month == 12) {
			  	$m[] = $month;
			  }
		  }
	  }

	  sort($m);

	  for($i = 0; $i < count($m); ++$i) {
		  echo "<option value=".$m[$i].">";
		  if($m[$i]==01) { echo "Jan"; }
		  elseif($m[$i]==02) { echo "Feb"; }
		  elseif($m[$i]==03) { echo "Mar"; }
		  elseif($m[$i]==04) { echo "Apr"; }
		  elseif($m[$i]==05) { echo "May"; }
		  elseif($m[$i]==06) { echo "Jun"; }
		  elseif($m[$i]==07) { echo "Jul"; }
		  elseif($m[$i]==08) { echo "Aug"; }
		  elseif($m[$i]==09) { echo "Sept"; }
		  elseif($m[$i]==10) { echo "Oct"; }
		  elseif($m[$i]==11) { echo "Nov"; }
		  elseif($m[$i]==12) { echo "Dec"; }
		  echo "</option>";
	  }
	  ?>
    </select>

	<input type="submit" name="formSubmit" value="Submit" />
    </form>
    </div>

</div>

<div id="container">

<div id="box_left">
<?php
/*$sql="SELECT * FROM actions WHERE userID=".$userID." AND (action='page' OR action='query' OR action='add-annotation' OR action='save-snippet') ORDER BY date DESC";
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
}*/
echo '</div>'; // Close box_left

echo '<div id="box_right">Click a thumbnail for details.</div>';

echo '</div>'; // Close container
?>

</body>
</html>
